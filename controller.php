
<?php 
function filter_jobs() {
	$categorySlug = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : 'all';
	$searchKeyword = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
	$current_page = isset($_POST['current_page']) ? intval($_POST['current_page']) : 1;
	$posts_per_page = 4;
// 	$offset = isset($_POST['$offset']) ? intval($_POST['$offset']) : 0;
	$args = array(
		'post_type' => 'jobs',
		'post_status' => 'publish',
		'posts_per_page' => $posts_per_page,
		'paged' => $current_page,
		'orderby' => 'date',
		'order' => 'DESC',
		's' => $searchKeyword // Search
	);
	// Nếu không phải là All thì bắt đầu truy vấn.	
	 if ($categorySlug !== 'all') {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'jobs-category',
                'field'    => 'slug',
                'terms'    => $categorySlug,
            ),
        );
    }

	$query = new WP_Query($args);
	ob_start();
	if ($query->have_posts()):
        while ($query->have_posts()): $query->the_post();
            $locations = get_field('location');
            $times = get_field('time');
            ?>
            <div class="container_jobs" style="margin-bottom: 30px">
                <div class="meta">
                    <div class="title">
                        <h3 style="margin:0"> <i class="fa-solid fa-briefcase"  style="margin-right: 10px"></i> <a href="<?= esc_url(get_permalink()) ?>"> <?= get_the_title(); ?> </a></h3> 
                    </div>
                    <div class="field">
						<?php foreach ($times as $time) : ?>
						<span><?= $time ?></span>
						<?php endforeach; ?>
						<?php foreach ($locations as $location) : ?>
						<span><?= $location ?></span>
						<?php endforeach; ?>
                    </div>
                    <div class="timer">
                        <i class="fa-regular fa-bookmark" style="margin-right: 10px"></i> <?= get_the_date('d/m/Y') ?>
                    </div>
                </div>
            </div>
        <?php
        endwhile;
        wp_reset_postdata();

    else :
        echo '<p>No more jobs found</p>';
    endif;

	$output = ob_get_clean();
	echo $output;
	exit;
}

add_action('wp_ajax_filter_jobs', 'filter_jobs');
add_action('wp_ajax_nopriv_filter_jobs', 'filter_jobs');


//---------------------
function submit_job_form() {
    // Lấy dữ liệu từ request POST
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $category = isset($_POST['form_jobs']) ? sanitize_text_field($_POST['form_jobs']) : '';
    
    // Xử lý tập tin được tải lên
    $file = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file_name = $_FILES['file']['name'];
        $file_temp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $file_size = $_FILES['file']['size'];

        // Lưu tập tin vào thư mục trên server
        $upload_dir = wp_upload_dir();
        $file_path = $upload_dir['path'] . '/' . $file_name;
        move_uploaded_file($file_temp, $file_path);

        // Thêm tập tin vào nội dung email
        $file = $file_path;
    };

    // Tạo nội dung email
    $subject = 'New job submission';
    $message = "Email: $email\n";
    $message .= "Category: $category\n";
    // Nếu có tệp tin đính kèm, thêm vào nội dung email
	if (!empty($file)) {
		$message .= "<p><strong>File:</strong> $file_name</p>";
	}

    // Gửi email cho quản trị viên
    $to_admin = get_option('admin_email');
    $headers = array('Content-Type: text/html; charset=UTF-8');
	$attachments = array($file_path); // Đính kèm tệp tin vào email
    wp_mail('ntd220996@gmail.com', $subject, $message, $headers, $attachments);
    // Phản hồi cho request AJAX
    echo 'success';

    // Dừng kịch bản và ngăn chặn WordPress kết thúc tập tin
    die();
}
add_action('wp_ajax_submit_job_form', 'submit_job_form');
add_action('wp_ajax_nopriv_submit_job_form', 'submit_job_form');

// NEWS SLIDE
add_action('wp_ajax_custom_post_ajax', 'custom_post_ajax');
add_action('wp_ajax_nopriv_custom_post_ajax', 'custom_post_ajax');

function custom_post_ajax() {
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
   	$args = array(
        'post_type' => 'post',
        'posts_per_page' => 3,
        'paged' => $page,
        'cat' => $category_id 
    );
    $query = new WP_Query($args);;
	ob_start();
    if ($query->have_posts()):
        while ($query->have_posts()): $query->the_post(); ?>
            <div>
                <h4><?php echo get_the_title(); ?></h4>
            </div>
        <?php endwhile;
    else:
        echo 'No posts found.';
    endif;
	$posts_html = ob_get_clean();
    wp_reset_postdata();
	$total_pages = $query->found_posts;
	wp_send_json(array(
		'total_pages' => $total_pages,
		'posts_html' => $posts_html
	));
    wp_die();
}

