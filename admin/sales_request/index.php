<!-- POS REQUEST -->
<div class="card card-outline rounded-0 card-dark">
	<div class="card-header">
		<h3 class="card-title">Stock POS Requests</h3>
        <div class="card-tools">
			<a class="btn btn-flat btn-primary" href=""><span class="fas fa-history"></span> History </a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered text-center" id="list">
				<colgroup>
					<col width="5%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="30%">
					<col width="20%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Product</th>
						<th>Date Request</th>
						<th>Status</th>
						<th>Request ID</th>
						<th>Request By</th>
						<th>Request Notes</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
                    <?php 
                        $result = pg_query($conn, "SELECT * FROM Ingredient_request ORDER by date_request ASC");
                        $counter = 1;
                        while ($row = pg_fetch_assoc($result)) {
                            $request_id = $row['request_id'];
                            $ingredient_name = $row['ingredient_name'];
                            $date_request = $row['date_request'];
                            $status = $row['status'];
                            $request_by = $row['request_by'];
                            $request_notes = $row['notes'];
                            $date_today = date('Y-m-d H:i:s');

                            if(isset($_POST['approve']) && $_POST['request_id'] == $request_id){
                                $status = $_POST['status'];
                                $update_query = "UPDATE Ingredient_request SET status='$status', date_approved='$date_today' WHERE request_id='$request_id'";
                                $update_result = pg_query($conn, $update_query);
                            }
                            if(isset($_POST['decline']) && $_POST['request_id'] == $request_id){
                                $status = $_POST['status'];
                                $update_query = "UPDATE Ingredient_request SET status='$status', date_declined='$date_today' WHERE request_id='$request_id'";
                                $update_result = pg_query($conn, $update_query);
                            }
                    ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo $ingredient_name; ?></td>
                            <td><?php echo $date_request; ?></td>
                            <td><?php echo $status; ?></td>
                            <td><?php echo $request_id; ?></td>
                            <td><?php echo $request_by; ?></td>
                            <td><?php echo $request_notes; ?></td>
                            <td class="d-flex justify-content-center">
                                <form method="POST">
                                    <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                                    <input type="hidden" name="status" value="Approved">
                                    <button type="submit" class="btn btn-flat btn-sm btn-dark bg-gradient-success border" name="approve">
                                        <span class="fa fa-check text-light"></span> Approve
                                    </button>
                                </form>
                                <form method="POST">
                                    <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                                    <input type="hidden" name="status" value="Declined">
                                    <button type="submit" class="btn btn-flat btn-sm btn-light bg-danger border" name="decline">
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

<!-- CONFIRMATION APPROVE MODAL -->
<!-- <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Approve Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="notes"> Request Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2" style="resize:none" readonly></textarea>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ingredient_name">Ingredient Name</label>
                            <input type="text" class="form-control" id="ingredient_name" name="ingredient_name" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="request_unit">Unit</label>
                            <input type="text" class="form-control" id="request_unit" name="request_unit" required/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="request_category">Category</label>
                            <input type="text" class="form-control" id="request_category" name="request_category" required/>
                        </div>
                    </div>
                </div>
				<div class="form-group">
                    <label for="request_quantity">Request Quantity</label>
                    <input type="number" class="form-control" id="request_quantity" name="request_quantity" required/>
                </div>
				<div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea class="form-control" id="remarks" name="remarks"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat btn-sm btn-dark bg-gradient-secondary border" data-dismiss="modal">Close</button>
                <form method="POST">
                    <button type="submit" class="btn btn-flat btn-sm btn-dark bg-gradient-success border" name="approve"> Confirm
                    </button>
                </form>
            </div>
        </div>
    </div>
</div> -->


<!-- Modal for decline confirmation -->
<!-- <div class="modal fade" id="declineModal<?php echo $request_id; ?>" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="declineModalLabel">Decline Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <p>Are you sure you want to decline this request?</p>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Enter reason for decline" id="reason<?php echo $request_id; ?>" name="reason" required></textarea>
                        <label for="reason">Reason for Decline</label>
                    </div>
                    <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                    <input type="hidden" name="status" value="Declined">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" name="decline">Decline</button>
                </div>
            </form>
        </div>
    </div>
</div> -->


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