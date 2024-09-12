<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 3/4/19
 * Time: 6:32 PM
 */

abstract class WFCH_Common {


	public static function init() {
		add_action( 'plugins_loaded', [ __CLASS__, 'plugins_loaded' ], - 1 );
		add_action( 'init', [ __CLASS__, 'register_post_type' ], 100 );
	}

	public static function plugins_loaded() {

		/**
		 * @since 1.6.0
		 * Detect heartbeat call from our customizer page
		 * Remove some unwanted warnings and error
		 */
		WooFunnel_Loader::include_core();
	}


	public static function register_post_type() {
		/**
		 * Funnel Post Type
		 */
		register_post_type( self::get_post_type_slug(), apply_filters( 'wfch_post_type_args', array(
			'labels'              => array(
				'name'          => __( 'Skip Carts', 'woofunnels-carthopper-skip-cart' ),
				'singular_name' => __( 'Skip Cart', 'woofunnels-carthopper-skip-cart' ),
				'add_new'       => __( 'Add Skip Cart', 'woofunnels-carthopper-skip-cart' ),
				'add_new_item'  => __( 'Add New Skip Cart', 'woofunnels-carthopper-skip-cart' ),
			),
			'public'              => true,
			'show_ui'             => false,
			'capability_type'     => 'product',
			'map_meta_cap'        => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_in_menu'        => false,
			'hierarchical'        => false,
			'show_in_nav_menus'   => false,
			'rewrite'             => array(
				'slug' => self::get_url_rewrite_slug(),
			),
			'query_var'           => true,
			'supports'            => array( 'title' ),
			'has_archive'         => false,
		) ) );
	}

	/**
	 * Get Post_type slug
	 * @return string
	 */
	public static function get_post_type_slug() {
		return 'wfch_cart';
	}

	public static function get_url_rewrite_slug() {
		$g_setting = get_option( '_wfch_global_settings', [] );

		return isset( $g_setting['rewrite_slug'] ) ? $g_setting['rewrite_slug'] : 'wf_skip_cart';
	}

	public static function get_highest_menu_order() {
		global $wpdb;
		$menu_order = 0;
		$result     = $wpdb->get_results( sprintf( "SELECT menu_order FROM `%s` where `post_type`='%s' ORDER BY `%s`.`menu_order`  DESC LIMIT 1", $wpdb->prefix . 'posts', self::get_post_type_slug(), $wpdb->prefix . 'posts' ), ARRAY_A );
		if ( is_array( $result ) && count( $result ) > 0 ) {
			$menu_order = $result[0]['menu_order'];
		}

		return $menu_order;
	}

