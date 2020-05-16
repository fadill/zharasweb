<?php

/* remove unnecessary headers being added to site header */
remove_action( 'wp_head', 'rsd_link' ) ;
remove_action( 'wp_head', 'wp_generator' ) ;
remove_action( 'wp_head', 'wlwmanifest_link' ) ;

/* Adds a top bar to Storefront, before the header. 
function storefront_add_topbar() {
    ?>
<div class="topbar" style="text-align: center; background: #000; color: #fff;  padding: .53em; font-weight: bold;">
	<span style="margin: 0 1em;">OFF SHIPPING</span>
	<span style="margin: 0 1em;">Libur Pengiriman dari tanggal 5 sampai 25 November </span>
</div>
    <?php
}
add_action( 'storefront_before_header', 'storefront_add_topbar' );
*/
/* Add my custom javascript */
add_action( 'wp_enqueue_scripts', 'mysite_enqueue' );
function mysite_enqueue() {
  $ss_url = get_stylesheet_directory_uri();
  wp_enqueue_script( 'mysite-scripts', "{$ss_url}/js/mycustomjs.js" );
}

/* customize storefront header */
function customize_storefront_header(){
	remove_action( 'storefront_header', 'storefront_header_cart', 60 );
	remove_action( 'storefront_header', 'storefront_product_search', 40 );
}
add_action( 'storefront_header', 'customize_storefront_header');
add_action( 'storefront_header', 'storefront_header_cart', 4 );
add_action( 'storefront_header', 'storefront_product_search', 3 );

/* customize storefront homepage */
function storefront_remove_homepage_content(){
	remove_action('homepage', 'storefront_product_categories', 20);
	remove_action('homepage', 'storefront_popular_products', 50);
}
add_action( 'init', 'storefront_remove_homepage_content' );

/* Frontpage Featured Products Title */
function custom_storefront_recent_product_title( $args ) {
    $args['title'] = __( 'JUST <br/> <span style="font-weight:800;">ARRIVED</span>', 'storefront' );
    return $args;  
}
function custom_storefront_product_featured_title( $args ) {
    $args['title'] = __( 'EDITOR\'S <br/> <span style="font-weight:800;"> PICK</span> ', 'storefront' );
    return $args;  
}
function custom_storefront_on_sale_title( $args ) {
    $args['title'] = __( 'HOT <br/> <span style="font-weight:800;">DEALS</span>', 'storefront' );
    return $args;  
}
function custom_storefront_best_seller_title( $args ) {
    $args['title'] = __( 'BEST <br/> <span style="font-weight:800;">SELLER</span>', 'storefront' );
    return $args;  
}
add_filter( 'storefront_recent_products_args', 'custom_storefront_recent_product_title');
add_filter( 'storefront_featured_products_args', 'custom_storefront_product_featured_title');
add_filter( 'storefront_on_sale_products_args', 'custom_storefront_on_sale_title');
add_filter( 'storefront_best_selling_products_args', 'custom_storefront_best_seller_title');
// Creates Testimonial Custom Post Type
function testimonial_init() {
    $args = array(
      'label' => 'Testimonial',
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => array('slug' => 'testimonial'),
        'query_var' => true,
        'menu_icon' => 'dashicons-format-chat',
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'trackbacks',
            'custom-fields',
            'comments',
            'revisions',
            'thumbnail',
            'author',
            'page-attributes',)
        );
    register_post_type( 'testimonial', $args );
}
add_action( 'init', 'testimonial_init' ); 

//Add login/logout link to navigation menu
function add_login_out_item_to_menu( $items, $args ){

	if (is_user_logged_in() && $args->theme_location == 'secondary') { 
		//$newitems .= '<li class="menu-item menu-item-type-custom"><a href="'. wp_logout_url( get_permalink( woocommerce_get_page_id( 'myaccount' ) ) ) .'">LOGOUT</a></li>'; 
		$newitems .= '<li class="menu-item menu-item-type-custom login-logout-account"><a href="' . get_permalink( woocommerce_get_page_id( 'myaccount' ) ) . '">MY ACCOUNT</a></li>'; 
		$items = $newitems.$items;
	} 
	elseif (!is_user_logged_in() && $args->theme_location == 'secondary') { 
		$newitems .= '<li class="menu-item menu-item-type-custom login-logout-account"><a href="' . get_permalink( woocommerce_get_page_id( 'myaccount' ) ) . '">LOG IN / REGISTER</a></li>'; 
		$items = $newitems.$items;
	} 

	return $items;
}
add_filter( 'wp_nav_menu_items', 'add_login_out_item_to_menu', 10, 2 );

