<div class="card card-outline rounded-0 card-dark">
	<div class="card-header">
		<h3 class="card-title">Purchasing Request</h3>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered text-center" id="list">
				<thead>
					<tr>
						<th>#</th>
						<th>Item</th>
						<th>Current Stock</th>
						<th>Reorder Level</th>
						<th>Status Level</th>
						<th>Order Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$qry = pg_query($conn, "SELECT i.*, c.name AS category, (COALESCE((SELECT SUM(quantity) FROM wh_stockin_list WHERE item_id = i.id),0) - COALESCE((SELECT SUM(quantity) FROM wh_stockout_list WHERE item_id = i.id),0)) AS available, s.min_stock, s.max_stock FROM wh_item_list i INNER JOIN wh_category_list c ON i.category_id = c.id INNER JOIN wh_stock_notif s ON s.id = 1 WHERE i.delete_flag = 0 ORDER BY i.date_updated DESC");

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
							} elseif ($available_quantity >= $max_stock) {
								$title = "Over Stock";
								$message = "You currently have " . $available_quantity . " more " . $name . " in stock than needed. Adjust your inventory stocks.";
								$class = "bg-info";
							} else {
								// Skip this row if item is in stock
								continue;
							}
						?>
							<tr>
								<td><?php echo $i++; ?></td>
								<td>
									<div style="line-height:1em">
										<div><?= $row['name'] ?> [<?= $row['unit'] ?>]</div>
										<div class="small"><i><?= $row['category'] ?></i></div>
									</div>
								</td>
								<td><?= (int)$row['available'] ?></td>
								<td><?= $row['min_stock'] ?></td>
								<td class="<?= $class ?>"><strong><?= $title ?></strong></td>
								<td>
									<?php
										$item_name = $row['name'];
										$request_status_query = pg_query($conn, "SELECT status FROM wh_ingredient_request WHERE name = '$item_name'");
										if (pg_num_rows($request_status_query) > 0) {
											$request_status = pg_fetch_result($request_status_query, 0);
											echo $request_status;
											if($request_status == "Approved") {
												$disable_request = "disabled";
												$disable_adjustment = "";
											} else if($request_status == "Pending") {
												$disable_request = "";
												$disable_adjustment = "disabled";
											} else {
												$disable_request = "disabled";
												$disable_adjustment = "disabled";
											}
										} else {
											echo "----------";
											$disable_request = "";
											$disable_adjustment = "disabled";
										}
									?>
								</td>
								<td>
									<?php
										if($disable_request == "disabled") {
											echo '<button class="btn btn-light border" disabled><span class="fa fa-cart-plus text-dark"></span> Request</button>';
										} else {
											echo '<a class="btn btn-light border" href="#" onclick="showRequestModal(\''. $row['id'] .'\', \''. $row['name'] .'\',\''. $row['unit'] .'\',\''. $row['category'] .'\')"><span class="fa fa-cart-plus text-dark"></span> Request</a>';
										}

										if($disable_adjustment == "disabled") {
											echo '<button class="btn btn-light border" disabled><span class="fa fa-adjust text-dark"></span> Adjustment</button>';
										} else {
											echo '<a class="btn btn-light border" href="./?page=stocks/stockin_adjustment&id='. $row['id'] .'"><span class="fa fa-adjust text-dark"></span> Adjustment</a>';
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
		$category = $_POST['category'];
		$requestedQuantity = $_POST['requestedQuantity'];
		$notes = $_POST['notes'];

		// Save the data to the database
		$query = "INSERT INTO wh_ingredient_request (request_id, request_by, personnel_role, name, unit, category, quantity, request_notes, status, date_request, item_id)
				VALUES ('$nextId', '$personnel', '$role', '$itemName', '$itemUnit', '$category', $requestedQuantity, '$notes', 'Pending', CURRENT_DATE, $itemID)";
		
		$result = pg_query($conn, $query);

		if($result) {
			// Data saved successfully
			echo "<script>Swal.fire('Success', 'Request submitted successfully!', 'success')</script>";
		} else {
			// Error saving data
			echo "<script>Swal.fire('Error', 'Error: " . pg_last_error($conn) . "', 'error')</script>";
		}
		
	}
?>

<!-- REQUEST MODAL -->
<div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestModalLabel">Request Item</h5>
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
						<div class="col-md-4">
							<div class="form-group">
								<label for="itemName">Item Name</label>
								<input type="text" class="form-control" id="itemName" name="itemName" readonly>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="itemUnit">Unit</label>
								<input type="text" class="form-control" id="itemUnit" name="itemUnit" value="<?php echo $row['unit']; ?>" readonly>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<label for="category">Category</label>
								<input type="text" class="form-control" id="category" name="category" readonly>
							</div>
						</div>
                    </div>
                    <div class="form-group">
                        <label for="requestedQuantity">Requested Quantity</label>
                        <input type="number" class="form-control" id="requestedQuantity" name="requestedQuantity" required>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" form="requestForm" class="btn btn-primary">Submit Request</button>
            </div>
        </div>
    </div>
</div>


<script>
	// REQUEST ITEMS
	function showRequestModal(id, name, unit, category) {
		$('#item-id').val(id);
		$('#itemName').val(name);
		$('#itemUnit').val(unit);
		$('#category').val(category);
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



