<?php
namespace Selenia\Platform\Config;

use Electro\Interfaces\AssignableInterface;
use Electro\Interfaces\UserInterface;
use Electro\Traits\ConfigurationTrait;

/**
 * Configuration settings for the Platform module.
 *
 * @method $this|boolean allowDeleteSelf (boolean $v = null) Allow a user to delete him(her)self?
 * @method $this|boolean allowEditRole (boolean $v = null) Allow users to edit their role?
 * @method $this|boolean allowRename (boolean $v = null) Allow users to change their usernames?
 * @method $this|string  defaultRole (string $v = null) The pre-selected role when creating new users
 * @method $this|boolean enableProfile (boolean $v = null) Display a menu item for viewing/editing the logged-in user?
 * @method $this|boolean enableTranslations (boolean $v = null) Enable translations editor?
 * @method $this|boolean enableUsersDisabling (boolean $v = null) Support active/inactive user feature?
 * @method $this|boolean enableUsersManagement (boolean $v = null) Enable users management pages?
 * @method $this|string  footer (string $v = null) Sets the footer text displayed on all pages.
 * @method $this|boolean requireAuthentication (boolean $v = null) Enable the authentication middleware for all routes?
 * @method $this|boolean showMenu (boolean $v = null) Display an item for the admin area on the main menu?
 * @method $this|int     sideMenuOffset (int $v = null) The offs. on the active nav. trail from where to begin the menu
 * @method $this|string  topMenuTarget (string $v = null) The target navigation id for generating the top menu
 * @method $this|string  urlPrefix (string $v = null) Relative URL that prefixes all URLs to your app (ex: 'admin')
 * @method $this|boolean autoRouting (boolean $v = null) Enable the auto routing middleware?
 */
class PlatformSettings implements AssignableInterface
{
  use ConfigurationTrait;

  private $allowDeleteSelf       = true;
  private $allowEditRole         = true;
  private $allowRename           = true;
  private $defaultRole           = UserInterface::USER_ROLE_ADMIN;
  private $enableProfile         = true;
  private $enableTranslations    = true;
  private $enableUsersDisabling  = true;
  private $enableUsersManagement = true;
  private $footer                = 'Copyright &copy; <a href="http://impactwave.com">Impactwave; Lda</a>. All rights reserved.' .
                                   '<div class="pull-right hidden-xs">Version 1.0</div>';
  private $requireAuthentication = false;
  private $showMenu              = true;
  private $sideMenuOffset        = 1;
  private $topMenuTarget         = 'mainMenu';
  private $urlPrefix             = '';
  private $autoRouting           = false;

  /**
   * @return string Gets a route pattern suitable for defining a routing group for routes whose URL begins with
   * {@see urlPrefix}.
   */
  public function getBaseURLPattern ()
  {
    return $this->urlPrefix === '' ? '*' : "$this->urlPrefix...";
  }

}
