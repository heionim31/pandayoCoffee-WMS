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


<div class="card card-outline rounded-5">
	<div class="card-header">
		<h3 class="card-title mt-2 font-weight-bold">ITEMS LIST</h3>
		<div class="card-tools">
			<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> New Item</a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered text-center" id="list">
				<colgroup>
					<col width="5%">
					<col width="20%">
					<col width="10%">
					<col width="15%">
					<col width="10%">
					<col width="10%">
					<col width="15%">
					<col width="10%">
					<col width="5%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Item</th>
						<th>Unit</th>
						<th>Category</th>
						<th>Type</th>
						<th>Current Stock</th>
						<th>Last Update</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					$i = 1;
					$qry = pg_query($conn, "SELECT i.*, c.name as category, u.name as unit_name, i.item_type, 
							(COALESCE((SELECT SUM(quantity) FROM wh_stockin_list WHERE item_id = i.id), 0) 
							- COALESCE((SELECT SUM(quantity) FROM wh_stockout_list WHERE item_id = i.id), 0)
							+ COALESCE((SELECT SUM(quantity) FROM wh_stockin_list_deleted WHERE item_id = i.id), 0)
							- COALESCE((SELECT SUM(quantity) FROM wh_waste_list WHERE item_id = i.id), 0)) AS available, 
							COALESCE((SELECT date_updated FROM wh_stockin_list WHERE item_id = i.id ORDER BY date_updated DESC LIMIT 1), 
										(SELECT date_updated FROM wh_stockin_list_deleted WHERE item_id = i.id ORDER BY date_updated DESC LIMIT 1)) AS last_updated,
							(SELECT SUM(quantity) FROM wh_stockin_list WHERE item_id = i.id) as total_stock_in_quantity
						FROM wh_item_list i 
						INNER JOIN wh_category_list c ON i.category_id = c.id 
						LEFT JOIN wh_unit_list u ON i.unit = u.id
						WHERE i.delete_flag = 0 
						ORDER BY i.date_updated DESC");

										
					while($row = pg_fetch_assoc($qry)):
						$name = $row['name'];
						$available_quantity = (int)$row['available'];
				?>
					<tr>
						<td class="text-center"><?php echo $i++; ?></td>
						<td><?= $row['name'] ?></td>
						<td><?= $row['unit_name'] ?></td>

						<td><?= $row['category'] ?></td>
						<td><?= $row['item_type'] ?></td>
						<td><?= (int)$row['available'] ?></td>
						<td><?php echo !empty($row['last_updated']) ? date("Y-m-d H:i",strtotime($row['last_updated'])) : '----------'; ?></td>
						<td class="text-center">
							<?php if($row['status'] == 1): ?>
								<span class="badge badge-success px-3 rounded-pill">Active</span>
							<?php else: ?>
								<span class="badge badge-danger px-3 rounded-pill">Inactive</span>
							<?php endif; ?>
						</td>
						<td align="center" class="dropdown">
							<button class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-caret-down"></i>
							</button>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<a class="dropdown-item view-data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark text-md"></span> View</a>
								<a class="dropdown-item edit-data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary text-md"></span> Edit</a>
								<?php if($available_quantity == 0): ?>
								<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger text-md"></span> Delete</a>
								<?php else: ?>
									<a class="dropdown-item" href="javascript:void(0)" onclick="event.preventDefault(); Swal.fire({title: 'Cannot delete item', text: 'This item cannot be deleted because there are still stock on it', icon: 'warning', confirmButtonText: 'Ok'});"><span class="fa fa-trash text-muted"></span> Delete</a>
								<?php endif; ?>
								<a class="dropdown-item" href="./?page=stocks/view_stock&id=<?php echo $row['id'] ?>"><span class="fa fa-history text-success text-md"></span> History</a>
							</div>
						</td>
					</tr>
				<?php endwhile; ?>
				</tbody>

			</table>
		</div>
	</div>
</div>



<script>
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip(); 
	});
</script>

<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Item permanently?","delete_item",[$(this).attr('data-id')])
		})
		$('#create_new').click(function(){
			uni_modal("<i class='far fa-plus-square'></i> Add New Item ","items/manage_item.php")
		})
		$('.edit-data').click(function(){
			uni_modal("<i class='fa fa-edit'></i> Edit Item ","items/manage_item.php?id="+$(this).attr('data-id'))
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