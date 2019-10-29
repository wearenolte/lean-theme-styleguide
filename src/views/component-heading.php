<?php

$style          = $args['style'] ?? '';
$component_slug = $args['component_slug'] ?? '';
$component_type = $args['component_type'] ?? '';

$class = 'print-margins' === $style ? 'sg-my-12' : '';
?>

<div
	id="<?php echo esc_attr( sanitize_title( $component_slug ) ); ?>"
	class="<?php echo esc_attr( $class ); ?>">

	<a
		class="sg-block sg-py-2 sg-bg-gray-4"
		href="#<?php echo esc_attr( sanitize_title( $component_slug ) ); ?>">

		<h3 class="container">
			<b><?php echo esc_html( $component_type . ' - ' . $component_slug ); ?></b>
		</h3>

	</a>