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
                <div class="card card-outline rounded-5 card-dark">
                    <div class="card-header">
                        <h3 class="card-title mt-2">REQUEST ADJUSTMENT</h3>
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
                                        <h3>Requested Ingredient</h3>
                                    </div>
                                    <div class="col-md-2">
                                        
                                    </div>
                                    <div class="col-md-4 text-center mb-3">
                                        <h3>Personnel</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="ingredient_name">Ingredient Name</label>
                                            <input type="text" class="form-control" id="ingredient_name" name="ingredient_name" value="<?php echo $row['ingredient_name']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="request_id">Request ID</label>
                                            <input type="text" class="form-control" id="request_id" name="request_id" value="<?php echo $row['request_id']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        
                                    </div>
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
                                            <input type="date" class="form-control" id="date_request" name="date_request" value="<?php echo $row['date_request']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                            
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="personnel_role">Personnel Role</label>
                                            <input type="text" class="form-control" id="personnel_role" name="personnel_role" value="<?php echo ucwords($_settings->userdata('role')) ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="requested_quantity">Quantity</label>
                                            <input type="text" class="form-control" id="requested_quantity" name="requested_quantity" value="<?php echo $row['requested_quantity']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="unit">Unit</label>
                                            <input type="text" class="form-control" id="unit" name="unit" value="<?php echo $row['unit']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                    
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="date_prepared">Date Prepared</label>
                                            <input type="text" class="form-control" id="date_prepared" name="date_prepared" value="<?php echo $row['date_prepared']; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="notes">Request Notes</label>
                                            <textarea type="text" class="form-control" id="notes" name="notes" readonly cols="3" style="resize: none;"><?php echo $row['notes']; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-2">

                                    </div>
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
    // Get the "Date Approved" field and the error message element
    const dateApprovedField = document.getElementById('date_approved');
    const dateApprovedError = document.getElementById('date_approved_error');

    // Get the "Add Stock Out" button and disable it by default
    const addStockOutButton = document.getElementById('add_stockout');
    addStockOutButton.disabled = true;

    // Add event listeners to the "Date Approved" field
    dateApprovedField.addEventListener('change', function() {
        // Get the selected date from the field
        const selectedDate = new Date(dateApprovedField.value);

        // Get the current date
        const currentDate = new Date();

        // Check if the selected date is greater than the current date
        if (selectedDate > currentDate) {
            // If it is, display an error message and disable the "Add Stock Out" button
            dateApprovedError.textContent = 'Date must not be greater than current date';
            addStockOutButton.disabled = true;
        } else {
            // If it's not, clear the error message and enable the "Add Stock Out" button
            dateApprovedError.textContent = '';
            addStockOutButton.disabled = false;
        }
    });

    dateApprovedField.addEventListener('keyup', function(event) {
        // Get the value of the field
        const fieldValue = dateApprovedField.value;

        // If the value of the field is empty, disable the "Add Stock Out" button
        if (!fieldValue) {
            addStockOutButton.disabled = true;
        }
    });
</script>

<script>
    $(function(){
        // Stockout
        $('#add_stockout').click(function(){
            uni_modal("<i class='far fa-plus-square'></i> Add Stock-out Data", `stocks/manage_stockout.php?iid=<?= isset($id) ? $id : '' ?>&request_id=${$('#request_id').val()}&ingredient_name=${$('#ingredient_name').val()}&request_by=${$('#request_by').val()}&date_request=${$('#date_request').val()}&requested_quantity=${$('#requested_quantity').val()}&notes=${$('#notes').val()}&personnel=${$('#personnel').val()}&personnel_role=${$('#personnel_role').val()}&date_prepared=${$('#date_prepared').val()}&date_approved=${$('#date_approved').val()}`);
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