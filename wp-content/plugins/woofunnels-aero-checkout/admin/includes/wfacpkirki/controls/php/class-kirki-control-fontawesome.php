<?php
/**
 * Customizer Control: wfacpkirki-fontawesome.
 *
 * @package     WFACPKirki
 * @subpackage  Controls
 * @copyright   Copyright (c) 2017, Aristeides Stathopoulos
 * @license     http://opensource.org/licenses/https://opensource.org/licenses/MIT
 * @since       1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Select control.
 */
class WFACPKirki_Control_FontAwesome extends WFACPKirki_Control_Base {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'wfacpkirki-fontawesome';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {
		parent::enqueue();

		wp_enqueue_script( 'wfacpkirki-fontawesome-font', 'https://use.fontawesome.com/30858dc40a.js', array(), '4.0.7' );

		ob_start();
		$json_path = wp_normalize_path( WFACPKirki::$path . '/assets/vendor/fontawesome/fontawesome.json' );
		include $json_path;
		$font_awesome_json = ob_get_clean();

		wp_localize_script( 'wfacpkirki-script', 'fontAwesomeJSON', $font_awesome_json );
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
        <label>
            <# if ( data.label ) { #><span class="customize-control-title">{{{data.label }}}</span><# } #>
            <# if ( data.description ) { #><span class="description customize-control-description">{{{data.description }}}</span><# } #>
            <select {{{ data.inputAttrs }}} {{{ data.link }}}></select>
        </label>
		<?php
	}
}
