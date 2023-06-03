<?php if($_settings->chk_flashdata('success')): ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
	</script>
<?php endif;?>


<style>
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
	img#cimg2{
		height: 50vh;
		width: 100%;
		object-fit: contain;
	}
</style>


<div class="col-lg-12">
	<div class="card card-outline rounded-5">
		<div class="card-header">
			<h5 class="card-title font-weight-bold">SYSTEM INFORMATION</h5>
		</div>
		<div class="card-body">
			<form action="" id="system-frm">
				<div id="msg" class="form-group"></div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="name" class="control-label">System Name</label>
							<input type="text" class="form-control" name="name" id="name" value="<?php echo $_settings->info('name') ?>">
						</div>
						<div class="form-group mt-3">
							<label for="short_name" class="control-label">System Short Name</label>
							<input type="text" class="form-control" name="short_name" id="short_name" value="<?php echo  $_settings->info('short_name') ?>">
						</div>
						<div class="form-group mt-3">
							<label for="" class="control-label">System Logo</label>
							<div class="custom-file">
								<input type="file" class="custom-file-input rounded-circle" id="customFile1" name="img" onchange="displayImg(this,$(this))">
								<label class="custom-file-label" for="customFile1">Choose file</label>
							</div>
						</div>
						<div class="form-group d-flex justify-content-center mt-5">
							<img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Website Cover</label>
							<div class="custom-file">
								<input type="file" class="custom-file-input rounded-circle" id="customFile2" name="cover" onchange="displayImg2(this,$(this))">
								<label class="custom-file-label" for="customFile2">Choose file</label>
							</div>
						</div>
						<div class="form-group d-flex justify-content-center">
							<img src="<?php echo validate_image($_settings->info('cover')) ?>" alt="" id="cimg2" class="img-fluid img-thumbnail bg-gradient-dark border-dark">
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="card-footer">
			<div class="row">
				<div class="col-md-12 d-flex justify-content-center">
					<button class="btn btn-primary" form="system-frm">Update Setting</button>
				</div>
			</div>
		</div>

	</div>
</div>


<script>
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        	_this.siblings('.custom-file-label').html(input.files[0].name)
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}

	function displayImg2(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	_this.siblings('.custom-file-label').html(input.files[0].name)
	        	$('#cimg2').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}

	function displayImg3(input,_this) {
		var fnames = [];
		Object.keys(input.files).map(function(k){
			fnames.push(input.files[k].name)

		})
		_this.siblings('.custom-file-label').html(fnames.join(", "))
	}
	
	function delete_img($path){
        start_loader()
        
        $.ajax({
            url: _base_url_+'classes/Master.php?f=delete_img',
            data:{path:$path},
            method:'POST',
            dataType:"json",
            error:err=>{
                console.log(err)
                alert_toast("An error occured while deleting an Image","error");
                end_loader()
            },
            success:function(resp){
                $('.modal').modal('hide')
                if(typeof resp =='object' && resp.status == 'success'){
                    $('[data-path="'+$path+'"]').closest('.img-item').hide('slow',function(){
                        $('[data-path="'+$path+'"]').closest('.img-item').remove()
                    })
                    alert_toast("Image Successfully Deleted","success");
                }else{
                    console.log(resp)
                    alert_toast("An error occured while deleting an Image","error");
                }
                end_loader()
            }
        })
    }
	
	$(document).ready(function(){
		$('.rem_img').click(function(){
            _conf("Are sure to delete this image permanently?",'delete_img',["'"+$(this).attr('data-path')+"'"])
        })
		 $('.summernote').summernote({
			height: 200,
			toolbar: [
				[ 'style', [ 'style' ] ],
				[ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
				[ 'fontname', [ 'fontname' ] ],
				[ 'fontsize', [ 'fontsize' ] ],
				[ 'color', [ 'color' ] ],
				[ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
				[ 'table', [ 'table' ] ],
				[ 'view', [ 'undo', 'redo', 'fullscreen', 'help' ] ]
			]
		})
	})
</script>