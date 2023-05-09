<div class="card card-outline rounded-5">
	<div class="card-header">
		<h3 class="card-title mt-2 font-weight-bold">PURCHASING REQUEST</h3>
		<div class="card-tools">
			<a href="#" class="btn btn-flat btn-success" onclick="location.href = window.location.href; return false;">
				<span class="fas fa-sync"></span> Refresh
			</a>
			<!-- <a href="./?page=purchasing_request/history" class="btn btn-flat btn-dark">
                <span class="fas fa-file-alt"></span> History
            </a> -->
			<a href="./?page=reports/stockin" class="btn btn-flat btn-info">
				<span class="fas fa-history"></span> Reports
			</a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered text-center" id="list">
				<colgroup>
                    <col width="2%">
                    <col width="20%">
                    <col width="5%">
                    <col width="5%">
                    <col width="12%">
                    <col width="12%">
                    <col width="24%">
                    <col width="20%">
                </colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Item</th>
						<th>Current Stock</th>
						<th>Reorder Level</th>
						<th>Status Level</th>
						<th>Order Status</th>
						<th>Message</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$qry = pg_query($conn, "SELECT i.*, c.name AS category, u.abbreviation AS unit_abbr, u.name AS unit_name, (COALESCE((SELECT SUM(quantity) FROM wh_stockin_list WHERE item_id = i.id),0) - COALESCE((SELECT SUM(quantity) FROM wh_stockout_list WHERE item_id = i.id),0)) AS available, s.min_stock, s.max_stock 
						FROM wh_item_list i 
						INNER JOIN wh_category_list c ON i.category_id = c.id 
						INNER JOIN wh_stock_notif s ON s.id = 1 
						INNER JOIN wh_unit_list u ON i.unit = u.id
						WHERE i.delete_flag = 0 
						ORDER BY i.date_updated DESC");

						while($row = pg_fetch_assoc($qry)):
							$name = $row['name'];
							$min_stock = (int) $row['min_stock'];
							$max_stock = (int) $row['max_stock'];

							$available_quantity = (int)$row['available'];
							$title = '';
							$message = '';

							if ($available_quantity == 0) {
								$title = "Out of Stock";
								$message = $name . " is currently out of stock. Please consider ordering more.";
								$class = "bg-danger";
							} elseif ($available_quantity <= $min_stock ) {
								$title = "Low Stock";
								$message = $available_quantity . " " . $name . " left in stock. Consider reordering soon to avoid running out.";
								$class = "bg-warning";
							} elseif ($available_quantity > $max_stock) {
								$title = "Over Stock";
								$message = "You currently have " . $available_quantity . " more " . $name . " in stock than needed. Adjust your inventory stocks.";
								$class = "bg-info";
							} else {
								// Skip this row if item is in stock
								continue;
							}

							// HIDE DATA IF IT IS IN-STOCK AND HAS "RECEIVED" VALUE IN STATUS TABLE COLUMN
							$request_status_query = pg_query($conn, "SELECT status FROM wh_ingredient_request WHERE name = '$name'");
							if (pg_num_rows($request_status_query) > 0) {
								$request_status = pg_fetch_result($request_status_query, 0);
								if ($request_status == "Received" && $title == "In Stock") {
									continue;
								}
							}
						?>
							<tr>
								<td><?php echo $i++; ?></td>
								<td>
									<div style="line-height:1em">
									<div><?= $row['name'] ?> (<?= $row['unit_abbr'] ?>)</div>
										<div class="small"><i><?= $row['category'] ?></i></div>
									</div>
								</td>
								<td><?= (int)$row['available'] ?></td>
								<td><?= $row['min_stock'] ?></td>
								<td class="<?= $class ?>"><strong><?= $title ?></strong></td>
								<td>
									<?php
										$item_name = $row['name'];
										$request_status_query = pg_query($conn, "SELECT status FROM wh_ingredient_request WHERE name = '$item_name' ORDER BY request_id DESC LIMIT 1");
										if (pg_num_rows($request_status_query) > 0) {
										$request_status = pg_fetch_result($request_status_query, 0);
										if($request_status == "Received") {
											$request_status = "Awaiting";
											$disable_request = "";
											$disable_adjustment = "disabled";
										} else {
											$disable_request = "disabled";
											$disable_adjustment = "disabled";
										}
										echo $request_status;
										if($request_status == "Approved") {
											$disable_adjustment = "";
										}
										} else {
											$request_status = "Awaiting";
											echo $request_status;
											$disable_request = "";
											$disable_adjustment = "disabled";
										}
									?>
								</td>
								<td class="font-italic">
									<?php
										if($request_status == "Approved") {
										echo "You can now adjust your inventory.";
										} else if ($request_status == "Pending") {
										echo "Your request is waiting for approval.";
										} else if ($request_status == "Awaiting") {
										echo "Please replenish your stock now!";
										} else if ($request_status == "Declined") {
										echo "Your request has been declined.";
										} else {
										echo "test";
										}
									?>
								</td>
								<td>
									<?php
										if($disable_request == "disabled") {
											echo '<button class="btn btn-info border" disabled><span class="fa fa-cart-plus text-light"></span> Request</button>';
										} else {
											echo '<a class="btn btn-info border" onclick="showRequestModal(\''. $row['id'] .'\', \''. $row['name'] .'\',\''. $row['unit_name'] .'\',\''. intval($row['available']) .'\', \''. $row['max_stock'] .'\')"><span class="fa fa-cart-plus text-light"></span> Request</a>';
										}
										if($disable_adjustment == "disabled") {
											echo '<button class="btn btn-success border" disabled><span class="fa fa-adjust text-light"></span> Adjustment</button>';
										} else {
											echo '<a class="btn btn-success border" href="./?page=stocks/stockin_adjustment&id='. $row['id'] .'"><span class="fa fa-adjust text-light"></span> Adjustment</a>';
										}
									?>
								</td>
							</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<?php
	if($_SERVER['REQUEST_METHOD'] === 'POST') {

		// Get the latest request ID
		$query = "SELECT request_id FROM wh_ingredient_request ORDER BY request_id DESC LIMIT 1";
		$result = pg_query($conn, $query);

		if (pg_num_rows($result) > 0) {
			$row = pg_fetch_assoc($result);
			$latestId = $row['request_id'];
		} else {
			$latestId = '000000000000';
		}

		// Generate the next request ID
		$lastCounter = substr($latestId, 7);
		$lastDate = substr($latestId, 0, 6);
		$today = date('ymd');
		if ($lastDate == $today) {
			if ($lastCounter == str_repeat('9', strlen($lastCounter))) {
				$nextId = $today . '-001';
			} else {
				$nextCounter = str_pad($lastCounter + 1, strlen($lastCounter), '0', STR_PAD_LEFT);
				$nextId = $today . '-' . $nextCounter;
			}
		} else {
			$nextId = $today . '-001';
		}

		// Get the form data
		$itemID = $_POST['item-id'];
		$personnel = $_POST['personnel'];
		$role = $_POST['role'];
		$itemName = $_POST['itemName'];
		$itemUnit = $_POST['itemUnit'];
		$requestedQuantity = $_POST['requestedQuantity'];
		$notes = $_POST['notes'];

		// Save the data to the database
		$query = "INSERT INTO wh_ingredient_request (request_id, request_by, personnel_role, name, unit, quantity, request_notes, status, date_request, item_id)
				VALUES ('$nextId', '$personnel', '$role', '$itemName', '$itemUnit', $requestedQuantity, '$notes', 'Pending', CURRENT_DATE, $itemID)";
		
		$result = pg_query($conn, $query);

		if($result) {
			// Data saved successfully
			echo "<script>
				window.onload = function() {
					Swal.fire({
						icon: 'success',
						title: 'Success',
						text: 'Successfully declined the request item.',
						showConfirmButton: true
					}).then(function() {
						location.href = window.location.href;
					});
				};
			</script>";
		} else {
			// Error saving data
			echo "<script>
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Error: " . pg_last_error($conn) . "',
						showConfirmButton: false,
						timer: 1500
					});
				 </script>";
		}
	}
