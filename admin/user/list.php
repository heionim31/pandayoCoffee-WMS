
<?php if($_settings->chk_flashdata('success')): ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
	</script>
<?php endif;?>


<style>
    .user-avatar{
        width:3rem;
        height:3rem;
        object-fit:scale-down;
        object-position:center center;
    }
</style>


<div class="card card-outline rounded-5">
	<div class="card-header">
		<h3 class="card-title font-weight-bold">LIST OF USERS</h3>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered text-center" id="list">
				<thead>
					<tr>
						<th>#</th>
						<th>Avatar</th>
						<th>Name</th>
						<th>Username</th>
						<th>Role</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$qry = pg_query($conn, "SELECT * from users WHERE role IN ('warehouse_manager', 'warehouse_staff') ORDER BY id ASC");
						while($row = pg_fetch_assoc($qry)):
					?>
						<tr>
							<td class="text-center align-middle"><?php echo $i++; ?></td>
							<td class="text-center align-middle">
								<?php 
									$imgUrl = $row['imgurl'];
									$userAvatar = validate_image($imgUrl) ? $imgUrl : DEFAULT_AVATAR;
								?>
								<img src="<?= $userAvatar ?>" alt="" class="img-thumbnail rounded-circle user-avatar">
							</td>
							<td class="align-middle"><?php echo $row['fullname'] ?></td>
							<td class="align-middle"><?php echo $row['username'] ?></td>
							<td class="text-center align-middle">
								<?php if($row['role'] == 'warehouse_manager'): ?>
									Warehouse Manager
								<?php elseif($row['role'] == 'warehouse_staff'): ?>
									Warehouse Staff
								<?php else: ?>
									N/A
								<?php endif; ?>
							</td>
							<td class="align-middle">
								<a class="btn btn-flat p-1 btn-default btn-sm" href="./?page=user/manage_user&id=<?= $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View Info</a>
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
		$('.table').dataTable({
			columnDefs: [
				{ orderable: false, targets: [6] }
			],
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})
</script>