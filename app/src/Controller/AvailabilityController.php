<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Availability Controller
 *
 */
class AvailabilityController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Availability->find();
        $availability = $this->paginate($query);

        $this->set(compact('availability'));

        // Carrega as tabelas que vamos usar
        $availableTable = $this->fetchTable('Available');
        $exceptionsTable = $this->fetchTable('Exceptions');

        // Busca todas as regras de horário de trabalho padrão
        $availableRules = $availableTable->find('all')->all();

        // Busca todos os bloqueios que ainda não terminaram
        $exceptions = $exceptionsTable->find()
            ->where(['end_exception >=' => date('Y-m-d H:i:s')])
            ->all();

        // Envia as duas listas de dados para o Template (View)
        $this->set(compact('availableRules', 'exceptions'));
    }

    /**
     * View method
     *
     * @param string|null $id Availability id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $availability = $this->Availability->get($id, contain: []);
        $this->set(compact('availability'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $availability = $this->Availability->newEmptyEntity();
        if ($this->request->is('post')) {
            $availability = $this->Availability->patchEntity($availability, $this->request->getData());
            if ($this->Availability->save($availability)) {
                $this->Flash->success(__('The availability has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The availability could not be saved. Please, try again.'));
        }
        $this->set(compact('availability'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Availability id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $availability = $this->Availability->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $availability = $this->Availability->patchEntity($availability, $this->request->getData());
            if ($this->Availability->save($availability)) {
                $this->Flash->success(__('The availability has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The availability could not be saved. Please, try again.'));
        }
        $this->set(compact('availability'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Availability id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $availability = $this->Availability->get($id);
        if ($this->Availability->delete($availability)) {
            $this->Flash->success(__('The availability has been deleted.'));
        } else {
            $this->Flash->error(__('The availability could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