/* layer slider */
add_action( 'init', 'child_theme_init' );
function child_theme_init() {
      add_action( 'storefront_before_content', 'add_full_slider', 5 );
}

function add_full_slider() { 
 if (is_front_page()) :
      ?> 
		<div id="slider"><?php echo do_shortcode("[layerslider id=1]"); ?></div>
		<div id="slider"><?php echo do_shortcode("[layerslider id=2]"); ?></div>
      <?php
 endif; 
}

// Change number or products per row to 4
add_filter('loop_shop_columns', 'loop_columns',999);
function loop_columns() {
	return 4; // 3 products per row
}

// Remove Additional Information Tab WooCommerce
add_filter( 'woocommerce_product_tabs', 'remove_info_tab', 98);
function remove_info_tab($tabs) {
	unset($tabs['additional_information']);
 return $tabs;
}

// Remove SKU on product page
add_filter('wc_product_sku_enabled', '__return_false');

// Restrict Backend access and wp-login page 
add_action('init', 'prevent_wp_login');

function prevent_wp_login() {
    // WP tracks the current page - global the variable to access it
    global $pagenow;
    // Check if a $_GET['action'] is set, and if so, load it into $action variable
    $action = (isset($_GET['action'])) ? $_GET['action'] : '';
    // Check if we're on the login page, and ensure the action is not 'logout'
    if( $pagenow == 'wp-login.php' && ( ! $action || ( $action && ! in_array($action, array('logout', 'lostpassword', 'rp', 'resetpass'))))) {
        // Redirect to the home page
        wp_redirect(home_url());
        // Stop execution to prevent the page loading for any reason
        exit();
    }
}

/* Add packaging dus field */
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );
function my_custom_checkout_field_update_order_meta( $order_id ) {

    if ( ! empty( $_POST['insurance_chkbox'] ) ) {
        update_post_meta( $order_id, 'Packaging Option', sanitize_text_field( $_POST['insurance_chkbox'] ) );
    }
}

/* Delete shipping_address column and add our own shipping data column */
add_filter( 'manage_edit-shop_order_columns', 'MY_COLUMNS_FUNCTION' );
function MY_COLUMNS_FUNCTION($columns){

	unset( $columns['shipping_address'] );
	unset( $columns['order_notes'] );
    $new_columns = (is_array($columns)) ? $columns : array();  

    //edit this for you column(s)
    //$new_columns['Data Pengiriman'] = 'MY_COLUMN_1_TITLE';
	$phone = array('Data Pengiriman' => 'Data Pengiriman');
    $position = 5;
    $new_columns = array_slice($columns, 0, $position, true) +  $phone +  array_slice($columns, $position, count($columns)-$position, true);	
    //stop editing

    //$new_columns['order_actions'] = $columns['order_actions'];
    return $new_columns;
}

add_action( 'manage_shop_order_posts_custom_column', 'MY_COLUMNS_VALUES_FUNCTION', 2 );
function MY_COLUMNS_VALUES_FUNCTION($column){

    global $post, $the_order;

    if ( empty( $the_order ) || $the_order->id != $post->ID ) {
        $the_order = wc_get_order( $post->ID );
    }	

    if ( $column == 'Data Pengiriman' ) {   

		$pilihan_dus =  get_post_meta( $the_order->id, 'Packaging Option', true );
		if ($pilihan_dus){
			echo '<p><strong>'.__('Packaging:').'</strong> Dus </p>';
		}else {
			echo '<p><strong>'.__('Packaging:').'</strong> - </p>';	
		}

		if ( $address = $the_order->get_formatted_shipping_address() ) {
			echo '<p>'. esc_html( preg_replace( '#<br\s*/?>#i', ', ', $address ) ) .'</p>';
		} else {
			echo '&ndash;';
		}
		
			
		/* kelurahan */
		if ( $the_order->billing_address_3 ) {
			echo '<small class="meta">' . __( 'Kelurahan:', 'woocommerce' ) . ' ' . esc_html( $the_order->billing_address_3 ) . '</small>';
			//echo '<p><strong>No. Telp:</strong> ' . get_post_meta( $the_order->id, '_billing_phone', true ) . '</p>';
		}
		
		/* Fadill custom code to add phone to admin order page */
		if ( $the_order->billing_phone ) {
			echo '<small class="meta">' . __( 'Tel:', 'woocommerce' ) . ' ' . esc_html( $the_order->billing_phone ) . '</small>';
			//echo '<p><strong>No. Telp:</strong> ' . get_post_meta( $the_order->id, '_billing_phone', true ) . '</p>';
		}				
		
		if ( $the_order->get_shipping_method() ) {
			echo '<small class="meta">' . __( 'Via', 'woocommerce' ) . ' ' . esc_html( $the_order->get_shipping_method() ) . '</small>';
		}	
    }
}

