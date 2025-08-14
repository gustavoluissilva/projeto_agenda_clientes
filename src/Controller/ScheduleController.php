<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenDate;

/**
 * Schedule Controller
 *
 * @property \App\Model\Table\ScheduleTable $Schedule
 */
class ScheduleController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Schedule->find();
        $schedule = $this->paginate($query);

        $this->set(compact('schedule'));
    }

    /**
     * View method
     *
     * @param string|null $id Schedule id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $schedule = $this->Schedule->get($id, contain: []);
        $this->set(compact('schedule'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $schedule = $this->Schedule->newEmptyEntity();
        if ($this->request->is('post')) {
            $schedule = $this->Schedule->patchEntity($schedule, $this->request->getData());
            if ($this->Schedule->save($schedule)) {
                $this->Flash->success(__('The schedule has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The schedule could not be saved. Please, try again.'));
        }
        $this->set(compact('schedule'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Schedule id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $schedule = $this->Schedule->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $schedule = $this->Schedule->patchEntity($schedule, $this->request->getData());
            if ($this->Schedule->save($schedule)) {
                $this->Flash->success(__('The schedule has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The schedule could not be saved. Please, try again.'));
        }
        $this->set(compact('schedule'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Schedule id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $schedule = $this->Schedule->get($id);
        if ($this->Schedule->delete($schedule)) {
            $this->Flash->success(__('The schedule has been deleted.'));
        } else {
            $this->Flash->error(__('The schedule could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function calendarioAdmin()
    {
        $this->Authorization->skipAuthorization();

        // ETAPA 1: CONTROLE DE ACESSO (sem alterações)
        $identity = $this->request->getAttribute('identity');
        if (!$identity || $identity->get('user_type') !== 'admin') {
            $this->Flash->error('Acesso restrito para administradores.');
            return $this->redirect(['controller' => 'Pages', 'action' => 'display', 'home']);
        }

        // ETAPA 2: BUSCAR TODOS OS AGENDAMENTOS PARA O CALENDÁRIO (sem alterações)
        $agendamentos = $this->Schedule->find('all', ['contain' => ['Users']]);
        $eventos = [];
        foreach ($agendamentos as $agendamento) {
            $eventos[] = [
                'id' => $agendamento->id,
                'title' => $agendamento->user->names ?? 'Cliente ID: ' . $agendamento->id_users,
                'start' => $agendamento->date_start->format('Y-m-d\TH:i:s'),
                'extendedProps' => [
                    'horario' => $agendamento->date_start->format('H:i'),
                    'status' => ucfirst($agendamento->status),
                    'observacao' => $agendamento->observation,
                ]
            ];
        }
        $agendamentosJson = json_encode($eventos);

        // ETAPA 3: BUSCAR AS LISTAS DE AGENDAMENTOS (ATUALIZADO)
        $hoje = new FrozenDate('now');
        $umAnoAtras = $hoje->subYears(1);

        // Lista 1: Agendamentos de hoje e do futuro
        $proximosAgendamentos = $this->Schedule->find('all')
            ->where(['DATE(Schedule.date_start) >=' => $hoje->format('Y-m-d')])
            ->contain(['Users'])
            ->order(['Schedule.date_start' => 'ASC']);

        // NOVO: Lista 2: Histórico de agendamentos passados
        $agendamentosPassados = $this->Schedule->find('all')
            ->where(['DATE(Schedule.date_start) <' => $hoje->format('Y-m-d'),
            'DATE(Schedule.date_start) >=' => $umAnoAtras->format('Y-m-d'),]) // Condição 2: Maior ou igual a um ano atrás
            ->contain(['Users'])
            ->order(['Schedule.date_start' => 'DESC']); // Ordem descendente para mostrar os mais recentes primeiro

        // Envia TODAS as variáveis para o template
        $this->set(compact('agendamentosJson', 'proximosAgendamentos', 'agendamentosPassados', 'hoje'));
    }
}
