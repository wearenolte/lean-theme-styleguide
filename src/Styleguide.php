<?php

namespace LeanStyleguide;

use LeanStyleguide\Helpers\PageOnTheFly;
use LeanStyleguide\Helpers\UIComponent;
use LeanStyleguide\Helpers\LoadFile;

/**
 * Class Styleguide
 *
 * Builds the styleguide page.
 *
 * @package LeanStyleguide
 */
class Styleguide {
	/**
	 * Class singleton instance
	 *
	 * @var Styleguide
	 */
	private static $instance;

	/**
	 * The PageOnTheFly instance that holds the WP page info.
	 *
	 * @var PageOnTheFly
	 */
	private static $fly_page_instance;

	/**
	 * Library base path
	 *
	 * @var string
	 */
	private static $base_path;

	/**
	 * Initialize singleton.
	 *
	 * @return Styleguide
	 */
	public static function init() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Styleguide constructor.
	 */
	public function __construct() {
		/**
		 * Create the fake WP page.
		 */
		self::$fly_page_instance = new PageOnTheFly(
			[
				'slug'         => 'styleguide',
				'post_title'   => 'Styleguide',
				'post_content' => 'Components styleguide',
			]
		);

		self::$base_path = dirname( __FILE__ );

		add_action( 'template_redirect', [ $this, 'set_fly_page_template' ] );
		add_action( 'lean_styleguide_header', [ __CLASS__, 'create_header' ] );
		add_action( 'lean_styleguide_footer', [ __CLASS__, 'create_footer' ] );
		add_action( 'lean_styleguide_content', [ __CLASS__, 'create_content' ] );
	}

	/**
	 * Views loader.
	 *
	 * @param string $file_name The view name
	 * @param array  $args      The view arguments.
	 */
	public static function load_view( string $view_name = '', $args = [] ) {
		LoadFile::file_loader( $view_name, self::$base_path . '/views', $args );
	}

	/**
	 * Auto load classes in directory by looking on folder and sub-folders.
	 *
	 * @param string $directory The directories to load.
	 */
	public static function autoload_components( string $directory ) {
		$default_directory_path = get_template_directory() . '/frontend/components/' . $directory;
		$directory_path         = apply_filters( 'lean_styleguide_component_dir_path', $default_directory_path );

		$iterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator( $directory_path ),
			\RecursiveIteratorIterator::SELF_FIRST
		);

		$regex = new \RegexIterator(
			$iterator, '/^.+\.php$/i',
			\RecursiveRegexIterator::GET_MATCH
		);

		foreach ( $regex as $file => $object ) {
			$component_slug = UIComponent::get_component_slug( $directory, $file );

			self::load_view(
				'component-heading',
				[
					'component_slug' => $component_slug,
				]
			);

			$component_data = UIComponent::get_component_data( $file );

			if ( $component_data ) {
				$container       = $component_data['container'] ?? true;
				$component_class = $component_data['class'] ?? '';

				if ( ! empty( $component_data['variants'] ) ) {
					foreach ( (array) $component_data['variants'] as $component_instance_data ) {

						self::load_view(
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

						self::load_view(
							'component',
							[
								'directory'       => $directory,
								'component_slug'  => $component_slug,
								'component_data'  => $component_instance_data,
								'container'       => $container,
								'component_class' => $component_class,
							]
						);
					}
				} else {
					self::load_view(
						'component',
						[
							'directory'       => $directory,
							'component_slug'  => $component_slug,
							'container'       => $container,
							'component_class' => $component_class,
						]
					);
				}
			} else {
				self::load_view(
					'component',
					[
						'directory'      => $directory,
						'component_slug' => $component_slug,
					]
				);
			}

			self::load_view( 'component-close-heading' );
		}
	}

	/**
	 * Creates the Styleguide page header.
	 */
	public static function create_header() {
		$default_stylesheet = get_template_directory_uri() . '/frontend/dist/main.css';
		$stylesheet         = apply_filters( 'lean_styleguide_css', $default_stylesheet );
		?>

		<link rel="stylesheet" href="<?php echo esc_url( $stylesheet ); ?>">

		<?php
		include 'styles.php';
	}

	/**
	 * Creates the Styleguide page footer.
	 */
	public static function create_footer() {
		?>

		<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/frontend/dist/main.js"></script>

		<?php
		include 'js-scripts.php';
	}

	/**
	 * Creates the Styleguide page main content.
	 */
	public static function create_content() {
		$default_directories = [
			'atoms',
			'molecules',
			'organisms',
			'templates',
		];

		$directories = apply_filters( 'lean_styleguide_component_directories', $default_directories );

		foreach ( $directories as $directory ) {
			self::load_view(
				'directory-heading',
				[
					'directory' => $directory,
				]
			);

			self::autoload_components( $directory );
		}
	}

	/**
	 * Sets a custom template.
	 */
	public static function set_fly_page_template() {
		if ( is_page( self::$fly_page_instance::PAGE_ID ) ) {
			$default_template = 'styleguide-template.php';
			include apply_filters( 'lean_styleguide_template', $default_template );
			exit;
		}
	}
}
