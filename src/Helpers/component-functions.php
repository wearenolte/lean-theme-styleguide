<?php
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
function get_component_slug( string $directory, string $file_path ): string {
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
function get_component_data( string $file_path ): array {
	$data_file = str_replace( 'php', 'json', $file_path );

	if ( file_exists( $data_file ) ) {
		return (array) json_decode( file_get_contents( $data_file ), true );
	}

	return [];
}


