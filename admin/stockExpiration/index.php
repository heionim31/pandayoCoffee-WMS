<div class="card card-outline rounded-0 card-dark">
    <div class="card-header">
        <h3 class="card-title">Stock Expiration</h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <table class="table table-hover table-striped table-bordered text-center" id="list">
                <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="5%">
                    <col width="5%">
                    <col width="10%">
                    <col width="15%">
                    <col width="10%">
                    <col width="30%">
                    <col width="10%">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Unit</th>
                        <th>Current Stock</th>
                        <th>Manufactured Date</th>
                        <th>Expiration Date</th>
                        <th>Expiry Status</th>
                        <th>Message</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Get the stock items that have expired, will expire today, or will expire tomorrow
                        $today = date('Y-m-d');
                        $tomorrow = date('Y-m-d', strtotime('+1 day'));
                        $stock_items = $conn->query("SELECT s.*, i.name, i.unit FROM stockin_list s INNER JOIN item_list i ON s.item_id = i.id WHERE (s.expire_date = '$today' OR s.expire_date = '$tomorrow' OR s.expire_date < '$today') AND s.expire_date != '0000-00-00'");

                        // Initialize the ID variable
                        $id = 1;

                        foreach ($stock_items as $index => $item):
                            // Determine the expiry status and message for each item
                            $expiry_status = '';
                            $expiry_class = '';
                            $message = '';
                            
                            if ($item['expire_date'] == $today) {
                                $expiry_status = "Expired Today";
                                $expiry_class = 'bg-danger';
                                $message = intval($item['quantity']) . " {$item['name']} is Expired Today";
                            } else if ($item['expire_date'] == date('Y-m-d', strtotime('+1 day'))) {
                                $expiry_status = "Expires Tomorrow";
                                $expiry_class = 'bg-warning';
                                $message = intval($item['quantity']) . " {$item['name']} is Expiring Tomorrow";
                            } else if ($item['expire_date'] < $today) {
                                $days_until_expiry = floor((strtotime($item['expire_date']) - strtotime($today)) / (60 * 60 * 24));
                                $expiry_class = 'bg-danger';
                                $expiry_status = ($days_until_expiry == -1) ? 'Expired 1 day ago' : "Expired " . abs($days_until_expiry) . " days ago";
                                $message = intval($item['quantity']) . " {$item['name']} is $expiry_status";
                            } else if ($item['expire_date'] > $today) {
                                $days_until_expiry = floor((strtotime($item['expire_date']) - strtotime($today)) / (60 * 60 * 24));
                                if ($days_until_expiry == 1) {
                                    $expiry_status = "Expiring in 1 day";
                                } else {
                                    $expiry_status = "Expiring in $days_until_expiry days";
                                }
                                $expiry_class = 'bg-warning';
                                $message = intval($item['quantity']) . " {$item['name']} is expiring $expiry_status";
                            }

                            // Display the item if it has expired or will expire today or tomorrow
                            if ($expiry_status)?>
                                <tr>
                                    <td><?= $id ?></td>
                                    <td><?= $item['name'] ?></td>
                                    <td><?= $item['unit'] ?></td>
                                    <td><?= (int)$item['quantity'] ?></td>
                                    <td><?= date('m-d-Y', strtotime($item['date'])) ?></td>
                                    <td><?= date('m-d-Y', strtotime($item['expire_date'])) ?></td>
                                    <td class="<?= $expiry_class ?>"><?= $expiry_status ?></td>
                                    <td><?= $message ?></td>
                                    <td>
                                    <?php if ($expiry_status !== "Expires Tomorrow"): ?>
                                        <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#exampleModal" data-item-name="<?= $item['name'] ?>" data-expiration-date="<?= $item['expire_date'] ?>" data-quantity="<?= intval($item['quantity']) ?>">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                    </td>
                                </tr>
                            <?php $id++; ?>
                         
                        <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- MODAL FORM -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Do you want to throw away this item?</h5>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="item-name" class="col-form-label">Item Name:</label>
            <input type="text" class="form-control" id="item-name" disabled>
          </div>
          <div class="form-group">
            <label for="quantity" class="col-form-label">Quantity:</label>
            <input type="text" class="form-control" id="quantity" disabled>
          </div>
          <div class="form-group">
            <label for="expiration-date" class="col-form-label">Expiration Date:</label>
            <input type="text" class="form-control" id="expiration-date" disabled>
          </div>
          <div class="form-group">
            <label for="remarks" class="col-form-label">Remarks:</label>
            <textarea class="form-control" id="remarks"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger">Waste</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL FORM SCRIPT -->
<script>
    $('#exampleModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var itemName = button.data('item-name')
        var expirationDate = button.data('expiration-date')
        var quantity = button.data('quantity')
        var modal = $(this)
        modal.find('.modal-body #item-name').val(itemName)
        modal.find('.modal-body #expiration-date').val(expirationDate)
        modal.find('.modal-body #quantity').val(quantity)
    })
</script>




<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this enroll permanently?","delete_enroll",[$(this).attr('data-id')])
		})
		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [5] }
			],
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})
	function delete_enroll($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_enroll",
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



