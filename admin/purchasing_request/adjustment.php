<div class="d-flex justify-content-between">
    <div class="col-12">
        <form method="post" action="">
            <div class="row mt-4 justify-content-between">
                <div class="col-md-6 bg-white p-4">
                    <h4>Requested Item Information (From Purchasing Department)</h4>
                    <div class="form-group">
                        <label for="item_name">Item Name:</label>
                        <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Enter item name" required>
                    </div>
                    <div class="form-group">
                        <label for="current_quantity">Current Quantity:</label>
                        <input type="text" class="form-control" id="current_quantity" name="current_quantity" placeholder="Enter current quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="requested_quantity">Requested Quantity:</label>
                        <input type="text" class="form-control" id="requested_quantity" name="requested_quantity" placeholder="Enter requested quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="adjustment_reason">Reason for Adjustment:</label>
                        <textarea class="form-control" id="adjustment_reason" name="adjustment_reason" placeholder="Enter reason for adjustment" required></textarea>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Adjust Quantity</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 bg-white p-4">
                    <h4>Physical Count Information (From Warehouse)</h4>
                    <div class="form-group">
                        <label for="physical_count">Physical Count:</label>
                        <input type="text" class="form-control" id="physical_count" name="physical_count" placeholder="Enter physical count" required>
                    </div>
                    <div class="form-group">
                        <label for="physical_count_date">Date of Physical Count:</label>
                        <input type="date" class="form-control" id="physical_count_date" name="physical_count_date" required>
                    </div>
                    <div class="form-group">
                        <label for="physical_count_personnel">Personnel who conducted Physical Count:</label>
                        <input type="text" class="form-control" id="physical_count_personnel" name="physical_count_personnel" placeholder="Enter name of personnel" required>
                    </div>
                    <div class="form-group">
                        <label for="discrepancy_notes">Notes on Discrepancy (If any):</label>
                        <textarea class="form-control" id="discrepancy_notes" name="discrepancy_notes" placeholder="Enter notes on discrepancy (if any)"></textarea>
                    </div>
                    <div class="form-group">
                        <span class="text-muted">Note: If you find any discrepancies, please click the button below to notify the purchasing department.</span>
                        <br>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#discrepancyModal">Notify Purchasing</button>
                    </div>



                </div>
            
                <!-- Submit Button -->
                
            </div>
        </form>
    </div>
</div>
