<?php

/**
 * @author woofunnels
 * @package WooFunnels
 */
class WooFunnels_Support {

	protected static $instance;
	public $validation = true;
	public $is_submitted;

	/**
	 *
	 * WooFunnels_Support constructor.
	 */
	public function __construct() {

		//      add_action( 'wp_ajax_woofunnels_fetch_tools_data', array( $this, 'fetch_tools_data' ) );
		//      add_action( 'admin_footer', array( $this, 'js_script' ) );
		//
		//      add_action( 'woofunnels_tools_right_area', array( $this, 'tools_right_area' ) );
	}

	/**
	 * Creates and instance of the class
	 * @return WooFunnels_Support
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Prepares the system information report.
	 *
	 * @access public
	 * @return string $report
	 */
	public function prepare_system_information_report( $return = false ) {

		global $wpdb, $wp_version;

		$output = array();

		/* Queue up needed information. */
		$php_extensions         = get_loaded_extensions();
		$php_ini_get            = array( 'memory_limit', 'max_execution_time', 'upload_max_filesize', 'max_file_uploads', 'post_max_size', 'max_input_vars' );
		$mysql_version          = $wpdb->get_var( $wpdb->prepare( 'SELECT VERSION() AS %s', 'version' ) );
		$db_character_set       = $wpdb->get_var( 'SELECT @@character_set_database' );
		$db_collation           = $wpdb->get_var( 'SELECT @@collation_database' );
		$plugins                = get_plugins();
		$active_plugins         = get_option( 'active_plugins', array() );
		$active_network_plugins = is_multisite() ? $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM {$wpdb->sitemeta} WHERE meta_key=%s", 'active_sitewide_plugins' ) ) : array();
		$has_log_files          = false;

		$theme               = array();
		$get_theme_info      = wp_get_theme();
		$theme['name']       = $get_theme_info->get( 'Name' );
		$theme['uri']        = $get_theme_info->get( 'ThemeURI' );
		$theme['version']    = $get_theme_info->get( 'Version' );
		$theme['author']     = $get_theme_info->get( 'Author' );
		$theme['author_uri'] = $get_theme_info->get( 'AuthorURI' );

		/* Begin report. */
		$report = '#### System Information Report ####' . "\n\n";

		/* PHP information. */
		$report                      .= '*** PHP Information ***' . "\n";
		$report                      .= 'Version: ' . phpversion() . "\n";
		$output['php']['phpversion'] = phpversion();
		foreach ( $php_ini_get as $ini_get ) {
			$report                    .= $ini_get . ': ' . ini_get( $ini_get ) . "\n";
			$output['php']["$ini_get"] = ini_get( $ini_get );
		}
		$report                .= 'cURL Enabled: ' . ( function_exists( 'curl_init' ) ? 'Yes' : 'No' ) . "\n";
		$output['php']['curl'] = ( function_exists( 'curl_init' ) ? 'Yes' : 'No' );
		$report                .= 'Mcrypt Enabled: ' . ( function_exists( 'mcrypt_encrypt' ) ? 'Yes' : 'No' ) . "\n";

		$output['php']['mcrypt']   = ( function_exists( 'mcrypt_encrypt' ) ? 'Yes' : 'No' );
		$report                    .= 'Mbstring Enabled: ' . ( function_exists( 'mb_strlen' ) ? 'Yes' : 'No' ) . "\n";
		$output['php']['mbstring'] = ( function_exists( 'mb_strlen' ) ? 'Yes' : 'No' );
		$report                    .= 'Loaded Extensions: ' . implode( ', ', $php_extensions ) . "\n\n";

		$output['php']['loaded_extenstions'] = $php_extensions;
		/* MySQL information. */
		$report                           .= '*** MySQL Information ***' . "\n";
		$report                           .= 'Version: ' . $mysql_version . "\n";
		$output['php']['mysql_version']   = $mysql_version;
		$report                           .= 'Database Character Set: ' . $db_character_set . "\n";
		$output['php']['mysql_charset']   = $db_character_set;
		$report                           .= 'Database Collation: ' . $db_collation . "\n\n";
		$output['php']['mysql_collation'] = $db_collation;

		/* Web server information. */
		$report                            .= '*** Web Server Information ***' . "\n";
		$report                            .= 'Software: ' . $_SERVER['SERVER_SOFTWARE'] . "\n";
		$output['server']['software']      = $_SERVER['SERVER_SOFTWARE'];
		$report                            .= 'Port: ' . $_SERVER['SERVER_PORT'] . "\n";
		$output['server']['post']          = $_SERVER['SERVER_PORT'];
		$report                            .= 'Document Root: ' . $_SERVER['DOCUMENT_ROOT'] . "\n\n";
		$output['server']['document_root'] = $_SERVER['DOCUMENT_ROOT'];

		/* WordPress information. */
		$report                              .= '*** WordPress Information ***' . "\n";
		$report                              .= 'WP_VERSION: ' . ( $wp_version ) . "\n";
		$output['wordpress']['wp_version']   = $wp_version;
		$report                              .= 'WP_DEBUG: ' . ( WP_DEBUG ? 'Enabled' : 'Disabled' ) . "\n";
		$output['wordpress']['wp_debug']     = ( WP_DEBUG ? 'Enabled' : 'Disabled' );
		$report                              .= 'WP_DEBUG_LOG: ' . ( WP_DEBUG_LOG ? 'Enabled' : 'Disabled' ) . "\n";
		$output['wordpress']['wp_debug_log'] = ( WP_DEBUG_LOG ? 'Enabled' : 'Disabled' );

		$report .= 'WP_MEMORY_LIMIT: ' . WP_MEMORY_LIMIT . "\n";

		$output['wordpress']['wp_memory_limit'] = WP_MEMORY_LIMIT;
		$report                                 .= 'Multisite: ' . ( is_multisite() ? 'Enabled' : 'Disabled' ) . "\n";
		$output['wordpress']['mutltisite']      = ( is_multisite() ? 'Enabled' : 'Disabled' );
		$report                                 .= 'Site URL: ' . get_site_url() . "\n";
		$output['wordpress']['site_url']        = get_site_url();
		$report                                 .= 'Home URL: ' . get_home_url() . "\n\n";
		$output['wordpress']['home_url']        = get_home_url();
		$output['wordpress']['timezone_string'] = get_option( 'timezone_string' );
		$output['wordpress']['gmt_offset']      = get_option( 'gmt_offset' );

		if ( class_exists( 'WooCommerce' ) ) {
			$payment_gateways = WC()->payment_gateways->payment_gateways();
			$sections         = array();
			foreach ( $payment_gateways as $gateway ) {
				if ( 'yes' === $gateway->enabled ) {
					$sections[] = esc_html( $gateway->get_title() );
				}
			}

			$output['woocommerce']['payement_gateways'] = $sections;
			/* WordPress information. */
			$report .= '*** WooCommerce Information ***' . "\n";
			$report .= 'WC_GATEWAYS: ' . implode( ' || ', $sections ) . "\n\n";
		}

		/* Current Theme Info. */
		$report                        .= '*** Theme Information ***' . "\n";
		$report                        .= 'Name: ' . $theme['name'] . "\n";
		$output['theme']['name']       = $theme['name'];
		$report                        .= 'URI: ' . $theme['uri'] . "\n";
		$output['theme']['uri']        = $theme['uri'];
		$report                        .= 'Version: ' . $theme['version'] . "\n";
		$output['theme']['version']    = $theme['version'];
		$report                        .= 'Author: ' . $theme['author'] . "\n";
		$output['theme']['author']     = $theme['author'];
		$report                        .= 'Author URI: ' . $theme['author_uri'] . "\n";
		$output['theme']['author_uri'] = $theme['author_uri'];

		/* Plugin information. */
		$report .= '*** Active Plugins ***' . "\n";
		foreach ( $plugins as $plugin_path => $plugin ) {
			if ( in_array( $plugin_path, $active_plugins ) ) {
				$report                                        .= $plugin['Name'] . ' (' . $plugin['Version'] . ')' . "\n";
				$output['active_plugins']["{$plugin['Name']}"] = $plugin['Version'];
			} else {
				$output['inactive_plugins']["{$plugin['Name']}"] = $plugin['Version'];
			}
		}
		$report .= "\n";

		/* Multi site plugin information. */
		if ( is_multisite() && ! empty( $active_network_plugins ) ) {
			$report                 .= '*** Network Active Plugins ***' . "\n";
			$active_network_plugins = maybe_unserialize( $active_network_plugins );
			foreach ( $active_network_plugins as $plugin_slug => $activated ) {
				$plugin                                         = get_plugin_data( WP_CONTENT_DIR . '/plugins/' . $plugin_slug );
				$report                                         .= $plugin['Name'] . ' (' . $plugin['Version'] . ')' . "\n";
				$output['network_plugins']["{$plugin['Name']}"] = $plugin['Version'];
			}
			$report .= "\n";
		}

		$report .= "\n" . '#### System Information Report ####';
		if ( $return ) {
			return $output;
		}

		return $report;

	}