/* Disable post code on checkout page */
add_filter( 'woocommerce_checkout_fields' , 'custom_remove_billing_postcode_checkout' );

function custom_remove_billing_postcode_checkout( $fields ) {
  unset($fields['billing']['billing_last_name']);
  unset($fields['billing']['billing_postcode']);

  return $fields;
}

// Custom address section. Use billing_address2 for kecamatan & remove uneeded field in my account address page
add_filter( 'woocommerce_billing_fields' , 'override_billing_fields' );
 
function override_billing_fields( $fields ) {

	unset($fields['billing_company']);
	unset($fields['billing_last_name']);
	unset($fields['billing_postcode']);
	$fields['billing_address_2']['label'] = 'Kecamatan';

	return $fields;
}

/* Disable required last name on account page */
add_filter( 'woocommerce_save_account_details_required_fields', 'wc_remove_required_last_name');

function wc_remove_required_last_name( $fields) {

	unset( $fields['account_last_name'] );
	return $fields;
}

/* add message in my account - recent order page */
add_filter( 'woocommerce_before_account_orders', 'add_custom_message_recent_orders');

function add_custom_message_recent_orders( $fields) {
	echo '<div width="100%" float="right">Halaman ini menampilkan pesanan terbaru kamu selama 6 bulan terakhir.</div><br>';
}

// add price before discount in cart page
add_filter( 'woocommerce_cart_item_price', 'bbloomer_change_cart_table_price_display', 30, 3 );
function bbloomer_change_cart_table_price_display( $price, $values, $cart_item_key ) {

	$slashed_price = $values['data']->get_price_html();
	$is_on_sale = $values['data']->is_on_sale();

	if ( $is_on_sale ) {
		$price = $slashed_price;
	}

	return $price;
}

// remove picture from cart page
// add_filter( 'woocommerce_cart_item_thumbnail', '__return_false' );

// Check if Logged In User Has Already Purchased a Product
add_action ( 'woocommerce_after_shop_loop_item', 'user_logged_in_product_already_bought', 30);
function user_logged_in_product_already_bought() {
	if ( is_user_logged_in() ) {
		global $product;
		$current_user = wp_get_current_user();
		if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product->id ) ) echo '<div class="user-bought">&hearts; Purchased Item</div>';
	}
}

/* disable konfirmasi pembayaran button if status is completed or cancelled --- disabled in plugin directly
add_filter('woocommerce_my_account_my_orders_actions','disable_konfirmasi_pembayaran_button',99,2);
function disable_konfirmasi_pembayaran_button($actions,$the_order) {
	if (  $the_order->has_status( array( 'completed', 'cancelled', 'refunded', 'processing' ) ) ) { // if order is cancelled or completed 
		unset( $actions['Konfirmasi Pembayaran'] );
	}
	return $actions;
}*/

/* enable calendar on mozilla, IE, safari etc */
add_filter( 'wpcf7_support_html5_fallback', '__return_true' );

/* Restrict plugin scripts and styles */
function conditionally_load_plugin_js_css(){
	if(! is_page( array(1417) ) ){	# Load post-grid js only on testimonial page (ID 1417)	
    wp_dequeue_script('post_grid_scripts'); 
    wp_dequeue_script('owl.carousel.min'); 
    wp_dequeue_script('imagesloaded.pkgd.js'); 	
    wp_dequeue_script('masonry.pkgd.min'); 
    wp_dequeue_style('post_grid_style'); 	
    wp_dequeue_style('owl.carousel');
    wp_dequeue_style('style-woocommerce');
    wp_dequeue_style('style.skins');
    wp_dequeue_style('style.layout');
    }

	if(! is_page( array(76) ) ){	# Load contact form 7 js only on konfirmasi pembayaran page (ID 76)	
    wp_dequeue_script('contact-form-7'); 
    wp_dequeue_script('jquery-ui-datepicker');
    wp_dequeue_style('contact-form-7');  	
    }
	
	#if (!is_woocommerce() || is_admin()){			# Load infinite scroll only on woocommerce page
	#wp_dequeue_script('yith-infinitescroll');
	#wp_dequeue_script('yith-infs');
	#wp_dequeue_style('yith-infs-style');
	#}

}	
add_action( 'wp_enqueue_scripts', 'conditionally_load_plugin_js_css' );

