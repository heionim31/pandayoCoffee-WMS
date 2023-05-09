<?php
    if(isset($_GET['id']) && $_GET['id'] > 0){
        $qry = pg_query($conn, "SELECT i.*, c.name as category, (COALESCE((SELECT SUM(quantity) FROM wh_stockin_list where item_id = i.id),0) - COALESCE((SELECT SUM(quantity) FROM wh_stockout_list where item_id = i.id),0)) as available from wh_item_list i inner join wh_category_list c on i.category_id = c.id where i.id = '{$_GET['id']}' and i.delete_flag = 0 ");
        if(pg_num_rows($qry) > 0){
            $result = pg_fetch_assoc($qry);
            extract($result);
        }else{
            echo '<script>alert("item ID is not valid."); location.replace("./?page=items")</script>';
        }
    }else{
        echo '<script>alert("item ID is Required."); location.replace("./?page=items")</script>';
    }
?>
     
     
<div class="row mt-3 justify-content-center">
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">

        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline rounded-5">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold mt-2">REQUEST ADJUSTMENT (SALES)</h3>
                        <div class="card-tools">
                            <a href="./?page=sales_request" class="btn btn-flat btn-success">
                                Go Back <span class="fas fa-arrow-right"></span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="">
                            <div class="container-fluid">
                                <?php 
                                    $item_id = $_GET['id'];
                                    $sql = "SELECT * FROM ingredient_request WHERE item_id = '$item_id'";
                                    $result = pg_query($sql);
                                    $row = pg_fetch_assoc($result);
                                ?>
                                <div class="row">
                                    <div class="col-md-6 text-center mb-3">
                                        <h3>Requested Items</h3>
                                    </div>
                                    <div class="col-md-2">
                                        
                                    </div>
                                    <div class="col-md-4 text-center mb-3">
                                        <h3>Personnel</h3>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="ingredient_name">Item Name</label>
                                            <input type="text" class="form-control" id="ingredient_name" name="ingredient_name" value="<?php echo $row['ingredient_name']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="request_id">Request ID</label>
                                            <input type="text" class="form-control" id="request_id" name="request_id" value="<?php echo $row['request_id']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="personnel">Personnel</label>
                                            <input type="text" class="form-control" id="personnel" name="personnel" value="<?php echo ucwords($_settings->userdata('fullname')) ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="request_by">Request By</label>
                                            <input type="text" class="form-control" id="request_by" name="request_by" value="<?php echo $row['request_by']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="date_request">Date Request</label>
                                            <input type="text" class="form-control" id="date_request" name="date_request" value="<?php echo $row['date_request']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="personnel_role">Personnel Role</label>
                                            <input type="text" class="form-control" id="personnel_role" name="personnel_role" value="<?php echo ucwords($_settings->userdata('role')) ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="quantity" class="control-label">Quantity</label>
                                            <input type="number" step="any" name="quantity" id="quantity" class="form-control" value="<?= isset($quantity) ? format_num($quantity) : '' ?>"  max="<?= $max_quantity ?>" required oninput="checkQuantity()">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="wh_quantity">Warehouse Quantity</label>
                                            <input type="text" class="form-control" id="wh_quantity" name="wh_quantity" value="<?php echo intval($available); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="unit">Unit</label>
                                            <input type="text" class="form-control" id="unit" name="unit" value="<?php echo $row['unit']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="date_prepared">Date Prepared</label>
                                            <input type="text" class="form-control" id="date_prepared" name="date_prepared" value="<?php echo $row['date_prepared']; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span id="quantityError" style="color: red; display: none;">Sorry, the quantity entered exceeds the maximum allowed quantity.</span>
                                    </div>
                                    <div class="col-md-6"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="expiration_alert">Expiration Alert</label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="notes">Request Notes</label>
                                    </div>
                                    <div class="col-md-6"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <?php
                                            // get item type from item_list table
                                            $item_id = isset($_GET['id']) ? $_GET['id'] : '';
                                            $item_type_query = "SELECT item_type FROM wh_item_list WHERE id = '$item_id'";
                                            $item_type_result = pg_query($conn, $item_type_query);
                                            $item_type_row = pg_fetch_assoc($item_type_result);
                                            $item_type = $item_type_row['item_type'];

                                            // check if item is non-perishable
                                            // if ($item_type != 'Non-Perishable') {
                                                // get total quantity of same items from stockin list table
                                                $total_quantity_query = "SELECT SUM(quantity) as total_quantity FROM wh_stockin_list WHERE item_id = '$item_id'";
                                                $total_quantity_result = pg_query($conn, $total_quantity_query);
                                                $total_quantity_row = pg_fetch_assoc($total_quantity_result);
                                                $total_quantity = $total_quantity_row['total_quantity'];

                                                // get total quantity of same items from stockout list table
                                                $existing_quantity_query = "SELECT SUM(quantity) as existing_quantity FROM wh_stockout_list WHERE item_id = '$item_id'";
                                                $existing_quantity_result = pg_query($conn, $existing_quantity_query);
                                                $existing_quantity_row = pg_fetch_assoc($existing_quantity_result);
                                                $existing_quantity = $existing_quantity_row['existing_quantity'];

                                                // subtract existing quantity from total quantity if item already exists in stockout list
                                                if ($existing_quantity) {
                                                    $total_quantity -= $existing_quantity;
                                                }

                                                // check if item is non-perishable
                                                // if ($item_type != 'Non-Perishable') {
                                                    // get total quantity of expired items from stockin list table
                                                    $expired_quantity = 0;
                                                    $expired_quantity_query = "SELECT SUM(quantity) as expired_quantity FROM wh_stockin_list WHERE item_id = '$item_id' AND expire_date <= CURRENT_DATE";
                                                    $expired_quantity_result = pg_query($conn, $expired_quantity_query);
                                                    $expired_quantity_row = pg_fetch_assoc($expired_quantity_result);
                                                    if ($expired_quantity_row['expired_quantity']) {
                                                    $expired_quantity = $expired_quantity_row['expired_quantity'];
                                                    }
                                                    
                                                    // check if there are any expired items
                                                    $total_quantity = intval($total_quantity);
                                                    $expired_quantity = intval($expired_quantity);
                                                    if ($total_quantity == $expired_quantity && $total_quantity != 0) {
                                                        // all items have expired, show the message
                                                        echo "<div class='alert alert-warning'>Sorry, you cannot add a stock-out because all <b>({$expired_quantity})</b> items in stock have already expired. Please restock before performing any stock-out transactions.</div>";
                                                        // set maximum quantity allowed to 0
                                                        $max_quantity = 0;
                                                    } else if ($expired_quantity > 0) {
                                                        // some items have expired, show the message about remaining quantity
                                                        $remaining_quantity_query = "SELECT SUM(quantity) as stockout_quantity FROM wh_stockout_list WHERE item_id = '$item_id'";
                                                        $remaining_quantity_result = pg_query($conn, $remaining_quantity_query);
                                                        $remaining_quantity_row = pg_fetch_assoc($remaining_quantity_result);
                                                        $remaining_quantity = $total_quantity - $expired_quantity;
                                                        echo "<div class='alert alert-warning'>NOTE: You can only add <b>({$remaining_quantity})</b> stock-out items because <b>({$expired_quantity} out of {$total_quantity})</b> have already expired.</div>";
                                                        // calculate the maximum quantity allowed
                                                        $max_quantity = $total_quantity - $expired_quantity;
                                                    }
                                                    else {
                                                        // no items have expired, set maximum quantity allowed to the total quantity
                                                        $max_quantity_query = "SELECT SUM(quantity) as stockout_quantity FROM wh_stockout_list WHERE item_id = '$item_id'";
                                                        $max_quantity_result = pg_query($conn, $max_quantity_query);
                                                        $max_quantity_row = pg_fetch_assoc($max_quantity_result);
                                                        $max_quantity = $total_quantity - intval($max_quantity_row['stockout_quantity']);
                                                        echo "<div class='alert alert-info'>No Expired Ingredients in Inventory</div>";
                                                    }
                                                // } 
                                            // }
                                        ?>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <textarea type="text" class="form-control" id="notes" name="notes" rows="4" readonly  style="resize: none;"><?php echo $row['notes']; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="date_approved">Date Approved</label>
                                            <input type="date" class="form-control" id="date_approved" name="date_approved">
                                            <span id="date_approved_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 text-center">
                                        <div class="form-group">
                                            <button class="btn btn-sm btn-flat btn-light bg-gradient-light border" type="button" id="add_stockout"><i class="far fa-plus-square"></i> Approve Request</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>
    const dateApprovedField = document.getElementById("date_approved");
    const quantityInput = document.getElementById("quantity");
    const quantityError = document.getElementById("quantityError");
    const expiredQuantity = <?= $expired_quantity ?>;
    const totalQuantity = <?= $total_quantity ?>;
    const addStockOutButton = document.getElementById("add_stockout");
    const dateApprovedError = document.getElementById("date_approved_error");
    const maxQuantity = <?= $max_quantity ?>;
    
    addStockOutButton.disabled = true;

    function checkQuantity() {
        if (totalQuantity == 0) {
            quantityError.style.display = "inline";
            quantityError.innerText = "There are no items in stock.";
            addStockOutButton.disabled = true;
        } else if (totalQuantity == expiredQuantity) {
            quantityError.style.display = "inline";
            quantityError.innerText = "All items in stock have already expired.";
            addStockOutButton.disabled = true;
        } else if (quantityInput.value <= 0) {
            quantityError.style.display = "inline";
            quantityError.innerText = "Sorry, the quantity entered must be greater than zero.";
            addStockOutButton.disabled = true;
        } else if (quantityInput.value > maxQuantity) {
            quantityError.style.display = "inline";
            quantityError.innerText = "Sorry, the quantity entered exceeds the maximum allowed quantity.";
            addStockOutButton.disabled = true;
        } else {
            quantityError.style.display = "none";
        }

        if (quantityError.style.display === "inline" || !dateApprovedField.value || dateApprovedError.textContent) {
            addStockOutButton.disabled = true;
        } else {
            addStockOutButton.disabled = false;
        }
    }


    dateApprovedField.addEventListener('change', function() {
        const selectedDate = new Date(dateApprovedField.value);
        const currentDate = new Date();

        if (selectedDate > currentDate) {
            dateApprovedError.textContent = 'Date must not be greater than current date';
            addStockOutButton.disabled = true;
        } else if (selectedDate < currentDate && !quantityInput.value || selectedDate < currentDate && totalQuantity === expiredQuantity) {
            dateApprovedError.textContent = '';
            addStockOutButton.disabled = true;
        } else if (selectedDate.getFullYear().toString().length < 4) {
            addStockOutButton.disabled = true;
        } else {
            dateApprovedError.textContent = '';
            addStockOutButton.disabled = false;
        }
    });

    dateApprovedField.addEventListener('keyup', function(event) {
        const fieldValue = dateApprovedField.value;

        if (!fieldValue) {
            addStockOutButton.disabled = true;
        }
    });
