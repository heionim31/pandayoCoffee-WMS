<?php 
	if(isset($_GET['id'])){
		$id = pg_escape_string($_GET['id']);
		$query = "SELECT * FROM users WHERE id = $1";
		$stmt = pg_prepare($conn, "user_query", $query);
		$result = pg_execute($conn, "user_query", array($id));
		
		if($result){
			$meta = pg_fetch_assoc($result);
		}
	}
?>


<?php if($_settings->chk_flashdata('success')): ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
	</script>
<?php endif;?>


<div class="card card-outline rounded-0 card-dark">
	<div class="card-header">
		<h3 class="card-title">View Account Information</h3>
		<div class="card-tools">
			<a href="./?page=user/list" class="btn btn-flat btn-success"><span class="fas fa-arrow-left"></span> Back</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<div id="msg"></div>
			<form action="" id="manage-user">	
				<input type="hidden" name="id" value="<?= isset($meta['id']) ? $meta['id'] : '' ?>">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="name">Full Name</label>
							<input type="text" name="fullname" id="fullname" class="form-control" value="<?php echo isset($meta['fullname']) ? $meta['fullname']: '' ?>" readonly>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="username">Username</label>
							<input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>" readonly  autocomplete="off">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="birthdate">Birthdate</label>
							<input type="date" name="birthdate" id="birthdate" class="form-control" value="<?php echo isset($meta['birthdate']) ? $meta['birthdate']: '' ?>" readonly  autocomplete="off">
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="address">Address</label>
							<input type="text" name="address" id="address" class="form-control" value="<?php echo isset($meta['address']) ? $meta['address']: '' ?>" readonly  autocomplete="off">
						</div>
						<div class="form-group">
							<label for="email">Email</label>
							<input type="text" name="email" id="email" class="form-control" value="<?php echo isset($meta['email']) ? $meta['email']: '' ?>" readonly  autocomplete="off">
						</div>
						<div class="form-group">
							<label for="contact">Contact</label>
							<input type="text" name="contact" id="contact" class="form-control" value="<?php echo isset($meta['contact']) ? $meta['contact']: '' ?>" readonly  autocomplete="off">
						</div>
						
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="role">Role</label>
							<input type="text" name="role" id="role" class="form-control" value="<?php echo isset($meta['role']) ? $meta['role']: '' ?>" readonly  autocomplete="off">
						</div>
						<div class="form-group d-flex justify-content-center mt-5">
							<img src="<?php echo isset($meta['imgurl']) ? $meta['imgurl'] : validate_image('') ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
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
					location.href='./?page=user/list'
				}else{
					$('#msg').html('<div class="alert alert-danger">Username already exist</div>')
					end_loader()
				}
			}
		})
	})
</script>