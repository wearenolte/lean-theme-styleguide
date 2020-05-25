<?php
namespace LeanStyleguide\Helpers;

/**
 * Class UIComponent
 *
 * A UI component functions.
 *
 * @package LeanStyleguide\Helpers
 */
class UIComponent {
	/**
	 * Get the component slug based on the filepath.
	 *
	 * Regex gets the string between $directory/ and .php
	 *
	 * Ex:
	 * /app/wordpress/wp-content/themes/lean-theme/frontend/components/molecules/menus/main-menu/main-menu.php
	 * => menus/main-menu/main-menu
	 *
	 * @param string $directory The component directory.
	 * @param string $file_path The component path.
	 *
	 * @return string
	 */
	public static function get_component_slug( string $directory, string $file_path ): string {
		preg_match( '/' . $directory . '\/([a-zA-Z0-9-\/]+)\.php/', $file_path, $output_array );

		return $output_array[1] ?? '';
	}

	/**
	 * Returns the component's data array.
	 *
	 * @param string $file_path The component path.
	 *
	 * @return array
	 */
	public static function get_component_data( string $file_path ): array {
		$data_file = str_replace( 'php', 'json', $file_path );

		if ( file_exists( $data_file ) ) {
			$data = (array) json_decode( file_get_contents( $data_file ), true );
			return self::replace_variables( $data );
		}

		return [];
	}

	/**
	 * Reads the variants arguments, and replaces the variable placeholders with their value.
	 *
	 * @param array $data The json data from a components json file.
	 *
	 * @return array
	 *
	 */
	public static function replace_variables( array $data ): array {
		if ( ! isset( $data['variants'] ) ) {
			return $data;
		}

		$variables = apply_filters( 'lean_styleguide_component_variables', [] );

		array_walk_recursive( $data['variants'], 'self::look_in_arguments', $variables );

		return $data;
	}

	/**
	 * Check an array element for the image placeholder and replaced its the value.
	 *
	 * @param mixed $data An array's element.
	 * @param string $key An array's key
	 * @param array $variables The varibles placeholder's values.
	 */
	public static function look_in_arguments( &$data, string $key, array $variables ) {
		foreach ( $variables as $variable_key => $variable_value ) {
			if ( '${' . $variable_key . '}' === $data ) {
				$data = $variable_value;
			}
		}
	}
}
