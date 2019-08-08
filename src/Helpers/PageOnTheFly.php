<?php
namespace LeanStyleguide\Helpers\PageOnTheFly;

/**
 * PageOnTheFly
 *
 * @author Ohad Raz
 * @since  0.1
 *
 * Class to create pages "On the FLY"
 *
 * Usage:
 *   $args = array(
 *       'slug' => 'fake_slug',
 *       'post_title' => 'Fake Page Title',
 *       'post content' => 'This is the fake page content'
 *   );
 *   new PageOnTheFly($args);
 *
 * Extended by @FcogT
 */
class PageOnTheFly {

	const  PAGE_ID = - 42;
	public $slug = '';
	public $args = [];

	/**
	 * __construct
	 *
	 * @param array $args post data to create on the fly
	 */
	public function __construct( $args ) {
		add_filter( 'the_posts', [ $this, 'create_fly_page' ] );
		add_action( 'template_redirect', [ $this, 'set_fly_page_template' ] );
		$this->args = $args;
		$this->slug = $args['slug'];
	}

	/**
	 * Function that catches the request and returns the page as if it was retrieved from the database
	 *
	 * @param array $posts The posts.
	 *
	 * @return array
	 */
	public function create_fly_page( $posts ) {
		global $wp, $wp_query;
		$page_slug = $this->slug;

		//check if user is requesting our fake page
		if ( count( $posts ) == 0 && ( strtolower( $wp->request ) == $page_slug || $wp->query_vars['page_id'] == $page_slug ) ) {

			//create a fake post
			$post              = new stdClass;
			$post->post_author = 1;
			$post->post_name   = $page_slug;
			$post->guid        = get_bloginfo( 'wpurl' . '/' . $page_slug );
			$post->post_title  = 'page title';
			//put your custom content here
			$post->post_content = "Fake Content";
			//just needs to be a number - negatives are fine
			$post->ID             = self::PAGE_ID;
			$post->post_status    = 'static';
			$post->comment_status = 'closed';
			$post->ping_status    = 'closed';
			$post->comment_count  = 0;
			//dates may need to be overwritten if you have a "recent posts" widget or similar - set to whatever you want
			$post->post_date     = current_time( 'mysql' );
			$post->post_date_gmt = current_time( 'mysql', 1 );

			$post    = (object) array_merge( (array) $post, (array) $this->args );
			$posts   = null;
			$posts[] = $post;

			$wp_query->is_page     = true;
			$wp_query->is_singular = true;
			$wp_query->is_home     = false;
			$wp_query->is_archive  = false;
			$wp_query->is_category = false;

			unset( $wp_query->query['error'] );

			$wp_query->query_vars['error'] = "";
			$wp_query->is_404              = false;
		}

		return $posts;
	}

	/**
	 * Set a custom template.
	 */
	public static function set_fly_page_template() {
		if ( is_page( self::PAGE_ID ) ) {
			$default_template = 'styleguide-template.php';
			include apply_filters( 'lean_styleguide_template', $default_template );
			exit;
		}
	}
}