/* remove woocommerce scripts on unnecessary pages 
function woocommerce_de_script() {
    if (function_exists( 'is_woocommerce' )) {
     if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page() && !(is_home()||is_front_page())) { // if we're not on a Woocommerce page, dequeue all of these scripts
    	wp_dequeue_script('wc-add-to-cart');
    	wp_dequeue_script('jquery-blockui');
    	wp_dequeue_script('woocommerce');
    	//wp_dequeue_script('jquery-cookie');
    	wp_dequeue_script('wc-cart-fragments');
      }
    }
}
add_action( 'wp_print_scripts', 'woocommerce_de_script', 100 );*/

add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_styles_scripts', 99 );

function dequeue_woocommerce_styles_scripts() {
    //remove generator meta tag
    remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );

	//first check that woo exists to prevent fatal errors
    if ( function_exists( 'is_woocommerce' ) ) {

        if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
            # Styles
            wp_dequeue_style( 'woocommerce-general' );
            wp_dequeue_style( 'woocommerce-layout' );
            wp_dequeue_style( 'woocommerce-smallscreen' );
            wp_dequeue_style( 'woocommerce_frontend_styles' );
            wp_dequeue_style( 'woocommerce_fancybox_styles' );
            wp_dequeue_style( 'woocommerce_chosen_styles' );
            wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
            # Scripts
            wp_dequeue_script( 'wc_price_slider' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-add-to-cart' );
            wp_dequeue_script( 'wc-cart-fragments' );
            wp_dequeue_script( 'wc-checkout' );
            wp_dequeue_script( 'wc-add-to-cart-variation' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-cart' );
            wp_dequeue_script( 'wc-chosen' );
            wp_dequeue_script( 'woocommerce' );
            wp_dequeue_script( 'prettyPhoto' );
            wp_dequeue_script( 'prettyPhoto-init' );
            wp_dequeue_script( 'jquery-blockui' );
            wp_dequeue_script( 'jquery-placeholder' );
            wp_dequeue_script( 'fancybox' );
            wp_dequeue_script( 'jqueryui' );
        }
    }
}

/* Change number related products for Storefront theme */ 
add_filter( 'woocommerce_output_related_products_args', 'bbloomer_change_number_related_products_storefront', 11 );
function bbloomer_change_number_related_products_storefront( $args ) { 
 $args['posts_per_page'] = 4; // # of related products
 $args['columns'] = 4; // # of columns per row
 return $args;
}

// hide coupon form everywhere
function hide_coupon_field( $enabled ) {
	if ( /*is_cart() ||*/ is_checkout() ) {
		$enabled = false;
	}
	
	return $enabled;
}
add_filter( 'woocommerce_coupons_enabled', 'hide_coupon_field' );

/* sort by date for storefront on sale product */
function storefront_on_sale_products( $args ) {

	if ( storefront_is_woocommerce_activated() ) {
		$args = apply_filters( 'storefront_on_sale_products_args', array(
			'limit'   => 4,
			'columns' => 4,
			'orderby' => 'date',
			'order'   => 'desc',
			'title'   => __( 'On Sale', 'storefront' ),
		) );

		echo '<section class="storefront-product-section storefront-on-sale-products" aria-label="On Sale Products">';
		do_action( 'storefront_homepage_before_on_sale_products' );

		echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';
		do_action( 'storefront_homepage_after_on_sale_products_title' );

		echo storefront_do_shortcode( 'sale_products', array(
			'per_page' => intval( $args['limit'] ),
			'columns'  => intval( $args['columns'] ),
			'orderby'  => esc_attr( $args['orderby'] ),
			'order'    => esc_attr( $args['order'] ),
		) );
		do_action( 'storefront_homepage_after_on_sale_products' );

		echo '</section>';
	}
}

