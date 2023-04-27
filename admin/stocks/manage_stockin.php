<?php 
    require_once('../../config.php');
    if(isset($_GET['id']) && $_GET['id'] > 0){
        $qry = pg_query($conn, "SELECT * FROM wh_stockin_list where id = '{$_GET['id']}' ");
        if(pg_num_rows($qry) > 0){
            foreach(pg_fetch_assoc($qry) as $k => $v){
                $$k=$v;
            }
        }
    }

    $item_type = "";
    $item_id = isset($item_id) ? $item_id : (isset($_GET['iid']) ? $_GET['iid'] : '');
    $quantity = isset($_GET['quantity']) ? $_GET['quantity'] : '';
    $manufactured_date = isset($_GET['manufactured_date']) ? $_GET['manufactured_date'] : '';
    $expired_date = isset($_GET['expired_date']) ? $_GET['expired_date'] : '';
    $request_id = isset($_GET['request_id']) ? $_GET['request_id'] : '';
    $supplier = isset($_GET['supplier']) ? $_GET['supplier'] : '';
    $physical_count = isset($_GET['physical_count']) ? $_GET['physical_count'] : '';
    $date_approved = isset($_GET['date_approved']) ? $_GET['date_approved'] : '';
    $date_received = isset($_GET['date_received']) ? $_GET['date_received'] : '';
    $physical_count_date = isset($_GET['physical_count_date']) ? $_GET['physical_count_date'] : '';
    $personnel = isset($_GET['personnel']) ? $_GET['personnel'] : '';
    $personnel_role = isset($_GET['personnel_role']) ? $_GET['personnel_role'] : '';

    if(!empty($item_id)) {
        $item_qry = pg_query($conn, "SELECT item_type FROM wh_item_list WHERE id = '$item_id'");
        if(pg_num_rows($item_qry) > 0) {
            $item_type = pg_fetch_assoc($item_qry)['item_type'];
        }
    }
?>


<div class="container-fluid">
    <form action="" id="stockin-form">

        <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>">
        <input type="hidden" name="item_id" value="<?= $item_id ?>">

        <div class="form-group" hidden>
            <label for="request_id" class="control-label">Request ID</label>
            <input type="text" step="any" name="request_id" id="request_id" class="form-control form-control-sm rounded-0 text-left" value="<?= isset($_GET['request_id']) ? $_GET['request_id'] : '' ?>" readonly>
        </div>

        <div class="form-group" hidden>
            <label for="quantity" class="control-label">Requested Quantity</label>
            <input type="number" step="any" name="quantity" id="quantity" class="form-control form-control-sm rounded-0 text-left" value="<?= isset($_GET['quantity']) ? $_GET['quantity'] : '' ?>" readonly>
        </div>

        <div class="form-group" hidden>
            <label for="physical_count" class="control-label">Physical Count</label>
            <input type="text" step="any" name="physical_count" id="physical_count" class="form-control form-control-sm rounded-0 text-left" value="<?= isset($_GET['physical_count']) ? $_GET['physical_count'] : '' ?>" readonly>
        </div>

        <div class="form-group" hidden>
            <label for="physical_count_date" class="control-label">Date Physical Count</label>
            <input type="text" name="physical_count_date" id="physical_count_date" class="form-control form-control-sm rounded-0 text-left" value="<?= isset($_GET['physical_count_date']) ? $_GET['physical_count_date'] : '' ?>" readonly>
        </div>

        
        <div class="form-group" hidden>
            <label for="date_approved" class="control-label">Date Approved</label>
            <input type="date" name="date_approved" id="date_approved" class="form-control form-control-sm rounded-0" value="<?= isset($_GET['date_approved']) ? $_GET['date_approved'] : '' ?>" readonly>
        </div>

        <div class="form-group" hidden>
            <label for="date_received" class="control-label">Date Received</label>
            <input type="date" name="date_received" id="date_received" class="form-control form-control-sm rounded-0" value="<?= isset($_GET['date_received']) ? $_GET['date_received'] : '' ?>" readonly>
        </div>


        <div class="form-group" hidden>
            <label for="date" class="control-label">Manufactured Date</label>
            <input type="date" name="date" id="date" class="form-control form-control-sm rounded-0" value="<?= isset($_GET['manufactured_date']) ? $_GET['manufactured_date'] : '' ?>" readonly>
        </div>

        <?php if ($item_type != "Non-Perishable"): ?>
            <div class="form-group" hidden>
                <label for="expire_date" class="control-label">Expiration Date</label>
                <input type="date" name="expire_date" id="expire_date" class="form-control form-control-sm rounded-0 " value="<?= isset($_GET['expired_date']) ? $_GET['expired_date'] : '' ?>" readonly>
            </div>
        <?php else: ?>
            <input type="hidden" name="expire_date" value="">
        <?php endif; ?>


        <div class="form-group" hidden>
            <label for="personnel" class="control-label">Personnel</label>
            <input type="text" name="personnel" id="personnel" class="form-control form-control-sm rounded-0" value="<?= isset($_GET['personnel']) ? $_GET['personnel'] : '' ?>" readonly>
        </div>
    
        <div class="form-group" hidden>
            <label for="personnel_role" class="control-label">Personnel Role</label>
            <input type="text" name="personnel_role" id="personnel_role" class="form-control form-control-sm rounded-0" value="<?= isset($_GET['personnel_role']) ? $_GET['personnel_role'] : '' ?>" readonly>
        </div>
            

        <div class="form-group" hidden>
            <label for="supplier" class="control-label">Supplier</label>
            <input type="text" step="any" name="supplier" id="supplier" class="form-control form-control-sm rounded-0 text-left" value="<?= isset($_GET['supplier']) ? $_GET['supplier'] : '' ?>" readonly>
        </div>

        <div class="form-group">
            <label for="remarks" class="control-label">Add Remarks</label>
            <textarea type="3" name="remarks" id="remarks" class="form-control form-control-sm rounded-0" placeholder="Add Remarks (if any)"><?= isset($remarks) ? ($remarks) : '' ?></textarea>
        </div>
    </form>
</div>


<script>
    $(function(){
        $('#stockin-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
             if(_this[0].checkValidity() == false){
                _this[0].reportValidity() 
                return false
             }
			start_loader();
            
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_stockin",
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
						window.location.href = './?page=purchasing_request';
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            $("html, body, .modal").scrollTop(0);
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