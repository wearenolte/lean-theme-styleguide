<?php

/**
 * Class ViewsLoader
 *
 * Prints the files of the views folder.
 *
 * @package LeanStyleguide\Helpers
 */

namespace LeanStyleguide\Helpers;

class ViewsLoader {
	/**
	 * Library base path
	 *
	 * @var string
	 */
	private $base_path;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->base_path = dirname( __FILE__, 2 );
	}

	/**
	 * Views loader.
	 *
	 * @param string $file_name The view name
	 * @param array  $args      The view arguments.
	 */
	public function load_view( string $view_name = '', $args = [] ) {
		Loader::file_loader( $view_name, $this->base_path . '/views', $args );
	}
}