</script>


<script>
    $(function(){
        // Stockout
        $('#add_stockout').click(function(){
            uni_modal("<i class='far fa-plus-square'></i> Add Stock-out Data", `stocks/manage_stockout.php?iid=<?= isset($id) ? $id : '' ?>&request_id=${$('#request_id').val()}&ingredient_name=${$('#ingredient_name').val()}&request_by=${$('#request_by').val()}&date_request=${$('#date_request').val()}&quantity=${$('#quantity').val()}&notes=${$('#notes').val()}&personnel=${$('#personnel').val()}&personnel_role=${$('#personnel_role').val()}&date_prepared=${$('#date_prepared').val()}&date_approved=${$('#date_approved').val()}`);
        })
        $('.edit_stockout').click(function(){
            uni_modal("<i class='fa fa-edit'></i> Edit Stock-out Data", 'stocks/manage_stockout.php?iid=<?= isset($id) ? $id : '' ?>&id=' + $(this).attr('data-id'))
        })
        $('.delete_stockout').click(function(){
			_conf("Are you sure to delete this stock-out data permanently?","delete_stockout",[$(this).attr('data-id')])
		})

        tbl2 = $('#stockout-tbl').dataTable({
			columnDefs: [
					{ orderable: false, targets: [3] }
			],
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
        $('.dataTables_paginate .pagination>li>a').addClass('p-1');
        $('.dataTables_filter input').addClass('rounded-0 form-control-sm py-1');
        
    })
    
    function delete_stockout($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_stockout",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>