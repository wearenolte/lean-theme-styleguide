<?php
namespace LeanStyleguide\Helpers;

/**
 * Class LoadFile
 *
 * Wrapper to load a php file passing data.
 *
 * @package LeanStyleguide\Helpers
 */
class LoadFile {
	/**
	 * Function that loads a file given its filename (without extension) and pass the arguments to the file.
	 *
	 * @param string $file_name The name of the file (without extension).
	 * @param string $base_path The base path of the file.
	 * @param array  $args      Data to pass along to the included file.
	 *
	 * @return void;
	 * @since 0.1.0
	 *
	 */
	public static function file_loader( string $file_name = '', string $base_path = '', $args = [] ): void {
		$file_path = $base_path . '/' . $file_name . '.php';

		if ( file_exists( $file_path ) ) {
			include $file_path;
		}
	}
}
