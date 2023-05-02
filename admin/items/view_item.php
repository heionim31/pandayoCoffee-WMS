<?php
    require_once('./../../config.php');
    if(isset($_GET['id']) && $_GET['id'] > 0){
        $id = $_GET['id'];
        $result = pg_query($conn, "SELECT i.*, c.name as category from wh_item_list i inner join wh_category_list c on i.category_id = c.id where i.id = '{$id}'");
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

	th.text-muted {
		width: 30%;
	}
</style>


<div class="container">
  <div class="row">
    <div class="col">
      <table class="table">
        <tbody>
          <tr>
            <th class="text-muted">Name</th>
            <td><?= isset($name) ? $name : "" ?></td>
          </tr>
          <tr>
            <th class="text-muted">Unit</th>
            <td><?= isset($unit) ? $unit : "" ?></td>
          </tr>
          <tr>
            <th class="text-muted">Category</th>
            <td><?= isset($category) ? $category : "" ?></td>
          </tr>
          <tr>
            <th class="text-muted">Item Type</th>
            <td><?= isset($item_type) ? $item_type : "" ?></td>
          </tr>
          <tr>
            <th class="text-muted">Description</th>
            <td><?= isset($description) ? str_replace(["\n\r", "\n", "\r"],"<br>", htmlspecialchars_decode($description)) : '' ?></td>
          </tr>
          <tr>
            <th class="text-muted">Status</th>
            <td>
              <?php if($status == 1): ?>
                <span>Active</span>
              <?php else: ?>
                <span>Inactive</span>
              <?php endif; ?>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>


<hr class="mx-n3">

<div class="text-right pt-1">
	<button class="btn btn-sm btn-flat btn-light bg-gradient-light border" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
</div>