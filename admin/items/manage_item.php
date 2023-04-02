<?php
	require_once('./../../config.php');
	if(isset($_GET['id']) && $_GET['id'] > 0){
		$qry = pg_query($conn, "SELECT * FROM item_list where id = '{$_GET['id']}' and delete_flag = 0 ");
		if(pg_num_rows($qry) > 0){
			foreach(pg_fetch_assoc($qry) as $k => $v){
				$$k=$v;
			}
		}
	}
?>


<div class="container-fluid">
	<form action="" id="item-form">
		<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">

		<div class="form-group">
			<label for="name" class="control-label">Name</label>
			<input type="text" name="name" id="name" class="form-control form-control-sm rounded-0" value="<?php echo isset($name) ? $name : ''; ?>"  required/>
		</div>

		<div class="form-group">
			<label for="category_id" class="control-label">Category</label>
			<select name="category_id" id="category_id" class="form-control form-control-sm rounded-0" required="required">
				<option value="" <?= isset($category_id) ? 'selected' : '' ?>></option>
				<?php 
				$items = pg_query($conn, "SELECT * FROM category_list where delete_flag = 0 and status = 1 ");
				while($row= pg_fetch_assoc($items)):
				?>
				<option value="<?= $row['id'] ?>" <?= isset($category_id) && $category_id == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
				<?php endwhile; ?>
			</select>
		</div>

		<div class="form-group">
			<label for="type_id" class="control-label">Item Type</label>
			<select name="item_type" id="type_id" class="form-control form-control-sm rounded-0" required="required">
				<option value="" <?= isset($item_type) ? 'selected' : '' ?>></option>
				<option value="Perishable" <?php echo isset($item_type) && $item_type == 'Perishable' ? 'selected' : ''; ?>>Perishable</option>
				<option value="Non-Perishable" <?php echo isset($item_type) && $item_type == 'Non-Perishable' ? 'selected' : ''; ?>>Non-Perishable</option>
			</select>
		</div>

		<div class="form-group">
			<label for="unit_id" class="control-label">Unit</label>
			<select name="unit" id="unit_id" class="form-control form-control-sm rounded-0" required="required">
				<option value="" <?= isset($unit_id) ? 'selected' : '' ?>></option>
				<?php 
				$items = pg_query($conn, "SELECT * FROM unit_list where delete_flag = 0 and status = 1 ");
				while($row= pg_fetch_assoc($items)):
				?>
				<option value="<?= $row['abbreviation'] ?>" <?= isset($unit_id) && $unit_id == $row['id'] ? 'selected' : '' ?>><?= $row['abbreviation'] ?></option>
				<?php endwhile; ?>
			</select>
		</div>
		
		<div class="form-group">
			<label for="description" class="control-label">Description</label>
			<textarea rows="3" name="description" id="description" class="form-control form-control-sm rounded-0" required><?php echo isset($description) ? $description : ''; ?></textarea>
		</div>

		<div class="form-group">
			<label for="status" class="control-label">Status</label>
			<select name="status" id="status" class="form-control form-control-sm rounded-0" required="required">
				<option value="1" <?= isset($status) && $status == 1 ? 'selected' : '' ?>>Active</option>
				<option value="0" <?= isset($status) && $status == 0 ? 'selected' : '' ?>>Inactive</option>
			</select>
		</div>
	</form>
</div>


<script>
	$(document).ready(function(){
		$('#uni_modal').on('shown.bs.modal', function(){
			$('#category_id').select2({
				placeholder:"Select Category Here",
				width:'100%',
				containerCssClass:'form-control form-control-sm rounded-0',
				dropdownParent:$('#uni_modal')
			})
		})

		$('#uni_modal').on('shown.bs.modal', function(){
			$('#type_id').select2({
				placeholder:"Select Item Type Here",
				width:'100%',
				containerCssClass:'form-control form-control-sm rounded-0',
				dropdownParent:$('#uni_modal')
			})
		})

		$('#uni_modal').on('shown.bs.modal', function(){
			$('#unit_id').select2({
				placeholder:"Select Unit Here",
				width:'100%',
				containerCssClass:'form-control form-control-sm rounded-0',
				dropdownParent:$('#uni_modal')
			})
		})

		$('#item-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_item",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						// location.reload()
						alert_toast(resp.msg, 'success')
						// Delay the page reload by 2 seconds and redirect to item page
                        setTimeout(function(){
                          window.location.href = './?page=items';
                        }, 1000);
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            $("html, body").scrollTop(0);
                            end_loader()
                    }else{
						alert_toast("An error occured",'error');
						end_loader();
                        console.log(resp)
					}
				}
			})
		})

	})
</script>