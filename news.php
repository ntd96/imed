<?php 
function debug_to_console($data, $context = 'Debug in Console') {
	// Buffering to solve problems frameworks, like header() in this and not a solid return.
	ob_start();
	$output  = 'console.info(\'' . $context . ':\');';
	$output .= 'console.log(' . json_encode($data) . ');';
	$output  = sprintf('<script>%s</script>', $output);
	echo $output;
}

$subcategoriesBook = get_categories(array(
	'child_of' => 35,
	'hide_empty' => false,
)); 

$subCatBook_name = '';
$subCatBook_ids = [];

// 
foreach($subcategoriesBook as $sub) :
	$subCatBook_ids[] = $sub->term_id;
	$subCatBook_name = $sub->name;
endforeach;
//
$query_args = array(
	'post_type' => 'post', 
	'posts_per_page' => -1,
	'category__in' => $subCatBook_ids,
	'orderby' => 'date',
	'order' => 'DESC',
	'meta_query' => array( // Nếu chỉ có 1 đk thì dùng 1 arr
		'relation' => 'OR', // Để thoả mãn điều kiện 1 trong 2 meta
		array(
			'key' => 'bai_viết_nổi_bật',
			'value' => 'featured',
			'compare' => 'LIKE', 
		)
	)
	// neu muon thêm dieu kien meta thì thêm array
);

$query = new WP_Query($query_args);
	

if ($query->have_posts()) :
	echo '<div class="container_featured_post">';
		foreach($query->posts as $index => $post) : debug_to_console($query->posts); ?>
			<?php if($index == 0) : ?>
			<div class="featured_main_posts">
				<h3> <?php echo $post->post_title; ?> </h3>
			</div>
			<?php
			else: ?>
			<div class="featured_latest_posts">
				<h3>Latest Posts</h3>
				<p> <?php echo $post->post_title; ?> </p>
			</div>
			<?php endif; ?>
		<?php endforeach; 
	echo '</div>';
	wp_reset_postdata();
else :
	echo 'Không có bài viết nào được đánh dấu là "Bài viết nổi bật"';
endif;
?>