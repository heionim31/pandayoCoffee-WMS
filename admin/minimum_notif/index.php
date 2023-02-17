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
	<div class="card card-outline rounded-0 card-teal">
		
		<div class="card-header">
			<h5 class="card-title">Stock Notification</h5>
		</div>

		<div class="card-body">
			<form action="" id="system-frm">
            
				<div id="msg" class="form-group"></div>

				<div class="form-group">
					<label for="low_stock" class="control-label">Low Stock</label>
					<input type="text" class="form-control form-control-sm" name="low_stock" id="low_stock" value="<?php echo $_settings->info('name') ?>">
				</div>

				<div class="form-group">
					<label for="out_of_stock" class="control-label">Out of Stock</label>
					<input type="text" class="form-control form-control-sm" name="out_of_stock" id="out_of_stock" value="0" disabled>
				</div>
          
                <div class="form-group">
					<label for="date_updated" class="control-label">Date Updated</label>
					<input type="date" class="form-control form-control-sm" name="date_updated" id="date_updated" value="<?php echo date("Y-m-d H:i",strtotime($row['date_updated'])) ?>" disabled>
				</div>
           
			</form>
		</div>

		<div class="card-footer">
			<div class="col-md-12">
				<div class="row">
					<button class="btn btn-sm btn-primary">Set Notification</button>
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