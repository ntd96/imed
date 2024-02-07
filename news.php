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
    // $query->the_post();
    $countLatestPost = 0;
    $firstPost = $query->posts[0];
    $post_thumbnail_url = get_the_post_thumbnail_url($firstPost->ID, 'full');
    $post_time = get_the_time('d/m/Y', $firstPost->ID);
    debug_to_console($firstPost);
?>
    <div class="container_featured_post">
        <div class="featured_main_posts">
            <div class="featured_main_posts-image">
                <img src="<?php echo $post_thumbnail_url; ?>" alt="<?php $firstPost->post - title; ?>">
                <span class="shadow">
                    <span class="featured"></span>
                    <p> Nổi bật </p>
                </span>
            </div>
            <div class="featured_main_posts-content">
                <div style="display: flex;gap:20px;flex-wrap:wrap;column-wrap:20px">
                    <span>
                        <h3 style="margin:0"> <a href="<?php echo get_permalink($firstPost->ID) ?>"> <?php echo $firstPost->post_title; ?></a></h3>
                    </span>
                    <span class="stack_title"> <?php echo $subCatBook_name; ?> </span>
                </div>
                <p style="text-align:left;margin-top:10px"> <i class="fa-regular fa-clock"></i> <?php echo get_the_time('d/m/Y', $firstPost->ID) ?> </p>
                <p style="text-align:left"> <?php echo wp_trim_words(get_the_excerpt($firstPost->ID), 20) ?> </p>
                <div style="text-align:right;font-size:24px"><a href="<?php echo get_permalink($firstPost->ID) ?>"><i class="fa-solid fa-arrow-right-long"></i></a></div>
            </div>
        </div>
        <div class="featured_latest_posts">
            <h3>Latest Posts</h3>
            <?php
            while ($query->have_posts()) :
                $query->the_post();
                $post_date = ' <i class="fa-solid fa-calendar-days"></i> ' . get_the_date('d/m/Y') . ' <i class="fa fa-clock"></i> ' . get_the_time('H:i');
                $excerpt = get_the_excerpt();
                $excerpt = wp_trim_words($excerpt, 20);
                if ($countLatestPost > 0) : ?>
                    <div class="featured_latest_posts-content">
                        <h4><a href="<?php echo the_permalink(); ?>"> <?php echo get_the_title(); ?></a> </h4>
                        <p style="margin-bottom: 10px"> <?php echo $excerpt; ?> </p>
                        <p style="margin-bottom: 10px"> <?php echo $post_date; ?> </p>
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
        flex-wrap: wrap;
        width: 100%;
        max-width: 1400px;
        margin: auto;
        column-gap: 35px;
        row-gap: 20px;
    }

    .featured_main_posts-image {
        position: relative;
    }

    .featured_main_posts-image img {
        border-radius: 10px 10px 0px 0px;
    }

    .featured_main_posts-image .shadow {
        filter: drop-shadow(0px 6px 6px rgba(0, 0, 0, 0.4));
        position: absolute;
        top: 0;
        left: 35px;
    }

    .featured_main_posts-image .featured {
        position: absolute;
        clip-path: polygon(0 0, 100% 0%, 100% 100%, 50% 65%, 0 100%);
        background-color: #FDE267;
        width: 130px;
        height: 115px;
    }

    .featured_main_posts-image p {
        position: absolute;
        width: 130px;
        margin-bottom: 0;
        height: 60px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: 700;
        font-size: 18px;
        color: black;
    }

    .featured_main_posts-content {
        padding: 20px 40px;
        background-color: #83CBC9;
        border-radius: 0 0 10px 10px;
        color: black;
        text-align: left;
    }

    .featured_main_posts-content a,
    .featured_latest_posts a {
        color: black;
    }

    .featured_main_posts-content a:hover,
    .featured_latest_posts a:hover {
        color: #5C7479;
    }

    .featured_main_posts-content .stack_title {
        background-color: #FDE267;
        padding: 5px;
        color: black;
        font-size: 12px;
        border-radius: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .featured_main_posts,
    .featured_latest_posts {
        width: 50%;
    }

    .featured_latest_posts {
        width: 40%;
    }

    .featured_latest_posts h3 {
        border-bottom: 1px solid #5C7479;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    .featured_latest_posts-content {
        text-align: left;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid;
    }

    @media screen and (max-width:1200px) {}

    @media screen and (max-width:992px) {}

    @media screen and (max-width:768px) {}
</style>