/* greyed out product variation if it is out of stock */
add_filter( 'woocommerce_variation_is_active', 'grey_out_variations_when_out_of_stock', 10, 2 );
function grey_out_variations_when_out_of_stock( $grey_out, $variation ) {

    if ( ! $variation->is_in_stock() || ($variation->get_stock_quantity() == 0))
        return false;

    return true;
}

/* Add sold out badge on archive pages */
add_action( 'woocommerce_before_shop_loop_item_title', function() {
    global $product;
    if ( !$product->is_in_stock() || ((!$product->has_child()) && ($product->get_stock_quantity() < 1))) {
		echo '<div class="soldout-wrap">';
        echo '<div class="soldout">Habis</div>';
		echo '</div>';
    }
});

/* Add Sale badge, remove if sold out */
add_filter('woocommerce_sale_flash', 'my_woocommerce_sale_flash', 10, 3);
function my_woocommerce_sale_flash($flash, $post, $product){

	if( $product->is_in_stock()){
		echo '<div class="sale-badge-wrapper">';
		echo '<div class="onsale">Sale!</div><div class="percentage">';
		echo '<span class="percent">';
		if ($product->product_type == 'simple'){
			$percentage = round( ( ( $product->regular_price - $product->sale_price ) / $product->regular_price ) * 100 );			
		}
		elseif( $product->product_type == 'variable'){						
			$percentage = 0;
			$available_variations = $product->get_available_variations();
			foreach($available_variations as $variation){
				$variation_id = $variation['variation_id'];
				$variable_product_obj= new WC_Product_Variation( $variation_id );
				$regular_price = $variable_product_obj ->regular_price;
				$sales_price = $variable_product_obj ->sale_price;
				$temp_percent= round(( ( $regular_price - $sales_price ) / $regular_price ) * 100) ;
				if ($temp_percent > $percentage) {
					$percentage = $temp_percent;
				}
			}	
		}
		echo sprintf( __('%s', 'woocommerce' ), $percentage . '%' );  
		echo '</span><span class="label-off">off</span></div></div>';
	}
	else
		return '';
}

/* hide star rating on product loop page */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
/* display star even if there is no rating yet 
add_action('woocommerce_after_shop_loop_item_title', 'get_star_rating', 5 );
function get_star_rating()
{
    global $woocommerce, $product;
    $average = $product->get_average_rating();

    echo '<div class="star-rating"><span style="float: right; width:'.( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">'.$average.'</strong> '.__( 'out of 5', 'woocommerce' ).'</span></div>';
}
*/
// Add the div to wrap the image on the archive pages
add_action( 'woocommerce_before_shop_loop_item_title', create_function('', 'echo "<div class=\"archive-img-wrap\">";'), 5, 2);
add_action( 'woocommerce_before_shop_loop_item_title',create_function('', 'echo "</div>";'), 12, 2);

/* add home in storefront handheld menu */
add_filter( 'storefront_handheld_footer_bar_links', 'jk_add_home_link' );
function jk_add_home_link( $links ) {
	$new_links = array(
		'home' => array(
			'priority' => 10,
			'callback' => 'jk_home_link',
		),
	);

	$links = array_merge( $new_links, $links );

	return $links;
}

function jk_home_link() {
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . __( 'Home' ) . '</a>';
}

/* -- tips from https://www.keycdn.com/blog/speed-up-wordpress/ -- */
/* Remove query strings from static resources */
function _remove_script_version( $src ){
	$parts = explode( '?ver', $src );
	return $parts[0];
}
add_filter( 'script_loader_src', '_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', '_remove_script_version', 15, 1 );

/* Remove WP embed script
function speed_stop_loading_wp_embed() {
if (!is_admin()) {
wp_deregister_script('wp-embed');
}
}
add_action('init', 'speed_stop_loading_wp_embed');

/* Disable the emoji's 
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );	
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );*/

/**
 * Filter function used to remove the tinymce emoji plugin.
 * 
 * @param    array  $plugins  
 * @return   array             Difference betwen the two arrays
 
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}*/

