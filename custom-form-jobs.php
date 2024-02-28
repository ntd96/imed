<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/sweetalert2.min.css">

<form id="job-form" class="jobs-loader" method='post' data-action="submit_job_form" enctype="multipart/form-data">
	<input type='email' placeholder="Email" name="email" required>

	<select class="form_jobs" id='form_jobs' name="form_jobs" required>
		<?php
		$categories = get_categories(array(
			'taxonomy' => 'jobs-category',
			'hide_empty' => false,
		));
		foreach ($categories as $category) : ?>
			<option value="<?php echo $category->name; ?>"> <?php echo $category->name; ?> </option>
		<?php endforeach;
		?>
	</select>
	<div class="meta-upload">
		<input type="file" id="file" name='file' accept=".doc, .docx, .pdf" required>
		<label for='file' id="file-label"> <i class="fa-solid fa-arrow-up-from-bracket"></i> <span class="file-label">Upload CV</span> </label>
		<button type="submit">
			Nộp
		</button>
	</div>
	<div class="loader-form-jobs loader" style="display: none"></div>
</form>

<script>
	$(document).ready(function() {
		// Dropdown js
		$('.form_jobs').select2();
		// Submit form
		$('#job-form').submit(function(e) {

			e.preventDefault();

			showLoader('.loader-form-jobs');
			let formData = new FormData(this);
			formData.append('action', 'submit_job_form');
			$.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				type: 'post',
				data: formData,
				processData: false,
				contentType: false,
				success: function(res) {
					hideLoader('.loader-form-jobs');
					$('#job-form')[0].reset(); // reset lai form
					Swal.fire({
						icon: 'success',
						title: 'Thành công!',
						text: 'Đăng kí thành công, chúng tôi sẽ liên hệ bạn sớm nhất',
					});
					$('.file-label').text('Upload CV');
				},
				error: function(xhr, status, error) {
					// Ẩn spin loader khi yêu cầu thất bại
					hideLoader('.loader-form-jobs');
				}
			})
		});
		// Show content upload file path
		$('#file').change(function() {
			let fileName = $(this).val().split('\\').pop();
			$('.file-label').text(fileName);
		});

	});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
	#job-form input[type="email"],
	#job-form input[type="email"]:focus,
	.select2-selection {
		background-color: transparent !important;
		box-shadow: none;
		color: #CECECE;
		border-radius: 5px;
		margin-bottom: 20px;
		padding: 0 20px;
	}

	::-webkit-input-placeholder,
	.select2-selection__rendered {
		color: #CECECE !important;
		padding: 0;
	}

	input[type="file"] {
		display: none;
	}

	#job-form button[type="submit"] {
		margin: 0;
		background-color: #FDE267;
		color: #5C7479;
		border-radius: 5px;
		text-transform: uppercase;
		width: 90px;
	}

	#job-form .select2-container {
		margin-bottom: 20px;
	}

	#job-form .meta-upload {
		display: flex;
		flex-wrap: wrap;
		gap: 10px;
	}

	#job-form #file-label {
		margin: 0;
		width: calc(100% - 100px);
		border: 1px solid #fff;
		border-radius: 5px;
		height: 40px;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 5px;
		color: #CECECE;
		font-weight: 400;
		cursor: pointer;
	}

	@media screen and (max-width: 992px) {}
</style>