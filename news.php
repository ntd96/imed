<?php
function debug_to_console($data, $context = 'Debug in Console')
{
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
foreach ($subcategoriesBook as $sub) :
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
    $countLatestPost = 0;
    $firstPost = $query->posts[0];
    debug_to_console($firstPost);
?>
    <div class="container_featured_post">
        <div class="featured_main_posts">
            <h3><a href="<?php echo $firstPost->guid; ?>"> <?php echo $firstPost->post_title ?></a> </h3>
            <p> <?php echo $subCatBook_name; ?> </p>
        </div>
        <div class="featured_latest_posts">
            <h3>Latest Posts</h3>
            <?php
            while ($query->have_posts()) :
                $query->the_post();
                $post_date = ' <i class="fa-solid fa-calendar-days"></i> ' . get_the_date('d/m/Y') . ' <i class="fa fa-clock"></i> ' . get_the_time('H:i:s');
                $excerpt = get_the_excerpt();
                $excerpt = wp_trim_words($excerpt, 20);
                if ($countLatestPost > 0) : ?>
                    <div class="featured_latest_posts-content">
                        <h4><a href="<?php echo the_permalink(); ?>"> <?php echo get_the_title(); ?></a> </h4>
                        <p> <?php echo $excerpt; ?> </p>
                        <p> <?php echo $post_date; ?> </p>
                    </div>
            <?php endif;
                $countLatestPost++;
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
<?php
else :
    echo 'Không có bài viết nào được đánh dấu là "Bài viết nổi bật"';
endif;
?>


<style>
    .container_featured_post {
        display: flex;
        width: 100%;
    }

    .featured_main_posts,
    .featured_latest_posts {
        width: calc(100% / 2);
    }
</style>