/* Custom footer */
function storefront_credit() {
	if (!is_cart() && !is_checkout()){
		?>
		<div class="wa-button">
		<a href="https://api.whatsapp.com/send?phone=6282240424024&text=Hai%20Zharasonline" data-number="6282240424024" style="position: fixed; bottom: 50px; left: 15px; ">
		<div class="fa-stack fa-lg whatsapp-button fa-2x" >
			<i class="fa fa-circle fa-stack-2x" style="color: #25d366;"></i>
			<i class="fab fa-whatsapp fa-stack-1x fa-inverse"></i>
		</div>
		</a>	
		</div>
		<?php
	}
	?>
	<div class="site-info">	
		<div class="main-footer">	
			<li id="social-footer-1" class="fb_footer"><a href="https://www.facebook.com/zharasonline"><i class="social_icon fa"></i></a></li>
			<li id="social-footer-2" class="instagram_footer"><a href="https://instagram.com/zharasonline"><i class="social_icon fa"></i></a></li>
			<li id="social-footer-3" class="email_footer"><a href="mailto:cs@zharasonline.com?Subject=Hello%20zharasonline"><i class="social_icon fa"></i></a></li>
			<br /> 
			<?php
			echo esc_html( apply_filters( 'storefront_copyright_text', $content = ' ' . get_bloginfo( 'name' ) . ' &copy; ' . date( 'Y' ) ) ); ?>
			<br />
			<div class="row">	 <?php 
			printf( esc_html__( 'Your Trusted Cosmetic Partner' )); ?>
			</div>
		</div>
		<div class="payment-footer">
			<img src="//static.zharasonline.com/2018/12/bca-mandiri-bni4.png">
		</div>
	</div><!-- .site-info -->
	<?php
}

/* 
 * Add customer email to Cancelled Order recipient list
 */
 function wc_cancelled_order_add_customer_email( $recipient, $order ){
     return $recipient . ',' . $order->billing_email;
 }
 add_filter( 'woocommerce_email_recipient_cancelled_order', 'wc_cancelled_order_add_customer_email', 10, 2 );


/**
 * Custom Woocommerce Email Headers
 * add multiple bcc recipients
 */
function custom_wooemail_headers( $headers, $object ) {
	
	// replace the emails below to your desire email
	$emails = array('zharasonline@gmail.com');
	
	switch($object) {
		case 'customer_completed_order':
			$headers .= 'Bcc: ' . implode(',', $emails) . "\r\n";
			break;
		default:
	}
 
	return $headers;
}
 
add_filter( 'woocommerce_email_headers', 'custom_wooemail_headers', 10, 2);

/**
 * add product brand/category to product items in loop
 */
function add_product_category_in_shop_page(){

    /*$product_cats = wp_get_post_terms( get_the_ID(), 'product_cat', array('fields' => 'names'));*/

	$categories = get_the_terms( $post->ID, 'product_cat', array('fields' => 'names') );
	foreach( $categories as $category ) {
		if ($category->name == "Tony Moly" || $category->name == "Innisfree" || $category->name == "Etude House" || $category->name == "The Face Shop"
			|| $category->name == "Freeman" || $category->name == "Oxy" || $category->name == "St. Ives" || $category->name == "Neutrogena"
			|| $category->name == "Theramed")
			echo $category->name . '<br />';
	}
    /*if ( $product_cats && ! is_wp_error ( $product_cats ) ){
        $single_cat = array_shift( $product_cats ); ?>
        <font size="2" itemprop="name" class="product_category_title"><span><?php echo $single_cat; ?></span></font>

<?php }*/
}
add_action( 'woocommerce_shop_loop_item_title', 'add_product_category_in_shop_page', 2 );

/**
 * @snippet       Merge Two "My Account" Tabs @ WooCommerce Account
 * @source        https://businessbloomer.com/?p=73601
 * @compatible    Woo 3.3.3
 */
 
// -------------------------------
// 1. First, hide the tab that needs to be merged/moved (edit-address in this case)
add_filter( 'woocommerce_account_menu_items', 'bbloomer_remove_address_my_account', 999 );
  
function bbloomer_remove_address_my_account( $items ) {
	unset($items['edit-address']);
	return $items;
}
 
// -------------------------------
// 2. Second, print the ex tab content into an existing tab (edit-account in this case)
add_action( 'woocommerce_before_edit_account_form', 'woocommerce_account_edit_address' );

/* account details page customization */
add_action( 'woocommerce_before_edit_account_form', 'add_profil_saya_title' );
function add_profil_saya_title() {
		echo '<div class="profil-saya-title"><h3> Profil Saya </h3></div>';
}

/**
 * Print the customer avatar in My Account page, after the welcome message
 */
