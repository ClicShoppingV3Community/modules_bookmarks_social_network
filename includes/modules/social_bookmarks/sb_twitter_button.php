<?php
/**
 *
 *  @copyright 2008 - https://www.clicshopping.org
 *  @Brand : ClicShopping(Tm) at Inpi all right Reserved
 *  @Licence GPL 2 & MIT
 *  @licence MIT - Portion of osCommerce 2.4
 *  @Info : https://www.clicshopping.org/forum/trademark/
 *
 */

  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\HTML;
  use ClicShopping\OM\HTTP;

  class sb_twitter_button {
    public $code;
    public $title;
    public $description;
    public $sort_order;
    public $enabled = false;
    public $icon;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);
      $this->icon = 'twitter.png';

      $this->title = CLICSHOPPING::getDef('module_social_bookmarks_twitter_button_title');
      $this->public_title = CLICSHOPPING::getDef('module_social_bookmarks_twitter_button_public_title');
      $this->description = CLICSHOPPING::getDef('module_social_bookmarks_twitter_button_description');

      if ( defined('MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_STATUS') ) {
        $this->sort_order = MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_SORT_ORDER;
        $this->enabled = (MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_STATUS == 'True');
      }
    }

    public function getOutput() {
      $params = array('url=' . urlencode(CLICSHOPPING::link(null, 'Products&Description&products_id=' . $_GET['products_id'])));

      if ( strlen(MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_ACCOUNT) > 0 ) {
        $params[] = 'via=' . urlencode(MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_ACCOUNT);
      }

      if ( strlen(MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_RELATED_ACCOUNT) > 0 ) {
        $params[] = 'related=' . urlencode(MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_RELATED_ACCOUNT) . ((strlen(MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_RELATED_ACCOUNT_DESC) > 0) ? ':' . urlencode(MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_RELATED_ACCOUNT_DESC) : '');
      }

      if ( MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_COUNT_POSITION == 'Vertical' ) {
        $params[] = 'count=vertical';
      } elseif ( MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_COUNT_POSITION == 'None' ) {
        $params[] = 'count=none';
      }

      $params = implode('&', $params);

      return '<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script><a href="http://twitter.com/share?' . $params . '" target="_blank" rel="noreferrer" class="twitter-share-button">' . HTML::outputProtected($this->public_title) . '</a>';
    }

    public function isEnabled() {
      return $this->enabled;
    }

    public function getIcon() {
      return $this->icon;
    }

    public function getPublicTitle() {
      return $this->public_title;
    }

    public function check() {
      return defined('MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_STATUS');
    }

    public function install() {
      $CLICSHOPPING_Db = Registry::get('Db');

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Enable Twitter Button Module',
          'configuration_key' => 'MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Do you want to allow products to be shared through Twitter Button?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Shop Owner Twitter Account',
          'configuration_key' => 'MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_ACCOUNT',
          'configuration_value' => '',
          'configuration_description' => 'The Twitter account to attribute the tweet to and is recommended to the user to follow.',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Related Twitter Account',
          'configuration_key' => 'MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_RELATED_ACCOUNT',
          'configuration_value' => '',
          'configuration_description' => 'A related Twitter account that is also recommended to the user to follow.',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Related Twitter Account Description',
          'configuration_key' => 'MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_RELATED_ACCOUNT_DESC',
          'configuration_value' => '',
          'configuration_description' => 'A description for the related Twitter account.',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Count Position',
          'configuration_key' => 'MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_COUNT_POSITION',
          'configuration_value' => 'Horizontal',
          'configuration_description' => 'The position of the counter.',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'Horizontal\', \'Vertical\', \'None\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Sort Order',
          'configuration_key' => 'MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_SORT_ORDER',
          'configuration_value' => '0',
          'configuration_description' => 'Sort order of display. Lowest is displayed first.',
          'configuration_group_id' => '6',
          'sort_order' => '6',
          'date_added' => 'now()'
        ]
      );
    }

    public function remove() {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys() {
      return array('MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_STATUS',
                   'MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_ACCOUNT',
                   'MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_RELATED_ACCOUNT',
                   'MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_RELATED_ACCOUNT_DESC',
                   'MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_COUNT_POSITION',
                   'MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_SORT_ORDER'
                  );
    }
  }
