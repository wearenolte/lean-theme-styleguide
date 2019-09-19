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
			return self::replace_image_id( $data );
		}

		return [];
	}

	/**
	 * Reads the variants arguments, and replaces the image placeholders for the Image ID set with a filter
	 *
	 * @param array $data The json data from a components json file.
	 *
	 * @return array
	 */
	public static function replace_image_id( array $data ): array {
		if ( ! isset( $data['variants'] ) ) {
			return $data;
		}

		$default_image_id = 0;
		$image_id = apply_filters( 'lean_styleguide_component_image_id', $default_image_id );

		foreach ( (array) $data['variants'] as $index_variant => $variant ) {
			foreach ( $variant as $index_argument => $data_item ) {
				if ( '${image-id}' === $data_item ) {
					$data['variants'][ $index_variant ][ $index_argument ] = $image_id;
				}
			}
		}

		return $data;
	}
}
