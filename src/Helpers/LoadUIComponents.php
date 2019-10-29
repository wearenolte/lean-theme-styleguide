<?php

/**
 * Class LoadUIComponents
 *
 * Function to load the components.
 *
 * @package LeanStyleguide\Helpers
 */

namespace LeanStyleguide\Helpers;

class LoadUIComponents {
	/**
	 * Components base path
	 *
	 * @var string
	 */
	private $base_path;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->base_path = get_template_directory() . '/frontend/components/';
	}

	/**
	 * Loads only one component.
	 *
	 * @param string $component_query               The URL query data of a component.
	 * @param string $component_visualization_query The URL query that specifies the visualization of the component in
	 *                                              the styleguide.
	 */
	public function load_one_component( string $component_query, string $component_visualization_query ) {
		$parts = explode( '/', $component_query );

		$directory      = $parts[0] ?? '';
		$component_slug = str_replace( $directory, '', $component_query );

		$default_directory_path = $this->base_path . $directory;
		$directory_path         = apply_filters( 'lean_styleguide_component_dir_path', $default_directory_path );

		$file_path      = $directory_path . $component_slug . '.php';
		$component_data = UIComponent::get_component_data( $file_path );

		$component = new PrintUIComponent( $component_slug, $directory, $component_data );
		$component->print_component_box(
			'raw' === $component_visualization_query,
			false
		);
	}

	/**
	 * Get all component's filenames with path.
	 */
	public function get_all_components_filenames() {
		$default_directories = [
			'atoms',
			'molecules',
			'organisms',
			'templates',
		];

		$directories = apply_filters( 'lean_styleguide_component_directories', $default_directories );

		$component_filenames = [];

		foreach ( $directories as $directory ) {
			$default_directory_path = $this->base_path . $directory;
			$directory_path         = apply_filters( 'lean_styleguide_component_dir_path', $default_directory_path );

			$component_filenames[] = [
				'filename'  => Loader::load_directories( $directory_path ),
				'directory' => $directory,
			];
		}

		return $component_filenames;
	}

	/**
	 * Auto load classes in directory by looking on folder and sub-folders.
	 */
	public function load_all_components() {
		$component_filenames = self::get_all_components_filenames();

		foreach ( $component_filenames as $component_filename ) {
			foreach ( $component_filename['filename'] as $file_path => $object ) {
				$component = new PrintUIComponent(
					UIComponent::get_component_slug( $component_filename['directory'], $file_path ),
					$component_filename['directory'],
					UIComponent::get_component_data( $file_path )
				);

				$component->print_component_box();
			}
		}
	}

	/**
	 * Auto load classes in directory by looking on folder and sub-folders.
	 *
	 * @param string $type the type of the component which should be the directory name.
	 */
	public function load_all_components_per_type( string $type ) {
		$directory_path = $this->base_path . $type;
		$filenames = Loader::load_directories( $directory_path );

		foreach ( $filenames as $file_path => $object ) {
			$component = new PrintUIComponent(
				UIComponent::get_component_slug( $type, $file_path ),
				$type,
				UIComponent::get_component_data( $file_path )
			);

			$component->print_component_box();
		}
	}

	/**
	 * Auto load classes in a specific directory by looking on folder and sub-folders.
	 *
	 * @param string $type The type of the components to load.
	 */
	public function load_all_components_of_type( string $type ) {
		$component_filenames = self::get_all_components_filenames();

		foreach ( $component_filenames as $component_filename ) {
			foreach ( $component_filename['filename'] as $file_path => $object ) {
				$component = new PrintUIComponent(
					UIComponent::get_component_slug( $component_filename['directory'], $file_path ),
					$component_filename['directory'],
					UIComponent::get_component_data( $file_path )
				);

				$component->print_component_box();
			}
		}
	}

	/**
	 * Auto load classes in directory by looking on folder and sub-folders and print them in JSON format.
	 */
	public function load_all_components_json() {
		$component_filenames = self::get_all_components_filenames();

		$json_output = [];

		foreach ( $component_filenames as $component_filename ) {
			foreach ( $component_filename['filename'] as $file_path => $object ) {
				$json_output[] = [
					'filename'  => $file_path,
					'directory' => $component_filename['directory'],
					'slug'      => UIComponent::get_component_slug( $component_filename['directory'], $file_path ),
				];
			}
		}

		echo wp_send_json( $json_output );
	}
}
