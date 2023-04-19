<!-- VIEW PRODUCT INGREDIENTS -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-dialog-centered" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h4 class="modal-title" id="myModalLabel">Modal Title</h4>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      		</div>
      		<div class="modal-body">
        		<p>Modal body text goes here.</p>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      		</div>
    	</div>
  	</div>
</div>

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
					<col width="15%">
					<col width="10%">
					<col width="10%">
					<col width="15%">
					<col width="20%">
					<col width="25%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Product</th>
						<th>Date Request</th>
						<th>Status</th>
						<th>Request ID</th>
						<th>Request By</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
                    <?php 
                        $result = pg_query($conn, "SELECT * FROM Ingredient_request WHERE status <> 'Approved' ORDER by date_request ASC");
                        $counter = 1;
                        while ($row = pg_fetch_assoc($result)) {
                            $request_id = $row['request_id'];
                            $product_name = $row['product_name'];
                            $code = $row['code'];
                            $date_request = $row['date_request'];
                            $status = $row['status'];
                            $request_by = $row['request_by'];
                            $date_approved = date('Y-m-d H:i:s');

                            if(isset($_POST['approve']) && $_POST['request_id'] == $request_id){
                                $status = $_POST['status'];
                                $update_query = "UPDATE Ingredient_request SET status='$status', date_approve='$date_approved' WHERE request_id='$request_id'";
                                $update_result = pg_query($conn, $update_query);
                                if($update_result){
                                    $message = "Request approved.";
                                    $message_type = "success";
                                }else{
                                    $message = "Error approving request.";
                                    $message_type = "danger";
                                }
                        }
                    ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td>
                                <div style="line-height: 1em">
                                    <div><?php echo $product_name; ?></div>
                                    <div class="small"><i><?php echo $code; ?></i></div>
                                </div>
                            </td>
                            <td><?php echo $date_request; ?></td>
                            <td><?php echo $status; ?></td>
                            <td><?php echo $request_id; ?></td>
                            <td><?php echo $request_by; ?></td>
                            <td class="d-flex justify-content-center">
                                <button class="btn btn-flat btn-sm bg-gradient-light border" data-toggle="modal" data-target="#myModal" data-product-name="<?php echo $product_name; ?>" data-code="<?php echo $code; ?>">
                                    <span class="fa fa-eye text-dark"></span> View
                                </button>
                                <form method="POST">
                                    <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                                    <input type="hidden" name="status" value="Testing">
                                    <button type="submit" class="btn btn-flat btn-sm btn-dark bg-gradient-success border" name="approve">
                                        <span class="fa fa-check text-light"></span> Approve
                                    </button>
                                </form>
                                <button class="btn btn-flat btn-sm btn-light bg-danger border"><span class="fa fa-times text-light"></span> Decline</button>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if(isset($message)): ?>
                        <div class="alert alert-<?php echo $message_type ?> alert-dismissible fade show" role="alert">
                            <?php echo $message ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif;?>
                </tbody>
			</table>
		</div>
	</div>
</div>


<script>
	// VIEW PRODUCT INGREDIENTS
	$('#myModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var productName = button.data('product-name');
        var code = button.data('code');
        var modal = $(this);
        modal.find('.modal-title').text(productName);
        modal.find('.modal-body p').text(code);
    });

	// TABLE
	$(document).ready(function(){
		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [5] }
			],
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')

		$('#pos_request').click(function(){
			uni_modal("<i class='far fa-plus-square'></i> Add New Item ","stocks/manage_stockin.php")
		})
	})
</script>