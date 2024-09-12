<?php


abstract class WFACP_Elementor_Template extends WFACP_Template_Common {
	public $default_setting_el = [];
	public $set_bredcrumb_data = [];
	public $stepsData = [];

	protected function __construct() {
		parent::__construct();
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'get_ajax_exchange_keys' ] );
		$this->url = WFACP_Core()->url( '/builder/elementor/template/views/' );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'reset_session' ] );
		add_filter( 'wfacp_forms_field', [ $this, 'hide_product_switcher' ], 10, 2 );

		add_filter( 'wfacp_cart_show_product_thumbnail', [ $this, 'display_order_summary_thumb' ], 10 );
		add_action( 'process_wfacp_html', [ $this, 'layout_order_summary' ], 55, 4 );

		add_filter( 'wfacp_html_fields_order_summary', '__return_false' );

		add_action( 'wfacp_internal_css', [ $this, 'get_elementor_localize_data' ], 9 );


		/* Add div ID  */
		add_action( 'wfacp_before_form', [ $this, 'element_start_before_the_form' ], 9 );
		add_action( 'wfacp_after_form', [ $this, 'element_end_after_the_form' ], 9 );

		/* Add div for angel eye express checkout  */
		add_action( 'wfacp_checkout_preview_form_start', [ $this, 'element_start_before_the_form' ], 9 );
		add_action( 'wfacp_checkout_preview_form_end', [ $this, 'element_end_after_the_form' ], 9 );


		add_filter( 'wfacp_css_js_deque', [ $this, 'remove_theme_styling' ], 10, 4 );
		//add_action( 'wfacp_after_checkout_page_found', [ $this, 'set_default_setting_el' ] );
		//Snippet Compatibility for header and footer JS Based
		add_action( 'wp_head', [ $this, 'wfacp_header_print_in_head' ], 999 );
		add_action( 'wp_footer', [ $this, 'wfacp_footer_before_print_scripts' ], - 1 );


		add_action( 'wp_footer', [ $this, 'wfacp_footer_after_print_scripts' ], 999 );
		add_action( 'wfacp_before_sidebar_content', array( $this, 'add_order_summary_to_sidebar' ), 11 );
		add_filter( 'wfacp_show_form_coupon', [ $this, 'check_layout_9_sidebar_hide_coupon' ], 10 );

		add_filter( 'wfacp_mini_cart_hide_coupon', [ $this, 'enable_collapsed_coupon_field' ], 10 );


		add_filter( 'wfacp_order_summary_cols_span', [ $this, 'change_col_span_for_order_summary' ] );
		add_filter( 'wfacp_order_total_cols_span', [ $this, 'change_col_span_for_order_summary' ] );

		add_filter( 'wfacp_for_mb_style', [ $this, 'get_product_switcher_mobile_style' ] );
		add_action( 'wfacp_checkout_preview_form_start', [ $this, 'add_checkout_preview_div_start' ] );
		add_action( 'wfacp_checkout_preview_form_end', [ $this, 'add_checkout_preview_div_end' ] );
		add_action( 'wp', [ $this, 'run_divi_styling' ] );

		add_action( 'wfacp_before_progress_bar', [ $this, 'before_cart_link' ] );
		add_action( 'wfacp_before_breadcrumb', [ $this, 'before_cart_link' ] );

		add_action( 'wfacp_after_next_button', [ $this, 'before_return_to_cart_link' ] );

		add_action( 'woocommerce_before_checkout_form', [ $this, 'add_form_steps' ], 999 );
		add_action( 'woocommerce_before_checkout_form', [ $this, 'display_progress_bar' ], 999 );
		add_filter( 'woocommerce_order_button_html', [ $this, 'add_class_change_place_order' ], 11 );
		add_filter( 'wfacp_change_back_btn', [ $this, 'change_back_step_label' ], 11, 3 );


		add_filter( 'wfacp_blank_back_text', [ $this, 'add_blank_back_text' ], 11, 3 );

		add_filter( 'wfacp_form_coupon_widgets_enable', '__return_true' );
		/* activate flatsome theme hook */
		add_action( 'wfacp_footer_before_print_scripts', [ $this, 'activate_theme_hook' ] );

		/* Coupon button text */
		add_action( 'wfacp_collapsible_apply_coupon_button_text', [ $this, 'get_collapsible_coupon_button_text' ] );
		add_action( 'wfacp_form_apply_coupon_button_text', [ $this, 'get_form_coupon_button_text' ] );
		add_action( 'wfacp_sidebar_apply_coupon_button_text', [ $this, 'get_mini_cart_coupon_button_text' ] );
	}


	public function run_divi_styling() {
		if ( function_exists( 'et_divi_add_customizer_css' ) ) {
			et_divi_add_customizer_css();
		}
	}

	public function change_col_span_for_order_summary( $colspan_attr1 ) {

		return '';
	}

	public function check_layout_9_sidebar_hide_coupon() {
		return true;
	}

	public function element_start_before_the_form() {
		$template_slug = $this->get_template_slug();
		if ( strpos( $template_slug, 'elementor' ) !== false ) {


			echo "<div id=wfacp-e-form>";

			$template = wfacp_template();
			$this->breadcrumb_start();


			$label_position = '';
			if ( isset( $this->form_data['wfacp_label_position'] ) ) {
				$label_position = $this->form_data['wfacp_label_position'];
			}

			if ( is_array( $this->form_data ) ) {

				$mbDevices = [ 'wfacp_collapsible_order_summary_wrap' ];


				if ( isset( $this->form_data['enable_callapse_order_summary'] ) && "yes" === $this->form_data['enable_callapse_order_summary'] ) {

					$mbDevices[] = 'wfacp_desktop';
				}

				if ( isset( $this->form_data['enable_callapse_order_summary_tablet'] ) && "yes" === $this->form_data['enable_callapse_order_summary_tablet'] ) {

					$mbDevices[] = 'wfacp_tablet';
				}
				if ( isset( $this->form_data['enable_callapse_order_summary_mobile'] ) && "yes" === $this->form_data['enable_callapse_order_summary_mobile'] ) {
					$mbDevices[] = 'wfacp_mobile';
				}


				$deviceClass = implode( ' ', $mbDevices );

				if ( empty( $deviceClass ) ) {
					$deviceClass = 'wfacp_not_active';
				}

				if ( $this->form_data['enable_callapse_order_summary'] != 'no' ) {
					echo "<div class='" . $deviceClass . "'>";

					$template->get_mobile_mini_cart( $this->form_data );
					echo "</div>";
				}


			}


			echo "<div class='" . implode( ' ', [ 'wfacp-form', $label_position ] ) . "'>";

		}

	}

	public function element_end_after_the_form() {
		$template_slug = $this->get_template_slug();
		if ( strpos( $template_slug, 'elementor' ) !== false ) {
			echo "</div>";
			echo "</div>";
		}

	}


	public function reset_session() {
		WFACP_Common::set_session( 'wfacp_order_total_widgets', [] );
		WFACP_Common::set_session( 'wfacp_min_cart_widgets', [] );
	}

	public function get_ajax_exchange_keys() {
		$keys = WFACP_Common::$exchange_keys;

		if ( ! empty( is_array( $keys ) ) && isset( $keys['elementor'] ) ) {
			$form_id         = $keys['elementor']['wfacp_form'];
			$this->form_data = WFACP_Common::get_session( $form_id );
			if ( isset( $keys['elementor']['wfacp_form_summary'] ) ) {
				$mini_cart_form_id    = $keys['elementor']['wfacp_form_summary'];
				$this->mini_cart_data = WFACP_Common::get_session( $mini_cart_form_id );
			}
		}
	}

	public function get_localize_data() {
		$data = parent::get_localize_data();

		$data['exchange_keys']['elementor'] = WFACP_Elementor::get_locals();

		return $data;

	}

	protected function get_field_css_ready( $template_slug, $field_index ) {

		if ( '' == $field_index ) {
			return '';
		}
		$field_key_index    = 'wfacp_' . $template_slug . '_' . $field_index . '_field';
		$field_custom_class = 'wfacp_' . $template_slug . '_' . $field_index . '_field_class';
		if ( isset( $this->form_data[ $field_key_index ] ) ) {

			return $this->form_data[ $field_key_index ] . ' ' . $this->form_data[ $field_custom_class ];
		}

		return '';

	}


	public function payment_heading() {
		if ( isset( $this->form_data['wfacp_payment_method_heading_text'] ) && '' !== trim( $this->form_data['wfacp_payment_method_heading_text'] ) ) {
			return trim( $this->form_data['wfacp_payment_method_heading_text'] );
		}


		return parent::payment_heading();
	}

	public function payment_sub_heading() {


		if ( isset( $this->form_data['wfacp_payment_method_subheading'] ) ) {
			return trim( $this->form_data['wfacp_payment_method_subheading'] );
		}

		return parent::payment_sub_heading();
	}

	public function get_payment_desc() {

		if ( isset( $this->form_data['text_below_placeorder_btn'] ) ) {
			return trim( $this->form_data['text_below_placeorder_btn'] );
		}

		return parent::get_payment_desc();

	}


	public function change_single_step_label( $name, $current_action ) {

		if ( isset( $this->form_data['wfacp_payment_button_1_text'] ) && '' != trim( $this->form_data['wfacp_payment_button_1_text'] ) ) {
			return trim( $this->form_data['wfacp_payment_button_1_text'] );
		}

		return $name;
	}

	public function change_two_step_label( $name, $current_action ) {
		if ( isset( $this->form_data['wfacp_payment_button_2_text'] ) && '' != trim( $this->form_data['wfacp_payment_button_2_text'] ) ) {
			return trim( $this->form_data['wfacp_payment_button_2_text'] );
		}

		return $name;
	}

	public function change_place_order_button_text( $text ) {

		if ( ! empty( $_GET['woo-paypal-return'] ) && ! empty( $_GET['token'] ) && ! empty( $_GET['PayerID'] ) ) {
			return $text;
		}
		if ( isset( $this->form_data['wfacp_payment_place_order_text'] ) && '' != trim( $this->form_data['wfacp_payment_place_order_text'] ) ) {
			return trim( $this->form_data['wfacp_payment_place_order_text'] );
		}


		return $text;
	}

	public function payment_button_text() {
		if ( isset( $this->form_data['wfacp_payment_place_order_text'] ) && '' != trim( $this->form_data['wfacp_payment_place_order_text'] ) ) {
			return trim( $this->form_data['wfacp_payment_place_order_text'] );
		}

		return __( "Place order", 'woocommerce' );
	}


	public function payment_button_alignment() {
		if ( isset( $this->form_data['wfacp_form_payment_button_alignment'] ) && '' != trim( $this->form_data['wfacp_form_payment_button_alignment'] ) ) {
			return trim( $this->form_data['wfacp_form_payment_button_alignment'] );
		}

		return parent::payment_button_alignment();
	}


	public function change_back_step_label( $text, $next_action, $current_action ) {

		$i = 1;
		if ( 'third_step' == $current_action ) {
			$i = 3;
		} elseif ( 'two_step' == $current_action ) {
			$i = 2;
		}
		$key = 'payment_button_back_' . $i . '_text';


		if ( isset( $this->form_data[ $key ] ) ) {
			return trim( $this->form_data[ $key ] );
		}


		return $text;
	}

	public function add_blank_back_text( $label, $step, $current_step ) {

		$i = 1;
		if ( 'third_step' == $step ) {
			$i = 3;
		} elseif ( 'two_step' == $step ) {
			$i = 2;
		}
		$key = 'payment_button_back_' . $i . '_text';


		if ( isset( $this->form_data[ $key ] ) && $this->form_data[ $key ] == '' ) {
			return "wfacp_back_link_empty";
		}


		return $label;
	}


	public function add_mini_cart_fragments( $fragments ) {
		$min_cart_key     = 'wfacp_mini_cart_widgets_' . $this->get_template_type();
		$min_cart_widgets = WFACP_Common::get_session( $min_cart_key );
		if ( ! empty( $min_cart_widgets ) ) {
			$min_cart_widgets = array_unique( $min_cart_widgets );
			foreach ( $min_cart_widgets as $widget_id ) {
				$fragments = $this->get_mini_cart_fragments( $fragments, $widget_id );
			}
		}

		return $fragments;
	}

	/*
	 * Hide product switcher if client use product switcher as widget
	 */
	public function hide_product_switcher( $fields, $key ) {

		$wfacp_id = WFACP_Common::get_id();
		if ( 'product_switching' == $key ) {
			$us_as_widget = get_post_meta( $wfacp_id, '_wfacp_el_product_switcher_us_a_widget', true );
			if ( 'yes' == $us_as_widget ) {

				$fields = [];
			}


		}

		return $fields;
	}

	public function display_order_summary_thumb( $status ) {
		if ( isset( $this->form_data['order_summary_enable_product_image'] ) && 'yes' === trim( $this->form_data['order_summary_enable_product_image'] ) ) {
			$status = true;
		}

		return $status;

	}


	/* Override the order summary section */

	public function add_fragment_order_summary( $fragments ) {
		$input_data = $this->form_data;
		$path       = WFACP_BUILDER_DIR . '/customizer/templates/layout_9';
		if ( isset( $this->checkout_fields['advanced'] ) && isset( $this->checkout_fields['advanced']['order_summary'] ) ) {
			ob_start();
			include WFACP_BUILDER_DIR . '/customizer/templates/layout_9/views/template-parts/main-order-summary.php';
			$fragments['.wfacp_order_summary'] = ob_get_clean();
		}

		$mbDevices = [];
		if ( isset( $this->form_data['enable_callapse_order_summary'] ) && "yes" === $this->form_data['enable_callapse_order_summary'] ) {
			$mbDevices[] = 'wfacp_desktop';
		}

		if ( isset( $this->form_data['enable_callapse_order_summary_tablet'] ) && "yes" === $this->form_data['enable_callapse_order_summary_tablet'] ) {
			$mbDevices[] = 'wfacp_tablet';
		}
		if ( isset( $this->form_data['enable_callapse_order_summary_mobile'] ) && "yes" === $this->form_data['enable_callapse_order_summary_mobile'] ) {
			$mbDevices[] = 'wfacp_mobile';
		}
		if ( empty( $mbDevices ) ) {
			return $fragments;
		}

		ob_start();
		include $path . '/views/template-parts/order-review.php';
		$fragments['.wfacp_mb_mini_cart_sec_accordion_content .wfacp_template_9_cart_item_details'] = ob_get_clean();

		ob_start();
		include $path . '/views/template-parts/order-total.php';
		$fragments['.wfacp_mb_mini_cart_sec_accordion_content .wfacp_template_9_cart_total_details'] = ob_get_clean();


		ob_start();
		include $path . '/views/template-parts/order-total.php';
		$fragments['.wfacp_mb_mini_cart_sec_accordion_content .wfacp_mini_cart_reviews'] = ob_get_clean();

		ob_start();
		wc_cart_totals_order_total_html();
		$fragments['.wfacp_cart_mb_fragment_price'] = ob_get_clean();
		$fragments['.wfacp_show_price_wrap']        = '<div class="wfacp_show_price_wrap">' . do_action( "wfacp_before_mini_price" ) . '<strong>' . wc_price( WC()->cart->total ) . '</strong>' . do_action( 'wfacp_after_mini_price' ) . '</div>';

		return $fragments;
	}

	public function layout_order_summary( $field, $key, $args, $value ) {

		if ( 'order_summary' === $key ) {
			WC()->session->set( 'wfacp_order_summary_' . WFACP_Common::get_id(), $args );
			include WFACP_BUILDER_DIR . '/customizer/templates/layout_9/views/template-parts/main-order-summary.php';
		}
	}

	public function get_elementor_localize_data() {
		$localData = [];
		if ( isset( $this->form_data['wfacp_make_button_sticky_on_mobile'] ) ) {
			$localData['wfacp_make_button_sticky_on_mobile'] = $this->form_data['wfacp_make_button_sticky_on_mobile'];
		}
		wp_localize_script( 'wfacp_checkout_js', 'wfacp_elementor_data', $localData );
	}

	public function set_default_setting_el() {
		$this->default_setting_el = [
			'heading' => [
				'color' => "red",
				'class' => "red",
			],
		];
	}

	public function remove_theme_styling( $bool, $path, $url, $currentEle ) {


		if ( false !== strpos( $url, '/themes/' ) ) {
			return false;
		}

		return $bool;
	}

	public function wfacp_header_print_in_head() {
		do_action( 'wfacp_header_print_in_head' );
	}

	public function wfacp_footer_before_print_scripts() {
		do_action( 'wfacp_footer_before_print_scripts' );
	}

	public function wfacp_footer_after_print_scripts() {
		do_action( 'wfacp_footer_after_print_scripts' );
	}


	public function get_mobile_mini_cart_collapsible_title() {

		if ( isset( $this->form_data['cart_collapse_title'] ) && '' !== $this->form_data['cart_collapse_title'] ) {
			return $this->form_data['cart_collapse_title'];
		}

		return parent::get_mobile_mini_cart_collapsible_title();

	}


	public function enable_collapsed_coupon_field() {
		if ( isset( $this->form_data['collapse_enable_coupon'] ) && $this->form_data['collapse_enable_coupon'] != '' ) {
			return $this->form_data['collapse_enable_coupon'];
		}

		return false;
	}

	public function collapse_enable_coupon_collapsible() {
		if ( isset( $this->form_data['collapse_enable_coupon_collapsible'] ) && $this->form_data['collapse_enable_coupon_collapsible'] != '' ) {
			return $this->form_data['collapse_enable_coupon_collapsible'];
		}

		return false;
	}

	public function collapse_order_quantity_switcher() {

		if ( isset( $this->form_data['collapse_order_quantity_switcher'] ) && $this->form_data['collapse_order_quantity_switcher'] != '' ) {
			$collapse_order_quantity_switcher = $this->form_data['collapse_order_quantity_switcher'];

			return $collapse_order_quantity_switcher;

		}

		return false;
	}

	public function collapse_order_delete_item() {

		if ( isset( $this->form_data['collapse_order_delete_item'] ) && $this->form_data['collapse_order_delete_item'] != '' ) {
			$collapse_order_delete_item = $this->form_data['collapse_order_delete_item'];

			return $collapse_order_delete_item;

		}

		return false;
	}


	public function get_mobile_mini_cart_expand_title() {
		if ( isset( $this->form_data['cart_expanded_title'] ) && '' !== $this->form_data['cart_expanded_title'] ) {
			return $this->form_data['cart_expanded_title'];
		}

		return parent::get_mobile_mini_cart_expand_title();

	}

	public function use_own_template() {
		return false;
	}


	public function add_order_summary_to_sidebar() {

		include WFACP_BUILDER_DIR . '/customizer/templates/layout_9/views/template-parts/order-summary.php';

	}

	public function breadcrumb_start() {

		$number_of_steps    = $this->get_step_count();
		$step_form_data     = [];
		$progress_form_data = [];


		$cls = 'wfacp_one_step';
		if ( $number_of_steps == 2 ) {
			$cls = 'wfacp_two_step';
		} elseif ( $number_of_steps == 3 ) {
			$cls = 'wfacp_three_step';
		}

		$progress_bar_type = isset( $this->form_data['select_type'] ) ? $this->form_data['select_type'] : '';
		$devices           = [ $progress_bar_type ];



		if ( isset( $this->form_data['enable_progress_bar'] ) && 'yes' === $this->form_data['enable_progress_bar'] ) {
			$devices[] = 'wfacp_desktop';
		}

		if ( isset( $this->form_data['enable_progress_bar_tablet'] ) && 'yes' === $this->form_data['enable_progress_bar_tablet'] ) {
			$devices[] = 'wfacp_tablet';
		}
		if ( isset( $this->form_data['enable_progress_bar_mobile'] ) && 'yes' === $this->form_data['enable_progress_bar_mobile'] ) {
			$devices[] = 'wfacp_mobile';
		}


		$deviceClass = implode( ' ', $devices );
		$wrapClass   = [];


		if ( ! empty( $cls ) ) {
			$wrapClass[] = $cls;
		}


		if ( empty( $deviceClass ) ) {
			$deviceClass = 'wfacp_not_active';
		}

		$wrapClass[] = $deviceClass;

		$stepWrapClass = '';
		if ( is_array( $wrapClass ) && count( $wrapClass ) > 0 ) {
			$stepWrapClass = implode( ' ', $wrapClass );
		}


		ob_start();
		echo "<div class='$stepWrapClass'>";

		for ( $i = 0; $i < $number_of_steps; $i ++ ) {


			$tab_heading_key    = '';
			$tab_subheading_key = '';

			$progress_bar_text = '';

			if ( 'tab' == $progress_bar_type ) {
				$tab_heading_key    = "step_" . $i . "_heading";
				$tab_subheading_key = "step_" . $i . "_subheading";
			}


			if ( $tab_heading_key != '' && is_array( $this->form_data ) && isset( $this->form_data[ $tab_heading_key ] ) ) {
				$step_form_data[ $i ]['heading'] = $this->form_data[ $tab_heading_key ];
			}
			if ( $tab_subheading_key != '' && is_array( $this->form_data ) && isset( $this->form_data[ $tab_subheading_key ] ) ) {
				$step_form_data[ $i ]['subheading']   = $this->form_data[ $tab_subheading_key ];
				$this->set_bredcrumb_data['tab_data'] = $step_form_data;
			}
			if ( 'tab' !== $progress_bar_type ) {
				$progress_bar_text = "step_" . $i . "_progress_bar";
			}

			if ( isset( $this->form_data['select_type'] ) && $this->form_data['select_type'] == 'bredcrumb' ) {
				$progress_bar_text = "step_" . $i . "_bredcrumb";
			}

			if ( $progress_bar_text != '' && is_array( $this->form_data ) && isset( $this->form_data[ $progress_bar_text ] ) ) {
				$progress_form_data[]                      = $this->form_data[ $progress_bar_text ];
				$this->set_bredcrumb_data['progress_data'] = $progress_form_data;
			}


		}


		if ( ( is_array( $step_form_data ) && count( $step_form_data ) > 0 ) ) {
			?>

            <div class="wfacp_form_steps">
                <div class="wfacp-payment-title wfacp-hg-by-box">
                    <div class="wfacp-payment-tab-wrapper">
						<?php
						$count          = 1;
						$count_of_steps = sizeof( $step_form_data );
						$steps          = [ 'single_step', 'two_step', 'third_step' ];


						$addfull_width = "full_width_cls";
						if ( $count_of_steps == 2 ) {
							$addfull_width = "wfacpef_two_step";
						}
						if ( $count_of_steps == 3 ) {
							$addfull_width = "wfacpef_third_step";
						}
						$active_breadcrumb = apply_filters( 'wfacp_el_bread_crumb_active_class_key', 0, $this );
						foreach ( $step_form_data as $key => $value ) {

							if ( isset( $steps[ $key ] ) ) {
								$steps_count_here = $steps[ $key ];
							}

							$active        = '';
							$bread_visited = '';
							if ( $count == 2 ) {
								$page_class = 'two_step';
							} else if ( $count == 3 ) {
								$page_class = 'third_step';
							} else {
								$page_class = 'single_step';
							}

							if ( $active_breadcrumb > $key ) {
								$bread_visited = 'visited_cls';
							}
							if ( $key == $active_breadcrumb ) {
								$active = 'wfacp-active visited_cls';
							}


							$activeClass = apply_filters( 'wfacp_embed_active_progress_bar', $active, $count, $number_of_steps );


							?>
                            <div class="wfacp-payment-tab-list <?php echo $activeClass . ' ' . $page_class . " " . $addfull_width . ' ' . $bread_visited; ?>  wfacp-tab<?php echo $count; ?>"
                                 step="<?php echo $steps_count_here; ?>">
                                <div class="wfacp-order2StepNumber"><?php echo $count; ?></div>
                                <div class="wfacp-order2StepHeaderText">
                                    <div class="wfacp-order2StepTitle wfacp-order2StepTitleS1 wfacp_tcolor"><?php echo $value['heading']; ?></div>
                                    <div class="wfacp-order2StepSubTitle wfacp-order2StepSubTitleS1 wfacp_tcolor"><?php echo $value['subheading']; ?></div>
                                </div>
                            </div>
							<?php
							$count ++;
						}
						?>
                    </div>
                </div>
            </div>
			<?php

		}

		$steps_arr = [ 'single_step', 'two_step', 'third_step' ];
		if ( 'progress_bar' == $progress_bar_type ) {
			if ( ( is_array( $progress_form_data ) && count( $progress_form_data ) > 0 ) ) {

				echo '<div class="wfacp_custom_breadcrumb wfacp_custom_breadcrumb_el">';
				echo '<div class=wfacp_steps_wrap>';
				echo '<div class=wfacp_steps_sec>';

				echo '<ul>';

				do_action( 'wfacp_before_' . $progress_bar_type, $progress_form_data );

				foreach ( $progress_form_data as $key => $value ) {
					$active = '';

					if ( $key == 0 ) {
						$active = 'wfacp_bred_active wfacp_bred_visited';
					}

					$step = ( isset( $steps_arr[ $key ] ) ) ? $steps_arr[ $key ] : '';

					$active = apply_filters( 'wfacp_layout_9_active_progress_bar', $active, $step );

					echo "<li class='wfacp_step_$key wfacp_bred $active $step' step='$step' ><a href='javascript:void(0)' class='wfacp_step_text_have' data-text='" . sanitize_title( $value ) . "'>$value</a> </li>";
				}
				do_action( 'wfacp_after_breadcrumb' );
				echo '</ul></div></div></div>';
			}
		}
		echo "</div>";
		$result = ob_get_clean();


		$this->stepsData[ $progress_bar_type ] = $result;


		if ( "progress_bar" !== $progress_bar_type ) {
			echo $result;
		}


	}

	public function add_form_steps() {

		$number_of_steps = $this->get_step_count();
		$steps_arr       = [ 'single_step', 'two_step', 'third_step' ];

		$devices = [];

		if ( $number_of_steps <= 1 || $this->form_data['enable_progress_bar'] == '' || $this->form_data['enable_progress_bar'] == 'no' ) {
			return;
		}

		if ( isset( $this->form_data['enable_progress_bar'] ) && "yes" === $this->form_data['enable_progress_bar'] ) {
			$devices[] = 'wfacp_desktop';
		}

		if ( isset( $this->form_data['enable_progress_bar_tablet'] ) && "yes" === $this->form_data['enable_progress_bar_tablet'] ) {
			$devices[] = 'wfacp_tablet';
		}
		if ( isset( $this->form_data['enable_progress_bar_mobile'] ) && "yes" === $this->form_data['enable_progress_bar_mobile'] ) {
			$devices[] = 'wfacp_mobile';
		}


		$deviceClass = implode( ' ', $devices );

		if ( empty( $deviceClass ) ) {
			$deviceClass = 'wfacp_not_active';
		}

		$select_type = $this->form_data['select_type'];

		echo "<div class='$deviceClass $select_type' >";
		if ( isset( $this->form_data['select_type'] ) && 'bredcrumb' == $this->form_data['select_type'] ) {
			if ( isset( $this->set_bredcrumb_data['progress_data'] ) && is_array( $this->set_bredcrumb_data['progress_data'] ) && $this->set_bredcrumb_data['progress_data'] > 0 ) {
				$progress_form_data = $this->set_bredcrumb_data['progress_data'];

				printf( '<div class="%s">', "wfacp_steps_wrap wfacp_breadcrumb_wrap_here" );
				echo '<div class=wfacp_steps_sec>';

				echo '<ul>';

				do_action( 'wfacp_before_breadcrumb', $progress_form_data );

				$active_breadcrumb = apply_filters( 'wfacp_el_bread_crumb_active_class_key', 0, $this );
				foreach ( $progress_form_data as $key => $value ) {
					$active        = '';
					$bread_visited = '';
					if ( $active_breadcrumb > $key ) {
						$bread_visited = 'wfacp_bred_visited';
					}
					if ( $key == $active_breadcrumb ) {
						$active = 'wfacp_bred_active wfacp_bred_visited';
					}

					$step       = ( isset( $steps_arr[ $key ] ) ) ? $steps_arr[ $key ] : '';
					$text_class = ( ! empty( $value ) ) ? 'wfacp_step_text_have' : 'wfacp_step_text_nohave';
					echo "<li class='wfacp_step_$key wfacp_bred $bread_visited $active $step' step='$step'>";
					?>
                    <a href='javascript:void(0)' class="<?php echo $text_class; ?> wfacp_breadcrumb_link"
                       data-text="<?php echo sanitize_title( $value ); ?>"><?php echo $value; ?></a>
					<?php

					echo '</li>';
				}
				do_action( 'wfacp_after_breadcrumb' );
				echo '</ul></div></div>';
			}

		}
		echo "</div>";

	}

	public function get_product_switcher_mobile_style() {

		if ( isset( $this->form_data['product_switcher_mobile_style'] ) && $this->form_data['product_switcher_mobile_style'] != '' ) {
			return $this->form_data['product_switcher_mobile_style'];
		}

		return parent::get_product_switcher_mobile_style();
	}

	public function add_body_class( $classes ) {
		$classes   = parent::add_body_class( $classes );
		$classes[] = 'wfacp_elementor_template';


		return $classes;
	}

	/**
	 * Wrap Order preview form in Embed form div start style
	 */

	public function add_checkout_preview_div_start() {
		echo '<div id="wfacp-e-form">';
	}

	/**
	 * Wrap Order preview form in Embed form div start style
	 */

	public function add_checkout_preview_div_end() {
		echo '</div>';
	}

	/**
	 * Cart Link before the step bar
	 */
	public function before_cart_link() {

		$is_global_checkout = WFACP_Core()->public->is_checkout_override();

		if ( $is_global_checkout === false ) {
			return;
		}

		if ( ! isset( $this->form_data['step_cart_link_enable'] ) || $this->form_data['step_cart_link_enable'] == 'no' ) {
			return;
		}

		if ( ! isset( $this->form_data['select_type'] ) ) {
			return;
		}


		$select_type = $this->form_data['select_type'];
		$key         = "step_cart_" . $select_type . "_link";


		if ( ! isset( $this->form_data[ $key ] ) ) {
			return;
		}

		$cartName = $this->form_data[ $key ];


		$cart_page_id = wc_get_page_id( 'cart' );
		$cartURL      = $cart_page_id ? get_permalink( $cart_page_id ) : '';


		echo "<li class='df_cart_link wfacp_bred_visited'><a class='wfacp_cart_link' href='$cartURL'>$cartName</a></li>";
	}

	public function before_return_to_cart_link( $current_action ) {

		$is_global_checkout = WFACP_Core()->public->is_checkout_override();

		if ( $is_global_checkout === false ) {
			return;
		}

		if ( ! isset( $this->form_data['step_cart_link_enable'] ) || $this->form_data['step_cart_link_enable'] == 'no' ) {
			return;
		}
		if ( ! isset( $this->form_data['return_to_cart_text'] ) || $this->form_data['return_to_cart_text'] == 'no' ) {
			return;
		}


		if ( $current_action != 'single_step' ) {
			return;
		}


		$cart_page_id = wc_get_page_id( 'cart' );
		$cartURL      = $cart_page_id ? get_permalink( $cart_page_id ) : '';
		?>

        <div class="btm_btn_sec wfacp_back_cart_link">
            <div class="wfacp-back-btn-wrap">
                <a href="<?php echo $cartURL; ?>"><?php echo $this->form_data['return_to_cart_text']; ?></a>
            </div>
        </div>
		<?php


	}

	public function display_progress_bar() {

		if ( isset( $this->stepsData['progress_bar'] ) ) {
			if ( isset( $this->form_data['select_type'] ) && 'progress_bar' == $this->form_data['select_type'] ) {
				echo $this->stepsData['progress_bar'];
			}
		}


	}

	public function add_class_change_place_order( $btn_html ) {


		$stepCount = $this->get_step_count();


		if ( ! empty( $_GET['woo-paypal-return'] ) && ! empty( $_GET['token'] ) && ! empty( $_GET['PayerID'] ) ) {
			return $btn_html;
		}


		$output = '';

		$key = "payment_button_back_" . $stepCount . "_text";

		$black_backbtn_cls = '';
		if ( isset( $this->form_data[ $key ] ) && $this->form_data[ $key ] == '' ) {

			$black_backbtn_cls = 'wfacp_back_link_empty';

		}

		$output .= sprintf( '<div class="wfacp-order-place-btn-wrap %s">', $black_backbtn_cls );
		$output .= $btn_html;

		if ( $stepCount > 1 ) {


			if ( ! isset( $this->form_data[ $key ] ) ) {
				return $btn_html;
			}
			$back_btn_text = $this->form_data[ $key ];


			$last_step = 'single_step';
			if ( $this->current_step == 'third_step' ) {
				$last_step = 'two_step';
			}

			if ( $back_btn_text != '' ) {
				$output .= "<div class='place_order_back_btn wfacp_none_class '><a class='wfacp_back_page_button' data-next-step='" . $last_step . "' data-current-step='" . $this->current_step . "' href='javascript:void(0)'>" . __( $back_btn_text, 'woofunnels-aero-checkout' ) . '</a> </div>';
			}
		}
		$output .= '</div>';

		return $output;
	}

	public function activate_theme_hook() {
		if ( function_exists( 'flatsome_mobile_menu' ) ) {
			add_action( 'wp_footer', 'flatsome_mobile_menu' );
		}
	}

	public function get_order_pay_summary( $order ) {
		include WFACP_BUILDER_DIR . '/customizer/templates/layout_9/views/template-parts/main-order-pay-summary.php';
	}

	public function mini_cart_heading() {
		return $this->mini_cart_data['mini_cart_heading'];
	}

	public function mini_cart_allow_product_image() {
		return isset( $this->mini_cart_data['enable_product_image'] ) && 'yes' === trim( $this->mini_cart_data['enable_product_image'] );
	}

	public function mini_cart_allow_quantity_box() {
		return isset( $this->mini_cart_data['enable_quantity_box'] ) && 'yes' === trim( $this->mini_cart_data['enable_quantity_box'] );
	}

	public function mini_cart_allow_deletion() {
		return isset( $this->mini_cart_data['enable_delete_item'] ) && 'yes' === trim( $this->mini_cart_data['enable_delete_item'] );
	}

	public function mini_cart_allow_coupon() {
		return isset( $this->mini_cart_data['enable_coupon'] ) && 'yes' == $this->mini_cart_data['enable_coupon'];
	}

	public function mini_cart_collapse_enable_coupon_collapsible() {

		return isset( $this->mini_cart_data['enable_coupon_collapsible'] ) && wc_string_to_bool( $this->mini_cart_data['enable_coupon_collapsible'] );
	}

	public function display_image_in_collapsible_order_summary() {

		return isset( $this->form_data['order_summary_enable_product_image_collapsed'] ) && 'yes' === trim( $this->form_data['order_summary_enable_product_image_collapsed'] );
	}

	/* Coupon Button Text */
	public function get_collapsible_coupon_button_text() {
		if ( isset( $this->form_data['collapse_coupon_button_text'] ) && '' !== $this->form_data['collapse_coupon_button_text'] ) {
			return $this->form_data['collapse_coupon_button_text'];
		}

		return parent::get_coupon_button_text();

	}

	public function get_form_coupon_button_text() {
		if ( isset( $this->form_data['form_coupon_button_text'] ) && '' !== $this->form_data['form_coupon_button_text'] ) {
			return $this->form_data['form_coupon_button_text'];
		}

		return parent::get_coupon_button_text();

	}

	public function get_mini_cart_coupon_button_text() {
		if ( isset( $this->mini_cart_data['mini_cart_coupon_button_text'] ) && '' !== $this->mini_cart_data['mini_cart_coupon_button_text'] ) {
			return $this->mini_cart_data['mini_cart_coupon_button_text'];
		}

		return parent::get_coupon_button_text();

	}
	/* End Coupon Button Text */

}
