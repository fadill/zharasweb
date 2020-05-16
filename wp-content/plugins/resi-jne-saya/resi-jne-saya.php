<?php
/**
 * Plugin Name: Resi JNE Saya
 * Plugin URI: http://zharasonline.com
 * Description: Plugin untuk menambahkan nomer resi ke woocommerce dan melacak resi
 * Version: 1.0.0
 * Author: Fadillah Purnama Rezha
 * Author URI: http://zharasonline.com
 * License: GPL2
 */
 
 if (in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins'))) || array_key_exists( 'woocommerce/woocommerce.php', maybe_unserialize( get_site_option( 'active_sitewide_plugins') ) )) {


	/**
	* Display Metabox Shipment Tracking on order admin page
	**/	
	add_action( 'add_meta_boxes', 'resi_jne_saya_side_panel' );	
	function resi_jne_saya_side_panel(){	
		add_meta_box(
			'woocommerce-order-my-custom',
			__( 'Nomer Resi JNE' ),
			'tracking_number_field',
			'shop_order',
			'side',
			'high'
		);
	}
			
	/**
	* Add fields to the metabox
	**/	
	function tracking_number_field( $post ){
		wp_nonce_field( 'save_resi_jne', 'resi_jne_nonce' );
		
		woocommerce_wp_text_input(
			array(
			'id' => '_nomer_resi_jne',
			'class' => '',
			'label' => __('Nomer resi JNE: ', 'woocommerce')
			)
		);		
	}
	
	/**
	* Save Shipping Tracking information when clicking "save order"
	**/	
	add_action( 'save_post', 'save_resi_jne', 5, 1 );	
	function save_resi_jne( $post_id ) {
	
		// Check if nonce is set
		if ( ! isset( $_POST['resi_jne_nonce'] ) ) {
			return $post_id;
		}
		
		if ( ! wp_verify_nonce( $_POST['resi_jne_nonce'], 'save_resi_jne' ) ) {
			return $post_id;
		}
		
		// Check that the logged in user has permission to edit this post
		if ( ! current_user_can( 'edit_post' ) ) {
			return $post_id;
		}
		
		//$shipping_service = sanitize_text_field( $_POST['_MD_shipping_service'] );
		$tracking_link = sanitize_text_field( $_POST['_nomer_resi_jne'] );
		// update_post_meta( $post_id, '_MD_shipping_service', $shipping_service );
		update_post_meta( $post_id, '_nomer_resi_jne', $tracking_link );
	}	
	
	/* display tracking number to customer, via email and via my-account menu */
   add_action('woocommerce_view_order', 'display_tracking_info');
   add_action('woocommerce_email_before_order_table', 'add_order_tracking_email', 100, 2);	
   function display_tracking_info($order_id){
	   
	   $order = new WC_Order($order_id);
	   $jne_string = "JNE";
	   $shipping_method = $order->get_shipping_method();
	   
	   /* display tracking number only if order has been completed */
	   if($order -> get_status() === 'completed'){
		$track_message_1 = 'Pesanan anda telah dikirim melalui ';
		$track_message_2 = '. Nomer resi pesanan anda adalah ';	
		$tracking_number = get_post_meta( $order_id,  '_nomer_resi_jne', true );
		if (strpos($shipping_method, $jne_string) !== false){
			$tracking_link = '<a href="https://track.aftership.com/jne/'.$tracking_number.'" target="_blank">'.$tracking_number.'</a>';
			$tracking_button = '<a href="https://track.aftership.com/jne/'.$tracking_number.'" target="_blank" id="track-button" style="background-color: #f39c12;border-radius: 4px; color: #fff; padding: 4px 12px;"><i class="fa fa-cube fa-fw"></i> Track </a>';			
			echo '<p>'.$track_message_1 . esc_html( $shipping_method ) . $track_message_2 .$tracking_link . '. ' . $tracking_button . '</p>';
		}
		else{
			echo '<p>'.$track_message_1 . esc_html( $shipping_method ) .$track_message_2 .$tracking_number . '</p>';
		}
	   }

   }
   
	function add_order_tracking_email( $order, $sent_to_admin ) {
	
		if ( ! $sent_to_admin && $order->has_status( 'completed' )) {
			display_tracking_info($order->id);	
		}
	}  
 }