function storefront_myaccount_customer_avatar1() {
    $current_user = wp_get_current_user();
    echo '<div class="account_nav_container"><div class="myaccount_avatar">' . get_avatar( $current_user->user_email, 150, '', $current_user->display_name ) . '</div>';
}
add_action( 'woocommerce_before_account_navigation', 'storefront_myaccount_customer_avatar1', 5 );

function storefront_myaccount_customer_avatar2() {	echo '</div>';}
add_action( 'woocommerce_after_account_navigation', 'storefront_myaccount_customer_avatar2', 5 );

/* Add checkout flow to cart, checkout, and order confirmation page*/
function add_checkout_flow(){
	echo ' 	<div class="checkout-flow-container">';
	echo '	<nav class="checkout-flow text-center">';

	if (is_cart()) 
		echo '	<div class="current">';
	else 
		echo '	<div class="hide-for-small">';
	checkout_step_cart();
	echo '</div>';
    echo '	<div class="divider hide-for-small"><i class="fa fa-angle-right"></i></div>';
	
	if (is_checkout() && !is_wc_endpoint_url())
		echo '	<div class="current">';
	else
		echo '	<div class="hide-for-small">';
	checkout_step_checkout_details();
	echo '</div>';
    echo '	<div class="divider hide-for-small"><i class="fa fa-angle-right"></i></div>';
	
	if ((is_wc_endpoint_url( 'order-received' )))
	{
		echo '	<div class="current">';
		checkout_step_order_complete();
		echo '	</div>';
		echo '	<p>Terima kasih atas kepercayaan anda terhadap zharasonline. Pesanan Anda telah kami terima dengan rincian sebagai berikut:</p>';
	}
	else
	{
		echo '	<div class="hide-for-small">';
		checkout_step_order_complete();
		echo '	</div>';
	}
	echo '		</div>'; /* div.checkout-flow-container*/
}
add_action( 'woocommerce_before_cart', 'add_checkout_flow', 5 );
add_action( 'woocommerce_before_checkout_form', 'add_checkout_flow', 5 );
add_action( 'woocommerce_thankyou_order_received_text', 'add_checkout_flow');

function checkout_step_cart()
{
	global $woocommerce;
	$cart_url = $woocommerce->cart->get_cart_url();	

	?>
	<span class="fa-stack">
		<i class="fa fa-circle fa-stack-2x" style="color: #ffb8c9;"></i>
		<i class="fa fa-shopping-basket fa-stack-1x fa-inverse"></i>
	</span>		
	<?php		
	echo '	   	<div class="checkout-step-text"><a href="'. $cart_url.'">KERANJANG BELANJA</a></div>';	
}

function checkout_step_checkout_details()
{
	global $woocommerce;
	$checkout_url = $woocommerce->cart->get_checkout_url();	

	?>
	<div class="fa-stack">
		<i class="fa fa-circle fa-stack-2x" style="color: #ffb8c9;"></i>
		<i class="fa fa-file-alt fa-stack-1x fa-inverse"></i>
	</div>		
	<?php		
	echo '	   	<div class="checkout-step-text"><a href="'. $checkout_url.'">RINCIAN PEMBAYARAN</a></div>';	
}

function checkout_step_order_complete()
{
	?>
	<div class="fa-stack">
		<i class="fa fa-circle fa-stack-2x" style="color: #ffb8c9;"></i>
		<i class="fa fa-check fa-stack-1x fa-inverse"></i>
	</div>		
	<?php		
	echo '	   	<div class="checkout-step-text"><a href="#">PEMBELIAN SELESAI</a></div></nav>';	

}

/* accordion sidebar */
add_filter('storefront_sidebar_widget_tags','accordion_sidebar');
function accordion_sidebar($widget_tags)
{
	$widget_tags['before_title'] = '<p class="gamma widget-title"><button id="sidebar-button" class="accordion">';
	$widget_tags['after_title'] = '</button></p><div class="panel">';
	$widget_tags['after_widget'] = '</div></div>';

	return $widget_tags;
}

/* add "browse" to sidebar */
add_action('storefront_sidebar','add_sidebar_title');
function add_sidebar_title()
{
	echo '<div class="right-sidebar widget-area" style="margin-bottom: 0;">';
	echo '<span style="font-size: 1.41575em;"><i class="fa fa-list-ul"></i> Browse</span>';
	echo '<div class="is-divider" style="max-width:30px; margin: 0.4em 0 1em;"></div></div>';
}

