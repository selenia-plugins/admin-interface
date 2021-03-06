<?php

namespace Selenia\Platform\Config;

use Electro\Authentication\Middleware\AuthenticationMiddleware;
use Electro\Http\Lib\Http;
use Electro\Interfaces\Http\RedirectionInterface;
use Electro\Interfaces\Http\RequestHandlerInterface;
use Electro\Interfaces\Http\RouterInterface;
use Electro\Interfaces\Http\Shared\ApplicationMiddlewareInterface;
use Electro\Interfaces\SessionInterface;
use Electro\Interfaces\UserInterface;
use Electro\Routing\Middleware\AutoRoutingMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Selenia\Platform\Components\Pages\Languages\LanguagesList;
use Selenia\Platform\Components\Pages\Translations\TranslationsForm;
use Selenia\Platform\Components\Pages\Translations\TranslationsList;
use Selenia\Platform\Components\Pages\Users\UserPage;
use Selenia\Platform\Components\Pages\Users\UsersPage;

class Routes implements RequestHandlerInterface
{
  /** @var RedirectionInterface */
  private $redirection;
  /** @var RouterInterface */
  private $router;
  /**
   * @var SessionInterface
   */
  private $session;
  /** @var PlatformSettings */
  private $settings;

  public function __construct (RouterInterface $router, RedirectionInterface $redirection, PlatformSettings $settings,
                               ApplicationMiddlewareInterface $middleware, SessionInterface $session)
  {
    $this->router      = $router;
    $this->redirection = $redirection;
    $this->settings    = $settings;

    if ($settings->autoRouting ())
      $middleware->add (AutoRoutingMiddleware::class, null, null, 'router');

    $this->session = $session;
  }

  function __invoke (ServerRequestInterface $request, ResponseInterface $response, callable $next)
  {
    $this->redirection->setRequest ($request);
    $base = $this->settings->urlPrefix ();
    $base = $base ? "$base..." : '*';
    return $this->router
      ->set ([
        $base =>
          [
            when ($this->settings->requireAuthentication (), AuthenticationMiddleware::class),

            '.' => page ('platform/home.html'),

            'settings...' => [
              'languages/enabled'           => LanguagesList::class,
              'languages/translations'      => TranslationsList::class,
              'languages/translations/@key' => [
                function ($req, $res, $next) {
                  $oUser = $this->session->user ();
                  $sKey  = $req->getAttribute ('@key');
                  if (!$sKey && $oUser && $oUser->getFields ()['role'] < UserInterface::USER_ROLE_ADMIN)
                    return Http::response ($res, 'Not allowed', 403);
                  return $next();
                },
                TranslationsForm::class,
              ],
              when ($this->settings->enableUsersManagement (),
                [
                  'users-management...' => [
                    '.'     => redirectTo ('app_home'),
                    'users' => injectableWrapper (function (UsersPage $page) {
                      // This is done here just to show off this possibility
                      $page->templateUrl = 'platform/users/users.html';
                      return $page;
                    }),

                    'users/@id' => UserPage::class,

                    'profile' => injectableWrapper (function (UserPage $page) {
                      $page->editingSelf = true;
                      return $page;
                    }),
                  ],
                ]
              ),
            ],
          ],
      ])
      ->__invoke ($request, $response, $next);
  }

}
