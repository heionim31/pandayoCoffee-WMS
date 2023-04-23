<!-- POS REQUEST -->
<div class="card card-outline rounded-0 card-dark">
	<div class="card-header">
		<h3 class="card-title">Stock POS Requests</h3>
        <div class="card-tools">
			<a class="btn btn-flat btn-primary" href=""><span class="fas fa-history"></span> Sales Request History </a>
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
                            $date_approved = date('Y-m-d H:i:s');

                            if(isset($_POST['approve']) && $_POST['request_id'] == $request_id){
                                $status = $_POST['status'];
                                $update_query = "UPDATE Ingredient_request SET status='$status', date_approve='$date_approved' WHERE request_id='$request_id'";
                                $update_result = pg_query($conn, $update_query);
                        }
                    ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo $ingredient_name; ?>
                            <td><?php echo $date_request; ?></td>
                            <td><?php echo $status; ?></td>
                            <td><?php echo $request_id; ?></td>
                            <td><?php echo $request_by; ?></td>
                            <td><?php echo $request_notes; ?></td>
                            <td class="d-flex justify-content-center">
								<button type="button" class="btn btn-flat btn-sm btn-dark bg-gradient-success border" name="approve" data-toggle="modal" data-target="#approveModal">
									<span class="fa fa-check text-light"></span> Approve
								</button>
                                <button class="btn btn-flat btn-sm btn-light bg-danger border"><span class="fa fa-times text-light"></span> Decline</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
			</table>
		</div>
	</div>
</div>

<!-- Add this modal code to your HTML file -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
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
                    <label for="productName">Product Name</label>
                    <input type="text" class="form-control" id="productName" name="productName" readonly>
                </div>
                <div class="form-group">
                    <label for="notes"> Request Notes</label>
                    <input type="text" class="form-control" id="notes" name="notes" readonly>
                </div>
				<div class="form-group">
                    <label for="request_quantity">Request Quantity</label>
                    <input type="number" class="form-control" id="request_quantity" name="request_quantity" />
                </div>
				<div class="form-group">
                    <label for="request_unit">Unit</label>
                    <input type="text" class="form-control" id="request_unit" name="request_unit" />
                </div>
				<div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea class="form-control" id="remarks" name="remarks"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat btn-sm btn-dark bg-gradient-secondary border" data-dismiss="modal">Close</button>
                <form method="POST">
                    <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                    <input type="hidden" name="status" value="test">
                    <button type="submit" class="btn btn-flat btn-sm btn-dark bg-gradient-success border" name="approve"> Confirm
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#approveModal').on('show.bs.modal', function(event) {
            let button = $(event.relatedTarget); // Button that triggered the modal
            let productName = button.closest('tr').find('td:nth-child(2)').text(); // Get the product name from the row
            let notes = button.closest('tr').find('td:nth-child(7)').text(); // Get the notes from the row

            let modal = $(this);
            modal.find('#productName').val(productName); // Set the product name in the modal
            modal.find('#notes').val(notes); // Set the notes in the modal

            modal.find('#approveBtn').click(function() {
                // Here you can add the code to submit the approve form
            });
        });
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