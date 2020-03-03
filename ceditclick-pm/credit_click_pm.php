<?php


class credit_click_pm{

	public  $option_name; //SAME as in credit_click_pm_admin.php

	public function __construct() {

		$this->option_name = 'credit_click_pm_name';  //SAME as in credit_click_pm_admin.php

		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_script') );

		//Hooks
		//1: archive page //https://businessbloomer.com/woocommerce-visual-hook-guide-archiveshopcat-page/
		add_action( 'woocommerce_after_shop_loop_item', array($this, 'place_ad_archive'), 5 );
		//2: single page //https://businessbloomer.com/woocommerce-visual-hook-guide-single-product-page/
		add_action( 'woocommerce_before_add_to_cart_form', array($this, 'place_ad_single'), 5 );
		//3: cart page  //https://businessbloomer.com/woocommerce-visual-hook-guide-cart-page/
		add_action( 'woocommerce_cart_totals_after_order_total', array($this, 'place_ad_cart'),10,0 );
	}


	public function get_amount(){

		$product = wc_get_product(get_the_ID());
		$price = $product->get_price() * 100;
		return $price;
	}


	public function get_amount_total(){

		global $woocommerce;
		$total_price = $woocommerce->cart->total * 100;
		return $total_price;
	}

	public function get_ad($amount, $class=''){

		ob_start();
		?>
		<style><?=$this->get_option('style');?></style>
		<div class="<?=$class?>" data-cc="<?=$amount;?>" data-cc-country="<?=$this->get_option('country');?>"></div>
		<?php
		return ob_get_clean();
	}

	public function place_ad_single() {

		if ($this->get_option_bool('single_enable')){
			echo $this->get_ad($this->get_amount());
		}
	}

	public function place_ad_archive() {

		if ($this->get_option_bool('archive_enable')){
			echo $this->get_ad($this->get_amount(), 'text-center');
		}
	}

	public function place_ad_cart() {

		if ($this->get_option_bool('cart_enable')){
			echo '<div style="float:right;">'.$this->get_ad($this->get_amount_total()).'</div>';
		}
	}


	public function get_option_bool($key){

		$options = get_option($this->option_name);
		if (isset($options[$key])){
			return true;
		} else {
			return false;
		}
	}


	public function get_option($key){

		$options = get_option($this->option_name);
		return $options[$key];
	}


	public function get_timestamp(){
		$date = new DateTime();
		return $date->getTimestamp();
	}


	public function enqueue_script() {

		wp_register_script('cca-script', 'https://ecom.creditclick.com/cc.min.js','', $this->get_timestamp(), false);
		wp_enqueue_script('cca-script');
		wp_add_inline_script( 'cca-script', 'loadCreditClick(); jQuery(function($) { $( document.body ).on( "updated_cart_totals", function(event) { loadCreditClick(); }); });' );
	}
}