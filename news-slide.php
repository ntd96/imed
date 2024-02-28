<div id="posts-container">
	
</div>

<div id="pagination-container" class="pagination-container">
	
</div>

<script>

jQuery(document).ready(function($) {
    let postsContainer = $('#posts-container');
    let paginationContainer = $('#pagination-container');
    let urlajax = '<?php echo admin_url('admin-ajax.php'); ?>'
    function loadNewsPosts(page) {
        $.ajax({
            url: urlajax,
            type: 'post',
            data: {
                action: 'custom_post_ajax',
                category_id: 26,
                page: page
            },
            success: function(res) {
                postsContainer.html(res.posts_html);
				initPagination(res.total_pages, page)
            },
        });
    }
	function initPagination(total,page) {
		let pageNumbers = [];
		for (let i = 1; i <= total; i++) {
				pageNumbers.push(i);
		}
		paginationContainer.pagination({
			dataSource: pageNumbers,
			pageNumber: page,
			pageRange: 1,
			prevText: 'prev',
			nextText: 'next',
			pageSize: 3,
			callback: function(data, pagination) {
				var newPage = pagination.pageNumber;
				if (newPage !== page) {
					loadNewsPosts(newPage);
				}
			}
		});
	}
    loadNewsPosts(1);
});

</script>

<style>
	.paginationjs {
		justify-content: center;
	}
	.paginationjs .paginationjs-pages li:last-child,.paginationjs .paginationjs-pages li {
		border: none;
	}
</style>