<?php
defined( 'ABSPATH' ) || exit;

/**
 * Compatibility base class
 * Class WFCH_CMP_Singleton
 */
abstract class WFCH_CMP_Singleton {
	protected function __construct() {
		add_action( 'wp_loaded', [ $this, 'loaded' ] );
		add_action( 'after_setup_theme', [ $this, 'setup_theme' ] );
		add_action( 'wp', [ $this, 'wp' ] );
		add_action( 'wp_head', [ $this, 'head' ] );
		add_action( 'wp_footer', [ $this, 'footer' ], 999 );
		$this->action();
	}

	protected function action() {

	}

	public function loaded() {

	}

	public function wp() {

	}

	public function head() {

	}

	public function footer() {

	}

	public function setup_theme() {

	}

	/**
	 * to avoid unserialize of the current class
	 */
	public function __wakeup() {
		throw new ErrorException( 'can`t converted to string' );
	}

	/**
	 * to avoid serialize of the current class
	 */
	public function __sleep() {
		throw new ErrorException( 'can`t converted to string' );
	}

	protected function is_active() {
		return false;
	}

	/**
	 * To avoid cloning of current class
	 */
	protected function __clone() {
	}


}
