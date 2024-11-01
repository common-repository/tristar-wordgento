<?php
/**
 * @package Wordgento
 */
/*
Plugin Name: Wordgento
Plugin URI: http://www.wordgento.com
Description: Wordgento allows you to seamlessly integrate blocks from your Magento installation into your Wordpress theme
Version: 1.0.0
Author: Tristar Web Design
Author URI: http://www.tristarwebdesign.co.uk
License: GPLv2
*/

/*
This program is free software; you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by 
the Free Software Foundation; version 2 of the License.

This program is distributed in the hope that it will be useful, 
but WITHOUT ANY WARRANTY; without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
GNU General Public License for more details. 

You should have received a copy of the GNU General Public License 
along with this program; if not, write to the Free Software 
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA 
*/


function wordgento_admin() {  
    include('wordgento_admin.php');  
}

function wordgento_admin_actions() {
	add_menu_page('Wordgento', 'Wordgento', 'administrator', __FILE__, 'wordgento_admin',plugins_url('/images/icon.png', __FILE__));
}

add_action('admin_menu', 'wordgento_admin_actions');

function register_scripts() {
	if (is_admin() ) {
	  wp_enqueue_script('wordgento_scripts', plugins_url('js/scripts.js',__FILE__));
	  wp_enqueue_script('wordgento_colorpicker_scripts', plugins_url('js/colorpicker.js',__FILE__));
	}
}
add_action('admin_print_scripts', 'register_scripts');

function register_styles() {
	if (is_admin() ) {
	  wp_enqueue_style('wordgento_styles', plugins_url('css/admin-styles.css',__FILE__));
	  wp_enqueue_style('wordgento_colorpicker', plugins_url('css/colorpicker.css',__FILE__));
	}
}
add_action( 'admin_print_styles', 'register_styles' );

function wordgento_setoptions() {
	add_option('wordgento_magepath', '/your-magento');
	add_option('wordgento_theme', 'default');
	add_option('wordgento_store', 'default');
	
	$wordgento_cssjs = array(
		'option_one' => 1,
		'option_two' => 1,
		'option_three' => 1,
		'option_four' => 1,
		'option_five' => 1,
		'option_six' => 1,
		'option_seven' => 1,
		'option_eight' => 1,
		'option_nine' => 1,
		'option_ten' => 1,
		'option_eleven' => 1,
		'option_twelve' => 1,
		'option_thirteen' => 1,
		'option_fourteen' => 1
	);

	add_option( 'wordgento_cssjs', $wordgento_cssjs );
	
	$wordgento_toplinks = array(
		'option_one' => 1,
		'option_two' => 1,
		'option_three' => 1,
		'option_four' => 1,
		'option_five' => 1
	);

	add_option( 'wordgento_toplinks', $wordgento_toplinks );
			
}

function wordgento_unsetoptions() {
	delete_option('wordgento_magepath');
	delete_option('wordgento_theme');
	delete_option('wordgento_store');
	delete_option( 'wordgento_cssjs');
}

register_activation_hook(__FILE__,'wordgento_setoptions');
// register_deactivation_hook(__FILE__,'wordgento_unsetoptions');

