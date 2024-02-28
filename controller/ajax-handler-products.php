<?php 

function custom_product_filter() {
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    );

    if ($category_id > 0) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $category_id,
            ),
        );
    }

    $products = new WP_Query($args);

    // Truyền biến $products vào template
    ob_start();
    get_template_part('template/product', 'filter', array('products' => $products));
    $response = ob_get_clean();
    echo $response;
    wp_die();
}