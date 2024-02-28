<?php
// Thêm filter dựa trên trường custom của ACF
function custom_filter_by_featured_posts()
{
    global $typenow;

    // Chỉ hiển thị filter trên trang quản lý bài viết
    if ($typenow == 'post') {
        // Hiển thị dropdown filter
?>
        <select name="custom_featured_post">
            <option value=""><?php _e('Chọn trạng thái bài viết nổi bật', 'text-domain'); ?></option>
            <option value="featured" <?php echo isset($_GET['custom_featured_post']) && $_GET['custom_featured_post'] === 'featured' ? ' selected="selected"' : ''; ?>>Bài viết nổi bật</option>
            <option value="not_featured" <?php echo isset($_GET['custom_featured_post']) && $_GET['custom_featured_post'] === 'not_featured' ? ' selected="selected"' : ''; ?>>Bài viết không nổi bật</option>
        </select>
<?php
    }
}
add_action('restrict_manage_posts', 'custom_filter_by_featured_posts');

// Xử lý filter khi được chọn
function custom_filter_query_by_featured_posts($query)
{
    global $pagenow, $typenow;

    // Chỉ thực thi filter trên trang quản lý bài viết và khi query là post type 'post'
    if (is_admin() && $pagenow == 'edit.php' && $typenow == 'post') {
        if (isset($_GET['custom_featured_post']) && $_GET['custom_featured_post'] != '') {
            if ($_GET['custom_featured_post'] === 'featured') {
                $meta_query = array(
                    'key' => 'bai_viết_nổi_bật',
                    'value' => 'featured',
                    'compare' => 'LIKE'
                );
            } else {
                $meta_query = array(
                    'relation' => 'OR',
                    array(
                        'key' => 'bai_viết_nổi_bật',
                        'compare' => 'NOT EXISTS'
                    ),
                    array(
                        'key' => 'bai_viết_nổi_bật',
                        'value' => '',
                        'compare' => '='
                    )
                );
            }

            // Thêm meta query vào query chính
            $query->set('meta_query', array($meta_query));
        }
    }
}
add_action('parse_query', 'custom_filter_query_by_featured_posts');


// BE xử lí hanlder ajax filter products
require_once get_stylesheet_directory() . '/ajax-handlers.php';