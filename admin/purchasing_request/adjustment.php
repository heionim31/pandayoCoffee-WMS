<div class="row">
	<div class="col-md-7">
		<div class="card card-outline rounded-5 card-dark">
			<div class="card-header">
				<h3 class="card-title">Requested Ingredient (From Purchasing Department)</h3>
				<div class="card-tools">
					<a href="./?page=purchasing_request" class="btn btn-flat btn-success"><span class="fas fa-arrow-left"></span> Go back</a>
				</div>
			</div>
			<div class="card-body">
				<form method="">
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="item_name">Item Name:</label>
									<input type="text" class="form-control" id="item_name" name="item_name"  readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="item_code">Item Code:</label>
									<input type="text" class="form-control" id="item_code" name="item_code" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="item_unit">Item Unit</label>
									<input type="text" class="form-control" id="item_unit" name="item_unit"  readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="item_category">Category:</label>
									<input type="text" class="form-control" id="item_category" name="item_category" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="manufactured_date">Manufactured Date:</label>
									<input type="text" class="form-control" id="manufactured_date" name="manufactured_date" readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="expiration_date">Expiration Date:</label>
									<input type="text" class="form-control" id="expiration_date" name="expiration_date" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="requested_quantity">Requested Quantity:</label>
									<input type="text" class="form-control" id="requested_quantity" name="requested_quantity" readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="supplier">Supplier:</label>
									<input type="text" class="form-control" id="supplier" name="supplier" readonly>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="purchasing_notes">Notes from Purchasing:</label>
							<textarea class="form-control" id="purchasing_notes" name="purchasing_notes" readonly></textarea>
						</div>
						<div class="modal-footer d-flex justify-content-center">
							<div class="form-group">
								<button type="submit" class="btn btn-primary" name="request_return"> Add to Inventory</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

  	<div class="col-md-5">
		<div class="card card-outline rounded-5 card-dark">
			<div class="card-header">
				<h3 class="card-title">Physical Count Information (From Warehouse)</h3>
			</div>
			<div class="card-body">
				<form method="">
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="personnel">Personnel:</label>
									<input type="text" class="form-control" id="personnel" name="personnel" value="<?php echo ucwords($_settings->userdata('fullname')) ?>" readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="personnel_role">Role:</label>
									<input type="text" class="form-control" id="personnel_role" name="personnel_role" value="<?php echo ucwords($_settings->userdata('role')) ?>" readonly>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="physical_count">Physical Count:</label>
							<input type="text" class="form-control" id="physical_count" name="physical_count" placeholder="Enter physical count" required>
						</div>
						<div class="form-group">
							<label for="physical_count_date">Date of Physical Count:</label>
							<input type="date" class="form-control" id="physical_count_date" name="physical_count_date" required>
						</div>
						<div class="form-group">
							<label for="discrepancy_notes">Notes on Discrepancy (If any):</label>
							<textarea class="form-control" id="discrepancy_notes" name="discrepancy_notes" placeholder="Enter notes on discrepancy (if any)"></textarea>
						</div>
						<div class="modal-footer d-flex justify-content-center">
							<div class="form-group">
								<span class="text-muted">Note: If you find any discrepancies, please click the button.</span>
							</div>		
							<button type="submit" class="btn btn-danger" name="request_return"> Request Return</button>
						</div>
          			</div>
       	 		</form>
      		</div>
    	</div>
  	</div>
</div>