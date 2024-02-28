<?php

$products = isset($args['products']) ? $args['products'] : null;

if ( $products->have_posts() ) :
    while ( $products->have_posts() ) :
        $products->the_post();
        // Hiển thị nội dung sản phẩm
    endwhile;
    wp_reset_postdata(); // Reset lại query
else :
    echo '<p>Không tìm thấy sản phẩm.</p>';
endif;