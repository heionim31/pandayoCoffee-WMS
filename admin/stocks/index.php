<?php if($_settings->chk_flashdata('success')): ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
	</script>
<?php endif;?>


<style>
	.enroll-logo{
		width:3em;
		height:3em;
		object-fit:cover;
		object-position:center center;
	}
</style>


<div class="card card-outline rounded-0 card-dark">
	<div class="card-header">
		<h3 class="card-title">Stock Purchasing Requests</h3>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered text-center" id="list">
				<colgroup>
					<col width="5%">
					<col width="20%">
					<col width="15%">
					<col width="15%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Item</th>
						<th>Unit</th>
						<th>Current Stock</th>
						<th>Reorder Level</th>
						<th>Last Updated</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$qry = pg_query($conn, "SELECT i.*, c.name AS category, 
											(COALESCE((SELECT SUM(quantity) FROM wh_stockin_list WHERE item_id = i.id), 0) 
											- COALESCE((SELECT SUM(quantity) FROM wh_stockout_list WHERE item_id = i.id), 0)
											+ COALESCE((SELECT SUM(quantity) FROM wh_stockin_list_deleted WHERE item_id = i.id), 0)
											- COALESCE((SELECT SUM(quantity) FROM wh_waste_list WHERE item_id = i.id), 0)) AS available, 
											COALESCE((SELECT date_updated FROM wh_stockin_list WHERE item_id = i.id ORDER BY date_updated DESC LIMIT 1), 
													(SELECT date_updated FROM wh_stockin_list_deleted WHERE item_id = i.id ORDER BY date_updated DESC LIMIT 1)) AS last_updated
										FROM wh_item_list i 
										INNER JOIN wh_category_list c ON i.category_id = c.id 
										WHERE i.delete_flag = 0 
										ORDER BY i.date_updated DESC");
						
						while($row = pg_fetch_assoc($qry)):
							$name = $row['name'];
							$available_quantity = (int)$row['available'];
					?>
						<tr>
							<td><?php echo $i++; ?></td>
							<td>
								<div style="line-height:1em">
									<div><?= $row['name'] ?></div>
									<div class="small"><i><?= $row['category'] ?></i></div>
								</div>
							</td>
							<td><?= $row['unit'] ?></td>
							<td><?= (int)$row['available'] ?></td>
							<td></td>
							<td><?php echo !empty($row['last_updated']) ? date("Y-m-d H:i",strtotime($row['last_updated'])) : ''; ?></td>
							<td>
								<a class="btn btn-flat btn-sm btn-light bg-gradient-light border" href="./?page=stocks/view_stock&id=<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Adjust</a>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<!-- POS REQUEST -->
<div class="card card-outline rounded-0 card-dark">
	<div class="card-header">
		<h3 class="card-title">Stock POS Requests</h3>
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
						$result = pg_query($conn, "SELECT * FROM Ingredient_request");
						$counter = 1;
						while ($row = pg_fetch_assoc($result)) {
					?>
						<tr>
							<td><?php echo $counter++; ?></td>
							<td>
								<div style="line-height: 1em">
									<div><?php echo $row['product_name']; ?></div>
									<div class="small"><i><?php echo $row['code']; ?></i></div>
								</div>
							</td>
							<td><?php echo $row['date_request']; ?></td>
							<td><?php echo $row['status']; ?></td>
							<td><?php echo $row['request_id']; ?></td>
							<td><?php echo $row['request_by']; ?></td>
							<td>
								<button class="btn btn-flat btn-sm bg-gradient-light border"><span class="fa fa-eye text-dark"></span> View</button>
								<button class="btn btn-flat btn-sm btn-dark bg-gradient-success border"><span class="fa fa-check text-light"></span> Approve</button>
								<button class="btn btn-flat btn-sm btn-light bg-danger border <?php echo ($row['request_by'] == 'AUTOMATIC SYSTEM') ? 'disabled' : 'bg-gradient-danger'; ?>"><span class="fa fa-times text-light"></span> Decline</button>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<script>
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