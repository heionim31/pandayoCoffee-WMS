<?php
    require_once('./../../config.php');
    if(isset($_GET['id']) && $_GET['id'] > 0){
        $qry = pg_query($conn, "SELECT * from unit_list where id = '{$_GET['id']}' and delete_flag = 0 ");
        if(pg_num_rows($qry) > 0){
            $result = pg_fetch_assoc($qry);
            extract($result);
        }else{
            echo '<script>alert("unit ID is not valid."); location.replace("./?page=units")</script>';
        }
    }else{
        echo '<script>alert("unit ID is Required."); location.replace("./?page=units")</script>';
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
		<dt class="text-muted">Abbreviation</dt>
		<dd class="pl-4"><?= isset($abbreviation) ? $abbreviation : "" ?></dd>
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