function wordgento_magento_frontend() {
	
	$wordgento_mage = get_option('wordgento_magepath');
	$wordgento_theme = strtolower(get_option('wordgento_theme'));
	$wordgento_store = strtolower(get_option('wordgento_store'));
	$wordgento_magepath_filename = $_SERVER['DOCUMENT_ROOT'].$wordgento_mage.'/app/Mage.php';
	
	$wordgento_css = get_option('wordgento_css');	
	$wordgento_js = get_option('wordgento_js');	
	$wordgento_cssjs = get_option('wordgento_cssjs');
	
	$wordgento_toplinks = get_option('wordgento_toplinks');
	
	if(file_exists($wordgento_magepath_filename)) {
		
		require_once($_SERVER['DOCUMENT_ROOT'].$wordgento_mage.'/app/Mage.php');
		
		if(class_exists('Mage')){ 
		umask(0);
		//$wordgento_app = Mage::app('default');
		// Mage::run('default');		
	
		$wordgento_app = Mage::app($wordgento_store);

		Mage::getSingleton('core/session', array('name'=>'frontend'));
		Mage::getSingleton("checkout/session");
		$wordgento_session = Mage::getSingleton('customer/session');
		// Mage::getSingleton('core/session')->setCoreSession('test');

		$wordgento_block = Mage::getSingleton('core/layout');
		
		$themearr = explode('/', $wordgento_theme);
        if (isset($themearr[1])) {
            Mage::getDesign()->setPackageName($themearr[0])->setTheme($themearr[1]);
        } else {
            Mage::getDesign()->setTheme($wordgento_theme);
        }

		//============================================================================== maybe make this optional

		$wordgento_app->getTranslator()->init('frontend'); 	
		
		# Init Blocks
		$wordgento_linksBlock = $wordgento_block->createBlock("page/template_links");
		
		$wordgento_checkoutLinksBlock = $wordgento_block->createBlock("checkout/links");

		$wordgento_checkoutLinksBlock->setParentBlock($wordgento_linksBlock);
		
		// Wishlist Link in top.links
		
		if($wordgento_toplinks['option_two'] == 1) {
		
			if ($wordgento_linksBlock && $wordgento_linksBlock->helper('wishlist')->isAllow()) {
			
			$wordgento_count = $wordgento_linksBlock->helper('wishlist')->getItemCount();
			
			if ($wordgento_count > 1) {
			
			$wordgento_text = $wordgento_linksBlock->__('My Wishlist (%d items)', $wordgento_count);
			
			}
			
			else if ($wordgento_count == 1) {
			
			$wordgento_text = $wordgento_linksBlock->__('My Wishlist (%d item)', $wordgento_count);
			
			}
			
			else {
			
			$wordgento_text = $wordgento_linksBlock->__('My Wishlist');
			
			}
			
			$wordgento_linksBlock->addLink($wordgento_text, 'wishlist', $wordgento_text, true, array(), 30, null, 'class="top-link-wishlist"');
			
			}
		
		}
	
		// End Wishlist Link in top.links
		
		# Add Links
		if($wordgento_toplinks['option_one'] == 1) { $wordgento_linksBlock->addLink($wordgento_linksBlock->__('My Account'), 'customer/account', $wordgento_linksBlock->__('My Account'), true, array(), 10, 'class="first"'); }
		
		// $wordgento_wishlistLinksBlock->addWishlistLink();
		if($wordgento_toplinks['option_three'] == 1) { $wordgento_checkoutLinksBlock->addCartLink(); }
		if($wordgento_toplinks['option_four'] == 1) { $wordgento_checkoutLinksBlock->addCheckoutLink(); }
		
		if($wordgento_toplinks['option_five'] == 1) {
			if ($wordgento_session->isLoggedIn()) {
			$wordgento_linksBlock->addLink($wordgento_linksBlock->__('Log Out'), 'customer/account/logout', $wordgento_linksBlock->__('Log Out'), true, array(), 100, 'class="last"');
			} else {
			$wordgento_linksBlock->addLink($wordgento_linksBlock->__('Log In'), 'customer/account/login', $wordgento_linksBlock->__('Log In'), true, array(), 100, 'class="last"');
			}
		}
		
		if($wordgento_toplinks['option_one'] == 1 || $wordgento_toplinks['option_two'] == 1 || $wordgento_toplinks['option_three'] == 1 || $wordgento_toplinks['option_four'] == 1 || $wordgento_toplinks['option_five'] == 1) {
			$wordgento_toplinks = $wordgento_linksBlock->toHtml();
		} else {
			$wordgento_toplinks = '<div style="border-width:1px; border-style:solid; padding:0 .6em; margin:5px 15px 10px; -moz-border-radius:3px; -khtml-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; background-color: #FFEBE8; border-color: #CC0000;"><p style="margin:.5em 0; line-height:1; padding:2px; font-size:12px; text-align:left;"><strong>Sorry, your settings are set to not use any of the toplinks available.</strong></p></div>';
		}
		
		// Create head.phtml block
		$wordgento_head = $wordgento_block->createBlock('Page/Html_Head');
		//===================================== perhaps run this through a function - so foreach cssjs - take part1_part2 as the path i.e prototype_prototype = prototype/prototype.js (Magic Method)
		// Add Js	
		if($wordgento_cssjs['option_one'] == 1) { $wordgento_head->addJs('prototype/prototype.js'); }
		if($wordgento_cssjs['option_two'] == 1) { $wordgento_head->addJs('lib/ccard.js'); }
		if($wordgento_cssjs['option_three'] == 1) { $wordgento_head->addJs('prototype/validation.js'); }
		if($wordgento_cssjs['option_four'] == 1) { $wordgento_head->addJs('scriptaculous/builder.js'); }
		if($wordgento_cssjs['option_five'] == 1) { $wordgento_head->addJs('scriptaculous/effects.js'); }
		if($wordgento_cssjs['option_six'] == 1) { $wordgento_head->addJs('scriptaculous/dragdrop.js'); }
		if($wordgento_cssjs['option_seven'] == 1) { $wordgento_head->addJs('scriptaculous/controls.js'); }
		if($wordgento_cssjs['option_eight'] == 1) { $wordgento_head->addJs('scriptaculous/slider.js'); }
		if($wordgento_cssjs['option_nine'] == 1) { $wordgento_head->addJs('varien/js.js'); }
		if($wordgento_cssjs['option_ten'] == 1) { $wordgento_head->addJs('varien/form.js'); }
		if($wordgento_cssjs['option_eleven'] == 1) { $wordgento_head->addJs('varien/menu.js'); }
		if($wordgento_cssjs['option_twelve'] == 1) { $wordgento_head->addJs('mage/translate.js'); }
		if($wordgento_cssjs['option_thirteen'] == 1) { $wordgento_head->addJs('mage/cookies.js'); }
		// Add CSS
		if($wordgento_cssjs['option_fourteen'] == 1) { $wordgento_head->addCss('css/styles.css'); }
		
		if($wordgento_js) { 
			foreach($wordgento_js as $key => $value) {
				$wordgento_head->addJs($value['wordgento_js_path']);
            }
        }
		
		if($wordgento_css) { 
			foreach($wordgento_css as $key => $value) {
				$wordgento_head->addCss($value['wordgento_css_path']);
            }
        }
		
		// Activate and Convert head.phtml html
		$wordgento_getcss = $wordgento_head->getCssJsHtml();
		$wordgento_getinc = $wordgento_head->getIncludes();
		
		// And the footer's HTML as well
		// $wordgento_footer = $wordgento_block->createBlock('Page/Html_Footer')->setTemplate('page/html/footer.phtml');
		// $wordgento_getfooter = $wordgento_footer->toHtml();
		
		// And the footer's HTML as well
		$wordgento_header = $wordgento_block->createBlock('Page/Html_Header');
		$wordgento_getwelcome = $wordgento_header->getWelcome();
		$wordgento_getlogosrc = $wordgento_header->getLogoSrc();
		$wordgento_getlogoalt = $wordgento_header->getLogoAlt();
		$wordgento_geturl = $wordgento_header->getUrl();
		
		$wordgento_logo = "<img src='".$wordgento_getlogosrc."' alt='".$wordgento_getlogoalt."' />";
		
		
		// Add topSearch
		$wordgento_block_topsearch = $wordgento_block->createBlock('core/template')->setTemplate("catalogsearch/form.mini.phtml")->toHtml();
		
		// Add cart_sidebar
		$wordgento_block_sidecart = $wordgento_block->createBlock('checkout/cart_sidebar')->setTemplate("checkout/cart/sidebar.phtml")->toHtml();
		
		// Add catalog.compare.sidebar
		$wordgento_block_compare = $wordgento_block->createBlock('catalog/product_compare_sidebar')->setTemplate("catalog/product/compare/sidebar.phtml")->toHtml();
		
		// Add right.reports.product.viewed
		$wordgento_block_viewed = $wordgento_block->createBlock('reports/product_viewed')->setTemplate("reports/product_viewed.phtml")->toHtml();
		
		// Add right.reports.product.viewed
		$wordgento_block_newsletter = $wordgento_block->createBlock('newsletter/subscribe')->setTemplate("newsletter/subscribe.phtml")->toHtml();
		
		// Add topMenu
		$wordgento_block_topmenu = $wordgento_block->createBlock('catalog/navigation')->setTemplate("catalog/navigation/top.phtml")->toHtml();
		
		// Add wishlist_sidebar
		$wordgento_block_wishlist = $wordgento_block->createBlock('wishlist/customer_sidebar')->setTemplate("wishlist/sidebar.phtml")->toHtml();
		
		// Custom Blocks
		$wordgento_block_topcart = $wordgento_block->createBlock('checkout/cart_sidebar')->setTemplate("checkout/cart/topcart.phtml")->toHtml();
		
		
		// LOOP THROUGH CUSTOM BLOCKS
		$wordgento_blocks = get_option('wordgento_blocks');
		
		if($wordgento_blocks) {
			
			foreach($wordgento_blocks as $wordgento_key => $wordgento_value) { 

				$wordgento_block_path = $wordgento_value['wordgento_template_path'];
						
				$wordgento_block_name = wordgento_block_name( $wordgento_block_path );
				
				
				
				// CHECK IF CUSTOM BLOCK EXISTS
				$themearr = explode('/', $wordgento_theme);
				if (isset($themearr[1])) {
					$customblock = $_SERVER['DOCUMENT_ROOT'].$wordgento_mage.'/app/design/frontend/'.$wordgento_theme.'/template/'.$wordgento_value['wordgento_template_path'];
				} else {
					$customblock = $_SERVER['DOCUMENT_ROOT'].$wordgento_mage.'/app/design/frontend/default/'.$wordgento_theme.'/template/'.$wordgento_value['wordgento_template_path'];
				}
				
				$customblock_base = $_SERVER['DOCUMENT_ROOT'].$wordgento_mage.'/app/design/frontend/base/default/template/'.$wordgento_value['wordgento_template_path'];
				
			
				if(file_exists($customblock) || file_exists($customblock_base)) {
					$wordgento_block_name_definition = strtoupper('WORDGENTO_'.$wordgento_block_name); // Returns topcart (for example)
					$wordgento_new_block = $wordgento_block->createBlock($wordgento_value['wordgento_block_type'])->setTemplate($wordgento_value['wordgento_template_path'])->toHtml();
					
					define($wordgento_block_name_definition, $wordgento_new_block);
				} else {
					$wordgento_block_name_definition = strtoupper('WORDGENTO_'.$wordgento_block_name); // Returns topcart (for example)
					define($wordgento_block_name_definition, '<div style="border-width:1px; border-style:solid; padding:0 .6em; margin:5px 15px 10px; -moz-border-radius:3px; -khtml-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; background-color: #FFEBE8; border-color: #CC0000;"><p style="margin:.5em 0; line-height:1; padding:2px; font-size:12px; text-align:left;"><strong>The block "'.$wordgento_block_name.'" does not exist. Please check your block type and template path.</strong></p></div>');
				}
				// echo $wordgento_block_name.'<br />';
				
			}
			
		}
		// END LOOP THROUGH CUSTOM BLOCKS
		
		define("WORDGENTO_CSSJS", $wordgento_getcss);
		define("WORDGENTO_INC", $wordgento_getinc);
		define("WORDGENTO_WISHLIST", $wordgento_block_wishlist);
		define("WORDGENTO_SEARCH", $wordgento_block_topsearch);
		define("WORDGENTO_TOPMENU", $wordgento_block_topmenu);
		define("WORDGENTO_NEWSLETTER", $wordgento_block_newsletter);
		define("WORDGENTO_VIEWED", $wordgento_block_viewed);
		define("WORDGENTO_TOPLINKS", $wordgento_toplinks);
		define("WORDGENTO_SIDECART", $wordgento_block_sidecart);
		// define("WORDGENTO_TOPCART", $wordgento_block_topcart);
		define("WORDGENTO_COMPARE", $wordgento_block_compare);
		define("WORDGENTO_WELCOME", $wordgento_getwelcome);
		define("WORDGENTO_LOGO", $wordgento_logo);
		define("WORDGENTO_URL", $wordgento_geturl);
	
	} else {
		
		$wordgento_error = '<div style="border-width:1px; border-style:solid; padding:0 .6em; margin:5px 15px 10px; -moz-border-radius:3px; -khtml-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; background-color: #FFEBE8; border-color: #CC0000;"><p style="margin:.5em 0; line-height:1; padding:2px; font-size:12px; text-align:left;"><strong>The path you entered to your magento installation folder was incorrect! Please go to the Wordgento settings and enter the right path.</strong></p></div>';
		
		// LOOP THROUGH CUSTOM BLOCKS
		$wordgento_blocks = get_option('wordgento_blocks');
		
		if($wordgento_blocks) {
			
			foreach($wordgento_blocks as $wordgento_key => $wordgento_value) { 

				$wordgento_block_path = $wordgento_value['wordgento_template_path'];
						
				$wordgento_block_name = wordgento_block_name( $wordgento_block_path );
				
				$wordgento_block_name_definition = strtoupper('WORDGENTO_'.$wordgento_block_name); // Returns topcart (for example)
								
				define($wordgento_block_name_definition, $wordgento_error);
				
			}
			
		}
		// END LOOP THROUGH CUSTOM BLOCKS
		
		define("WORDGENTO_CSSJS", $wordgento_error);
		define("WORDGENTO_INC", $wordgento_error);
		define("WORDGENTO_WISHLIST", $wordgento_error);
		define("WORDGENTO_SEARCH", $wordgento_error);
		define("WORDGENTO_TOPMENU", $wordgento_error);
		define("WORDGENTO_NEWSLETTER", $wordgento_error);
		define("WORDGENTO_VIEWED", $wordgento_error);
		define("WORDGENTO_TOPLINKS", $wordgento_error);
		define("WORDGENTO_SIDECART", $wordgento_error);
		// define("WORDGENTO_TOPCART", $wordgento_error);
		define("WORDGENTO_COMPARE", $wordgento_error);
		define("WORDGENTO_WELCOME", $wordgento_error);
		define("WORDGENTO_LOGO", $wordgento_error);
		define("WORDGENTO_URL", $wordgento_error);
	}
	
	} // End if Class_Exists(MAGE)
	
}

