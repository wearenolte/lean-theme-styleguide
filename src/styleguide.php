<?php

namespace LeanStyleguide;

use function LeanStyleguide\Helpers\LoadFile\file_loader;
use LeanStyleguide\Helpers\PageOnTheFly\PageOnTheFly;

/**
 * Views loader.
 *
 * @param string $file_name The view name
 * @param array  $args      The view arguments.
 */
function load_view( string $view_name = '', $args = [] ) {
	file_loader( $view_name, '/views', $args );
}

/**
 * Create the fake WP page.
 */
new PageOnTheFly(
	[
		'slug'         => 'styleguide',
		'post_title'   => 'Styleguide',
		'post_content' => 'Components styleguide',
	]
);


/**
 * Auto load classes in directory by looking on folder and sub-folders.
 *
 * @param string $directory The directories to load.
 */
function autoload_components( string $directory ) {
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
		$component_slug = get_component_slug( $directory, $file );
		?>
		<div
			id="<?php echo esc_attr( sanitize_title( $component_slug ) ); ?>"
			class="sg-my-12">

			<a
				class="sg-block sg-py-2 sg-bg-gray-4"
				href="#<?php echo esc_attr( sanitize_title( $component_slug ) ); ?>">

				<h3 class="container">
					<b><?php echo esc_html( $component_slug ); ?></b>
				</h3>

			</a>

			<?php
			$component_data = get_component_data( $file );

			if ( $component_data ) {
				$container       = $component_data['container'] ?? true;
				$component_class = $component_data['class'] ?? '';

				if ( ! empty( $component_data['variants'] ) ) {
					foreach ( (array) $component_data['variants'] as $component_instance_data ) {
						?>

						<div class="sg-bg-gray-4">
							<div class="container sg-pb-2">

								<?php
								$style  = empty( $component_instance_data['style'] ) ? 'default' : $component_instance_data['style'];
								$style2 = empty( $component_instance_data['style2'] ) ? '' : $component_instance_data['style2'];
								$style3 = empty( $component_instance_data['style3'] ) ? '' : $component_instance_data['style3'];
								$style4 = empty( $component_instance_data['style4'] ) ? '' : $component_instance_data['style4'];
								?>

								<b>Style:</b> <?php echo esc_html( $style ); ?>

								<?php if ( $style2 ) : ?>
									<div>
										<b>Style 2:</b> <?php echo esc_html( $style2 ); ?>
									</div>
								<?php endif; ?>

								<?php if ( $style3 ) : ?>
									<div>
										<b>Style 3:</b> <?php echo esc_html( $style3 ); ?>
									</div>
								<?php endif; ?>

								<?php if ( $style4 ) : ?>
									<div>
										<b>Style 4:</b> <?php echo esc_html( $style4 ); ?>
									</div>
								<?php endif; ?>

							</div>
						</div>

						<?php
						load_view(
							'component',
							[
								'directory'               => $directory,
								'component_slug'          => $component_slug,
								'component_instance_data' => $component_instance_data,
								'container'               => $container,
								'component_class'         => $component_class,
							]
						);
					}
				} else {
					load_view(
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
				load_view(
					'component',
					[
						'directory'      => $directory,
						'component_slug' => $component_slug,
					]
				);
			}
			?>

		</div>
		<?php
	}
}

add_action(
	'lean_styleguide_header',
	function () {
		$default_stylesheet = get_template_directory_uri() . '/frontend/dist/main.css';
		$stylesheet         = apply_filters( 'lean_styleguide_css', $default_stylesheet );
		?>
		<link rel='stylesheet'
			  href='https://fonts.googleapis.com/css?family=Anton%7CHeebo%3A400%2C500%2C700%2C800%2C900&#038;display=swap&#038;ver=1.0' />
		<link rel="stylesheet"
			  href="<?php echo esc_url( $stylesheet ); ?>">
		<style>
		body {
			background-color: #f0f0f0;
		}

		.fixed {
			position: static !important;
		}

		.sg-hidden {
			display: none;
		}

		.sg-block {
			display: block;
		}

		.sg-my-8 {
			margin-top: 2rem;
			margin-bottom: 2rem;
		}

		.sg-my-12 {
			margin-top: 3rem;
			margin-bottom: 3rem;
		}

		.sg-mb-4 {
			margin-bottom: 1rem;
		}

		.sg-mt-2 {
			margin-top: 0.5rem;
		}

		.sg-mb-2 {
			margin-bottom: 0.5rem;
		}

		.sg-pb-2 {
			margin-bottom: 0.5rem;
		}

		.sg-py-2 {
			padding-top: 0.5rem;
			padding-bottom: 0.5rem;
		}

		.sg-h1 {
			font-size: 30px;
			line-height: 40px;
			font-weight: bold;
		}

		.sg-h2 {
			font-size: 25px;
			line-height: 35px;
			font-weight: bold;
		}

		.sg-text-13 {
			font-size: 13px;
		}

		.sg-text-blue {
			color: blue;
		}

		.sg-bg-gray-4 {
			background-color: #D8D8D8;
		}

		.sg-border-0 {
			border-width: 0;
		}
		</style>
		<?php
	}
);

add_action(
	'lean_styleguide_footer',
	function () {
		?>
		<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/frontend/dist/main.js"></script>
		<script>
          document.querySelectorAll( '.js-collapse' ).forEach( function ( collapseContainer ) {
            collapseContainer.querySelector( '.js-collapse-button' ).addEventListener( 'click', function () {
              var content = collapseContainer.querySelector( '.js-collapse-content' )

              if ( content.classList.contains( 'hidden' ) ) {
                content.classList.remove( 'hidden' )
              } else {
                content.classList.add( 'hidden' )
              }
            } )
          } )
		</script>
		<?php
	}
);

add_action(
	'lean_styleguide_content',
	function () {
		$default_directories = [
			'atoms',
			'molecules',
			'organisms',
			'templates',
		];

		$directories = apply_filters( 'lean_styleguide_component_directories', $default_directories );
		?>

		<?php foreach ( $directories as $directory ) : ?>

			<h2 class="h2 container my-12">
				<?php echo esc_html( $directory ); ?>
			</h2>

			<?php autoload_components( $directory ); ?>

		<?php endforeach; ?>

		<?php
	}
);
