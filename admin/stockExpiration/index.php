<div class="card card-outline rounded-5">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">INGREDIENT EXPIRATION LIST</h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <table class="table table-hover table-striped table-bordered text-center" id="list">
                <colgroup>
                    <col width="5%">
                    <col width="20%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="15%">
                    <col width="30%">
                    <col width="10%">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="d-none">Item ID</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Manufactured</th>
                        <th>Expiration</th>
                        <th>Expiry Status</th>
                        <th>Message</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Insert and update data if form is submitted
                        if (isset($_POST['submit'])) {
                            $item_id = $_POST['item_id'];
                            $quantity = $_POST['quantity'];
                            $expiry_date = $_POST['expiry_date'];
                            $remarks = $_POST['remarks'];
                            $date = date('Y-m-d');
                            $id = $_POST['id'];
                            
                            // Insert data in waste_list table
                            $sql = "INSERT INTO wh_waste_list (id, item_id, date, quantity, remarks, date_created, expire_date, date_updated)
                            SELECT id, item_id, date, quantity, '$remarks', date_created, expire_date, date_updated FROM wh_stockin_list 
                            WHERE id = $id";
                            $result = pg_query($conn, $sql);

                            // Copy the deleted row to stockin list deleted table
                            $sql = "INSERT INTO wh_stockin_list_deleted (id, item_id, date, quantity, remarks, date_created, expire_date, date_updated)
                            SELECT id, item_id, date, quantity, remarks, date_created, expire_date, date_updated FROM wh_stockin_list 
                            WHERE id = $id";
                            $result = pg_query($conn, $sql);

                            if ($result) {
                                // Delete data from stockin list table
                                $sql = "DELETE FROM wh_stockin_list WHERE id = $id";
                                $result = pg_query($conn, $sql);
                                
                                if ($result) {
                                    $notification_updated = true;
                                } else {
                                    $notification_error = pg_last_error($conn);
                                }
                            } else {
                                $notification_error = pg_last_error($conn);
                            }
                            
                            
                            if (isset($notification_updated) && $notification_updated) {
                                echo '<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="notificationModalLabel">Item Added to Waste</h5>
                                              </div>
                                              <div class="modal-body">
                                                <p>The selected item has been successfully added to the waste.</p>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">Okay</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>';
                                echo '<script type="text/javascript">
                                        $(document).ready(function() {
                                            $("#notificationModal").modal("show");
                                        });
                                      </script>';
                            } elseif (isset($notification_error)) {
                                echo '<div class="alert alert-danger" role="alert">Error: ' . $notification_error . '</div>';
                            }
                            
                        }

                        // Get the stock items that have expired, will expire today, or will expire tomorrow
                        $today = date('Y-m-d');
                        $tomorrow = date('Y-m-d', strtotime('+1 day'));
                        $stock_items = pg_query($conn, "SELECT s.*, i.name, u.abbreviation, i.id AS item_id, c.name AS category_name 
                                FROM wh_stockin_list s 
                                INNER JOIN wh_item_list i ON s.item_id = i.id 
                                INNER JOIN wh_category_list c ON i.category_id = c.id
                                LEFT JOIN wh_unit_list u ON i.unit = u.id
                                WHERE (s.expire_date = '$today' OR s.expire_date = '$tomorrow' OR s.expire_date < '$today') 
                                AND (s.expire_date IS NOT NULL) 
                                AND (s.expire_date != '0001-01-01')");

                        $stock_items = pg_fetch_all($stock_items);

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
                                $expiry_class = 'bg-secondary';
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
                                    <td class="d-none"><?= $item['item_id'] ?></td>
                                    <td class="">
                                        <div style="line-height:1em">
                                            <div><?= $item['name'] ?> (<?= $item['abbreviation'] ?? $item['unit'] ?>)</div>
                                            <div class="small"><i><?= $item['category_name'] ?></i></div>
                                        </div>
                                    </td>
                                    <td><?= (int)$item['quantity'] ?></td>
                                    <td><?= date("Y-m-d",strtotime($item['date'])) ?></td>
                                    <td><?= date("Y-m-d",strtotime($item['expire_date'])) ?></td>
                                    <td class="<?= $expiry_class ?>"><?= $expiry_status ?></td>
                                    <td class="font-italic"><?= $message ?></td>
                                    <td>
                                    <?php if ($expiry_status !== "Expires Tomorrow"): ?>
                                        <form method="post">
                                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                            <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
                                            <input type="hidden" name="quantity" value="<?= intval($item['quantity']) ?>">
                                            <input type="hidden" name="expiry_date" value="<?= $item['expire_date'] ?>">
                                            <!-- <input type="text" name="remarks" placeholder="Enter remarks here"> -->
                                            <!-- <button type="submit" name="submit" class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </button> -->
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal<?= $id ?>">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            
                                            <!-- MODAL FORM FOR EXPIRATION -->
                                            <div class="modal fade" id="myModal<?= $id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Do you want to add this to waste?</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post">
                                                        <div class="form-group row">
                                                            <label for="item-name" class="col-sm-4 col-form-label">Ingredient Name:</label>
                                                            <div class="col-sm-8">
                                                            <input class="form-control" id="item-name" name="item_name" value="<?= $item['name'] ?>" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="quantity" class="col-sm-4 col-form-label">Expired Quantity:</label>
                                                            <div class="col-sm-8">
                                                            <input class="form-control" id="quantity" value="<?= (int)$item['quantity'] ?>" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="manunufacture-date" class="col-sm-4 col-form-label">Manufactured Date:</label>
                                                            <div class="col-sm-8">
                                                            <input class="form-control" id="manunufacture-date" value="<?= date('m-d-Y', strtotime($item['date'])) ?>" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="expiration-date" class="col-sm-4 col-form-label">Expiration Date:</label>
                                                            <div class="col-sm-8">
                                                            <input class="form-control" id="expiration-date" value="<?= date('m-d-Y', strtotime($item['expire_date'])) ?>" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="remarks" class="col-sm-4 col-form-label">Remarks:</label>
                                                            <div class="col-sm-8">
                                                            <input type="text" class="form-control" id="remarks" name="remarks" placeholder="Enter remarks here">
                                                            </div>
                                                        </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="submit" class="btn btn-danger">Waste</button>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

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



