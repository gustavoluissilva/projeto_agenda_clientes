<?php

declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

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
use Authentication\Middleware\AuthenticationMiddleware;
use Authorization\Middleware\AuthorizationMiddleware;
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authorization\AuthorizationService;
use Authorization\AuthorizationServiceInterface;
use Authorization\AuthorizationServiceProviderInterface;
use Authorization\Policy\MapRbac;
use Psr\Http\Message\ServerRequestInterface;
use Cake\Routing\Router;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 *
 * @extends \Cake\Http\BaseApplication<\App\Application>
 */
class Application extends BaseApplication implements AuthenticationServiceProviderInterface, AuthorizationServiceProviderInterface
{
    /**
     * Load all the application configuration and bootstrap logic.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        if (PHP_SAPI !== 'cli') {
            // The bake plugin requires fallback table classes to work properly
            FactoryLocator::add('Table', (new TableLocator())->allowFallbackClass(false));
        }
        $this->addPlugin('Authentication'); // Adicione esta linha
        $this->addPlugin('Authorization'); // Adicione esta linha
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            // Middleware de Erros: Deve ser o primeiro para pegar qualquer erro.
            ->add(new ErrorHandlerMiddleware(Configure::read('Error'), $this))

            // Middleware de Assets: Lida com CSS, JS, e imagens.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Middleware de Rotas: Descobre qual controller/action executar a partir da URL.
            ->add(new RoutingMiddleware($this))

            // Middleware de Análise de Corpo: Prepara os dados de formulários.
            ->add(new BodyParserMiddleware())

            // Middleware de Proteção CSRF: Protege contra ataques a formulários.
            ->add(new CsrfProtectionMiddleware([
                'httponly' => true,
            ]));

        // >>> ADICIONE A AUTENTICAÇÃO AQUI <<<
        // Middleware de Autenticação: Verifica QUEM é o usuário (se está logado).
        $middlewareQueue->add(new AuthenticationMiddleware($this));

        // >>> E A AUTORIZAÇÃO LOGO DEPOIS <<<
        // Middleware de Autorização: Verifica O QUE o usuário logado PODE FAZER.
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
    // Método que diz COMO autenticar um usuário
    // Em src/Application.php


    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $service = new AuthenticationService();
        $service->setConfig([
            'unauthenticatedRedirect' => Router::url(['controller' => 'Users', 'action' => 'login']),
            'queryParam' => 'redirect',
        ]);

        $fields = [
            'username' => 'email',
            'password' => 'password',
        ];

        // Carrega os autenticadores
        $service->loadAuthenticator('Authentication.Session');
        $service->loadAuthenticator('Authentication.Form', [
            'fields' => $fields,
            'loginUrl' => Router::url(['controller' => 'Users', 'action' => 'login']),
            // AQUI ESTÁ A CORREÇÃO FINAL E MAIS IMPORTANTE
            'userModel' => 'Users',
        ]);

        // Carrega o identificador
        $service->loadIdentifier('Authentication.Password', [
            'fields' => $fields,
            'resolver' => [
                'className' => 'Authentication.Orm',
                'userModel' => 'Users',
            ],
        ]);

        return $service;
    }

    // Método que diz O QUE cada tipo de usuário pode fazer
    // Em src/Application.php

    // SUBSTITUA SEU MÉTODO getAuthorizationService POR ESTE
    public function getAuthorizationService(ServerRequestInterface $request): AuthorizationServiceInterface
    {
        // Em vez de MapRbac, agora usamos um Resolver
        $resolver = new \Authorization\Policy\OrmResolver();

        // A lógica continua a mesma, mas é encapsulada no serviço
        return new AuthorizationService($resolver);
    }

    /**
     * Register application container services.
     *
     * @param \Cake\Core\ContainerInterface $container The Container to update.
     * @return void
     * @link https://book.cakephp.org/5/en/development/dependency-injection.html#dependency-injection
     */
    public function services(ContainerInterface $container): void {}
}
