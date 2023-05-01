<?php
    // Establish a connection to the database


    // Check if the "Set Notification" button was clicked
    if (isset($_POST['set_notification'])) {
        // Get the minimum and maximum stock values from the form
        $min_stock = $_POST['minimum_stock'];
        $max_stock = $_POST['max_stock'];

        // Update the values in the stock_notif table
        $sql = "UPDATE wh_stock_notif SET min_stock='$min_stock', max_stock='$max_stock' WHERE id=1";
        $result = pg_query($conn, $sql);
        if ($result) {
            $notification_updated = true;
        } else {
            echo "Error: " . $sql . "<br>" . pg_last_error($conn);
        }
    }

    // Select the data from the stock_notif table
    $sql = "SELECT * FROM wh_stock_notif WHERE id=1";
    $result = pg_query($conn, $sql);
    $row = pg_fetch_assoc($result);
?>



<div class="col-lg-12">
    <div class="card card-outline rounded-0 card-dark">
        <div class="card-header">
            <h5 class="card-title">Stock Alert Notification</h5>
        </div>

        <div class="card-body">
            <?php if(isset($notification_updated) && $notification_updated) { ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Notification Updated',
                        text: 'The stock notification has been updated successfully.',
                        confirmButtonText: 'Okay'
                    });
                </script>
            <?php } ?>

            <form action="" method="POST" id="system-forms">
                <div class="form-group">
                    <label for="minimum_stock" class="control-label">Minimum Stock</label>
                    <input type="text" class="form-control form-control-sm" name="minimum_stock" id="minimum_stock" placeholder="Enter stock range" value="<?php echo isset($row['min_stock']) ? $row['min_stock'] : '' ?>" required oninput="checkMinMaxStock()">
                    <span id="minStockError" style="color: red; display: none;">Sorry, the min stock entered must be greater than zero.</span>
                </div>

                <div class="form-group">
                    <label for="max_stock" class="control-label">Max Stock</label>
                    <input type="number" class="form-control form-control-sm" name="max_stock" id="max_stock" placeholder="Enter stock range" value="<?php echo isset($row['max_stock']) ? $row['max_stock'] : '' ?>" required oninput="checkMinMaxStock()">
                    <span id="maxStockError" style="color: red; display: none;">Sorry, the max stock entered must be greater than zero.</span>
                </div>

                <div class="form-group">
                    <label for="date_updated" class="control-label">Date Updated</label>
                    <input type="text" class="form-control form-control-sm" name="date_updated" id="date_updated" value="<?php echo isset($row['date_updated']) ? date("Y-m-d H:i",strtotime($row['date_updated'])) : '' ?>" disabled>
                </div>

                <button type="submit" name="set_notification" class="btn btn-primary">Set Notification</button>

                <script>
                    function checkMinMaxStock() {
                        let minStockInput = document.getElementById("minimum_stock");
                        let maxStockInput = document.getElementById("max_stock");
                        let minStockError = document.getElementById("minStockError");
                        let maxStockError = document.getElementById("maxStockError");
                        
                        if (maxStockInput.value <= 0) {
                            maxStockError.style.display = "inline";
                            maxStockError.innerText = "Sorry, the max stock entered must be greater than zero.";
                        } else {
                            maxStockError.style.display = "none";
                        }

                        if (minStockInput.value <= 0) {
                            minStockError.style.display = "inline";
                            minStockError.innerText = "Sorry, the min stock entered must be greater than zero.";
                        } else {
                            minStockError.style.display = "none";
                        }
                    }
                </script>
            </form>

		</div>
	</div>
</div>