<?php
declare(strict_types=1);

namespace App;

use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\Routing\Router;

// Imports de Segurança
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Authorization\AuthorizationService;
use Authorization\AuthorizationServiceInterface;
use Authorization\AuthorizationServiceProviderInterface;
use Authorization\Middleware\AuthorizationMiddleware;
use Authorization\Policy\OrmResolver;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Application setup class.
 */
class Application extends BaseApplication implements AuthenticationServiceProviderInterface, AuthorizationServiceProviderInterface
{
    /**
     * Load all the application configuration and bootstrap logic.
     */
   public function bootstrap(): void
{
    // Chama o bootstrap da classe pai para carregar as configurações padrão
    parent::bootstrap();

    // Carrega os plugins de segurança que precisamos para a aplicação
    $this->addPlugin('Authentication');
    $this->addPlugin('Authorization');
}
    /**
     * Setup the middleware queue your application will use.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            // Pega erros e mostra uma página de erro
            ->add(new ErrorHandlerMiddleware(Configure::read('Error'), $this))

            // Lida com arquivos de assets (CSS, JS, imagens)
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Lida com o roteamento de URLs para Controllers/Actions
            ->add(new RoutingMiddleware($this))

            // Prepara os dados de formulários (POST)
            ->add(new BodyParserMiddleware())

            // Proteção contra ataques CSRF
            ->add(new CsrfProtectionMiddleware([
                'httponly' => true,
            ]));
        
        // Adiciona a verificação de AUTENTICAÇÃO (quem é o usuário) à fila
        $middlewareQueue->add(new AuthenticationMiddleware($this));
        
        // Adiciona a verificação de AUTORIZAÇÃO (o que o usuário pode fazer) à fila
        $middlewareQueue->add(new AuthorizationMiddleware($this, [
            'unauthorizedHandler' => [
                'className' => 'Authorization.Redirect',
                'url' => '/users/login',
                'queryParam' => 'redirect',
                'exceptions' => [
                    \Authorization\Exception\MissingIdentityException::class,
                    \Authorization\Exception\ForbiddenException::class,
                ],
            ],
        ]));

        return $middlewareQueue;
    }

    /**
     * Configura o serviço de AUTENTICAÇÃO
     */
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $service = new AuthenticationService();
        $service->setConfig([

            'log' => true,


            'unauthenticatedRedirect' => Router::url(['controller' => 'Users', 'action' => 'login']),
            'queryParam' => 'redirect',
        ]);

        $fields = [
            'username' => 'email',
            'password' => 'password',
        ];

        // Define os métodos de login: pela sessão (se já logado) ou por formulário.
        $service->loadAuthenticator('Authentication.Session');
        $service->loadAuthenticator('Authentication.Form', [
            'fields' => $fields,
            'loginUrl' => Router::url(['controller' => 'Users', 'action' => 'login']),
            'userModel' => 'Users', // Configuração explícita
        ]);

        // Define como encontrar o usuário no banco de dados.
        $service->loadIdentifier('Authentication.Password', [
            'fields' => $fields,
            'resolver' => [
                'className' => 'Authentication.Orm',
                'userModel' => 'Users', // Configuração explícita
            ],
        ]);

        return $service;
    }
    
    /**
     * Configura o serviço de AUTORIZAÇÃO
     */
    public function getAuthorizationService(ServerRequestInterface $request): AuthorizationServiceInterface
    {
        // Usa o OrmResolver para encontrar os arquivos de Policy automaticamente
        $resolver = new OrmResolver();

        return new AuthorizationService($resolver);
    }

    /**
     * Register application container services.
     */
    public function services(ContainerInterface $container): void
    {
    }
}