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

  class sb_linkedin
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
      $this->icon = 'linkedin.png';

      $this->title = CLICSHOPPING::getDef('module_social_bookmarks_linkedin_title');
      $this->public_title = CLICSHOPPING::getDef('module_social_bookmarks_linkedin_public_title');
      $this->description = CLICSHOPPING::getDef('module_social_bookmarks_linkedin_description');

      if (defined('MODULE_SOCIAL_BOOKMARKS_LINKEDIN_STATUS')) {
        $this->sort_order = MODULE_SOCIAL_BOOKMARKS_LINKEDIN_SORT_ORDER;
        $this->enabled = (MODULE_SOCIAL_BOOKMARKS_LINKEDIN_STATUS == 'True');
      }
    }

    public function getOutput()
    {
      return '<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=' . urlencode(CLICSHOPPING::link(null, 'Products&Description&products_id=' . (int)$_GET['products_id'], false)) . '" target="_blank" rel="noreferrer"><img src="' . 'sources/images/icons/social_bookmarks/' . $this->icon . '" border="0" title="' . HTML::outputProtected($this->public_title) . '"  alt="' . HTML::outputProtected($this->public_title) . '"/></a>';

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
      return defined('MODULE_SOCIAL_BOOKMARKS_LINKEDIN_STATUS');
    }

    public function install()
    {
      $CLICSHOPPING_Db = Registry::get('Db');

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Souhaitez-vous activer ce module ?',
          'configuration_key' => 'MODULE_SOCIAL_BOOKMARKS_LINKEDIN_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Souhaitez vous activer ce module à votre boutique ?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Ordre de tri d\'affichage',
          'configuration_key' => 'MODULE_SOCIAL_BOOKMARKS_LINKEDIN_SORT_ORDER',
          'configuration_value' => '120',
          'configuration_description' => 'Ordre de tri pour l\'affichage (Le plus petit nombre est montré en premier)',
          'configuration_group_id' => '6',
          'sort_order' => '10',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      return $CLICSHOPPING_Db->save('configuration', ['configuration_value' => '1'],
        ['configuration_key' => 'WEBSITE_MODULE_INSTALLED']
      );
    }

    public function remove()
    {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys()
    {
      return array('MODULE_SOCIAL_BOOKMARKS_LINKEDIN_STATUS',
        'MODULE_SOCIAL_BOOKMARKS_LINKEDIN_SORT_ORDER');
    }
  }

