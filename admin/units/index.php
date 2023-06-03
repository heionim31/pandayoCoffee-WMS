<?php if($_settings->chk_flashdata('success')): ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
	</script>
<?php endif;?>


<style>
	.category-img{
		width:3em;
		height:3em;
		object-fit:cover;
		object-position:center center;
	}
</style>


<div class="card card-outline rounded-5">
	<div class="card-header mt-2">
		<h3 class="card-title mt-2 font-weight-bold">LIST OF UNITS</h3>
		<div class="card-tools">
			<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> New Unit</a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered text-center" id="list">
				<colgroup>
					<col width="5%">
					<col width="20%">
					<col width="5%">
					<col width="40%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Abbreviation</th>
						<th>Description</th>
						<th>Total Registered</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$qry = pg_query($conn, "SELECT * from wh_unit_list ORDER BY date_updated DESC");
						while($row = pg_fetch_assoc($qry)):
							$id = $row['id'];
							$item_count_query = "SELECT COUNT(*) FROM wh_item_list WHERE unit = '$id'";
							$item_count_result = pg_query($conn, $item_count_query);
							$item_count = pg_fetch_result($item_count_result, 0);
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class=""><?= $row['name'] ?></td>
							<td class=""><?= $row['abbreviation'] ?></td>
							<td class=""><p class="mb-0 truncate-1"><?= strip_tags(htmlspecialchars_decode($row['description'])) ?></p></td>
							<td><?php echo $item_count; ?></td>

							<td class="text-center">
                                <?php if($row['status'] == 1): ?>
                                    <span class="badge badge-success px-3 rounded-pill">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger px-3 rounded-pill">Inactive</span>
                                <?php endif; ?>
                            </td>
							<td align="center">
								 <button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
									<i class="fas fa-caret-down"></i>
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item view-data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item edit-data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
				                    <div class="dropdown-divider"></div>
									<?php if($item_count == 0): ?>
				                    	<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
										<?php else: ?>
										<a class="dropdown-item" href="javascript:void(0)" onclick="event.preventDefault(); Swal.fire({title: 'Cannot delete category', text: 'This category cannot be deleted because there are item/s registered to it.', icon: 'warning', confirmButtonText: 'Ok'});"><span class="fa fa-trash text-muted"></span> Delete</a>
									<?php endif; ?>
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
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Unit permanently?","delete_unit",[$(this).attr('data-id')])
		})
		$('#create_new').click(function(){
			uni_modal("<i class='far fa-plus-square'></i> Add New Units ","units/manage_unit.php")
		})
		$('.edit-data').click(function(){
			uni_modal("<i class='fa fa-edit'></i> Edit Units ","units/manage_unit.php?id="+$(this).attr('data-id'))
		})
		$('.view-data').click(function(){
			uni_modal("<i class='fa fa-th-list'></i> Unit Details ","units/view_unit.php?id="+$(this).attr('data-id'))
		})
		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [5] }
			],
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})
	function delete_unit($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_unit",
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