<?php

namespace LeanStyleguide\Helpers;

/**
 * Class PrintUIComponent
 *
 * Functions for printing a UI component.
 *
 * @package LeanStyleguide\Helpers
 */
class PrintUIComponent {
	/**
	 * The component's slug.
	 *
	 * @var string
	 */
	private $component_slug;

	/**
	 * The components main directory name.
	 *
	 * @var string
	 */
	private $directory;

	/**
	 * ViewsLoader instance.
	 *
	 * @var object
	 */
	private $views_loader;

	/**
	 * Constructor.
	 */
	public function __construct( string $component_slug, string $directory, array $component_data ) {
		$this->views_loader   = new ViewsLoader();
		$this->component_slug = $component_slug;
		$this->directory      = $directory;
		$this->component_data = $component_data;
	}

	/**
	 * Prints the whole section of a component (including header and footer)
	 *
	 * @param bool $raw Set as true to print the heading and styles information.
	 * @param bool $print_margins Print the boxes margins styles?
	 */
	public function print_component_box( bool $raw = false, bool $print_margins = true ) {
		if ( ! $raw ) {
			$this->views_loader->load_view(
				'component-heading',
				[
					'style'          => $print_margins ? 'print-margins' : '',
					'component_slug' => $this->component_slug,
					'component_type' => $this->directory,
				]
			);
		}

		$this->print_component_and_variants( $raw );

		if ( ! $raw ) {
			$this->views_loader->load_view( 'component-footer' );
		}
	}

	/**
	 * Prints a component and its variants
	 *
	 * @param bool $raw Set as true to print the heading and styles information.
	 */
	public function print_component_and_variants( bool $raw = false ) {
		if ( $this->component_data ) {
			$container       = $this->component_data['container'] ?? true;
			$component_class = $this->component_data['class'] ?? '';

			if ( ! empty( $this->component_data['variants'] ) ) {
				foreach ( (array) $this->component_data['variants'] as $component_instance_data ) {

					if ( ! $raw ) {
						$this->views_loader->load_view(
							'component-styles',
							[
								'styles' => [
									empty( $component_instance_data['style'] ) ? 'default' : $component_instance_data['style'],
									empty( $component_instance_data['style2'] ) ? '' : $component_instance_data['style2'],
									empty( $component_instance_data['style3'] ) ? '' : $component_instance_data['style3'],
									empty( $component_instance_data['style4'] ) ? '' : $component_instance_data['style4'],
								],
							]
						);
					}

					$this->views_loader->load_view(
						'component',
						[
							'print_data'      => ! $raw,
							'directory'       => $this->directory,
							'component_slug'  => $this->component_slug,
							'component_data'  => $component_instance_data,
							'container'       => $container,
							'component_class' => $component_class,
						]
					);
				}
			} else {
				$this->views_loader->load_view(
					'component',
					[
						'print_data'      => ! $raw,
						'directory'       => $this->directory,
						'component_slug'  => $this->component_slug,
						'container'       => $container,
						'component_class' => $component_class,
					]
				);
			}
		} else {
			$this->views_loader->load_view(
				'component',
				[
					'print_data'     => ! $raw,
					'directory'      => $this->directory,
					'component_slug' => $this->component_slug,
				]
			);
		}
	}
}