function wordgento_magento_backend() {
	
	$wordgento_mage = get_option('wordgento_magepath');
	$wordgento_theme = strtolower(get_option('wordgento_theme'));
	$wordgento_store = strtolower(get_option('wordgento_store'));
	$wordgento_magepath_filename = $_SERVER['DOCUMENT_ROOT'].$wordgento_mage.'/app/Mage.php';
	
	if(file_exists($wordgento_magepath_filename)) {
		
		require_once($_SERVER['DOCUMENT_ROOT'].$wordgento_mage.'/app/Mage.php');
		
		if(class_exists('Mage')){ 
		umask(0);
		$wordgento_app = Mage::app('default');
		// Mage::run('default');		
	
		$wordgento_app = Mage::app($wordgento_store);

		} // End if Class_Exists(MAGE)
	
	} // End if File_Exists
	
}

add_action('template_redirect', 'wordgento_magento_frontend');
add_action('admin_init', 'wordgento_magento_backend');

function wordgento($wordgento_vwg_var) {
	
	if(class_exists('Mage')){ 
	
	
	// LOOP THROUGH CUSTOM BLOCKS
	$wordgento_blocks = get_option('wordgento_blocks');
	
	if($wordgento_blocks) {
		
		foreach($wordgento_blocks as $wordgento_key => $wordgento_value) { 

			$wordgento_block_path = $wordgento_value['wordgento_template_path'];
			
			$wordgento_block_name = wordgento_block_name( $wordgento_block_path );
			
			$wordgento_block_name = $wordgento_block_name; // Returns topcart (for example)
			$wordgento_block_name_definition = strtoupper('WORDGENTO_'.$wordgento_block_name); // WORDGENTO_TOPCART (for example)
			
			if($wordgento_block_name == $wordgento_vwg_var) {
				return constant($wordgento_block_name_definition);
			}
			
			
			
		}
		
	}
	// END LOOP THROUGH CUSTOM BLOCKS
		
		

	switch ($wordgento_vwg_var) {
		case 'cssjs':
			return WORDGENTO_CSSJS;
			break;
		case 'inc':
			return WORDGENTO_INC;
			break;
		case 'wishlist':
			return WORDGENTO_WISHLIST;
			break;
		case 'topmenu':
			return WORDGENTO_TOPMENU;
			break;
		case 'newsletter':
			return WORDGENTO_NEWSLETTER;
			break;
		case 'recently_viewed':
			return WORDGENTO_VIEWED;
			break;
		case 'toplinks':
			return WORDGENTO_TOPLINKS;
			break;
		case 'compare':
			return WORDGENTO_COMPARE;
			break;
		case 'sidecart':
			return WORDGENTO_SIDECART;
			break;
		case 'welcome':
			return WORDGENTO_WELCOME;
			break;
		case 'search':
			return WORDGENTO_SEARCH;
			break;
		case 'logo':
			return WORDGENTO_LOGO;
			break;
		case 'url':
			return WORDGENTO_URL;
			break;
		default:
       		return '<div style="border-width:1px; border-style:solid; padding:0 .6em; margin:5px 15px 10px; -moz-border-radius:3px; -khtml-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; background-color: #FFEBE8; border-color: #CC0000;"><p style="margin:.5em 0; line-height:1; padding:2px; font-size:12px; text-align:left;"><strong>"'.$wordgento_vwg_var.'" is not currently available, or you have entered the wrong template code.</strong></p></div>';
			break;
	}
	
	}

}
/* Define the custom box */

