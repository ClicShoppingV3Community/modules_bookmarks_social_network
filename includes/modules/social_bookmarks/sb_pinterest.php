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

  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\HTML;
  use ClicShopping\OM\HTTP;

  class sb_pinterest
  {
    public string $code;
    public $title;
    public $description;
    public ?int $sort_order = 0;
    public bool $enabled = false;
    public $icon = 'pinterest.png';

    public function __construct()
    {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->title = CLICSHOPPING::getDef('module_social_bookmarks_pinterest_title');
      $this->public_title = CLICSHOPPING::getDef('module_social_bookmarks_pinterest_public_title');
      $this->description = CLICSHOPPING::getDef('module_social_bookmarks_pinterest_description');

      if (\defined('MODULE_SOCIAL_BOOKMARKS_PINTEREST_STATUS')) {
        $this->sort_order = MODULE_SOCIAL_BOOKMARKS_PINTEREST_SORT_ORDER;
        $this->enabled = (MODULE_SOCIAL_BOOKMARKS_PINTEREST_STATUS == 'True');
      }
    }

    public function getOutput()
    {

      $CLICSHOPPING_Template = Registry::get('Template');

      $CLICSHOPPING_Db = Registry::get('Db');
      $CLICSHOPPING_ProductsCommon = Registry::get('ProductsCommon');

// add the js in the footer
      $CLICSHOPPING_Template->addBlock('<script src="//assets.pinterest.com/js/pinit.js"></script>', 'footer_scripts');

      $params = [];

// grab the product name (used for description)
      $params['description'] = $CLICSHOPPING_ProductsCommon->getProductsName();

// and image (used for media)
      $Qimage = $CLICSHOPPING_Db->get('products', 'products_image', ['products_id' => (int)$_GET['products_id']]);

      if (!empty($Qimage->value('products_image'))) {
        $image_file = $Qimage->value('products_image');

        $Qimage = $CLICSHOPPING_Db->get('products_images', 'image', ['products_id' => (int)$_GET['products_id']], 'sort_order');

        if ($Qimage->fetch() !== false) {
          do {
            if (!empty($Qimage->value('image'))) {
              $image_file = $Qimage->value('image'); // overwrite image with first multiple product image
              break;
            }
          } while ($Qimage->fetch());
        }

        $params['media'] = CLICSHOPPING::link($CLICSHOPPING_Template->getDirectoryTemplateImages() . $image_file);
      }

// url
      $params['url'] = CLICSHOPPING::link(null, 'Products&Description&products_id=' . (int)$_GET['products_id']);

      $output = '<!-- Pinterest Button start -->' . "\n";
      $output = '<a href="http://pinterest.com/pin/create/button/?';

      foreach ($params as $key => $value) {
        $output .= $key . '=' . urlencode($value) . '&';
      }

      $output = substr($output, 0, -1); //remove last & from the url

      $icon = HTML::image(HTTP::getShopUrlDomain() . $CLICSHOPPING_Template->getDirectoryTemplateImages() . 'icons/social_bookmarks/' . $this->icon, HTML::outputProtected($this->public_title));

      $output .= '" class="pin-it-button" count-layout="' . strtolower(MODULE_SOCIAL_BOOKMARKS_PINTEREST_BUTTON_COUNT_POSITION) . '">' . $icon . '</a>';
      $output .= '<!-- Pinterest Button END -->' . "\n";

      return $output;
    }

    public function isEnabled()
    {
      return $this->enabled;
    }

    public function getPublicTitle()
    {
      return $this->public_title;
    }

    public function check()
    {
      return \defined('MODULE_SOCIAL_BOOKMARKS_PINTEREST_STATUS');
    }

    public function install()
    {
      $CLICSHOPPING_Db = Registry::get('Db');

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Enable Pinterest Module',
          'configuration_key' => 'MODULE_SOCIAL_BOOKMARKS_PINTEREST_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Do you want to allow Pinterest Button?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Layout Position',
          'configuration_key' => 'MODULE_SOCIAL_BOOKMARKS_PINTEREST_BUTTON_COUNT_POSITION',
          'configuration_value' => 'None',
          'configuration_description' => 'Horizontal or Vertical or None',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'Horizontal\', \'Vertical\', \'None\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Sort Order',
          'configuration_key' => 'MODULE_SOCIAL_BOOKMARKS_PINTEREST_SORT_ORDER',
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
      return array('MODULE_SOCIAL_BOOKMARKS_PINTEREST_STATUS',
        'MODULE_SOCIAL_BOOKMARKS_PINTEREST_BUTTON_COUNT_POSITION',
        'MODULE_SOCIAL_BOOKMARKS_PINTEREST_SORT_ORDER'
      );
    }
  }

