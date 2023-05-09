<?php 
    require_once('../../config.php');
    if(isset($_GET['id']) && $_GET['id'] > 0){
        $qry = pg_query($conn, "SELECT * from wh_stockout_list where id = '{$_GET['id']}' ");
        if(pg_num_rows($qry) > 0){
            foreach(pg_fetch_assoc($qry) as $k => $v){
                $$k=$v;
            }
        }
    }
?>


<?php
    // RETRIEVE DATA FROM (stockout_adjustment.php) AND DISPLAY IN STOCKOUT
    $ingredient_name = isset($_GET['ingredient_name']) ? $_GET['ingredient_name'] : '';
    $request_id = isset($_GET['request_id']) ? $_GET['request_id'] : '';
    $request_by = isset($_GET['request_by']) ? $_GET['request_by'] : '';
    $date_request = isset($_GET['date_request']) ? $_GET['date_request'] : '';
    $quantity = isset($_GET['quantity']) ? $_GET['quantity'] : '';
    $notes = isset($_GET['notes']) ? $_GET['notes'] : '';
    $personnel = isset($_GET['personnel']) ? $_GET['personnel'] : '';
    $personnel_role = isset($_GET['personnel_role']) ? $_GET['personnel_role'] : '';
    $date_prepared = isset($_GET['date_prepared']) ? $_GET['date_prepared'] : '';
    $date_approved = isset($_GET['date_approved']) ? $_GET['date_approved'] : '';

    // get item type from item_list table
    // $item_id = isset($_GET['iid']) ? $_GET['iid'] : '';
    // $item_type_query = "SELECT item_type FROM wh_item_list WHERE id = '$item_id'";
    // $item_type_result = pg_query($conn, $item_type_query);
    // $item_type_row = pg_fetch_assoc($item_type_result);
    // $item_type = $item_type_row['item_type'];

    // check if item is non-perishable
    // if ($item_type != 'Non-Perishable') {
    //     // get total quantity of same items from stockin list table
    //     $total_quantity_query = "SELECT SUM(quantity) as total_quantity FROM wh_stockin_list WHERE item_id = '$item_id'";
    //     $total_quantity_result = pg_query($conn, $total_quantity_query);
    //     $total_quantity_row = pg_fetch_assoc($total_quantity_result);
    //     $total_quantity = $total_quantity_row['total_quantity'];

    //     // get total quantity of same items from stockout list table
    //     $existing_quantity_query = "SELECT SUM(quantity) as existing_quantity FROM wh_stockout_list WHERE item_id = '$item_id'";
    //     $existing_quantity_result = pg_query($conn, $existing_quantity_query);
    //     $existing_quantity_row = pg_fetch_assoc($existing_quantity_result);
    //     $existing_quantity = $existing_quantity_row['existing_quantity'];

    //     // subtract existing quantity from total quantity if item already exists in stockout list
    //     if ($existing_quantity) {
    //         $total_quantity -= $existing_quantity;
    //     }

    //     // check if item is non-perishable
    //     if ($item_type != 'Non-Perishable') {
    //         // get total quantity of expired items from stockin list table
    //         $expired_quantity = 0;
    //         $expired_quantity_query = "SELECT SUM(quantity) as expired_quantity FROM wh_stockin_list WHERE item_id = '$item_id' AND expire_date <= CURRENT_DATE";
    //         $expired_quantity_result = pg_query($conn, $expired_quantity_query);
    //         $expired_quantity_row = pg_fetch_assoc($expired_quantity_result);
    //         if ($expired_quantity_row['expired_quantity']) {
    //         $expired_quantity = $expired_quantity_row['expired_quantity'];
    //         }
            
    //         // check if there are any expired items
    //         $total_quantity = intval($total_quantity);
    //         $expired_quantity = intval($expired_quantity);
    //         if ($total_quantity == $expired_quantity && $total_quantity != 0) {
    //             // all items have expired, show the message
    //             echo "<div class='alert alert-warning'>Sorry, you cannot add a stock-out because all <b>({$expired_quantity})</b> items in stock have already expired. Please restock before performing any stock-out transactions.</div>";
    //             // set maximum quantity allowed to 0
    //             $max_quantity = 0;
    //         } else if ($expired_quantity > 0) {
    //             // some items have expired, show the message about remaining quantity
    //             $remaining_quantity_query = "SELECT SUM(quantity) as stockout_quantity FROM wh_stockout_list WHERE item_id = '$item_id'";
    //             $remaining_quantity_result = pg_query($conn, $remaining_quantity_query);
    //             $remaining_quantity_row = pg_fetch_assoc($remaining_quantity_result);
    //             $remaining_quantity = $total_quantity - $expired_quantity;
    //             echo "<div class='alert alert-warning'>NOTE: You can only add <b>({$remaining_quantity})</b> stock-out items because <b>({$expired_quantity} out of {$total_quantity})</b> have already expired.</div>";
    //             // calculate the maximum quantity allowed
    //             $max_quantity = $total_quantity - $expired_quantity;
    //         }
    //         else {
    //             // no items have expired, set maximum quantity allowed to the total quantity
    //             $max_quantity_query = "SELECT SUM(quantity) as stockout_quantity FROM wh_stockout_list WHERE item_id = '$item_id'";
    //             $max_quantity_result = pg_query($conn, $max_quantity_query);
    //             $max_quantity_row = pg_fetch_assoc($max_quantity_result);
    //             $max_quantity = $total_quantity - intval($max_quantity_row['stockout_quantity']);
    //         }
    //     } 
    // }
?>


