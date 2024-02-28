<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Render Filter -->
<div class="job-filter">
	<!-- Category -->
	<select class="js-example-basic-single" name="job-category" id="job-category">
		<option value="all">Tất cả</option>
		<?php
		$categories = get_categories(array(
			'taxonomy' => 'jobs-category',
			'hide_empty' => false,
		));
		foreach ($categories as $category) : ?>
			<option value="<?= $category->slug ?>"> <?= $category->name ?> </option>
		<?php endforeach; ?>
	</select>
	<!-- Search	 -->
	<div class='job-search'>
		<input type="text" id="job-search" placeholder="Search...">
		<button id="job-search-btn" style="padding:0;margin:0"><i class="fa-solid fa-magnifying-glass"></i></button>
	</div>
</div>
<!-- Render View -->
<div style="position: relative;">
	<div class="job-list"></div>
	<div class="loader-filter-jobs loader" style="display: none"></div>
</div>
<button id="load-more-jobs">Xem thêm công việc</button>

<script>
	$(document).ready(function() {
		let currentPage = 1;
		let searchKeyword = '';
		let categorySlug = 'all';
		// Sử dụng Select2 cho dropdown danh mục công việc
		$('.js-example-basic-single').select2();
		// Lắng nghe sự kiện thay đổi trong dropdown danh mục
		$('#job-category').change(function() {
			let categorySlug = $(this).val();
			let searchKeyword = $('#job-search').val().trim();
			currentPage = 1;
			$('.job-list').empty();
			filterJobs(categorySlug, searchKeyword, currentPage);
		});
		// Search click
		$('#job-search-btn').click(function() {
			let searchKeyword = $('#job-search').val().trim();
			let categorySlug = $('#job-category').val();
			currentPage = 1;
			$('.job-list').empty();
			filterJobs(categorySlug, searchKeyword, currentPage)
		});
		// Search with keyboard
		$('#job-search').keypress(function(event) {
			if (event.which == 13) {
				event.preventDefault();
				currentPage = 1;
				$('.job-list').empty();
				let searchKeyword = $(this).val().trim();
				let categorySlug = $('#job-category').val();
				filterJobs(categorySlug, searchKeyword, currentPage)
			}
		});
		// Hàm gửi yêu cầu AJAX để lọc bài viết theo danh mục
		function filterJobs(categorySlug, searchKeyword = '', currentPage) {
			showLoader('.loader-filter-jobs')
			$.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				type: 'post',
				data: {
					action: 'filter_jobs',
					category: categorySlug,
					search: searchKeyword,
					current_page: currentPage
				},
				success: function(res) {
					// Settime out để nó bị async -> thay dc loading khi change option
					setTimeout(function() {
						hideLoader('.loader-filter-jobs');
					}, 500);
					$('.job-list').append(res);
					animEachItem();
				}
			})
		}
		// Animation cho từng item
		function animEachItem() {
			$('.container_jobs').each(function(index) {
				var item = $(this);
				setTimeout(function() {
					item.addClass('animate');
				}, index * 100); // Thay đổi giá trị 100 để thay đổi tốc độ xuất hiện
			});
		}
		// Button click load more job
		$('#load-more-jobs').click(function() {
			let searchKeyword = $('#job-search').val().trim();
			let categorySlug = $('#job-category').val();
			currentPage++;
			filterJobs(categorySlug, searchKeyword, currentPage)
		});
		filterJobs();
	});
</script>


<style>
	/* 	.col {
		padding: 0 ;
		box-sizing: border-box;
	} */
	.job-filter {
		/* 		margin-bottom: 20px; */
		display: flex;
		flex-wrap: wrap;
		gap: 10px
	}

	.job-filter .job-search {
		width: calc((100% - 55%) - 10px);
		position: relative;
	}

	.job-filter #job-search {
		border-radius: 5px;
		box-shadow: none;
	}

	.job-filter #job-search:focus {
		box-shadow: none;
	}

	.job-filter #job-search-btn {
		position: absolute;
		top: 0;
		right: 20px;
	}

	.job-list .container_jobs {
		opacity: 0;
		transition: opacity 0.5s ease;
	}

	.job-list .container_jobs.animate {
		opacity: 1;
	}

	/* Dropdown	 */
	.job-filter #job-category,
	.job-filter .select2-container {
		width: 55% !important;
		box-shadow: none;
		border-radius: 5px;
		height: 40px !important;
		margin: 0;
	}

	/* 	.select2-container {
		height: 40px;
		margin: 0;
	} */
	.select2-dropdown {
		border-color: #D9D9D9 !important;
	}

	.select2-selection {
		box-shadow: none !important;
		height: 40px !important;
		display: flex !important;
		align-items: center;
		border-color: #D9D9D9 !important;
		margin: 0;
	}

	.container_jobs .meta {
		display: flex;
		gap: 30px;
		align-items: center;
		border-radius: 5px;
		border: 1px solid #D9D9D9;
		padding: 40px;
		flex-wrap: wrap;
	}

	.container_jobs .meta .timer {
		flex: 1;
		text-align: right;
	}

	.container_jobs .meta .field {
		display: flex;
		gap: 10px;
		flex-wrap: wrap;
	}

	.container_jobs .meta .field span {
		padding: 5px 15px;
		border-radius: 50px;
		color: #fff;
		background-color: #83CBC9;
		font-weight: 600;
		display: flex;
		align-items: center;
	}

	.container_jobs .meta .field span:nth-child(1) {
		background-color: #FDE267;
		color: #5C7479;
	}

	#load-more-jobs {
		text-transform: capitalize;
		font-size: small;
		padding: 5px 15px;
		border: 1px solid #5C7479;
		color: #5C7479;
		margin: 0;
	}


	@media screen and (max-width: 992px) {}

	@media screen and (min-width: 850px) {
		.select2-dropdown {
			top: 30px;
		}
	}

	@media screen and (max-width: 768px) {

		.job-filter #job-category,
		.job-filter .job-search,
		.job-filter .select2-container {
			width: 100% !important;
		}

		/* 		.job-filter .job-search {
			width: 100%;
		} */
		.container_jobs .meta {
			padding: 15px;
		}

		.container_jobs .meta .title {
			overflow: hidden;
			display: -webkit-box;
			-webkit-line-clamp: 1;
			-webkit-box-orient: vertical;
		}

		.container_jobs .meta .field span {
			font-size: 12px;
		}

		.container_jobs .meta .field {
			width: 100%;
		}

		.container_jobs .meta .timer {
			font-size: 14px;
		}
	}

	@media screen and (max-width: 486px) {}
</style>





<!-- 			<?php if (link) : ?> -->
		<a href="<?php the_permalink(); ?>" class="read-more-button">Xem thêm</a>
		<!-- 				<a href="<?php echo esc_url($link);?>" class="read-more-button" >Xem thêm</a> -->
		<!-- 			<?php endif; ?> -->