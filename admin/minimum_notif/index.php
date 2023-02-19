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
	<div class="card card-outline rounded-0 card-dark">
		<div class="card-header">
			<h5 class="card-title">Stock Notification</h5>
		</div>

		<div class="card-body">
			<form action="" id="system-frm">
            
				<div id="msg" class="form-group"></div>

				<div class="form-group">
					<label for="max_stock" class="control-label">Max Stock</label>
					<input type="text" class="form-control form-control-sm" name="max_stock" id="max_stock" placeholder="Enter stock range"><!--value php echo $_settings->info('name')  -->
				</div>

				<div class="form-group">
					<label for="minimum_stock" class="control-label">Minimum Stock</label>
					 <input type="text" class="form-control form-control-sm" name="_stock" id="minimum_stock" placeholder="Enter stock range"> <!--value="php echo $_settings->info('name') -->
				</div>

				<!-- <div class="form-group">
					<label for="low_stock" class="control-label">Low Stock</label>
					<input type="text" class="form-control form-control-sm" name="low_stock" id="low_stock" placeholder="Enter stock range">
				</div>

				<div class="form-group">
					<label for="out_of_stock" class="control-label">Out of Stock</label>
					<input type="text" class="form-control form-control-sm" name="out_of_stock" id="out_of_stock" placeholder="Enter stock range">
				</div> -->
          
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
