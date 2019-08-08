<?php


$directory       = $args['directory'] ?? '';
$component_slug  = $args['component_slug'] ?? '';
$component_data  = $args['component_data'] ?? [];
$container       = $args['container'] ?? true;
$component_class = $args['component_class'] ?? '';

$container_class = $container ? 'container' : '';
?>

<div class="sg-mt-2 sg-mb-4 js-collapse <?php echo esc_attr( $container_class ); ?>">

	<div class="<?php echo esc_attr( $container ? '' : 'container' ); ?>">
		<button class="sg-mb-4 sg-text-13 sg-text-blue sg-border-0 js-collapse-button">View Data</button>
	</div>

	<div class="sg-hidden sg-text-13 js-collapse-content">
		<?php echo '<pre>' . print_r( $component_data, true ) . '</pre>'; ?>
	</div>


	<div class="sg-mt-4 <?php echo esc_attr( $component_class ); ?>">

		<?php
		Load::$directory(
			$component_slug,
			$component_data
		);
		?>

	</div>

</div>