/* override post meta for blog page */
	function storefront_post_meta() {
		?>
		<aside class="entry-meta">
			<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search.

			?>
			<div class="vcard author">
				<?php
					echo get_avatar( get_the_author_meta( 'ID' ), 128 );
					//echo '<div class="label">' . esc_attr( __( 'Written by', 'storefront' ) ) . '</div>';
					echo sprintf( '<a href="%1$s" class="url fn" style="font-size: .8em;" rel="author">%2$s</a>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), get_the_author() );
				?>
			</div>
			<?php
			/* translators: used between list items, there is a space after the comma 
			$categories_list = get_the_category_list( __( ', ', 'storefront' ) );

			if ( $categories_list ) : ?>
				<div class="cat-links">
					<?php
					echo '<div class="label">' . esc_attr( __( 'Posted in', 'storefront' ) ) . '</div>';
					echo wp_kses_post( $categories_list );
					?>
				</div>
			<?php endif; // End if categories. ?>

			<?php
			/* translators: used between list items, there is a space after the comma 
			$tags_list = get_the_tag_list( '', __( ', ', 'storefront' ) );

			if ( $tags_list ) : ?>
				<div class="tags-links">
					<?php
					echo '<div class="label">' . esc_attr( __( 'Tagged', 'storefront' ) ) . '</div>';
					echo wp_kses_post( $tags_list );
					?>
				</div>
			<?php endif;*/ // End if $tags_list. ?>

		<?php endif; // End if 'post' == get_post_type(). ?>

			<?php /* if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
				<div class="comments-link">
					<?php echo '<div class="label">' . esc_attr( __( 'Comments', 'storefront' ) ) . '</div>'; ?>
					<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'storefront' ), __( '1 Comment', 'storefront' ), __( '% Comments', 'storefront' ) ); ?></span>
				</div>
			<?php endif;*/ ?>
		</aside>
		<?php
	}

add_action( 'init', 'customise_storefront_blog' );

function customise_storefront_blog() {
	// Remove the storefromt post content function
	remove_action( 'storefront_loop_post', 'storefront_post_content', 30 );

	// Add our own custom function
	add_action( 'storefront_loop_post', 'custom_storefront_post_content',		30 );
}	

function custom_storefront_post_content() {
		?>
		<div class="entry-content" itemprop="articleBody">
		<?php
		
		the_excerpt();
		?>
		<p class="read-more"><a class="button" style="border-radius: 100px; padding: .3em 1.563em;" href="<?php the_permalink(); ?>"><?php echo get_theme_mod( 'woa_sf_blog_excerpt_button_text', __( 'BACA SELENGKAPNYA', 'woa-sf-blog-excerpt' ) ); ?></a></p>
		</div><!-- .entry-content -->	
		<?php		
}

/* add Plus Minus Quantity Buttons @ WooCommerce Single Product Page */

// 1. Show Buttons
add_action( 'woocommerce_before_add_to_cart_quantity', 'bbloomer_display_quantity_minus' );
 
function bbloomer_display_quantity_minus() {
    echo '<button type="button" class="minus" >-</button>';
}

add_action( 'woocommerce_after_add_to_cart_quantity', 'bbloomer_display_quantity_plus' );
 
function bbloomer_display_quantity_plus() {
    echo '<button type="button" class="plus" >+</button>';
}
 
// 2. Trigger jQuery script
 add_action( 'wp_footer', 'bbloomer_add_cart_quantity_plus_minus' );
 
function bbloomer_add_cart_quantity_plus_minus() {
    // Only run this on the single product page
    if ( ! is_product() ) return;
    ?>
        <script type="text/javascript">
             
        jQuery(document).ready(function($){ 
             
            $('form.cart').on( 'click', 'button.plus, button.minus', function() {
 
                // Get current quantity values
                var qty = $( this ).closest( 'form.cart' ).find( '.qty' );
                var val = parseFloat(qty.val());
                var max = parseFloat(qty.attr( 'max' ));
                var min = parseFloat(qty.attr( 'min' ));
                var step = parseFloat(qty.attr( 'step' ));
 
                // Change the value if plus or minus
                if ( $( this ).is( '.plus' ) ) {
                    if ( max && ( max <= val ) ) {
                        qty.val( max );
                    } else {
                        qty.val( val + step );
                    }
                } else {
                    if ( min && ( min >= val ) ) {
                        qty.val( min );
                    } else if ( val > 1 ) {
                        qty.val( val - step );
                    }
                }
                 
            });
             
        });
             
        </script>
    <?php
}
?>
