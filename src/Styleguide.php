<?php

namespace LeanStyleguide;

use LeanStyleguide\Helpers\LoadUIComponents;
use LeanStyleguide\Helpers\PageOnTheFly;
use LeanStyleguide\Helpers\ViewsLoader;

/**
 * Class Styleguide
 *
 * Builds the styleguide page.
 *
 * @package LeanStyleguide
 */
class Styleguide {

	/**
	 * URL query used for loading individual components.
	 */
	const COMPONENT_URL_QUERY               = 'component';
	const TYPE_URL_QUERY                    = 'type';
	const COMPONENT_VISUALIZATION_URL_QUERY = 'view';

	/**
	 * A LoadUIComponent instance.
	 *
	 * @var $loadUIComponent
	 */
	private $loadUIComponent;

	/**
	 * The PageOnTheFly instance that holds the WP page info.
	 *
	 * @var $fly_page_instance
	 */
	private $fly_page_instance;

	/**
	 * ViewsLoader instance.
	 *
	 * @var object
	 */
	private $views_loader;

	/**
	 * Components base path
	 *
	 * @var string
	 */
	private $base_path;

	/**
	 * Styleguide constructor.
	 */
	public function __construct() {
		/**
		 * Create the fake WP page.
		 */
		$this->fly_page_instance = new PageOnTheFly(
			[
				'slug'         => 'styleguide',
				'post_title'   => 'Styleguide',
				'post_content' => 'Components styleguide',
			]
		);

		$this->base_path = get_template_directory() . '/frontend/components/';

		$this->views_loader    = new ViewsLoader();
		$this->loadUIComponent = new LoadUIComponents();

		$component_query = sanitize_text_field( $_GET[ self::COMPONENT_URL_QUERY ] ?? '' );

		if ( 'all' === $component_query ) {
			$this->loadUIComponent->load_all_components_json();
		} else {
			add_action( 'template_redirect', [ $this, 'set_fly_page_template' ] );
			add_action( 'lean_styleguide_header', [ $this, 'create_header' ] );
			add_action( 'lean_styleguide_footer', [ $this, 'create_footer' ] );
			add_action( 'lean_styleguide_content', [ $this, 'create_content' ] );
		}
	}

	/**
	 * Creates the Styleguide page header.
	 */
	public function create_header() {
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
	public function create_footer() {
		?>

		<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/frontend/dist/main.js"></script>

		<?php
		include 'js-scripts.php';
	}

	/**
	 * Creates the Styleguide page main content.
	 */
	public function create_content() {
		$component_query               = sanitize_text_field( $_GET[ self::COMPONENT_URL_QUERY ] ?? '' );
		$type_query                    = sanitize_text_field( $_GET[ self::TYPE_URL_QUERY ] ?? '' );
		$component_visualization_query = sanitize_text_field( $_GET[ self::COMPONENT_VISUALIZATION_URL_QUERY ] ?? '' );

		if ( $component_query ) {
			$this->loadUIComponent->load_one_component( $component_query, $component_visualization_query );
		} else if ( $type_query ) {
			$this->loadUIComponent->load_all_components_per_type( $type_query );
		} else {
			$this->loadUIComponent->load_all_components();
		}
	}

	/**
	 * Sets a custom template.
	 */
	public function set_fly_page_template() {
		if ( is_page( $this->fly_page_instance::PAGE_ID ) ) {
			$default_template = 'styleguide-template.php';
			include apply_filters( 'lean_styleguide_template', $default_template );
			exit;
		}
	}
}
