<?php
    require_once('./../../config.php');
    if(isset($_GET['id']) && $_GET['id'] > 0){
        $id = $_GET['id'];
        $result = pg_query($conn, "SELECT i.*, c.name as category from item_list i inner join category_list c on i.category_id = c.id where i.id = '{$id}' and i.delete_flag = 0");
        if(pg_num_rows($result) > 0){
            $row = pg_fetch_assoc($result);
            foreach($row as $k => $v){
                $$k=$v;
            }
        }else{
            echo '<script>alert("item ID is not valid."); location.replace("./?page=items")</script>';
        }
    }else{
        echo '<script>alert("item ID is Required."); location.replace("./?page=items")</script>';
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
		<dt class="text-muted">Category</dt>
		<dd class="pl-4"><?= isset($category) ? $category : "" ?></dd>	
		<dt class="text-muted">Item Type</dt>
		<dd class="pl-4"><?= isset($item_type) ? $item_type : "" ?></dd>
		<dt class="text-muted">Unit</dt>
		<dd class="pl-4"><?= isset($unit) ? $unit : "" ?></dd>
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