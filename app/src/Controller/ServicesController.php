<?php
declare(strict_types=1); // Ativa verificação de tipos mais estrita no PHP

namespace App\Controller; // Define que esta classe pertence ao namespace App\Controller

/**
 * Services Controller
 *
 * @property \App\Model\Table\ServicesTable $Services
 * 
 * Essa anotação indica que o controlador tem acesso ao objeto $Services,
 * que é a tabela/model responsável por manipular dados da tabela `services` no banco.
 */
class ServicesController extends AppController
{
    /**
     * Index method
     * Lista todos os serviços com paginação
     */
    public function index()
    {
        // Autoriza apenas usuários com permissão de 'admin' para acessar
        $this->Authorization->authorize($this->Authentication->getIdentity(), 'admin');
        
        // Cria uma query para buscar todos os registros da tabela services
        $query = $this->Services->find();
        
        // Pagina o resultado (CakePHP aplica limite automático e navegação)
        $services = $this->paginate($query);

        // Envia a variável $services para a view
        $this->set(compact('services'));
    }

    /**
     * View method
     * Mostra os detalhes de um serviço específico pelo ID
     */
    public function view($id = null)
    {
        $this->Authorization->authorize($this->Authentication->getIdentity(), 'admin');

        // Busca um registro específico da tabela services pelo ID
        $service = $this->Services->get($id, contain: []);

        // Disponibiliza $service para ser usado na view
        $this->set(compact('service'));
    }

    /**
     * Add method
     * Adiciona um novo serviço no banco
     */
    public function add()
    {
        $this->Authorization->authorize($this->Authentication->getIdentity(), 'admin');

        // Cria uma nova entidade vazia para receber os dados
        $service = $this->Services->newEmptyEntity();

        // Se o formulário foi enviado via POST
        if ($this->request->is('post')) {
            // Preenche a entidade com os dados enviados
            $service = $this->Services->patchEntity($service, $this->request->getData());

            // Tenta salvar no banco
            if ($this->Services->save($service)) {
                $this->Flash->success(__('The service has been saved.'));
                return $this->redirect(['action' => 'index']); // Redireciona para a lista
            }
            // Caso dê erro no salvamento
            $this->Flash->error(__('The service could not be saved. Please, try again.'));
        }

        // Envia a entidade para a view (necessário para o form funcionar)
        $this->set(compact('service'));
    }

    /**
     * Edit method
     * Edita um serviço existente
     */
    public function edit($id = null)
    {
        $this->Authorization->authorize($this->Authentication->getIdentity(), 'admin');

        // Busca o serviço pelo ID
        $service = $this->Services->get($id, contain: []);

        // Se o formulário foi enviado via PATCH, POST ou PUT
        if ($this->request->is(['patch', 'post', 'put'])) {
            // Atualiza a entidade com os dados enviados
            $service = $this->Services->patchEntity($service, $this->request->getData());

            // Salva no banco
            if ($this->Services->save($service)) {
                $this->Flash->success(__('The service has been saved.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('The service could not be saved. Please, try again.'));
        }

        $this->set(compact('service'));
    }

    /**
     * Delete method
     * Remove um serviço do banco
     */
    public function delete($id = null)
    {
        $this->Authorization->authorize($this->Authentication->getIdentity(), 'admin');

        // Garante que só será possível deletar via POST ou DELETE (mais seguro)
        $this->request->allowMethod(['post', 'delete']);

        // Busca o serviço pelo ID
        $service = $this->Services->get($id);

        // Tenta excluir
        if ($this->Services->delete($service)) {
            $this->Flash->success(__('The service has been deleted.'));
        } else {
            $this->Flash->error(__('The service could not be deleted. Please, try again.'));
        }

        // Redireciona de volta para a lista
        return $this->redirect(['action' => 'index']);
    }
}
