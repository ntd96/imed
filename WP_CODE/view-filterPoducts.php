<?php 
function debug_to_console($data, $context = 'Debug in Console') {
	ob_start();
	$output  = 'console.info(\'' . $context . ':\');';
	$output .= 'console.log(' . json_encode($data) . ');';
	$output  = sprintf('<script>%s</script>', $output);
	echo $output;
}
?>


<div id="category-list">
	<?php
		$root_products_cat = get_terms(array(
			'taxonomy' => 'product_cat',
			'parent' => 0,
		)); 
		foreach($root_products_cat as $root_product_cat): debug_to_console($root_product_cat); ?>
		<div class="category-product-title">
			<h3> <?php echo $root_product_cat->name; ?> </h3>
		</div>
			<!-- Loop tiếp danh mục con của Root products cat -->
			<?php 
			$sub_products_cat = get_terms(array(
				'taxonomy' => 'product_cat',
				'parent' => $root_product_cat->term_id,
				'hide_empty' => true,
				'post_status' => 'publish',
			));
	
			foreach($sub_products_cat as $sub_product_cat) : debug_to_console($sub_product_cat->name); ?>
				<div class="category_product_content" style='display:none'>
					<p> <?php echo $sub_product_cat->name; ?> </p>
				</div>
			<?php endforeach;	
			// ----->
		endforeach;
	?>
</div>
<div class="result_product_filter">
	
</div>
<script>
	jQuery(document).ready(function($) {
		$('.category-product-title').click(function() {
			$(this).siblings('.category_product_content').slideToggle();
		});
	});
</script>