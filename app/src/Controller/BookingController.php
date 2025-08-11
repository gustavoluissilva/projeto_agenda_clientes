<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\WhatsappService; // Garante que a classe de serviço do WhatsApp está disponível

/**
 * Booking Controller
 *
 * Lida com todo o fluxo de agendamento do cliente.
 */
class BookingController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Permite que qualquer um veja a lista de serviços e os horários
        $this->Authentication->addUnauthenticatedActions(['index', 'selectTime']);
    }

    /**
     * Passo 1: O cliente escolhe o serviço.
     */
    public function index()
    {
        $this->Authorization->skipAuthorization(); // Página pública

        // Busca apenas os serviços que estão marcados como ativos
        $services = $this->fetchTable('Services')->find()->where(['active' => true])->all();
        $this->set(compact('services'));
    }

    /**
     * Passo 2: O cliente escolhe a data e a hora.
     */
    public function selectTime($service_id = null)
    {
        $this->Authorization->skipAuthorization(); // Página pública

        $service = $this->fetchTable('Services')->get($service_id);
        $duration = $service->time_spend;

        $availableRules = $this->fetchTable('Available')->find()->all();
        $bookedSchedules = $this->fetchTable('Schedule')->find()
            ->where(['date_start >=' => date('Y-m-d')])->all();
        $exceptions = $this->fetchTable('Exceptions')->find()
            ->where(['start_exception >=' => date('Y-m-d')])->all();

        $availableSlots = [];
        $today = new \DateTime();
        $endDate = new \DateTime('+30 days');

        for ($date = clone $today; $date <= $endDate; $date->modify('+1 day')) {
            $dayOfWeek = $date->format('w');
            foreach ($availableRules as $rule) {
                if ($rule->week_day == $dayOfWeek) {
                    $startShift = new \DateTime($date->format('Y-m-d') . ' ' . $rule->start_shift->format('H:i:s'));
                    $endShift = new \DateTime($date->format('Y-m-d') . ' ' . $rule->end_shift->format('H:i:s'));

                    for ($slotStart = clone $startShift; $slotStart < $endShift; $slotStart->modify("+" . $duration . " minutes")) {
                        $slotEnd = (clone $slotStart)->modify("+" . $duration . " minutes");
                        if ($slotEnd > $endShift) break;
                        if ($this->isSlotAvailable($slotStart, $slotEnd, $bookedSchedules, $exceptions)) {
                            $availableSlots[$date->format('Y-m-d')][] = $slotStart;
                        }
                    }
                }
            }
        }
        $this->set(compact('service', 'availableSlots'));
    }

    /**
     * Passo 3: O cliente confirma os detalhes do agendamento.
     */
    public function confirm($service_id = null, $datetime = null)
    {
        // Esta action requer que o usuário esteja logado
        $this->Authorization->authorize($this->Authentication->getIdentity());

        $service = $this->fetchTable('Services')->get($service_id);
        $scheduleTime = new \DateTime($datetime);

        $this->set(compact('service', 'scheduleTime'));
    }

    /**
     * Passo 4: Salva o agendamento no banco de dados e notifica.
     */
    public function save()
    {
        // Esta action também requer que o usuário esteja logado e só aceita POST
        $this->request->allowMethod('post');
        $this->Authorization->authorize($this->Authentication->getIdentity());

        $scheduleTable = $this->fetchTable('Schedule');
        $newSchedule = $scheduleTable->newEmptyEntity();

        $data = $this->request->getData();
        $service = $this->fetchTable('Services')->get($data['id_services']);
        
        // Preenche a entidade com os dados do formulário e outros dados necessários
        $newSchedule->id_users = $this->Authentication->getIdentity()->id;
        $newSchedule->id_services = $data['id_services'];
        $newSchedule->date_start = new \DateTime($data['date_start']);
        $newSchedule->date_end = (new \DateTime($data['date_start']))->modify('+' . $service->time_spend . ' minutes');
        $newSchedule->status = 'confirmado'; // Ou 'pendente' se precisar de confirmação manual
        $newSchedule->observation = $data['observation'] ?? '';
        
        if ($scheduleTable->save($newSchedule)) {
            // Sucesso! Vamos notificar.
            $client = $this->Authentication->getIdentity();
            $clientPhone = $client->phone;
            $templateSid = "HXxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"; // SEU ID DE TEMPLATE AQUI
            $templateVariables = [
                '1' => $newSchedule->date_start->format('d/m/Y'),
                '2' => $newSchedule->date_start->format('H:i'),
            ];
            
            $whatsapp = new WhatsappService();
            $whatsapp->sendTemplate($clientPhone, $templateSid, $templateVariables);
            
            $this->Flash->success('Seu agendamento foi confirmado com sucesso!');
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']); // Redireciona para um painel do cliente
        }
        
        $this->Flash->error('Ocorreu um erro ao salvar seu agendamento. Por favor, tente novamente.');
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Função auxiliar privada para checar disponibilidade.
     */
    private function isSlotAvailable(\DateTime $slotStart, \DateTime $slotEnd, $bookedSchedules, $exceptions): bool
    {
        foreach ($bookedSchedules as $booking) {
            if ($slotStart < $booking->date_end && $slotEnd > $booking->date_start) {
                return false;
            }
        }
        foreach ($exceptions as $exception) {
            if ($slotStart < $exception->end_exception && $slotEnd > $exception->start_exception) {
                return false;
            }
        }
        return true;
    }
}