<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #f5f5f5;
    }

    .additional-row {
        background-color: #f5f5f5;
        display: none;
    }

    .additional-row.active {
        display: table-row;
    }

    .additional-table {
        border-collapse: collapse;
        width: 100%;
        margin: 10px 0 20px;
    }

    .additional-table th, .additional-table td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }

    .icon-column {
        cursor: pointer;
    }

    .icon-column i {
        transition: transform 0.2s ease-in-out;
    }

    .icon-column i.rotate-icon {
        transform: rotate(180deg);
    }
</style>


<!-- POS REQUEST -->
<div class="card card-outline rounded-5">
	<div class="card-header">
		<h3 class="card-title mt-2 font-weight-bold">PURCHASING REQUEST HISTORY</h3>
        <div class="card-tools">
            <a href="./?page=purchasing_request" class="btn btn-flat btn-success">
                Go Back <span class="fas fa-arrow-right"></span>
            </a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
            <div class="form-group">
                <input type="text" class="form-control" id="searchInput" placeholder="Search...">
            </div>
			<table class="table table-hover table-striped table-bordered text-center" id="list">
				<thead>
					<tr>
						<th></th>
						<th>#</th>
						<th>Request ID</th>
						<th>Ingredient</th>
						<th>Quantity</th>
						<th>Unit</th>
						<th>Date Request</th>
						<th>Request Notes</th>
					</tr>
				</thead>
				<tbody>
                    <?php 
                        $result = pg_query($conn, "SELECT * FROM wh_ingredient_request WHERE status IN ('Decline', 'Received') ORDER BY date_request ASC");

                        $counter = 1;
                        while ($row = pg_fetch_assoc($result)) {
                            $request_id = $row['request_id'];
                            $name = $row['name'];
                            $quantity = $row['quantity'];
                            $unit = $row['unit'];
                            $date_request = $row['date_request'];
                            $request_by = $row['request_by'];
                            $personnel_role = $row['personnel_role'];
                            $date_approved = $row['date_approved'];
                            $status = $row['status'];
                            $request_notes = $row['request_notes'];
                            $expired_date = $row['expired_date'];
                            $manufactured_date = $row['manufactured_date'];
                            $supplier = $row['supplier'];
                    ?>
                        <tr>
                            <td class="toggle-icon" onclick="toggleTable(this)">
                                <i class="fa fa-plus"></i>
                            </td>
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo $request_id; ?></td>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $quantity; ?></td>
                            <td><?php echo $unit; ?></td>
                            <td><?php echo $date_request; ?></td>
                            <td><?php echo $request_notes; ?></td>
                        </tr>
                        <tr class="additional-row" style="display:none;">
                            <td colspan="8">
                                <table class="additional-table">
                                    <thead>
                                        <tr>
                                            <th>Request By</th>
                                            <th>Role</th>
                                            <th>Manfactured Date</th>
                                            <th>Expiration Date</th>
                                            <th>Supplier</th>
                                            <th>Date Approved</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $request_by?></td>
                                            <td><?php echo $personnel_role?></td>
                                            <td><?php echo $manufactured_date; ?></td>
                                            <td><?php echo $expired_date; ?></td>
                                            <td><?php echo $supplier; ?></td>
                                            <td><?php echo $date_approved; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    <?php 
                        }
                    ?>
                </tbody>
			</table>
		</div>
	</div>
</div>


<script>
    $(document).ready(function() {
        // Filter table rows based on search input
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#list tbody tr").filter(function() {
                $(this).toggle($(this).find('td:eq(2)').text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>


<script>
    function toggleTable(icon) {
        var row = icon.parentNode;
        var additionalRow = row.nextElementSibling;
        var additionalTable = additionalRow.querySelector('.additional-table');

        // hide all additional rows
        var allAdditionalRows = document.querySelectorAll('.additional-row');
        allAdditionalRows.forEach(function (row) {
            if (row !== additionalRow) {
                row.style.display = "none";
                row.previousElementSibling.querySelector('.toggle-icon i').className = 'fa fa-plus';
            }
        });

        // toggle the display of the selected additional row
        if (additionalRow.style.display === "none") {
            additionalRow.style.display = "table-row";
            additionalTable.style.marginLeft = ((row.offsetLeft + row.offsetWidth / 2) - (additionalTable.offsetWidth / 2)) + "px";
            icon.querySelector('i').className = 'fa fa-minus';
        } else {
            additionalRow.style.display = "none";
            icon.querySelector('i').className = 'fa fa-plus';
        }
    }
</script>

<script>
	// TABLE
	$(document).ready(function(){
		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [5] }
			],
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})
</script>