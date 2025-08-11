<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class UsersController extends AppController
{
    /**
     * beforeFilter: Executado antes de qualquer action.
     * Usamos para definir quais páginas são públicas.
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Permite que usuários NÃO LOGADOS acessem a página de login e a de registro.
        $this->Authentication->addUnauthenticatedActions(['login', 'add']);
    }

    /**
     * Action de Login
     */
    public function login()
    {
        // Pula a verificação de permissão, pois qualquer um pode ver a tela de login.
        $this->Authorization->skipAuthorization();

        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            // Se o login for válido, redireciona o usuário.
            $user = $this->Authentication->getIdentity();
            
            // LÓGICA DE REDIRECIONAMENTO MELHORADA:
            // Se for admin, vai para o painel de disponibilidade.
            // Se for cliente, vai para o seu próprio dashboard.
            $redirectTarget = ($user->user_type === 'admin')
                ? ['controller' => 'Availability', 'action' => 'index']
                : ['controller' => 'Users', 'action' => 'dashboard'];

            $redirect = $this->request->getQuery('redirect', $redirectTarget);
            
            return $this->redirect($redirect);
        }
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error('Usuário ou senha inválidos');
        }
    }

    /**
     * Action de Logout
     */
    public function logout()
    {
        $this->Authorization->skipAuthorization();
        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $this->Authentication->logout();
        }
        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }
    
    /**
     * Action de Registro (Add)
     * Acessível publicamente para novos clientes.
     */
    public function add()
    {
        // Pula a verificação de permissão para que novos usuários possam se registrar.
        $this->Authorization->skipAuthorization();

        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            
            // ALTERAÇÃO DE SEGURANÇA: Garante que todo novo registro seja do tipo 'cliente'.
            $user->user_type = 'cliente';

            if ($this->Users->save($user)) {
                $this->Flash->success('Sua conta foi criada com sucesso. Por favor, faça o login.');
                return $this->redirect(['action' => 'login']); // Redireciona para o login após o sucesso.
            }
            $this->Flash->error('Não foi possível criar sua conta. Por favor, tente novamente.');
        }
        $this->set(compact('user'));
    }

    /**
     * Dashboard do Cliente: Mostra os agendamentos do cliente logado.
     */
    public function dashboard()
    {
        // Garante que o usuário logado só pode ver o seu próprio dashboard.
        $this->Authorization->authorize($this->Authentication->getIdentity()->getOriginalData());

        $userId = $this->Authentication->getIdentity()->id;
        $schedules = $this->fetchTable('Schedule')->find()
            ->contain(['Services'])
            ->where([
                'id_users' => $userId,
                'date_start >=' => new \DateTime('now')
            ])
            ->order(['date_start' => 'ASC'])
            ->all();

        $this->set(compact('schedules'));
    }

    // --- ACTIONS ABAIXO SÃO PARA GERENCIAMENTO DO ADMIN ---
    // Elas não foram totalmente implementadas, mas a estrutura está aqui.

    /**
     * Index: Lista todos os usuários (apenas para admin).
     */
    public function index()
    {
        // Adicionaremos a verificação de permissão para admin aqui
        // $this->Authorization->authorize($this->Authentication->getIdentity()->getOriginalData(), 'admin');
        
        $query = $this->Users->find();
        $users = $this->paginate($query);
        $this->set(compact('users'));
    }

    /**
     * View: Vê o detalhe de um usuário (apenas para admin).
     */
    public function view($id = null)
    {
        // Adicionaremos a verificação de permissão para admin aqui
        
        $user = $this->Users->get($id, contain: []);
        $this->set(compact('user'));
    }

    /**
     * Edit: Edita um usuário (apenas para admin).
     */
    public function edit($id = null)
    {
        // Adicionaremos a verificação de permissão para admin aqui

        $user = $this->Users->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete: Deleta um usuário (apenas para admin).
     */
    public function delete($id = null)
    {
        // Adicionaremos a verificação de permissão para admin aqui

        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}