<!-- POS REQUEST -->
<div class="card card-outline rounded-0 card-dark">
	<div class="card-header">
		<h3 class="card-title mt-2">SALES REQUEST</h3>
        <div class="card-tools">
            <a href="#" class="btn btn-flat btn-success" onclick="location.href = window.location.href; return false;">
				<span class="fas fa-sync"></span> Refresh
			</a>
            <a href="./?page=sales_request/history" class="btn btn-flat btn-dark">
                <span class="fas fa-file-alt"></span> History
            </a>
			<a href="./?page=reports/stockout" class="btn btn-flat btn-info">
                <span class="fas fa-history"></span> Reports
            </a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered text-center" id="list">
				<thead>
					<tr>
						<th>#</th>
						<th>Request ID</th>
						<th>Ingredient</th>
						<th>Date Request</th>
						<th>Request By</th>
						<th>Request Notes</th>
                        <th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
                    <?php 
                       $result = pg_query($conn, "SELECT * FROM Ingredient_request WHERE status != 'Received' AND status != 'Approved' ORDER BY date_request ASC");

                        $counter = 1;
                        while ($row = pg_fetch_assoc($result)) {
                            $request_id = $row['request_id'];
                            $ingredient_name = $row['ingredient_name'];
                            $date_request = $row['date_request'];
                            $status = $row['status'];
                            $request_by = $row['request_by'];
                            $request_notes = $row['notes'];
                            $date_today = date('Y-m-d H:i:s');

                            // Disable adjustment button if status is 'Approved'
                            $adjustmentDisabled = '';
                            if ($status == 'Pending') {
                                $adjustmentDisabled = 'disabled';
                            }

                            // Disable review and decline buttons if status is 'Preparing'
                            $reviewDisabled = '';
                            $declineDisabled = '';
                            if ($status == 'Preparing') {
                                $reviewDisabled = 'disabled';
                                $declineDisabled = 'disabled';
                            }
                    ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo $request_id; ?></td>
                            <td><?php echo $ingredient_name; ?></td>
                            <td><?php echo $date_request; ?></td>
                            <td><?php echo $request_by; ?></td>
                            <td><?php echo $request_notes; ?></td>
                            <td><?php echo $status; ?></td>
                            <td class="d-flex justify-content-center">
                                <form method="POST">
                                    <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                                    <input type="hidden" name="status" value="Approved">
                                    <button type="button" class="btn btn-flat btn-sm btn-dark border approveBtn" 
                                        data-toggle="modal" data-target="#approveModal" 
                                        data-id="<?php echo $row['id'] . ',' . $row['item_id'] . ',' . $row['ingredient_name']  . ',' . $row['notes'] . ',' . ucwords($_settings->userdata('fullname')) . ',' . ucwords($_settings->userdata('role')); ?>"
                                        <?php echo $reviewDisabled; ?>>
                                        <span class="fa fa-eye text-light"></span> Review
                                    </button>
                                </form>
                                <button type="button" class="btn btn-flat btn-sm btn-success border" 
                                    onclick="location.href='./?page=stocks/stockout_adjustment&id=<?php echo $row['item_id'] ?>';" 
                                    <?php echo $adjustmentDisabled; ?>>
                                    <span class="fa fa-adjust text-light"></span> Adjustment
                                </button>
                                <form method="POST">
                                    <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                                    <input type="hidden" name="status" value="Declined">
                                    <button type="submit" class="btn btn-flat btn-sm btn-danger border" name="decline"
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


<!-- APPROVE MODAL -->
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
                        $unitName = $_POST['unit_name'];
                        $personnel = $_POST['personnel'];
                        $personnelRole = $_POST['personnel_role'];

                        $updateQuery = "UPDATE ingredient_request SET item_id='$itemId', unit='$unitName', personnel='$personnel',personnel_role='$personnelRole', status='Preparing', date_prepared=NOW() WHERE id='$requestId'";
                        $updateResult = pg_query($conn, $updateQuery);

                        if ($updateResult) {
                            echo "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Successfully approved the request ingredient.'
                                })
                            </script>";
                        } else {
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to approve the request ingredient.'
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
                        <div class="form-group">
                            <label for="sales_request_ingredient_name">Ingredient Request Name</label>
                            <input type="text" class="form-control" id="sales_request_ingredient_name" name="sales_request_ingredient_name" readonly>
                        </div>
                        <div class="form-group">
                            <label for="request_notes">Request Notes</label>
                            <textarea type="text" class="form-control" id="request_notes" name="request_notes" readonly></textarea>
                        </div>
                        <div class="form-group" hidden>
                            <label for="ingredient_id">Ingredient Request ID</label>
                            <input type="text" class="form-control" id="ingredient_id" name="ingredient_id" readonly>
                        </div>
                        <label for="ingredient_name">Warehouse Ingredient</label>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <select class="form-control" id="ingredient_name" name="ingredient_name" required size="4">
                                        <?php
                                        $result = pg_query($conn, "SELECT id, name FROM wh_item_list");
                                        if (!$result) {
                                            echo "Failed to retrieve the list of ingredients.";
                                        } else {
                                            while ($row = pg_fetch_assoc($result)) {
                                                echo "<option value='" . $row['name'] . "' data-id='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <div class="form-group">
                                    <a class="global-add-dditem" href="javascript:void(0)" id="new_ingredient" style="text-decoration: underline; color: black;">new ingredient?</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <span class="text-red" id="alertMsg"></span>
                            </div>
                        </div>
                        <div class="form-group" hidden>
                            <label for="item_id">Item ID</label>
                            <input type="text" class="form-control" id="item_id" name="item_id" readonly>
                        </div>
                        <label for="unit_name" class="mt-3">Warehouse Unit</label>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <select class="form-control" id="unit_name" name="unit_name" required size="4">
                                        <?php
                                        $result = pg_query($conn, "SELECT name, abbreviation FROM wh_unit_list");
                                        if (!$result) {
                                            echo "Failed to retrieve the list of units.";
                                        } else {
                                            while ($row = pg_fetch_assoc($result)) {
                                                $optionText = $row['name'] . " (" . $row['abbreviation'] . ")";
                                                echo "<option value='" . $row['name'] . "'>" . $optionText . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <div class="form-group">
                                    <a class="global-add-dditem" href="javascript:void(0)" id="new_unit" style="text-decoration: underline; color:black">new unit?</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="reviewBtn" class="btn btn-flat btn-sm btn-dark bg-gradient-success border" name="approve"> Prepare</button>
                        <button type="button" class="btn btn-flat btn-sm btn-light bg-gradient-light border" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>

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
            var itemName = $(this).closest('tr').find('.item_name').text();
            $('#ingredient_request_id').val(rowId);
            $('#ingredient_id').val(itemId);
            $('#sales_request_ingredient_name').val(sales_request_ingredient_name);
            $('#request_notes').val(request_notes);
            $('#personnel').val(personnel);
            $('#personnel_role').val(personnel_role);
        });

        $('#ingredient_name').on('change', function() {
            var selectedId = $(this).find(':selected').data('id');
            $('#item_id').val(selectedId);
            var requestedIngredient = $('#sales_request_ingredient_name').val().toLowerCase().replace(/\s/g, '');
            var warehouseIngredient = $(this).val().toLowerCase().replace(/\s/g, '');
            var alertMsg = $('#alertMsg');
            if (requestedIngredient !== warehouseIngredient) {
                alertMsg.text(' * It does not match the requested ingredient');
            } else {
                alertMsg.empty();
            }
        });
    });
</script>



<script>
    // Get the Ingredient Request Name, Ingredient Name, and Unit Name fields
    const requestNameField = document.getElementById('sales_request_ingredient_name');
    const ingredientNameField = document.getElementById('ingredient_name');
    const unitNameField = document.getElementById('unit_name');
    const reviewBtn = document.getElementById('reviewBtn');

    // Disable the submit button initially
    reviewBtn.disabled = true;

    // Add an event listener to the select elements
    [ingredientNameField, unitNameField].forEach(field => {
        field.addEventListener('change', function() {
            // Get the selected option values and convert them to lowercase
            const selectedIngredient = ingredientNameField.value.toLowerCase().replace(/\s/g, '');
            const selectedUnit = unitNameField.value.toLowerCase().replace(/\s/g, '');

            // Get the request name value and convert it to lowercase
            const requestValue = requestNameField.value.toLowerCase().replace(/\s/g, '');

            // Check if the values are equal and if the selected unit has a value
            if (requestValue === selectedIngredient && selectedUnit !== '') {
                // Enable the submit button
                reviewBtn.disabled = false;
            } else if (requestValue !== selectedIngredient && selectedUnit === '') {
                // Disable the submit button
                reviewBtn.disabled = true;
            } else {
                // Disable the submit button
                reviewBtn.disabled = true;
            }
        });
    });
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