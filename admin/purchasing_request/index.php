<div class="card card-outline rounded-0 card-dark">
	<div class="card-header">
		<h3 class="card-title">Purchasing Request</h3>
		<div class="card-tools">
			<a class="btn btn-flat btn-primary" href=""><span class="fas fa-history"></span> Purchasing Request History </a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered text-center" id="list">
				<!-- <colgroup>
					<col width="5%">
					<col width="15%">
					<col width="5%">
					<col width="5%">
					<col width="10%">
					<col width="25%">
					<col width="10%">
					<col width="25%">
				</colgroup> -->
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
								<td class="">
									<div style="line-height:1em">
										<div><?= $row['name'] ?> [<?= $row['unit'] ?>]</div>
										<div class="small"><i><?= $row['category'] ?></i></div>
									</div>
								</td>
								<td><?= (int)$row['available'] ?></td>
								<td><?= $row['min_stock'] ?></td>
								<td class="<?= $class ?>"><strong><?= $title ?></strong></td>
								<td></td>
								<td>
									<a class="btn btn-flat btn-sm btn-light bg-gradient-light border" onclick="showRequestModal('<?php echo $row['name']; ?>')"><span class="fa fa-cart-plus text-dark"></span> Request</a>
									<a class="btn btn-flat btn-sm btn-light bg-gradient-light border" href="./?page=stocks&id=<?php echo $row['id'] ?>"><span class="fa fa-adjust text-dark"></span> Adjustment</a>
									<a class="btn btn-flat btn-sm btn-light bg-gradient-light border" href="./?page=stocks/view_stock&id=<?php echo $row['id'] ?>"><span class="fa fa-history text-dark"></span> History</a>
								</td>
							</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

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
                <form id="requestForm" method="post">
                    <div class="form-group">
                        <label for="itemName">Item Name</label>
                        <input type="text" class="form-control" id="itemName" name="itemName" readonly>
                    </div>
                    <div class="form-group">
                        <label for="requestedQuantity">Requested Quantity</label>
                        <input type="number" class="form-control" id="requestedQuantity" name="requestedQuantity" required>
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason for Request</label>
                        <textarea class="form-control" id="reason" name="reason" required></textarea>
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
	function showRequestModal(itemName) {
		$('#itemName').val(itemName);
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



