<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT
   * @licence MIT - Portion of osCommerce 2.4
   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\HTTP;

  class sb_email
  {

    public $code;
    public string $title;
    public string $description;
    public ?int $sort_order = 0;
    public bool $enabled = false;
    public $icon;

    public function __construct()
    {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);
      $this->icon = 'email.png';

      $this->title = CLICSHOPPING::getDef('module_social_bookmarks_email_title');
      $this->public_title = CLICSHOPPING::getDef('module_social_bookmarks_email_public_title');
      $this->description = CLICSHOPPING::getDef('module_social_bookmarks_email_description');

      if (defined('MODULE_SOCIAL_BOOKMARKS_EMAIL_STATUS')) {
        $this->sort_order = MODULE_SOCIAL_BOOKMARKS_EMAIL_SORT_ORDER;
        $this->enabled = (MODULE_SOCIAL_BOOKMARKS_EMAIL_STATUS == 'True');
      }
    }

    public function getOutput()
    {
      $CLICSHOPPING_Template = Registry::get('Template');

      $return = HTML::link(CLICSHOPPING::link(null, 'Products&TellAFriend&products_id=' . (int)$_GET['products_id']), HTML::image(HTTP::getShopUrlDomain() . $CLICSHOPPING_Template->getDirectoryTemplateImages() . 'icons/social_bookmarks/' . $this->icon, HTML::outputProtected($this->public_title)));

      return $return;
    }

    public function isEnabled()
    {
      return $this->enabled;
    }

    public function getIcon()
    {
      return $this->icon;
    }

    public function getPublicTitle()
    {
      return $this->public_title;
    }

    public function check()
    {
      return defined('MODULE_SOCIAL_BOOKMARKS_EMAIL_STATUS');
    }

    public function install()
    {
      $CLICSHOPPING_Db = Registry::get('Db');

      if ($_SESSION['language_id'] == 1) {


        $CLICSHOPPING_Db->save('configuration', [
            'configuration_title' => 'Permettre le partage du produit via E-Mail',
            'configuration_key' => 'MODULE_SOCIAL_BOOKMARKS_EMAIL_STATUS',
            'configuration_value' => 'True',
            'configuration_description' => 'Souhaitez-vous permettre le partage du produit via les e-mail ?',
            'configuration_group_id' => '6',
            'sort_order' => '1',
            'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
            'date_added' => 'now()'
          ]
        );

        $CLICSHOPPING_Db->save('configuration', [
            'configuration_title' => 'Ordre de tri d\'affichage',
            'configuration_key' => 'MODULE_SOCIAL_BOOKMARKS_EMAIL_SORT_ORDER',
            'configuration_value' => '120',
            'configuration_description' => 'Ordre de tri pour l\'affichage (Le plus petit nombre est montré en premier)',
            'configuration_group_id' => '6',
            'sort_order' => '10',
            'set_function' => '',
            'date_added' => 'now()'
          ]
        );

      } else {

        $CLICSHOPPING_Db->save('configuration', [
            'configuration_title' => 'Enable E-Mail Module',
            'configuration_key' => 'MODULE_SOCIAL_BOOKMARKS_EMAIL_STATUS',
            'configuration_value' => 'True',
            'configuration_description' => 'Do you want to allow products to be shared through e-mail?',
            'configuration_group_id' => '6',
            'sort_order' => '1',
            'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
            'date_added' => 'now()'
          ]
        );

        $CLICSHOPPING_Db->save('configuration', [
            'configuration_title' => 'Sort Order',
            'configuration_key' => 'MODULE_SOCIAL_BOOKMARKS_EMAIL_SORT_ORDER',
            'configuration_value' => '0',
            'configuration_description' => 'Sort order of display. Lowest is displayed first.',
            'configuration_group_id' => '6',
            'sort_order' => '0',
            'date_added' => 'now()'
          ]
        );
      }
    }

    public function remove()
    {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys()
    {
      return array('MODULE_SOCIAL_BOOKMARKS_EMAIL_STATUS', 'MODULE_SOCIAL_BOOKMARKS_EMAIL_SORT_ORDER');
    }
  }

