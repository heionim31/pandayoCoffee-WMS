<?php
	require_once('./../../config.php');
	if(isset($_GET['id']) && $_GET['id'] > 0){
		$result = pg_query_params($conn, 'SELECT * from wh_category_list where id = $1 and delete_flag = 0', array($_GET['id']));
		$row = pg_fetch_assoc($result);
		if($row){
			foreach($row as $k => $v){
				$$k=$v;
			}
		}else{
			echo '<script>alert("category ID is not valid."); location.replace("./?page=categoryes")</script>';
		}
	}else{
		echo '<script>alert("category ID is Required."); location.replace("./?page=categoryes")</script>';
	}
?>


<style>
	#uni_modal .modal-footer{
		display:none;
	}
</style>


<div class="container-fluid">
	<dl>
		<dt class="text-muted">Name</dt>
		<dd class="pl-4"><?= isset($name) ? $name : "" ?></dd>
		<dt class="text-muted">Description</dt>
		<dd class="pl-4"><?= isset($description) ? str_replace(["\n\r", "\n", "\r"],"<br>", htmlspecialchars_decode($description)) : '' ?></dd>
		<dt class="text-muted">Status</dt>
		<dd class="pl-4">
			<?php if($status == 1): ?>
				<span class="badge badge-success px-3 rounded-pill">Active</span>
			<?php else: ?>
				<span class="badge badge-danger px-3 rounded-pill">Inactive</span>
			<?php endif; ?>
		</dd>
	</dl>
</div>

<hr class="mx-n3">

<div class="text-right pt-1">
	<button class="btn btn-sm btn-flat btn-light bg-gradient-light border" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
</div>