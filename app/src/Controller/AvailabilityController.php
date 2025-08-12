<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * Availability Controller
 *
 * Gerencia os horários de trabalho e bloqueios.
 * Apenas usuários 'admin' podem acessar.
 */
class AvailabilityController extends AppController
{
    /**
     * O método initialize é chamado antes de tudo no controller.
     * Vamos usá-lo para garantir que o componente de Autorização seja carregado.
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Authorization.Authorization');
    }

    /**
     * beforeFilter é executado após o initialize, mas antes de cada action.
     * Usamos para a verificação de segurança.
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $user = $this->Authentication->getIdentity();

        // VERIFICAÇÃO DE SEGURANÇA ADICIONADA
        // Se não há usuário logado, não há o que autorizar.
        if (!$user) {
            return;
        }

        // A autorização só acontece se tivermos um usuário.
        $this->Authorization->authorize($user->getOriginalData(), 'admin');
    }

    /**
     * Action Index: Mostra um resumo dos horários e bloqueios.
     */
    public function index()
    {
        $availableRules = $this->fetchTable('Available')->find('all')->all();

        // ALTERADO AQUI: Agora busca na tabela BlockedDates
        $blockedDates = $this->fetchTable('BlockedDates')->find()
            ->where(['end_date >=' => new \DateTime('now')]) // E usa a coluna end_date
            ->order(['start_date' => 'ASC'])
            ->all();

        // Altera o nome da variável enviada para o template
        $this->set(compact('availableRules', 'blockedDates'));
    }

    /**
     * Action setWeekly: Define os horários de trabalho da semana.
     */
    public function setWeekly()
    {
        $availableTable = $this->fetchTable('Available');

        if ($this->request->is('post')) {
            $availableTable->deleteAll(['1 = 1']);
            $formData = $this->request->getData('days', []);
            $error = false;

            foreach ($formData as $dayData) {
                if (isset($dayData['active']) && !empty($dayData['start_shift']) && !empty($dayData['end_shift'])) {
                    $rule = $availableTable->newEntity($dayData);
                    if (!$availableTable->save($rule)) {
                        $error = true;
                    }
                }
            }

            if (!$error) {
                $this->Flash->success('Horários de trabalho atualizados com sucesso.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('Ocorreu um erro ao salvar os horários.');
            }
        }

        $currentRules = $availableTable->find('all')->toArray();
        $this->set(compact('currentRules'));
    }

    /**
     * Action addException: Adiciona um novo bloqueio na agenda.
     */
    // Renomeado de addException para addBlockedDate
    public function addBlockedDate()
    {
        // O código aqui dentro já está correto usando BlockedDates
        $blockedDatesTable = $this->fetchTable('BlockedDates');
        $blockedDate = $blockedDatesTable->newEmptyEntity();

        if ($this->request->is('post')) {
            $blockedDate = $blockedDatesTable->patchEntity($blockedDate, $this->request->getData());
            if ($blockedDatesTable->save($blockedDate)) {
                $this->Flash->success('O período de bloqueio foi salvo com sucesso.');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Não foi possível salvar o período de bloqueio.');
        }

        $this->set(compact('blockedDate'));
    }
}
