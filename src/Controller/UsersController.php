<?php

declare(strict_types=1);

namespace App\Controller;

// Importa a classe de criptografia de senha para uso no login manual
use Authentication\PasswordHasher\DefaultPasswordHasher;
use App\Mailer\UserMailer;

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
     * beforeFilter: É executado antes de qualquer action.
     * Define quais páginas podem ser vistas por usuários não logados.
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Permite o acesso público às actions de 'login' e 'add' (registro).
        $this->Authentication->addUnauthenticatedActions(['login', 'add', 'forgotPassword', 'resetPassword']);
    }

    /**
     * Action de Login.
     */
    public function login()
    {
        $this->Authorization->skipAuthorization(); // Qualquer um pode ver a tela de login.

        // Primeiro, verifica se o usuário já está logado de uma sessão anterior.
        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $user = $this->Authentication->getIdentity();
            $redirectTarget = ($user->user_type === 'admin')
                ? ['controller' => 'Availability', 'action' => 'index']
                : ['controller' => 'Users', 'action' => 'dashboard'];

            return $this->redirect($this->request->getQuery('redirect', $redirectTarget));
        }

        // Se o formulário de login foi enviado (requisição POST).
        if ($this->request->is('post')) {
            $email = $this->request->getData('email');
            $password = $this->request->getData('password');

            // Lógica de autenticação manual que confirmamos que funciona.
            $user = $this->Users->findByEmail($email)->first();

            if ($user && (new DefaultPasswordHasher())->check($password, $user->password)) {
                // Se o usuário foi encontrado e a senha está correta...
                $this->Authentication->setIdentity($user); // ...coloca o usuário na sessão.

                $redirectTarget = ($user->user_type === 'admin')
                    ? ['controller' => 'Availability', 'action' => 'index']
                    : ['controller' => 'Users', 'action' => 'dashboard'];

                return $this->redirect($redirectTarget);
            }

            $this->Flash->error('Usuário ou senha inválidos');
        }
    }

    /**
     * Action de Logout.
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
     * Action de Registro de novos usuários (clientes).
     */
    public function add()
    {
        $this->Authorization->skipAuthorization(); // Permite o registro público.

        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());

            $user->user_type = 'cliente'; // Força o tipo de usuário como 'cliente'.
            $user->date_register = new \DateTime('now'); // Define a data de registro.

            if ($this->Users->save($user)) {
                $this->Flash->success('Sua conta foi criada com sucesso. Por favor, faça o login.');
                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error('Não foi possível criar sua conta. Por favor, tente novamente.');
        }
        $this->set(compact('user'));
    }

    /**
     * Dashboard do Cliente.
     */
    public function dashboard()
    {
        $user = $this->Authentication->getIdentity();
        if (!$user) {
            return $this->redirect(['action' => 'login']);
        }
        $this->Authorization->authorize($user->getOriginalData());

        $userId = $user->id;
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

    // --- MÉTODOS DE GERENCIAMENTO (CRUD) PARA ADMINS ---

    /**
     * Index method: Lista todos os usuários. Requer ser admin.
     */
    public function index()
    {
        $this->Authorization->authorize($this->Authentication->getIdentity()->getOriginalData(), 'admin');

        $query = $this->Users->find();
        $users = $this->paginate($query);
        $this->set(compact('users'));
    }

    /**
     * View method: Vê detalhes de um usuário. Requer ser admin.
     */
    public function view($id = null)
    {
        $this->Authorization->authorize($this->Authentication->getIdentity()->getOriginalData(), 'admin');

        $user = $this->Users->get($id, contain: []);
        $this->set(compact('user'));
    }

    /**
     * Edit method: Edita um usuário. Requer ser admin.
     */
    public function edit($id = null)
    {
        $this->Authorization->authorize($this->Authentication->getIdentity()->getOriginalData(), 'admin');

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
     * Delete method: Deleta um usuário. Requer ser admin.
     */
    public function delete($id = null)
    {
        $this->Authorization->authorize($this->Authentication->getIdentity()->getOriginalData(), 'admin');

        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function forgotPassword()
    {
        // Permite que qualquer um acesse esta página
        $this->Authorization->skipAuthorization();

        // Verifica se o formulário foi enviado (requisição do tipo POST)
        if ($this->request->is('post')) {
            $email = $this->request->getData('email');
            $user = $this->Users->findByEmail($email)->first();

            // Só executa a lógica se encontrar um usuário com o email fornecido
            if ($user) {
                $token = \Cake\Utility\Security::randomString(64);
                $user->password_reset_token = $token;
                $user->token_expires = new \DateTime('+1 hour');

                // Se conseguir salvar o token no banco de dados...
                if ($this->Users->save($user)) {
                    // ...tenta enviar o email.
                    (new \App\Mailer\UserMailer())->send('passwordReset', [$user]);
                }
            }

            // IMPORTANTE: Esta mensagem e o redirect acontecem DEPOIS da lógica acima,
            // independentemente de o email ter sido encontrado ou não.
            // Isso é uma prática de segurança para não confirmar se um email existe no sistema.
            $this->Flash->success('Se o seu email estiver em nossa base de dados, um link de redefinição foi enviado.');

            return $this->redirect(['action' => 'login']);
        }
    }
    /**
     * Action "Redefinir Senha".
     * Verifica o token e permite a alteração da senha.
     */
    public function resetPassword($token = null)
    {
        $this->Authorization->skipAuthorization(); // Página pública

        if ($this->request->is('post')) {
            // Busca o usuário pelo token que ainda está na URL
            $user = $this->Users->findByPasswordResetToken($this->request->getData('token'))->first();
            if ($user && $user->token_expires > new \DateTime('now')) {
                $user = $this->Users->patchEntity($user, $this->request->getData());

                // Limpa o token para que não possa ser usado novamente
                $user->password_reset_token = null;
                $user->token_expires = null;

                if ($this->Users->save($user)) {
                    $this->Flash->success('Sua senha foi alterada com sucesso. Você já pode fazer o login.');
                    return $this->redirect(['action' => 'login']);
                }
            }
            $this->Flash->error('O link de redefinição é inválido ou expirou. Por favor, tente novamente.');
            return $this->redirect(['action' => 'forgotPassword']);
        }

        // Se a requisição for GET, apenas passa o token para a view
        $this->set(compact('token'));
    }
}
