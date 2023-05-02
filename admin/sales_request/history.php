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
<div class="card card-outline rounded-0 card-dark">
	<div class="card-header">
		<h3 class="card-title mt-2">SALES REQUEST HISTORY</h3>
        <div class="card-tools">
            <a href="./?page=sales_request" class="btn btn-flat btn-success">
                Go Back <span class="fas fa-arrow-right"></span>
            </a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered text-center" id="list">
				<thead>
					<tr>
						<th></th>
						<th>#</th>
						<th>Request ID</th>
						<th>Ingredient</th>
						<th>Date Request</th>
						<th>Request By</th>
						<th>Request Notes</th>
                        <th>Status</th>
					</tr>
				</thead>
				<tbody>
                    <?php 
                        $result = pg_query($conn, "SELECT * FROM Ingredient_request WHERE status IN ('Approved', 'Received') ORDER BY date_request ASC");

                        $counter = 1;
                        while ($row = pg_fetch_assoc($result)) {
                            $request_id = $row['request_id'];
                            $ingredient_name = $row['ingredient_name'];
                            $date_request = $row['date_request'];
                            $status = $row['status'];
                            $request_by = $row['request_by'];
                            $request_notes = $row['notes'];
                            $date_today = date('Y-m-d H:i:s');
                            $date_approved = $row['date_approved'];
                            $date_prepared = $row['date_prepared'];
                            $add_stock = $row['add_stock'];
                            $unit = $row['unit'];
                            $personnel = $row['personnel'];
                            $personnel_role = $row['personnel_role'];
                    ?>
                        <tr>
                            <td class="toggle-icon" onclick="toggleTable(this)">
                                <i class="fa fa-plus"></i>
                            </td>
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo $request_id; ?></td>
                            <td><?php echo $ingredient_name; ?></td>
                            <td><?php echo $date_request; ?></td>
                            <td><?php echo $request_by; ?></td>
                            <td><?php echo $request_notes; ?></td>
                            <td><?php echo $status; ?></td>
                        </tr>
                        <tr class="additional-row" style="display:none;">
                            <td colspan="8">
                                <table class="additional-table">
                                    <thead>
                                        <tr>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <th>Date_Prepared</th>
                                            <th>Date Approved</th>
                                            <th>Personnel</th>
                                            <th>Personnel Role</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            
                                        <td><?php echo $add_stock?></td>
                                            <td><?php echo $unit?></td>
                                            <td><?php echo $date_prepared?></td>
                                            <td><?php echo $date_approved; ?></td>
                                            <td><?php echo $personnel; ?></td>
                                            <td><?php echo $personnel_role; ?></td>
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