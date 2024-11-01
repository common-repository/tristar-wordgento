<?php 
/*
	Loads Products defined in a post or page
	@since 2.0.0

	Wordgento
	Copyright (c) 2011 James C Kemp

*/	
require_once('load-wp.php' ); 

$post_sku = get_post_meta($post->ID, 'wordgento_product_sku', TRUE); 

$wordgento_product_options = get_option('wordgento_product_options');

// Setup
$wordgento_grid_list = $wordgento_product_options['option_one'];
$wordgento_grid_col = $wordgento_product_options['option_two'];
$wordgento_grid_col_spacing = $wordgento_product_options['option_three'];
$wordgento_initial_image_width = $wordgento_product_options['option_four'];

// Border
$wordgento_image_border = $wordgento_product_options['option_nine'];
$wordgento_border_color = $wordgento_product_options['option_ten'];
$wordgento_border_width = $wordgento_product_options['option_eleven'];
$wordgento_border_corners = $wordgento_product_options['option_twelve'];
$wordgento_border_corner_radius = $wordgento_product_options['option_thirteen'];

// Shadow
$wordgento_shadow = $wordgento_product_options['option_fourteen'];
$wordgento_shadow_x = $wordgento_product_options['option_fifteen'];
$wordgento_shadow_y = $wordgento_product_options['option_sixteen'];
$wordgento_shadow_blur = $wordgento_product_options['option_seventeen'];
$wordgento_shadow_color = $wordgento_product_options['option_eighteen'];

// Widths
if($wordgento_image_border == 1) {
	$wordgento_image_width = $wordgento_initial_image_width - ($wordgento_border_width * 2);
} else {
	$wordgento_image_width = $wordgento_initial_image_width;
}

// Data
$wordgento_show_title = $wordgento_product_options['option_five'];
$wordgento_title_tag = $wordgento_product_options['option_six'];
$wordgento_show_desc = $wordgento_product_options['option_seven'];
$wordgento_add_or_view = $wordgento_product_options['option_eight'];

// End STYLING

$post_skus = explode(',',$post_sku);

$count_products = count($post_skus);
$product_number = 1;

$output = '';


// Start Output
if($post_sku) {
	
	$output .= '<div id="wordgento_products">';
	
	$i = 1; foreach($post_skus as $post_sku) {
		
		$_product = Mage::getModel('catalog/product')->loadByAttribute('sku', $post_sku);
		
		if($_product) {
			
			if($i == 1) { $output .= '<div class="wordgento_products_grid">'; } 
			$output .= '<div class="wordgento_product'; if($i == $wordgento_grid_col) { $output .= ' last'; } $output .= '">';

                $output .= '<a class="wordgento_image" href="' . $prod_url . '" title="' . $_product->getName() . '">';
                    $output .= '<img width="' . $wordgento_image_width . '" title="' . $_product->getName() . '" src="' . Mage::helper('catalog/image')->init($_product, 'thumbnail')->constrainOnly(TRUE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize($wordgento_image_width) . '" alt="' . $_product->getName() . '">';
                $output .= '</a>';
				
				$output .= '<'.$wordgento_title_tag.' class="wordgento_product_title"><a href="' . $prod_url . '" title="' . $_product->getName() . '">' . $_product->getName() . '</a></'.$wordgento_title_tag.'>';

				$output .= '<div class="post-product-info">';
					$output .= $_product->getShortDescription();
					
					if ($_product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE && $wordgento_add_or_view == "add") {
						
						$attVal = Mage::getModel('Catalog/Product_Option')->getProductOptionCollection($_product);
						if($post_product_url) { $prod_url = $post_product_url; } else { $prod_url = $_product->getProductUrl(); };
						$wordgento_mage = get_option('wordgento_magepath');
                        
                        $output .= '<form method="get" action="' . Mage::getUrl('checkout/cart') . 'add" target="_blank">';
                        	$output .= '<input type="hidden" value="' . $_product->getID() . '" name="product" />';

							$options = "";
                            $hasAtts = 0;
                            
                            // $attVal = $_product->getOptions();
                            
                            if(sizeof($attVal)) {
                            
                            $hasAtts++;
                            
                            foreach($attVal as $optionVal) {
                                $options .= '<div style="clear:both; margin:0 0 15px;">';
                                $options .= $optionVal->getTitle().": ";
                                $options .= "<select name='options[".$optionVal->getId()."]'>";
                                
                                foreach($optionVal->getValues() as $valuesKey => $valuesVal) {
                                    $options .= "<option value='".$valuesVal->getId()."'>".$valuesVal->getTitle()."</option>";
                                }
                                
                                $options .= "</select>";
                                $options .= '</div>';
                            }
                            
                            $output .= '$options';
                            
                            }
                            

                            $output .= '<span class="qty-box"><label for="qty">Qty:</label>';
                            $output .= '<input type="text" value="" maxlength="12" class="input-text qty" name="qty"></span>';
                            $output .= '<button class="wordgento-addto" type="submit"><span><span>&pound;' . number_format($_product->getFinalPrice(), 2, '.', '') . '</span></span></button>';
						$output .= '</form>';

					} elseif ($_product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE && $wordgento_add_or_view == "view") {
						
						$output .= '<button class="wordgento-addto simple" type="submit"><span><span>&pound;' . number_format($_product->getFinalPrice(), 2, '.', '') . '</span></span></button>';

					} elseif ($_product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
						
						$output .= '<button class="wordgento-addto bundle" type="submit"><span><span>&pound;' . number_format($_product->getFinalPrice(), 2, '.', '') . '</span></span></button>';

					} elseif ($_product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_GROUPED) {

						$output .= '<button class="wordgento-addto grouped" type="submit"><span><span>&pound;' . number_format($_product->getFinalPrice(), 2, '.', '') . '</span></span></button>';

					} elseif ($_product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {

						$output .= '<button class="wordgento-addto configurable" type="submit"><span><span>&pound;' . number_format($_product->getFinalPrice(), 2, '.', '') . '</span></span></button>';

					}

				$output .= '</div>';

			} else {
				
				if($i == 1) { $output .= '<div class="wordgento_products_grid">'; } 

				$output .= '<div class="wordgento_product'; if($i == $wordgento_grid_col) { $output .= ' last'; } $output .= '">';
					$output .= '<p>Sorry, the product "' . $post_sku . '" does not exist.</p>';
			
			} // End if($_product)
			
		$output .= '</div>'; // End productitem

	if($i == $wordgento_grid_col && $count_products != $product_number) { $i = 0; $output .= '</div>'; /* End grid wrapper */ }
	if($count_products == $product_number) { $output .= '</div>';  /* End grid wrapper */ }

	$i++; $product_number++; } // End Foreach
	
	$output .= '</div>'; // End #wordgento_products

} 

return $output;

?>