	public static function search_products( $term, $include_variations = false ) {
		global $wpdb;
		$like_term     = '%' . $wpdb->esc_like( $term ) . '%';
		$post_types    = $include_variations ? array( 'product', 'product_variation' ) : array( 'product' );
		$post_statuses = current_user_can( 'edit_private_products' ) ? array(
			'private',
			'publish',
		) : array( 'publish' );
		$type_join     = '';
		$type_where    = '';

		$product_ids = $wpdb->get_col(

			$wpdb->prepare( "SELECT DISTINCT posts.ID FROM {$wpdb->posts} posts
				LEFT JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id
				$type_join
				WHERE (
					posts.post_title LIKE %s
					OR (
						postmeta.meta_key = '_sku' AND postmeta.meta_value LIKE %s
					)
				)
				AND posts.post_type IN ('" . implode( "','", $post_types ) . "')
				AND posts.post_status IN ('" . implode( "','", $post_statuses ) . "')
				$type_where
				ORDER BY posts.post_parent ASC, posts.post_title ASC", $like_term, $like_term ) );

		if ( is_numeric( $term ) ) {
			$post_id   = absint( $term );
			$post_type = get_post_type( $post_id );

			if ( 'product_variation' === $post_type && $include_variations ) {
				$product_ids[] = $post_id;
			} elseif ( 'product' === $post_type ) {
				$product_ids[] = $post_id;
			}

			$product_ids[] = wp_get_post_parent_id( $post_id );
		}

		return wp_parse_id_list( $product_ids );
	}

	public static function get_include_url() {
		return plugin_dir_url( WFCH_PLUGIN_FILE ) . 'includes';
	}

	public static function get_saved_pages() {
		global $wpdb;

		$slug = self::get_post_type_slug();
		$data = $wpdb->get_results( "SELECT `ID`, `post_title`, `post_type` FROM `{$wpdb->prefix}posts` WHERE `post_type` = '{$slug}' AND `post_title` != '' ORDER BY `post_title` ASC", ARRAY_A );

		return $data;
	}

	public static function save_publish_checkout_pages_in_transient( $force = true, $count = '-1' ) {

		$output = self::get_save_settings();

		/**
		 * @var $Woofunnel_cache_obj WooFunnels_Cache
		 */
		$Woofunnel_cache_obj     = WooFunnels_Cache::get_instance();
		$Woofunnel_transient_obj = WooFunnels_Transient::get_instance();

		$cache_key = 'wfch_publish_posts';
		/** $force = true */
		if ( true === $force ) {
			$Woofunnel_transient_obj->set_transient( $cache_key, $output, DAY_IN_SECONDS, WFCH_SLUG );
			$Woofunnel_cache_obj->set_cache( $cache_key, $output, 'wfch' );

			return $output;
		}

		$cache_data = $Woofunnel_cache_obj->get_cache( $cache_key, WFCH_SLUG );
		if ( false !== $cache_data ) {
			$wfch_publish_posts = $cache_data;
		} else {
			$transient_data = $Woofunnel_transient_obj->get_transient( $cache_key, WFCH_SLUG );

			if ( false !== $transient_data ) {
				$wfch_publish_posts = $transient_data;
			} else {

				$Woofunnel_transient_obj->set_transient( $cache_key, $output, DAY_IN_SECONDS, WFCH_SLUG );
			}
			$Woofunnel_cache_obj->set_cache( $cache_key, $output, WFCH_SLUG );
		}

		return $wfch_publish_posts;

	}

	public static function get_save_settings() {

		$data = [
			'category'  => [],
			'excludes'  => [],
			'rules'     => [],
			'skip_cart' => false,
		];
		$data = get_option( 'wfch_setting_rules', $data );

		$rules = self::get_publish_posts_data();
		if ( count( $rules ) > 0 ) {
			$data['rules'] = array_values( $rules );
			//$data['rules_data'] = $rules;
		} else {
			$data['rules'] = [];
		}

		return $data;
	}

	public static function get_publish_posts_data() {

		$rules_data = [];
		$args       = [
			'post_type'   => self::get_post_type_slug(),
			'post_status' => 'any',
			'order'       => 'DESC',
			'orderby'     => 'menu_order',
		];

		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {

				$query->the_post();
				global $post;
				$id   = $post->ID;
				$data = get_post_meta( $post->ID, '_wfch_data', true );
				if ( is_array( $data ) && count( $data ) > 0 ) {
					$rules_data[ $id ]              = self::get_map_data( $data );
					$rules_data[ $id ]['published'] = $post->post_status;
					if ( is_admin() ) {
						$rules_data[ $id ]['menu_order'] = $post->menu_order;
					}
				}
			}
		}

		return $rules_data;
	}

	public static function get_map_data( $data ) {

		$temp_products = [];
		if ( is_array( $data['products'] ) && count( $data['products'] ) > 0 ) {
			foreach ( $data['products'] as $index => $pid ) {
				$wc_product = wc_get_product( $pid );
				if ( ! $wc_product instanceof WC_Product ) {
					continue;
				}
				$temp_products[ $pid ] = [ 'id' => $pid, 'title' => self::get_formatted_product_name( $wc_product ) ];
			}
		}
		$data['products'] = $temp_products;
		if ( isset( $data['checkout'] ) && $data['checkout'] > 0 ) {
			$checkout         = $data['checkout'];
			$data['checkout'] = [];
			$data['checkout'] = [ 'id' => $checkout, 'title' => get_the_title( $checkout ), 'permalink' => get_the_permalink( $checkout ) ];

		}

		return $data;


	}

	public static function get_formatted_product_name( $product ) {
		$formatted_variation_list = self::get_variation_attribute( $product );

		$arguments = array();
		if ( ! empty( $formatted_variation_list ) && count( $formatted_variation_list ) > 0 ) {
			foreach ( $formatted_variation_list as $att => $att_val ) {
				if ( $att_val == '' ) {
					$att_val = __( 'any' );
				}
				$att         = strtolower( $att );
				$att_val     = strtolower( $att_val );
				$arguments[] = "$att: $att_val";
			}
		}

		return sprintf( '%s (#%d) %s', $product->get_title(), $product->get_id(), ( count( $arguments ) > 0 ) ? '(' . implode( ',', $arguments ) . ')' : '' );
	}

	public static function get_variation_attribute( $variation ) {
		if ( is_a( $variation, 'WC_Product_Variation' ) ) {
			$variation_attributes = $variation_attributes_basic = $variation->get_attributes();
		} else {
			$variation_attributes = array();
			if ( is_array( $variation ) ) {
				foreach ( $variation as $key => $value ) {
					$variation_attributes[ str_replace( 'attribute_', '', $key ) ] = $value;
				}
			}
		}

		return ( $variation_attributes );

	}


}