	/**
	 * Processing support request
	 *
	 * @param $posted_data
	 *
	 * @uses WooFunnels_API used to fire api request to generate request
	 * @uses WooFunnels_admin_notifications pushing success and failure notifications
	 * @since 1.0.4
	 */
	public function woofunnels_maybe_push_support_request( $posted_data ) {
		if ( ! $this->validation ) {
			return;
		}
		wp_verify_nonce( $posted_data['nonce'] );

		//check files uploaded
		$files                  = array();
		$woofunnels_support     = '';
		$woofunnels_support_url = '';
		$upload_dir             = wp_upload_dir();
		$basedir                = $upload_dir['basedir'];
		$baseurl                = $upload_dir['baseurl'];
		if ( is_writable( $basedir ) ) {
			$woofunnels_support     = $basedir . '/woofunnels_support';
			$woofunnels_support_url = $baseurl . '/woofunnels_support';
			if ( ! file_exists( $woofunnels_support ) ) {
				mkdir( $woofunnels_support, 0755, true );
				$file_handle = @fopen( trailingslashit( $woofunnels_support ) . '/.htaccess', 'w' ); // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_fopen
				if ( $file_handle ) {
					fwrite( $file_handle, 'deny from all' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fwrite
					fclose( $file_handle ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fclose
				}
			}
		}

		if ( $woofunnels_support != '' && isset( $_FILES ) && count( $_FILES ) > 0 ) {
			$length = count( $_FILES['woofunnels_required_files']['name'] );

			for ( $i = 0; $i < $length; $i ++ ) {
				$fileName          = $_FILES['woofunnels_required_files']['name'][ $i ];
				$fileAr            = explode( '.', $fileName );
				$extension         = end( $fileAr );
				$newFile           = md5( $fileName . $i ) . ".{$extension}";
				$support_dir       = $woofunnels_support . "/{$newFile}";
				$support_url       = $woofunnels_support_url . "/{$newFile}";
				$is_files_uploaded = move_uploaded_file( $_FILES['woofunnels_required_files']['tmp_name'][ $i ], $support_dir );
				if ( $is_files_uploaded ) {
					$files[] = $support_url;
				}
			}
			if ( count( $files ) > 0 ) {
				$posted_data['files'] = $files;
			}
		}

		$response = WooFunnels_API::post_support_request( $posted_data );

		if ( $response->status == 'success' ) {
			$this->is_submitted = true;

		} else {
			$this->is_submitted = false;

		}
	}


	public function fetch_tools_data() {
		if ( isset( $_POST['plugins'] ) && $_POST['plugins'] != '' ) {
			$plugins = explode( '/', $_POST['plugins'] );
			do_action( 'woofunnels_fetch_tools_data', end( $plugins ), $_POST );
		}
		exit;
	}

	public function js_script() {
		?>
        <script>
            (function ($) {

                function woofunnels_core() {
                    $(".woofunnels_dashboard_tab_content #choose_addon").on("change", function () {
                        var hasTools = $(this).find("option:selected").attr('data-tools');
                        var plugin = $(this).val();
                        if (hasTools == 1) {
                            console.log(hasTools);

                            $(".woofunnels_system_info_url").val("");
                            $.ajax({
                                method: 'post',
                                url: 'admin-ajax.php',
                                data: {
                                    action: 'woofunnels_fetch_tools_data',
                                    plugins: plugin
                                },
                                success: function (resp) {
                                    if (resp != "") {
                                        $(".woofunnels_system_info_url").val(resp);
                                        $(".woofunnels_system_info").hide();
                                    } else {
                                        $(".woofunnels_system_info").show();
                                    }
                                }
                            });
                        } else {
                            $(".woofunnels_system_info").show();
                        }
                    });
                }

                setTimeout(function () {
                    woofunnels_core();
                }, 1500);
            })(jQuery);
        </script>
		<?php
	}


}
