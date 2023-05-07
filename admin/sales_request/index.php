<!-- POS REQUEST -->
<style>
    .status-pending {
        color: #ff6600; /* orange */
    }
    .status-preparing {
        color: #0066ff; /* blue */
    }
    .status-approved {
        color: #00cc00; /* green */
    }
</style>


<div class="card card-outline rounded-5">
	<div class="card-header">
		<h3 class="card-title mt-2 font-weight-bold">PENDING SALES REQUEST</h3>
        <div class="card-tools">
            <a href="#" class="btn btn-flat btn-success" onclick="location.href = window.location.href; return false;">
				<span class="fas fa-sync"></span> Refresh
			</a>
            <!-- <a href="./?page=sales_request/history" class="btn btn-flat btn-dark">
                <span class="fas fa-file-alt"></span> History
            </a> -->
			<a href="./?page=reports/stockout" class="btn btn-flat btn-info">
                <span class="fas fa-history"></span> Reports
            </a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered text-center" id="list">
                <colgroup>
                    <col width="1%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="15%">
                    <col width="10%">
                    <col width="24%">
                    <col width="10%">
                </colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Request ID</th>
						<th>Ingredient</th>
						<th>Date Request</th>
						<th>Request By</th>
						<th>Request Notes</th>
                        <th>Status</th>
                        <th>Message</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
                <?php 
                    $result = pg_query($conn, "SELECT * FROM ingredient_request WHERE status IN ('Pending', 'Preparing', 'Approved') ORDER BY date_request ASC");
                    $counter = 1;
                    while ($row = pg_fetch_assoc($result)) {
                        $request_id = $row['request_id'];
                        $ingredient_name = $row['ingredient_name'];
                        $date_request = $row['date_request'];
                        $status = $row['status'];
                        $request_by = $row['request_by'];
                        $request_notes = $row['notes'];
                        $date_today = date('Y-m-d H:i:s');
                        $unit = $row['unit'];
                        $add_stock = $row['add_stock'];

                        // Set message based on status
                        $message = '';
                        switch ($status) {
                            case 'Pending':
                                $message = 'Your request is waiting approval.';
                                break;
                            case 'Preparing':
                                $message = 'You can now adjust your inventory.';
                                break;
                            case 'Approved':
                                $message = 'Waiting for the sales department to receive the request.';
                                break;
                            default:
                                $message = '';
                        }

                        // Disable adjustment button if status is 'Approved'
                        $adjustmentDisabled = '';
                        if ($status == 'Pending') {
                            $adjustmentDisabled = 'disabled';
                        }

                        $reviewDisabled = '';
                        $declineDisabled = '';
                        if ($status == 'Approved') {
                            $adjustmentDisabled = 'disabled';
                            $reviewDisabled = 'disabled';
                            $declineDisabled = 'disabled';
                        } elseif ($status == 'Pending') {
                            $adjustmentDisabled = 'disabled';
                        } elseif ($status == 'Preparing') {
                            $reviewDisabled = 'disabled';
                            $declineDisabled = 'disabled';
                        }
                    ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo $request_id; ?></td>
                            <td>
                                <span><?php echo $ingredient_name; ?></span><br>
                                <span class="font-weight-light">( <?php echo $add_stock; ?> <?php echo $unit; ?> )</span>
                            </td>
                            <td><?php echo $date_request; ?></td>
                            <td><?php echo $request_by; ?></td>
                            <td><?php echo $request_notes; ?></td>
                            <td><?php echo $status; ?></td>
                            <td class="font-italic"><?php echo $message; ?></td>
                            <td class="d-flex justify-content-center">
                                <form method="POST">
                                    <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                                    <input type="hidden" name="status" value="Approved">
                                    <button type="button" class="btn btn-dark border approveBtn" 
                                        data-toggle="modal" data-target="#approveModal" 
                                        data-id="<?php echo $row['id'] . ',' . $row['item_id'] . ',' . $row['ingredient_name']  . ',' . $row['notes'] . ',' . ucwords($_settings->userdata('fullname')) . ',' . ucwords($_settings->userdata('role')) . ',' . $row['unit']; ?>"
                                        <?php echo $reviewDisabled; ?>>
                                        <span class="fa fa-eye text-light"></span> Review
                                    </button>
                                </form>
                                <button type="button" class="btn btn-success border" 
                                    onclick="location.href='./?page=stocks/stockout_adjustment&id=<?php echo $row['item_id'] ?>';" 
                                    <?php echo $adjustmentDisabled; ?>>
                                    <span class="fa fa-adjust text-light"></span> Adjustment
                                </button>
                                <form method="POST">
                                    <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                                    <input type="hidden" name="status" value="Declined">
                                    <button type="button" class="btn btn-danger border declineBtn" data-toggle="modal" data-target="#declineModal" data-id="<?php echo $row['id'] . ',' . ucwords($_settings->userdata('fullname')) . ',' . ucwords($_settings->userdata('role')); ?>"
                                        <?php echo $declineDisabled; ?>>
                                        <span class="fa fa-times text-light"></span> Decline
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
			</table>
		</div>
	</div>
</div>


<!-- PREPARING MODAL -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel"><span class="fa fa-eye text-dark"></span> Review Requested Ingredients</h5>
            </div>
            <div class="modal-body">
                <?php
                    if (isset($_POST['approve'])) {
                        $requestId = $_POST['ingredient_request_id'];
                        $ingredientName = $_POST['ingredient_name'];
                        $itemId = $_POST['item_id'];
                        $personnel = $_POST['personnel'];
                        $personnelRole = $_POST['personnel_role'];

                        $updateQuery = "UPDATE ingredient_request SET item_id='$itemId', personnel='$personnel',personnel_role='$personnelRole', status='Preparing', date_prepared=NOW() WHERE id='$requestId'";
                        $updateResult = pg_query($conn, $updateQuery);

                        if ($updateResult) {
                            echo "<script>
                                window.onload = function() {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: 'Successfully prepared the request ingredient.',
                                        showConfirmButton: true
                                    }).then(function() {
                                        location.href = window.location.href;
                                    });
                                };
                            </script>";
                        } else {
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to prepare the request ingredient.'
                                })
                            </script>";
                        }
                        
                    }
                ?>
                <form method="POST">
                    <div class="modal-body">
                        <div class="form-group" hidden>
                            <label for="ingredient_request_id">ID</label>
                            <input type="text" class="form-control" id="ingredient_request_id" name="ingredient_request_id" readonly>
                        </div>
                        <div class="form-group" hidden>
                            <label for="personnel">Personnel</label>
                            <input type="text" class="form-control" id="personnel" name="personnel" readonly>
                        </div>
                        <div class="form-group" hidden>
                            <label for="personnel_role">Personnel Role</label>
                            <input type="text" class="form-control" id="personnel_role" name="personnel_role" readonly>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sales_request_ingredient_name">Ingredient Request Name</label>
                                    <input type="text" class="form-control" id="sales_request_ingredient_name" name="sales_request_ingredient_name" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unit">Request Unit</label>
                                    <input type="text" class="form-control" id="unit" name="unit" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="request_notes">Request Notes</label>
                            <textarea type="text" class="form-control" id="request_notes" name="request_notes" readonly></textarea>
                        </div>
                        <div class="form-group" hidden>
                            <label for="ingredient_id">Ingredient Request ID</label>
                            <input type="text" class="form-control" id="ingredient_id" name="ingredient_id" readonly>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="ingredient_name">Warehouse Ingredient</label>
                            </div>
                            <div class="col-md-6">
                                <label for="item_unit">Warehouse Unit</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control" id="ingredient_name" name="ingredient_name" required size="3">
                                        <?php
                                            $result = pg_query($conn, "SELECT wh_item_list.id, wh_item_list.name, wh_unit_list.name AS unit_name FROM wh_item_list JOIN wh_unit_list ON wh_item_list.unit = wh_unit_list.id");
                                            if (!$result) {
                                                echo "Failed to retrieve the list of ingredients.";
                                            } else {
                                                while ($row = pg_fetch_assoc($result)) {
                                                    echo "<option value='" . $row['name'] . "' data-id='" . $row['id'] . "' data-unit='" . $row['unit_name'] . "'>" . $row['name'] . "</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="item_unit" name="item_unit" readonly>
                                </div>
                                <div class="form-group d-flex justify-content-center">
                                    <a class="global-add-dditem" href="javascript:void(0)" id="new_ingredient" style="text-decoration: underline; color: black;">new ingredient?</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span class="alertMsg1 text-red"></span>
                            </div>
                            <div class="col-md-6">
                                <span class="alertMsg2 text-red"></span>
                            </div>
                        </div>
                        <div class="form-group" hidden>
                            <label for="item_id">Item ID</label>
                            <input type="text" class="form-control" id="item_id" name="item_id" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="reviewBtn" class="btn btn-dark bg-gradient-success border" name="approve"> Prepare</button>
                        <button type="button" class="btn btn-light bg-gradient-light border" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<!-- DECLINE MODAL -->
<div class="modal fade" id="declineModal" tabindex="-1" role="dialog" aria-labelledby="declineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="declineModalLabel">Reason for Decline</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
                if (isset($_POST['decline'])) {
                    $request_id = $_POST['ingredient_request_id_decline'];
                    $decline_reason = $_POST['decline_reason'];
                    $date_declined = date('Y-m-d');
                    $personnel_decline = $_POST['personnel_decline'];
                    $personnel_role_decline = $_POST['personnel_role_decline'];

                    // update the "ingredient_request" table with the new values
                    $update_query = "UPDATE ingredient_request SET status='Declined', decline_notes='$decline_reason', date_declined='$date_declined', personnel='$personnel_decline', personnel_role='$personnel_role_decline' WHERE id=$request_id";
                    $updateResult = pg_query($conn, $update_query);

                    if ($updateResult) {
                        echo "<script>
                            window.onload = function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Successfully declined the request ingredient.',
                                    showConfirmButton: true
                                }).then(function() {
                                    location.href = window.location.href;
                                });
                            };
                        </script>";
                    } else {
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to decline the request ingredient.'
                            })
                        </script>";
                    }
                }
            ?>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group" hidden>
                        <label for="ingredient_request_id_decline">ID</label>
                        <input type="text" class="form-control" id="ingredient_request_id_decline" name="ingredient_request_id_decline" readonly>
                    </div>
                    <div class="form-group" hidden>
                        <label for="personnel_decline">Personnel</label>
                        <input type="text" class="form-control" id="personnel_decline" name="personnel_decline" readonly>
                    </div>
                    <div class="form-group" hidden>
                        <label for="personnel_role_decline">Personnel Role</label>
                        <input type="text" class="form-control" id="personnel_role_decline" name="personnel_role_decline" readonly>
                    </div>
                    <textarea class="form-control" id="declineReason" name="decline_reason" rows="3" placeholder="Enter reason for declining the request (e.g. out of stock, unavailable, etc.)" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" name="decline">Decline</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('.approveBtn').on('click', function() {
            var rowData = $(this).data('id').split(',');
            var rowId = rowData[0];
            var itemId = rowData[1];
            var sales_request_ingredient_name = rowData[2];
            var request_notes = rowData[3];
            var personnel = rowData[4];
            var personnel_role = rowData[5];
            var itemUnit = rowData[6];
            var itemName = $(this).closest('tr').find('.item_name').text();
            $('#ingredient_request_id').val(rowId);
            $('#ingredient_id').val(itemId);
            $('#sales_request_ingredient_name').val(sales_request_ingredient_name);
            $('#request_notes').val(request_notes);
            $('#personnel').val(personnel);
            $('#personnel_role').val(personnel_role);
            $('#unit').val(itemUnit);
        });

        $('.declineBtn').on('click', function() {
            var rowData = $(this).data('id').split(',');
            var rowId = rowData[0];
            var personnel = rowData[1];
            var personnel_role = rowData[2];
            $('#ingredient_request_id_decline').val(rowId);
            $('#personnel_decline').val(personnel);
            $('#personnel_role_decline').val(personnel_role);
        });
    });