<!-- STOCK-OUT FORM -->
<div class="container-fluid">
    <form action="" id="stockout-form">
        <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>">
        <input type="hidden" name="item_id" value="<?= isset($item_id) ? $item_id : (isset($_GET['iid']) ? $_GET['iid'] : '') ?>">
        
        <div class="form-group" hidden>
            <label for="ingredient_name" class="control-label">Item Name</label>
            <input type="text" step="any" name="ingredient_name" id="ingredient_name" class="form-control form-control-sm rounded-0 text-left" value="<?= isset($_GET['ingredient_name']) ? $_GET['ingredient_name'] : '' ?>" readonly>
        </div>
        
        <div class="form-group" hidden>
            <label for="request_id" class="control-label">Request ID</label>
            <input type="text" step="any" name="request_id" id="request_id" class="form-control form-control-sm rounded-0 text-left" value="<?= isset($_GET['request_id']) ? $_GET['request_id'] : '' ?>" readonly>
        </div>

        <div class="form-group" hidden>
            <label for="request_by" class="control-label">Request By</label>
            <input type="text" step="any" name="request_by" id="request_by" class="form-control form-control-sm rounded-0 text-left" value="<?= isset($_GET['request_by']) ? $_GET['request_by'] : '' ?>" readonly>
        </div>

        <div class="form-group" hidden>
            <label for="date_request" class="control-label">Date Requested</label>
            <input type="text" step="any" name="date_request" id="date_request" class="form-control form-control-sm rounded-0 text-left" value="<?= isset($_GET['date_request']) ? $_GET['date_request'] : '' ?>" readonly>
        </div>
        
        <div class="form-group" hidden>
            <label for="quantity" class="control-label">Quantity</label>
            <input type="number" step="any" name="quantity" id="quantity" class="form-control form-control-sm rounded-0" value="<?= isset($_GET['quantity']) ? $_GET['quantity'] : '' ?>" readonly>
            <span id="quantityError" style="color: red; display: none;">Sorry, the quantity entered exceeds the maximum allowed quantity.</span>
        </div> 
       
        <div class="form-group" hidden>
            <label for="notes" class="control-label">Notes</label>
            <input type="text" step="any" name="notes" id="notes" class="form-control form-control-sm rounded-0 text-left" value="<?= isset($_GET['notes']) ? $_GET['notes'] : '' ?>" readonly>
        </div>

        <div class="form-group" hidden>
            <label for="personnel" class="control-label">Personnel</label>
            <input type="text" step="any" name="personnel" id="personnel" class="form-control form-control-sm rounded-0 text-left" value="<?= isset($_GET['personnel']) ? $_GET['personnel'] : '' ?>" readonly>
        </div>

        <div class="form-group" hidden>
            <label for="personnel_role" class="control-label">Personnel Role</label>
            <input type="text" step="any" name="personnel_role" id="personnel_role" class="form-control form-control-sm rounded-0 text-left" value="<?= isset($_GET['personnel_role']) ? $_GET['personnel_role'] : '' ?>" readonly>
        </div>

        <div class="form-group" hidden>
            <label for="date_prepared" class="control-label">Date Prepared</label>
            <input type="date" name="date_prepared" id="date_prepared" class="form-control form-control-sm rounded-0" value="<?= isset($date_prepared) ? $date_prepared : '' ?>" readonly>
        </div>

        <div class="form-group" hidden>
            <label for="date_approved" class="control-label">Date Approved</label>
            <input type="date" name="date_approved" id="date_approved" class="form-control form-control-sm rounded-0" value="<?= isset($date_approved) ? $date_approved : '' ?>" readonly>
        </div>

        <div class="form-group">
            <label for="remarks" class="control-label">Remarks</label>
            <textarea type="3" name="remarks" id="remarks" class="form-control form-control-sm rounded-0" placeholder="Please use this space to provide any additional comments or feedback regarding the request." required><?= isset($remarks) ? ($remarks) : '' ?></textarea>
        </div>
    </form>
</div>


<script>
    function checkQuantity() {
        var quantityInput = document.getElementById("quantity");
        var quantityError = document.getElementById("quantityError");
        var expiredQuantity = <?= $expired_quantity ?>;
        var totalQuantity = <?= $total_quantity ?>;
        
        if (totalQuantity == expiredQuantity) {
            // all items have expired, show error message and disable submit button
            quantityError.style.display = "inline";
            quantityError.innerText = "Stock-out transactions cannot be performed as all items in stock have already expired.";
            document.getElementById("submitBtn").disabled = true;
        } else if (quantityInput.value <= 0) {
            // quantity entered is less than or equal to zero, show error message and disable submit button
            quantityError.style.display = "inline";
            quantityError.innerText = "Sorry, the quantity entered must be greater than zero.";
            document.getElementById("submitBtn").disabled = true;
        } else if (quantityInput.value > <?= $max_quantity ?> ) {
            // quantity entered exceeds the maximum allowed quantity, show error message and disable submit button
            quantityError.style.display = "inline";
            quantityError.innerText = "Sorry, the quantity entered exceeds the maximum allowed quantity.";
            document.getElementById("submitBtn").disabled = true;
        } else {
            // quantity entered is valid, hide error message and enable submit button
            quantityError.style.display = "none";
            document.getElementById("submitBtn").disabled = false;
        }
    }
</script>


<script>
    $(function(){
        $('#stockout-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
             if(_this[0].checkValidity() == false){
                _this[0].reportValidity() 
                return false
             }
			start_loader();
            
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_stockout",
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
                        location.href = './?page=sales_request';
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