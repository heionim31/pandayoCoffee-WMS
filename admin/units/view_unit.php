<?php
    require_once('./../../config.php');
    if(isset($_GET['id']) && $_GET['id'] > 0){
        $qry = pg_query($conn, "SELECT * from wh_unit_list where id = '{$_GET['id']}'");
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
            <th class="text-muted">Abbreviation</th>
            <td><?= isset($abbreviation) ? $abbreviation : "" ?></td>
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