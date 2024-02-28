<select id="product-category-filter">
    <option value="all">Tất cả</option>
    <?php
    $categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
    ));

    foreach ($categories as $category) {
        echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
    }
    ?>
</select>

<div id="product-results">
    <!-- Kết quả tìm kiếm sẽ được hiển thị ở đây -->
</div>