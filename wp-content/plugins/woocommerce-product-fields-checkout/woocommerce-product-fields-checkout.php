<?php 
/*
Plugin Name: WooCommerce Conditional Product Fields at Checkout
Description: Checkout fields showed according to conditional logic.
Author: Lagudi Domenico
Version: 5.4
*/


/* Const */
//Domain: woocommerce-product-fields-checkout
define('WCPFC_PLUGIN_PATH', rtrim(plugin_dir_url(__FILE__), "/") ) ;
define('WCPFC_PLUGIN_ABS_PATH', dirname( __FILE__ ) ); ///ex.: "woocommerce/wp-content/plugins/woocommerce-product-fields-checkout"
define('WCPFC_PLUGIN_LANG_PATH', basename( dirname( __FILE__ ) ) . '/languages' ) ;


if ( !defined('WP_CLI') && ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ||
					   (is_multisite() && array_key_exists( 'woocommerce/woocommerce.php', get_site_option('active_sitewide_plugins') ))
					 )	
	)
{
	//For some reasins the theme editor in some installtion won't work. This directive will prevent that.
	if(isset($_POST['action']) && $_POST['action'] == 'edit-theme-plugin-file')
		return;
	
	if(isset($_REQUEST ['context']) && $_REQUEST['context'] == 'edit') //rest api
		return;
		
	if(isset($_POST['action']) && strpos($_POST['action'], 'health-check') !== false) //health check
		return;
	
	if(isset($_REQUEST['is_admin'])) //Fixes and uncompability with Project Manager plugin
		return;
		
	$wcpfc_id = 22556253;
	$wcpfc_name = "WooCommerce Conditional Product Fields at Checkout";
	$wcpfc_activator_slug = "wcpfc-activator";
	
	// Classes Init 
	include_once( "classes/com/WCPFC_Globals.php"); 
	require_once('classes/admin/WCPFC_ActivationPage.php');
	
	add_action('init', 'wcpfc_global_init');
	add_action('admin_menu', 'wcpfc_init_act');
	if(defined('DOING_AJAX') && DOING_AJAX)
		wcpfc_init_act();
	add_action('admin_notices', 'wcpfc_admin_notices' );

}
function wcpfc_admin_notices()
{
	global $wcpfc_notice, $wcpfc_name, $wcpfc_activator_slug;
	if($wcpfc_notice && (!isset($_GET['page']) || $_GET['page'] != $wcpfc_activator_slug))
	{
		 ?>
		<div class="notice notice-success">
			<p><?php wcpfc_html_escape_allowing_special_tags( sprintf(__( 'To complete the <span style="color:#96588a; font-weight:bold;">%s</span> plugin activation, you must verify your purchase license. Click <a href="%s">here</a> to verify it.', 'woocommerce-product-fields-checkout' ), $wcpfc_name, get_admin_url()."admin.php?page=".$wcpfc_activator_slug)); ?></p>
		</div>
		<?php
	}
}
function wcpfc_setup()
{
	global $wcpfc_country_model, $wcpfc_wpml_model, $wcpfc_field_model, $wcpfc_product_model, $wcpfc_datetime_model, $wcpfc_cart_model,
	$wcpfc_order_model, $wcpfc_order_details_page, $wcpfc_checkout_helper, $wcpfc_field_displayer_helper, $wcpfc_payment_method_model;
	//com	
	if(!class_exists('WCPFC_Country'))
	{
		require_once('classes/com/WCPFC_Country.php');
		$wcpfc_country_model = new WCPFC_Country();
	}
	if(!class_exists('WCPFC_Wpml'))
	{
		require_once('classes/com/WCPFC_Wpml.php');
		$wcpfc_wpml_model = new WCPFC_Wpml();
	}
	if(!class_exists('WCPFC_Field'))
	{
		require_once('classes/com/WCPFC_Field.php');
		$wcpfc_field_model = new WCPFC_Field();
	}
	if(!class_exists('WCPFC_Product'))
	{
		require_once('classes/com/WCPFC_Product.php');
		$wcpfc_product_model = new WCPFC_Product();
	}
	if(!class_exists('WCPFC_DateTime'))
	{
		require_once('classes/com/WCPFC_DateTime.php');
		$wcpfc_datetime_model = new WCPFC_DateTime();
	}
	if(!class_exists('WCPFC_Cart'))
	{
		require_once('classes/com/WCPFC_Cart.php');
		$wcpfc_cart_model = new WCPFC_Cart();
	}
	if(!class_exists('WCPFC_Order'))
	{
		require_once('classes/com/WCPFC_Order.php');
		$wcpfc_order_model = new WCPFC_Order();
	}
	if(!class_exists('WCPFC_PaymentMethod'))
	{
		require_once('classes/com/WCPFC_PaymentMethod.php');
		$wcpfc_payment_method_model = new WCPFC_PaymentMethod();
	}
	
	//admin
	if(!class_exists('WCPFC_FieldConfiguratorPage'))
	{
		require_once('classes/admin/WCPFC_FieldConfiguratorPage.php');
	}
	if(!class_exists('WCPFC_OrderDetailsPage'))
	{
		require_once('classes/admin/WCPFC_OrderDetailsPage.php');
		$wcpfc_order_details_page = new WCPFC_OrderDetailsPage();
	}
	
	
	//frontend
	if(!class_exists('WCPFC_Checkout'))
	{
		require_once('classes/frontend/WCPFC_Checkout.php');
		$wcpfc_checkout_helper = new WCPFC_Checkout();
	}
	if(!class_exists('WCPFC_FieldDisplayManagment'))
	{
		require_once('classes/frontend/WCPFC_FieldDisplayManagment.php');
		$wcpfc_field_displayer_helper = new WCPFC_FieldDisplayManagment();
	}
	
	//actions 
	//add_action('admin_init', 'wcpfc_admin_init');
	add_action('admin_menu', 'wcpfc_init_admin_panel');
	//add_action( 'wp_print_scripts', 'wcpfc_unregister_css_and_js' );
}
/* Functions */
function wcpfc_unregister_css_and_js($enqueue_styles)
{
	
}
function wcpfc_global_init()
{
	// Languages 
	load_plugin_textdomain('woocommerce-product-fields-checkout', false, basename( dirname( __FILE__ ) ) . '/languages' );
	
}
function wcpfc_init_act()
{
	global $wcpfc_activator_slug, $wcpfc_name, $wcpfc_id;
	new WCPFC_ActivationPage($wcpfc_activator_slug, $wcpfc_name, 'woocommerce-product-fields-checkout', $wcpfc_id, WCPFC_PLUGIN_PATH);
}
function wcpfc_admin_init()
{
}	
function wcpfc_init_admin_panel()
{ 
	$place = wcpfc_get_free_menu_position(60 , .1);
	$cap = 'manage_woocommerce';
	
	
	add_menu_page( 'WooCommerce Product Fields', esc_html__('WooCommerce Product Fields', 'woocommerce-product-fields-checkout'), $cap, 'woocommerce-product-fields-checkout', null,  'dashicons-cart' , (string)$place);
	$wccc_page = new WCPFC_FieldConfiguratorPage();
	$wccc_page->add_page($cap);
	
}
function wcpfc_get_free_menu_position($start, $increment = 0.1)
{
	foreach ($GLOBALS['menu'] as $key => $menu) {
		$menus_positions[] = $key;
	}
	
	if (!in_array($start, $menus_positions)) return $start;

	/* the position is already reserved find the closet one */
	while (in_array($start, $menus_positions)) 
	{
		$start += $increment;
	}
	return (string)$start;
}

function wcpfc_var_dump($var)
{
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
}
?>