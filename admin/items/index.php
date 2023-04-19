<?php if($_settings->chk_flashdata('success')): ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
	</script>
<?php endif;?>


<style>
	.item-img{
		width:1em;
		height:1em;
		object-fit:;
		object-position:center center;
	}
</style>


<div class="card card-outline rounded-0 card-dark">
	<div class="card-header">
		<h3 class="card-title">Stock Adjustment</h3>
		<div class="card-tools">
			<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> New Item</a>
			<a class="btn btn-flat btn-success" href="./?page=pos-request"><span class="fas fa-eye"></span> Sales Request</a>
			<a class="btn btn-flat btn-success" href="./?page=stockStatus"><span class="fas fa-eye"></span> Purchasing Request</a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered text-center" id="list">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="10%">
					<col width="20%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						
						<th>Item</th>
						<th>Item Type</th>
						<th>Description</th>
						<th>Current Stock</th>
						<th>Reorder Level</th>
						<th>Last Update</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					$i = 1;
					$qry = pg_query($conn, "SELECT i.*, c.name as category, i.item_type, 
									(COALESCE((SELECT SUM(quantity) FROM wh_stockin_list WHERE item_id = i.id), 0) 
									- COALESCE((SELECT SUM(quantity) FROM wh_stockout_list WHERE item_id = i.id), 0)
									+ COALESCE((SELECT SUM(quantity) FROM wh_stockin_list_deleted WHERE item_id = i.id), 0)
									- COALESCE((SELECT SUM(quantity) FROM wh_waste_list WHERE item_id = i.id), 0)) AS available, 
									COALESCE((SELECT date_updated FROM wh_stockin_list WHERE item_id = i.id ORDER BY date_updated DESC LIMIT 1), 
											(SELECT date_updated FROM wh_stockin_list_deleted WHERE item_id = i.id ORDER BY date_updated DESC LIMIT 1)) AS last_updated,
									(SELECT SUM(quantity) FROM wh_stockin_list WHERE item_id = i.id) as total_stock_in_quantity
								FROM wh_item_list i 
								INNER JOIN wh_category_list c ON i.category_id = c.id 
								WHERE i.delete_flag = 0 
								ORDER BY i.date_updated DESC");
										
					while($row = pg_fetch_assoc($qry)):
						$name = $row['name'];
						$available_quantity = (int)$row['available'];

				?>
					<tr>
						<td class="text-center"><?php echo $i++; ?></td>
						
						<td>
							<div style="line-height:1em">
								<div><?= $row['name'] ?> [<?= $row['unit'] ?>]</div>
								<div><small class="text-muted"><?= $row['category'] ?></small></div>
							</div>
						</td>
						<td><?= $row['item_type'] ?></td>
						<td><p class="mb-0 truncate-1"><?= strip_tags(htmlspecialchars_decode($row['description'])) ?></p></td>
						<td><?= (int)$row['available'] ?></td>
						<td></td>
						<td><?php echo !empty($row['last_updated']) ? date("Y-m-d H:i",strtotime($row['last_updated'])) : ''; ?></td>
						<td class="text-center">
							<?php if($row['status'] == 1): ?>
								<span class="badge badge-success px-3 rounded-pill">Available</span>
							<?php else: ?>
								<span class="badge badge-danger px-3 rounded-pill">Unavailable</span>
							<?php endif; ?>
						</td>
						<td align="center">
								<button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
									Action
								<span class="sr-only">Toggle Dropdown</span>
								</button>
								<div class="dropdown-menu" role="menu">
								<a class="dropdown-item view-data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item edit-data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
								</div>
						</td>
					</tr>
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<!-- <div class="card card-outline rounded-0 card-dark">
	<div class="card-header">
		<h3 class="card-title">POS Inventory</h3>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered text-center" id="list">
				<colgroup>
					<col width="10%">
					<col width="20%">
					<col width="15%">
					<col width="15%">
					<col width="10%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Product</th>
						<th>Code</th>
						<th>Status</th>
						<th>Current Stock</th>
						<th>Max Stock</th>
					</tr>
				</thead>
				<tbody>
				<?php 
						$result = pg_query($conn, "SELECT * FROM products_sales");
						$counter = 1;
						while ($row = pg_fetch_assoc($result)) {
					?>
						<tr>
							<td><?php echo $counter++; ?></td>
							<td>
								<div style="line-height: 1em">
									<div><?php echo $row['prod_name']; ?></div>
									<div class="small"><i><?php echo $row['category']; ?></i></div>
								</div>
							</td>
							<td><?php echo $row['code']; ?></td>
							<td><?php echo $row['status']; ?></td>
							<td><?php echo $row['current_stock']; ?></td>
							<td><?php echo $row['max_stock']; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div> -->


<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Item permanently?","delete_item",[$(this).attr('data-id')])
		})
		$('#create_new').click(function(){
			uni_modal("<i class='far fa-plus-square'></i> Add New Item ","items/manage_item.php")
		})
		$('.edit-data').click(function(){
			uni_modal("<i class='fa fa-edit'></i> Add New Item ","items/manage_item.php?id="+$(this).attr('data-id'))
		})
		$('.view-data').click(function(){
			uni_modal("<i class='fa fa-th-list'></i> Item Details ","items/view_item.php?id="+$(this).attr('data-id'))
		})
		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [5] }
			],
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})
	
	function delete_item($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_item",
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