?>

<!-- REQUEST MODAL -->
<div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestModalLabel">Request Ingredient in Purchasing Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="requestForm" method="POST">
					<div class="form-group">
						<input type="text" class="form-control" id="item-id" name="item-id" hidden>
						<input type="text" class="form-control" id="personnel" name="personnel" value="<?php echo ucwords($_settings->userdata('fullname')) ?>" hidden>
						<input type="text" class="form-control" id="role" name="role" value="<?php echo ucwords($_settings->userdata('role')) ?>" hidden>
					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label for="itemName">Ingredient Name</label>
								<input type="text" class="form-control" id="itemName" name="itemName" readonly>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="itemUnit">Unit</label>
								<input type="text" class="form-control" id="itemUnit" name="itemUnit" readonly>
							</div>
						</div>
                    </div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="available">Available</label>
								<input type="number" class="form-control" id="available" name="available" readonly>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="maxStock">Max Stock</label>
								<input type="number" class="form-control" id="maxStock" name="maxStock" readonly>
							</div>
						</div>
					</div>
                    <div class="form-group">
						<label for="requestedQuantity">Requested Quantity</label>
						<div class="row">
							<div class="col-md-9">
								<input type="number" class="form-control" id="requestedQuantity" name="requestedQuantity" placeholder="Enter Requested Quantity" required>
							</div>
							<div class="col-md-3">
								<button type="button" class="btn btn-success" id="fillInventoryBtn">Fill Request</button>
							</div>
						</div>
						<span class="text-red" id="alertMsg"></span>
					</div>
					<div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" placeholder="Please provide details about the requested item" required></textarea>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button id="submitBtn" type="submit" form="requestForm" class="btn btn-primary">Submit Request</button>
            </div>
        </div>
    </div>
