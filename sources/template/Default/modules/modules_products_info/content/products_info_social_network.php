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

  if (!empty($social_network)) {
?>
  <div class="<?php echo $text_position; ?> col-md-<?php echo $content_width; ?>">
   <div class="separator"></div>
     <div class="ProductsInfoSocialNetWorkTitle"><h4><?php echo CLICSHOPPING::getDef('module_products_info_social_network_text_share'); ?></h4></div>
     <div class="separator"></div>
     <div><?php echo $social_network; ?></div>
  </div>
  <div class="separator"></div>
<?php
   }
?>