// WP 3.0+
// add_action( 'add_meta_boxes', 'wordgento_add_custom_box' );

// backwards compatible
add_action( 'admin_init', 'wordgento_add_custom_box', 1 );

/* Do something with the data entered */
add_action( 'save_post', 'wordgento_save_postdata' );

/* Adds a box to the main column on the Post and Page edit screens */
function wordgento_add_custom_box() {
    add_meta_box( 
        'wordgento_sectionid',
        __( 'Wordgento - Add Product to Post', 'wordgento_textdomain' ),
        'wordgento_inner_custom_box',
        'post' 
    );
    add_meta_box(
        'wordgento_sectionid',
        __( 'Wordgento - Add Product to Page', 'wordgento_textdomain' ), 
        'wordgento_inner_custom_box',
        'page'
    );
}

/* Prints the box content */
function wordgento_inner_custom_box( ) {
	
	global $post;

  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'wordgento_noncename' );
  
  $wordgento_product_sku = get_post_meta($post->ID, 'wordgento_product_sku', true);

  // The actual fields for data entry
  echo '<div class="inside">
<p><label for="wordgento_new_field">';
       _e("Magento Product SKU", 'wordgento_textdomain' );
  echo '</label><br /> ';
  echo '<input class="code" type="text" id="wordgento_new_field" name="wordgento_new_field" value="'.$wordgento_product_sku.'" size="25" /></p></div>';
}