</div>

<script>
	// Get the input elements
	const requestedQuantityInput = document.getElementById("requestedQuantity");
	const availableInput = document.getElementById("available");
	const maxStockInput = document.getElementById("maxStock");
	const totalQuantityInput = document.getElementById("totalQuantity");
	const fillInventoryBtn = document.getElementById("fillInventoryBtn");
	const submitBtn = document.getElementById("submitBtn");
	const notesInput = document.getElementById("notes");

	// Calculate the total quantity
	function calculateTotalQuantity() {
		return maxStockInput.value - availableInput.value;
	}

	function updateTotalQuantity() {
		// Convert available and maxStock to integers
		const available = parseInt(availableInput.value);
		const maxStock = parseInt(maxStockInput.value);

		// Subtract maxStock from available to get the remaining quantity
		const remainingQuantity = available - maxStock;

		// Update the value of the totalQuantity input field
		totalQuantityInput.value = remainingQuantity;
	}

	// Disable the button initially
	submitBtn.disabled = true;

	// Add an event listener to the requestedQuantityInput element
	requestedQuantityInput.addEventListener("input", () => {
		const requestedQuantity = requestedQuantityInput.value;
		const totalQuantity = calculateTotalQuantity();

		if (requestedQuantity > totalQuantity) {
			// If requested quantity is greater than total quantity, display the error message and disable the button
			document.getElementById("alertMsg").textContent = `Requested quantity cannot be greater than ${totalQuantity}`;
			submitBtn.disabled = true;
		} else {
			// If requested quantity is less than or equal to total quantity, hide the error message and enable the button
			document.getElementById("alertMsg").textContent = "";
			submitBtn.disabled = requestedQuantity === "" || notesInput.value === "";
		}
	});

	// Add an event listener to the fillInventoryBtn element
	fillInventoryBtn.addEventListener("click", () => {
		const totalQuantity = calculateTotalQuantity();

		// Set the total quantity as the value of requestedQuantityInput
		requestedQuantityInput.value = totalQuantity;

		// Trigger the input event on requestedQuantityInput to update the button and error message
		requestedQuantityInput.dispatchEvent(new Event("input"));
	});

	// Add an event listener to the notesInput element
	notesInput.addEventListener("input", () => {
		const requestedQuantity = requestedQuantityInput.value;
		const totalQuantity = calculateTotalQuantity();

		// Disable the button if either the notes or requested quantity is empty, or if the requested quantity is greater than the total quantity
		submitBtn.disabled = requestedQuantity === "" || notesInput.value === "" || requestedQuantity > totalQuantity;
	});

	// Listen for changes on the available and maxStock input fields
	availableInput.addEventListener("input", updateTotalQuantity);
	maxStockInput.addEventListener("input", updateTotalQuantity);
</script>

<script>
	// REQUEST ITEMS
	function showRequestModal(id, name, unit, available, max_stock) {
		$('#item-id').val(id);
		$('#itemName').val(name);
		$('#itemUnit').val(unit);
		$('#available').val(available);
		$('#maxStock').val(max_stock);
		$('#requestModal').modal('show');
	}

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



