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

        // Passamos a entidade User original em vez do objeto Identity
        $this->Authorization->authorize($user->getOriginalData(), 'admin');
    }

    /**
     * Action Index: Mostra um resumo dos horários e bloqueios.
     */
    public function index()
    {
        $availableRules = $this->fetchTable('Available')->find('all')->all();
        $exceptions = $this->fetchTable('Exceptions')->find()
            ->where(['end_exception >=' => new \DateTime('now')])
            ->order(['start_exception' => 'ASC'])
            ->all();

        $this->set(compact('availableRules', 'exceptions'));
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
    public function addException()
    {
        $exceptionsTable = $this->fetchTable('Exceptions');
        $exception = $exceptionsTable->newEmptyEntity();

        if ($this->request->is('post')) {
            $exception = $exceptionsTable->patchEntity($exception, $this->request->getData());
            if ($exceptionsTable->save($exception)) {
                $this->Flash->success('O período de bloqueio foi salvo com sucesso.');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Não foi possível salvar o período de bloqueio.');
        }

        $this->set(compact('exception'));
    }
}