/* When the post is saved, saves our custom data */
function wordgento_save_postdata( $post_id ) {
	
  $wordgento_product_sku = get_post_meta($post->ID, 'wordgento_product_sku', true);
  
  // verify if this is an auto save routine. 
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !wp_verify_nonce( $_POST['wordgento_noncename'], plugin_basename( __FILE__ ) ) )
      return;

  
  // Check permissions
  if ( 'page' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;
  }

  // OK, we're authenticated: we need to find and save the data

  $wordgento_fielddata = $_POST['wordgento_new_field'];
        
        if ($wordgento_fielddata && $wordgento_fielddata != $wordgento_product_sku) {
            update_post_meta($post_id, 'wordgento_product_sku', $wordgento_fielddata);
        } elseif ('' == $new && $old) {
            add_post_meta($post_id, 'wordgento_product_sku', $wordgento_fielddata, true);
        }
	
}

// Generator Functions

function wordgento_block_name( $wordgento_block_path ) {
	$wordgento_remove = array('.phtml', '.php');
	$wordgento_replace = array(' ', '-', '.','/');
	$wordgento_block_name = str_replace($wordgento_remove, '', $wordgento_block_path);
	$wordgento_block_name = str_replace($wordgento_replace, '_', $wordgento_block_name);
	return $wordgento_block_name;
}

// Add Styles

add_action('wp_print_styles', 'wordgento_styles');

function wordgento_styles() {
	$myStyleUrl = WP_PLUGIN_URL . '/wordgento/css/style.css.php';
	$myStyleFile = WP_PLUGIN_DIR . '/wordgento/css/style.css.php';
	if ( file_exists($myStyleFile) ) {
		wp_register_style('wordgento_stylesheets', $myStyleUrl);
		wp_enqueue_style( 'wordgento_stylesheets');
	}
}





// Wordgento Products
$wordgento_product_options = get_option('wordgento_product_options');	
if($wordgento_product_options['automan'] == "man") {
	function wordgento_products() {
		
		$filepath = '/includes/wordgento_products.php';
		return require_once(dirname(__FILE__) . $filepath);
			
	}
} else {
	function wordgento_products($content) {
		
		$filepath = '/includes/wordgento_products.php';
		$foo = require_once(dirname(__FILE__) . $filepath);
		
		$content .= $foo;
		
		return $content;
	
	}
	add_filter( 'the_content', 'wordgento_products' );	
}