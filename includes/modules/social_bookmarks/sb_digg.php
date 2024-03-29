<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT

   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\HTML;
  use ClicShopping\OM\HTTP;

  class sb_digg
  {

    public string $code;
    public $title;
    public $description;
    public ?int $sort_order = 0;
    public bool $enabled = false;
    public $icon;

    public function __construct()
    {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->icon = 'digg.png';
      $this->title = CLICSHOPPING::getDef('module_social_bookmarks_digg_title');
      $this->public_title = CLICSHOPPING::getDef('module_social_bookmarks_digg_public_title');
      $this->description = CLICSHOPPING::getDef('module_social_bookmarks_digg_description');

      if (\defined('MODULE_SOCIAL_BOOKMARKS_DIGG_STATUS')) {
        $this->sort_order = MODULE_SOCIAL_BOOKMARKS_DIGG_SORT_ORDER;
        $this->enabled = (MODULE_SOCIAL_BOOKMARKS_DIGG_STATUS == 'True');
      }
    }

    public function isEnabled()
    {
      return $this->enabled;
    }

    public function getOutput()
    {
      $CLICSHOPPING_Template = Registry::get('Template');

      $link = '<a href="http://digg.com/submit?url=' . urlencode(CLICSHOPPING::link(null, 'Products&Description&products_id=' . (int)$_GET['products_id'])) . '" target="_blank" rel="noreferrer">' . HTML::image(HTTP::getShopUrlDomain() . $CLICSHOPPING_Template->getDirectoryTemplateImages() . 'icons/social_bookmarks/' . $this->icon, HTML::outputProtected($this->public_title)) . '</a>';

      return $link;
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
      return \defined('MODULE_SOCIAL_BOOKMARKS_DIGG_STATUS');
    }

    public function install()
    {
      $CLICSHOPPING_Db = Registry::get('Db');

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Enable Digg Module',
          'configuration_key' => 'MODULE_SOCIAL_BOOKMARKS_DIGG_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Do you want to allow products to be shared through Digg?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Sort Order',
          'configuration_key' => 'MODULE_SOCIAL_BOOKMARKS_DIGG_SORT_ORDER',
          'configuration_value' => '0',
          'configuration_description' => 'Sort order of display. Lowest is displayed first.',
          'configuration_group_id' => '6',
          'sort_order' => '0',
          'date_added' => 'now()'
        ]
      );
    }

    public function remove()
    {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys()
    {
      return array('MODULE_SOCIAL_BOOKMARKS_DIGG_STATUS', 'MODULE_SOCIAL_BOOKMARKS_DIGG_SORT_ORDER');
    }
  }

