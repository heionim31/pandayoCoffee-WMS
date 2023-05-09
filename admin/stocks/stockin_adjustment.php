<?php
    if(isset($_GET['id']) && $_GET['id'] > 0){
        $qry = pg_query($conn, "SELECT i.*, c.name as category, (COALESCE((SELECT SUM(quantity) FROM wh_stockin_list where item_id = i.id),0) - COALESCE((SELECT SUM(quantity) FROM wh_stockout_list where item_id = i.id),0)) as available from wh_item_list i inner join wh_category_list c on i.category_id = c.id where i.id = '{$_GET['id']}' and i.delete_flag = 0 ");
        if(pg_num_rows($qry) > 0){
            $result = pg_fetch_assoc($qry);
            extract($result);
        }else{
            echo '<script>alert("item ID is not valid."); location.replace("./?page=items")</script>';
        }
    }else{
        echo '<script>alert("item ID is Required."); location.replace("./?page=items")</script>';
    }
?>
     
<div class="row mt-2 justify-content-center">
    <div class="col-lg-12 col-md-10 col-sm-10 col-xs-10">
         
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline rounded-5">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold mt-2">REQUEST ADJUSTMENT (PURCHASING)</h3>
                        <div class="card-tools">
                            <a href="./?page=purchasing_request" class="btn btn-flat btn-success">
                                Go Back <span class="fas fa-arrow-right"></span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="">
                            <div class="container-fluid">
                                <?php 
                                    $item_id = $_GET['id'];
                                    $sql = "SELECT * FROM wh_ingredient_request WHERE item_id = '$item_id' ORDER BY request_id DESC LIMIT 1";
                                    $result = pg_query($sql);
                                    $row = pg_fetch_assoc($result);
                                ?>
                                <div class="row">
                                    <div class="col-md-6 text-center mb-3">
                                        <h3>Requested Item</h3>
                                    </div>
                                    <div class="col-md-2">
                                        
                                    </div>
                                    <div class="col-md-4 text-center mb-3">
                                        <h3>Physical Count Info</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="item_name">Item Name:</label>
                                            <input type="text" class="form-control" id="item_name" name="item_name" value="<?php echo $row['name']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="item_unit">Item Unit</label>
                                            <input type="text" class="form-control" id="item_unit" name="item_unit" value="<?php echo $row['unit']; ?>"  readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="request_id">Request ID</label>
                                            <input type="text" class="form-control" id="request_id" name="request_id" value="<?php echo $row['request_id']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">

                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="personnel">Personnel:</label>
                                            <input type="text" class="form-control" id="personnel" name="personnel" value="<?php echo ucwords($_settings->userdata('fullname')) ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="personnel_role">Role:</label>
                                            <input type="text" class="form-control" id="personnel_role" name="personnel_role" value="<?php echo ucwords($_settings->userdata('role')) ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="supplier">Supplier:</label>
                                            <input type="text" class="form-control" id="supplier" name="supplier" value="<?php echo $row['supplier']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="requested_quantity">Requested Quantity:</label>
                                            <input type="text" class="form-control" id="requested_quantity" name="requested_quantity" value="<?php echo $row['quantity']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">

                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="physical_count">Physical Count:</label>
                                            <input type="text" class="form-control" id="physical_count" name="physical_count" placeholder="Enter physical count" required>
                                            <span class="text-muted text-red" id="error-msg"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="manufactured_date">Manufactured Date:</label>
                                            <input type="text" class="form-control" id="manufactured_date" name="manufactured_date" value="<?php echo $row['manufactured_date']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="expired_date">Expiration Date:</label>
                                            <input type="text" class="form-control" id="expired_date" name="expired_date" value="<?php echo $row['expired_date']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                    
                                    </div>
                                    <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="physical_count_date">Physical Count Date:</label>
                                        <input type="date" class="form-control" id="physical_count_date" name="physical_count_date" required>
                                        <span class="error-message text-red"></span>
                                    </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="date_approved">Date Approved</label>
                                            <input type="text" class="form-control" id="date_approved" name="date_approved" value="<?php echo date('Y-m-d', strtotime($row['date_approved'])); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="date_received">Date Received:</label>
                                            <input type="date" class="form-control" id="date_received" name="date_received">
                                            <span class="error-message text-red"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        
                                    </div>
                                    <div class="col-md-4" hidden>
                                        <div class="form-group">
                                            <label for="discrepancy_notes">Discrepancy Notes (If any):</label>
                                            <textarea class="form-control" id="discrepancy_notes" name="discrepancy_notes" placeholder="Enter notes if  there is discrepancy"></textarea>
                                        </div>
                                    </div>
                                    <?php
                                        // Query the users table for the purchasing_manager role
                                        $result = pg_query("SELECT * FROM users WHERE role='purchasing_manager'");

                                        // Check if there is a result
                                        if (pg_num_rows($result) > 0) {
                                            // Display the data in a card
                                            $row = pg_fetch_assoc($result);
                                            echo "
                                            <div class='col-md-4'>
                                                <div class='card'>
                                                    <div class='card-body'>
                                                        <p class='card-text'>
                                                            If their is any discrepancies, please contact our Purchasing Manager, <strong>{$row['fullname']}</strong>, at <a href='mailto:{$row['email']}'><strong>{$row['email']}</strong></a> or by phone at <strong>{$row['contact']}</strong>.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>";
                                        } else {
                                            echo "
                                            <div class='col-md-4'>
                                                <div class='card'>
                                                    <div class='card-body'>
                                                        <p class='card-text'>
                                                            We apologize for the inconvenience, but we do not currently have a Purchasing Manager assigned. If you notice any discrepancies, please contact our Customer Support team for assistance.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>";
                                        }
                                    ?>
                                </div>
                               
                                <div class="row">
                                    <div class="col-md-6 text-center">
                                        <div class="form-group">
                                            <button class="btn btn-sm btn-primary bg-gradient-primary" type="button" id="add_stockin"><i class="far fa-plus-square"></i> Add to Inventory</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                    
                                    </div>
                                    <div class="col-md-4 text-center" hidden>
                                        <div class="form-group">
                                            <button id="request_return" type="submit" class="btn btn-sm btn-danger bg-gradient-danger" name="request_return"> Request Return</button>
                                        </div>	
                                    </div>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>
    $(function(){
        // Stockin
        $('#add_stockin').click(function(){
            uni_modal("<i class='far fa-plus-square'></i> Add Requested Item", `stocks/manage_stockin.php?iid=<?= isset($id) ? $id : '' ?>&quantity=${$('#requested_quantity').val()}&expired_date=${$('#expired_date').val()}&manufactured_date=${$('#manufactured_date').val()}&request_id=${$('#request_id').val()}&supplier=${$('#supplier').val()}&physical_count=${$('#physical_count').val()}&date_approved=${$('#date_approved').val()}&date_received=${$('#date_received').val()}&physical_count_date=${$('#physical_count_date').val()}&personnel=${$('#personnel').val()}&personnel_role=${$('#personnel_role').val()}`)
        })

        tbl1 = $('#stockin-tbl').dataTable({
			columnDefs: [
					{ orderable: false, targets: [3] }
			],
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
        $('.dataTables_paginate .pagination>li>a').addClass('p-1');
        $('.dataTables_filter input').addClass('rounded-0 form-control-sm py-1');
        
    })

    // RESTRICTION FOR STOCK-IN BUTTON
    const physicalCountField = document.getElementById("physical_count");
    const requestedQuantityField = document.getElementById("requested_quantity");
    const addStockInButton = document.getElementById("add_stockin");
    const dateReceivedField = document.getElementById("date_received");
    const physicalCountDateField = document.getElementById("physical_count_date");
    const requestReturnButton = document.getElementById("request_return");
    const discrepancyNotes = document.getElementById("discrepancy_notes");

    // Create a function to check the conditions and enable/disable the button
   // Create a function to check the conditions and enable/disable the button
function checkConditions() {
    const currentDate = new Date();
    const dateReceived = new Date(dateReceivedField.value);
    const physicalCountDate = new Date(physicalCountDateField.value);

    // Check if input dates are greater than current date
    if (dateReceived > currentDate) {
        dateReceivedField.nextElementSibling.textContent = "Date must not be greater than current date";
        addStockInButton.disabled = true;
        requestReturnButton.disabled = true;
    } else {
        dateReceivedField.nextElementSibling.textContent = "";
    }

    if (physicalCountDate > currentDate) {
        physicalCountDateField.nextElementSibling.textContent = "Date must not be greater than current date";
        addStockInButton.disabled = true;
        requestReturnButton.disabled = true;
    } else if (physicalCountDate < dateReceived) { // Check if physical count date is less than date received
        physicalCountDateField.nextElementSibling.textContent = "Physical count date must not be less than date received";
        addStockInButton.disabled = true;
        requestReturnButton.disabled = true;
    } else {
        physicalCountDateField.nextElementSibling.textContent = "";
    }

    // Check other conditions
    if (!physicalCountField.value ||
        !requestedQuantityField.value ||
        !dateReceivedField.value ||
        !physicalCountDateField.value ||
        dateReceived > currentDate ||
        physicalCountDate > currentDate ||
        physicalCountDate < dateReceived) {
        addStockInButton.disabled = true;
        requestReturnButton.disabled = true;
    } else if (parseInt(physicalCountField.value) < parseInt(requestedQuantityField.value)) {
        addStockInButton.disabled = true;
        requestReturnButton.disabled = false;
        discrepancyNotes.disabled = false;
    } else if (parseInt(physicalCountField.value) > parseInt(requestedQuantityField.value)) {
        addStockInButton.disabled = true;
        requestReturnButton.disabled = true;
        discrepancyNotes.disabled = true;
    } else {
        addStockInButton.disabled = false;
        requestReturnButton.disabled = true;
        discrepancyNotes.disabled = true;
    }
}


    // Disable initially
    addStockInButton.disabled = true;
    requestReturnButton.disabled = true;
    discrepancyNotes.disabled = true;

    // Add event listeners to the input fields
    physicalCountField.addEventListener("input", checkConditions);
    requestedQuantityField.addEventListener("input", checkConditions);
    dateReceivedField.addEventListener("input", checkConditions);
    physicalCountDateField.addEventListener("input", checkConditions);

    const requestedQuantity = document.getElementById('requested_quantity').value;
    const physicalCount = document.getElementById('physical_count');

    physicalCount.addEventListener('input', () => {
        if (Number(physicalCount.value) > Number(requestedQuantity)) {
            document.getElementById('error-msg').textContent = 'Cannot be greater than requested quantity';
        } else if (Number(physicalCount.value) < Number(requestedQuantity)) {
            document.getElementById('error-msg').textContent = 'Cannot be less than requested quantity';
        } else {
            document.getElementById('error-msg').textContent = '';
        }
    });
</script>