<?php
$component_slug = $args['component_slug'] ?? '';
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