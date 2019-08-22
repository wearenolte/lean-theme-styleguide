<?php
$styles = $args['styles'] ?? [];
?>

<div class="sg-bg-gray-4">
	<div class="container sg-pb-2">

		<?php $style_counter = 1; ?>

		<?php foreach ( $styles as $style ) : ?>

			<?php if ( $style ) : ?>

				<div>
					<b>Style <?php echo 1 === $style_counter ? '' :  esc_html( $style_counter ); ?>:</b>
					<?php echo esc_html( $style ); ?>
				</div>

			<?php endif; ?>

			<?php $style_counter++; ?>

		<?php endforeach; ?>

	</div>
</div>