</script>


<script>
    // Get the Ingredient Request Name, Ingredient Name, and Unit Name fields
    const requestNameField = document.getElementById('sales_request_ingredient_name');
    const ingredientNameField = document.getElementById('ingredient_name');
    const itemUnitField = document.getElementById('item_unit');
    const requestUnitField = document.getElementById('unit');
    const reviewBtn = document.getElementById('reviewBtn');

    // Disable the submit button initially
    reviewBtn.disabled = true;

    // Add an event listener to the ingredient name dropdown
    ingredientNameField.addEventListener('change', function() {
        const selectedId = $(this).find(':selected').data('id');
        $('#item_id').val(selectedId);
        const itemUnit = $(this).find(':selected').data('unit');
        $('#item_unit').val(itemUnit);
        checkEnableButton();
    });

    // Add an event listener to the request unit field
    requestUnitField.addEventListener('change', function() {
        checkEnableButton();
    });

    function checkEnableButton() {
    // Get the selected option values and convert them to lowercase
    const selectedIngredient = ingredientNameField.value.toLowerCase().replace(/\s/g, '');
    const selectedUnit = itemUnitField.value.toLowerCase().replace(/\s/g, '');
    const requestValue = requestNameField.value.toLowerCase().replace(/\s/g, '');
    const requestUnitValue = requestUnitField.value.toLowerCase().replace(/\s/g, '');

    // Get the Warehouse Ingredient and Unit fields
    const warehouseIngredientField = document.getElementById('ingredient_name');
    const warehouseUnitField = document.getElementById('item_unit');
    const alertMsg1 = document.querySelector('.alertMsg1');
    const alertMsg2 = document.querySelector('.alertMsg2');

    // Check if the requested ingredient and dropdown ingredient match, and if the item unit and request unit match
    if (requestValue === selectedIngredient) {
        alertMsg1.textContent = '';
        if (requestUnitValue === selectedUnit) {
            alertMsg2.textContent = '';
            // Enable the submit button
            reviewBtn.disabled = false;
        } else {
            alertMsg2.textContent = 'Warehouse and requested units do not match.';
            // Disable the submit button
            reviewBtn.disabled = true;
        }
    } else {
        alertMsg1.textContent = 'Warehouse and requested ingredients do not match.';
        // Disable the submit button
        reviewBtn.disabled = true;
    }
}

</script>


<script>
    $(document).ready(function(){
        // CREATE ITEM
        $('#new_ingredient').click(function(){
          uni_modal("<i class='far fa-plus-square'></i> Add New Item ","items/manage_item.php")
        })

         // CREATE UNIT
        $('#new_unit').click(function(){
          uni_modal("<i class='far fa-plus-square'></i> Add New Units ","units/manage_unit.php")
        })
      });
</script>

<script>
	// TABLE
	$(document).ready(function(){
		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [5] }
			],
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})
</script>