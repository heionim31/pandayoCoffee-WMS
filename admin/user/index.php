<?php 
	$user = pg_query($conn, "SELECT * FROM users where id ='".$_settings->userdata('id')."'");
	$meta = pg_fetch_assoc($user);
?>

<?php if($_settings->chk_flashdata('success')): ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
	</script>
<?php endif;?>


<div class="card card-outline rounded-0 card-dark">
	<div class="card-body">
		<div class="container-fluid">
			<div id="msg"></div>
			<form action="" id="manage-user">	
				<input type="hidden" name="id" value="<?php echo $_settings->userdata('id') ?>">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="name">Full Name</label>
							<input type="text" name="fullname" id="fullname" class="form-control" value="<?php echo isset($meta['fullname']) ? $meta['fullname']: '' ?>" required>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="username">Username</label>
							<input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>" required  autocomplete="off">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="birthdate">Birthdate</label>
							<input type="date" name="birthdate" id="birthdate" class="form-control" value="<?php echo isset($meta['birthdate']) ? $meta['birthdate']: '' ?>" required  autocomplete="off">
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="address">Address</label>
							<input type="text" name="address" id="address" class="form-control" value="<?php echo isset($meta['address']) ? $meta['address']: '' ?>" required  autocomplete="off">
						</div>
						<div class="form-group">
							<label for="email">Email</label>
							<input type="text" name="email" id="email" class="form-control" value="<?php echo isset($meta['email']) ? $meta['email']: '' ?>" required  autocomplete="off">
						</div>
						<div class="form-group">
							<label for="contact">Contact</label>
							<input type="text" name="contact" id="contact" class="form-control" value="<?php echo isset($meta['contact']) ? $meta['contact']: '' ?>" required  autocomplete="off">
						</div>
						<div class="form-group">
							<label for="password">Password</label>
							<input type="password" name="password" id="password" class="form-control" value="" autocomplete="off">
							<small><i>Leave this blank if you dont want to change the password.</i></small>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Avatar</label>
							<div class="custom-file">
								<input type="file" class="custom-file-input rounded-circle" id="customFile" name="img" onchange="displayImg(this,$(this))" accept="image/png, image/jpeg">
								<label class="custom-file-label" for="customFile">Choose file</label>
							</div>
						</div>
						<div class="form-group d-flex justify-content-center">
							<img src="<?php echo validate_image(isset($meta['avatar']) ? $meta['avatar'] :'') ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
						</div>
						<div class="form-group d-flex justify-content-center pt-5">
							<button class="btn btn-sm btn-primary" form="manage-user">Update</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>


<style>
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>


<script>
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }else{
			$('#cimg').attr('src', "<?php echo validate_image(isset($meta['avatar']) ? $meta['avatar'] :'') ?>");
		}
	}
	
	$('#manage-user').submit(function(e){
		e.preventDefault();
		start_loader()
		$.ajax({
			url:_base_url_+'classes/Users.php?f=save',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp ==1){
					location.reload()
				}else{
					$('#msg').html('<div class="alert alert-danger">Username already exist</div>')
					end_loader()
				}
			}
		})
	})
</script>