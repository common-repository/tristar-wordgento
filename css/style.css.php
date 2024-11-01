<?php header("Content-type: text/css");
/*
	Styles for Wordgento Products
	@since 2.0.0

	Wordgento
	Copyright (c) 2011 James C Kemp

*/ 
require_once('../includes/load-wp.php' );
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



$products_grid_width = (($wordgento_grid_col - 1) * $wordgento_grid_col_spacing) + ($wordgento_initial_image_width * $wordgento_grid_col);
$blur_max = max($wordgento_shadow_x, $wordgento_shadow_y);

?>

button::-moz-focus-inner { 
    border: 0;
    padding: 0;
}

#wordgento_products { 
	width:<?php echo $products_grid_width + (($wordgento_shadow_blur + $blur_max)*2); ?>px; 
    overflow:hidden;
    <?php if($wordgento_shadow == 1) { ?>
    margin: -<?php echo ($wordgento_shadow_blur + $blur_max); ?>px -<?php echo ($wordgento_shadow_blur + $blur_max); ?>px <?php echo $wordgento_grid_col_spacing; ?>px;
    <?php } else { ?>
    margin: 0 0 <?php echo $wordgento_grid_col_spacing; ?>px;
    <?php } ?>
}

.wordgento_products_grid { 
	width:<?php echo $products_grid_width; ?>px; 
    overflow:hidden; 
    <?php if($wordgento_shadow == 1) { ?>
    padding: <?php echo ($wordgento_shadow_blur + $blur_max); ?>px;
    <?php } ?>
    float: left;
    clear: both;
    margin:0 0 20px;
}
.wordgento_product { width:<?php echo $wordgento_initial_image_width; ?>px; float:left; display:inline; margin:0 <?php echo $wordgento_grid_col_spacing; ?>px 0 0 }

.wordgento_product p { margin: 0 0 15px !important; }

.last { margin:0; }

.wordgento_image { 
	display:block; 
    overflow:hidden; 
    width:<?php echo $wordgento_image_width; ?>px;  
    <?php if($wordgento_shadow == 1) { ?>
    -moz-box-shadow: <?php echo $wordgento_shadow_x; ?>px <?php echo $wordgento_shadow_y; ?>px <?php echo $wordgento_shadow_blur; ?>px #<?php echo $wordgento_shadow_color; ?>; /* Firefox */
  	-webkit-box-shadow: <?php echo $wordgento_shadow_x; ?>px <?php echo $wordgento_shadow_y; ?>px <?php echo $wordgento_shadow_blur; ?>px #<?php echo $wordgento_shadow_color; ?>; /* Safari, Chrome */
  	box-shadow: <?php echo $wordgento_shadow_x; ?>px <?php echo $wordgento_shadow_y; ?>px <?php echo $wordgento_shadow_blur; ?>px #<?php echo $wordgento_shadow_color; ?>; /* CSS3 */
    <?php } ?>
    <?php if($wordgento_image_border == 1) { ?>
    border: <?php echo $wordgento_border_width.'px solid #'; echo $wordgento_border_color; ?>;
    <?php if($wordgento_border_corners == 1) { ?>
    -webkit-border-radius: <?php echo $wordgento_border_corner_radius; ?>px <?php echo $wordgento_border_corner_radius; ?>px <?php echo $wordgento_border_corner_radius; ?>px <?php echo $wordgento_border_corner_radius; ?>px;
    -moz-border-radius: <?php echo $wordgento_border_corner_radius; ?>px <?php echo $wordgento_border_corner_radius; ?>px <?php echo $wordgento_border_corner_radius; ?>px <?php echo $wordgento_border_corner_radius; ?>px;
    border-radius: <?php echo $wordgento_border_corner_radius; ?>px <?php echo $wordgento_border_corner_radius; ?>px <?php echo $wordgento_border_corner_radius; ?>px <?php echo $wordgento_border_corner_radius; ?>px;
    <?php } ?>
    <?php } ?>
    margin:0 0 15px;
}
.wordgento_image img { float:left; width:<?php echo $wordgento_image_width; ?>px; }

<?php if($wordgento_product_options['button_style'] == 'lgreen') { ?>
.wordgento-addto {  
    cursor: pointer;   
    border:none;
	background:url(../images/green-addto.png) no-repeat left 0;
	padding:0;
	margin:15px 0 0;
	width:auto;
	overflow:visible;					
	text-align:center;	
	white-space:nowrap;	
	height:42px;
}
.wordgento-addto span { 
	background:url(../images/green-addto.png) no-repeat right 0; 
    height:42px; 
    padding:0 0 0 112px; 
    color: #678338;
    float: left;
    font: bold 13px/42px Tahoma,Verdana,Arial,sans-serif;
    text-align: center;
    text-transform: uppercase;
    white-space: nowrap;
}
.wordgento-addto span span { background:url(../images/green-addto-repeat.png) repeat 0 0; margin:0 20px 0 0; padding:0 0 0 10px; line-height:42px; text-shadow: -1px 1px 0 #d0e5a4  }
<?php } ?>

<?php if($wordgento_product_options['button_style'] == 'lblack') { ?>
.wordgento-addto {  
    cursor: pointer;   
    border:none;
	background:url(../images/black-addto.png) no-repeat left 0;
	padding:0;
	margin:15px 0 0;
	width:auto;
	overflow:visible;					
	text-align:center;	
	white-space:nowrap;	
	height:42px;
}
.wordgento-addto span { 
	background:url(../images/black-addto.png) no-repeat right 0; 
    height:42px; 
    padding:0 0 0 112px; 
    color: #fff;
    float: left;
    font: bold 13px/42px Tahoma,Verdana,Arial,sans-serif;
    text-align: center;
    text-transform: uppercase;
    white-space: nowrap;
}
.wordgento-addto span span { background:url(../images/black-addto-repeat.png) repeat 0 0; margin:0 20px 0 0; padding:0 0 0 10px; line-height:42px; text-shadow: -1px 1px 0 #000  }
<?php } ?>

<?php if($wordgento_product_options['button_style'] == 'lsilver') { ?>
.wordgento-addto {  
    cursor: pointer;   
    border:none;
	background:url(../images/silver-addto.png) no-repeat left 0;
	padding:0;
	margin:15px 0 0;
	width:auto;
	overflow:visible;					
	text-align:center;	
	white-space:nowrap;	
	height:42px;
}
.wordgento-addto span { 
	background:url(../images/silver-addto.png) no-repeat right 0; 
    height:42px; 
    padding:0 0 0 112px; 
    color: #222;
    float: left;
    font: bold 13px/42px Tahoma,Verdana,Arial,sans-serif;
    text-align: center;
    text-transform: uppercase;
    white-space: nowrap;
}
.wordgento-addto span span { background:url(../images/silver-addto-repeat.png) repeat 0 0; margin:0 20px 0 0; padding:0 0 0 10px; line-height:42px; text-shadow: -1px 1px 0 #fff  }
<?php } ?>

.configurable, .grouped, .bundle, .simple {
	background-position: left bottom;
    margin:0
}

.wordgento_product .qty-box .qty {
	background:#FFFFFF;
    border: 4px solid #EEEEEE;
    border-radius: 3px 3px 3px 3px;
    -webkit-border-radius: 3px 3px 3px 3px;
    -moz-border-radius: 3px 3px 3px 3px;
    box-shadow: none;
    float: left;
    height: 20px;
    line-height: 20px;
    margin: 0 0 0 10px;
    width: 35px;
    display:inline;
}
.wordgento_product .qty-box label {
	float:left;
    display:inline;
    width:34px;
    line-height: 29px;
}