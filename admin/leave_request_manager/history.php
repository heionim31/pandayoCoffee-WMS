<?php 
    $month = isset($_GET['month']) ? $_GET['month'] : date("Y-m");
?>


<div class="row">
  <div class="col-md-8">
    <div class="card card-outline rounded-5">
      <div class="card-header">
        <div class="row">
          <div class="col-3">
            <h3 class="card-title mt-2 font-weight-bold">LEAVE REQUEST HISTORY</h3>
          </div>
          <div class="col-md-1">

          </div>
          <div class="col-5">
            <form action="" id="filter-form" class=" d-flex justify-content-center">
              <input type="month" class="form-control" name="month" id="month" value="<?= $month ?>" required>
              <button class="btn btn-primary bg-gradient-primary" type="submit"><i class="fa fa-filter"></i></button>
          </form>
          </div>
          <div class="col-md-1">

          </div>
          <div class="col-2">
            <a href="./?page=leave_request_manager" class="btn btn-success">
              Go Back <span class="fas fa-arrow-right"></span>
            </a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="container-fluid">
          <table id="leave-table" class="table table-hover table-striped table-bordered text-center">
            <thead>
              <tr>
                <th>#</th>
                <th hidden>id</th>
                <th hidden>EID</th>
                <th>Name</th>
                <th hidden>Date to Leave</th>
                <th>Date Requested</th>
                <th hidden>Reason</th>
                <th hidden>Email</th>
                <th hidden>Contact</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $i = 1;
                $qry = pg_query($conn, "SELECT * FROM wh_leave_request WHERE to_char(date_approved, 'YYYY-MM') = '{$month}' OR to_char(date_decline, 'YYYY-MM') = '{$month}' ORDER BY id DESC");

                while($row = pg_fetch_assoc($qry)):
              ?>
              <tr>
                <td><?php echo $i++; ?></td>
                <td hidden><?= $row['id'] ?></td>
                <td hidden><?= $row['employeeid'] ?></td>
                <td><?= $row['name'] ?></td>
                <td hidden><?= $row['from_date'] . ' - ' . $row['to_date'] ?></td>
                <td><?= $row['date_requested'] ?></td>
                <td hidden><?= $row['reason'] ?></td>
                <td hidden><?= $row['email'] ?></td>
                <td hidden><?= $row['contact'] ?></td>
                <td><?= $row['status'] ?></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card card-outline rounded-5">
      <div class="card-header">
        <h3 class="card-title font-weight-bold">LEAVE DETAILED INFORMATION</h3>
      </div>
      <div class="card-body">
        <form method="POST">
          <div class="container-fluid">
            <div class="form-group" hidden>
              <label for="id">ID</label>
              <input type="text" class="form-control form-control-sm" id="id" name="id" readonly>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="employee-name">Employee Name</label>
                  <input type="text" class="form-control form-control-sm" id="employee-name" name="employee-name" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="date-requested">Date Requested</label>
                  <input type="text" class="form-control form-control-sm" id="date-requested" name="date-requested" readonly>
                </div>
              </div>
            </div>
            <div class="form-group" hidden>
              <label for="employee-id">Employee ID</label>
              <input type="text" class="form-control form-control-sm" id="employee-id" name="employee-id" readonly>
            </div>
            <div class="form-group">
              <label for="date-to-leave">Date to leave</label>
              <input type="text" class="form-control form-control-sm" id="date-to-leave" name="date-to-leave" readonly>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control form-control-sm" id="email" name="email" readonly>
            </div>
            <div class="form-group">
              <label for="contact-number">Contact Number</label>
              <input type="number" class="form-control form-control-sm" id="contact-number" name="contact-number" readonly>
            </div>
            <div class="form-group">
              <label for="reason">Reason</label>
              <textarea rows="4" class="form-control form-control-sm" id="reason" name="reason" readonly></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
    // FETCH TABLE DATA
    const rows = document.querySelectorAll('#leave-table tbody tr');

    rows.forEach(row => {
        row.addEventListener('click', () => {
            const cells = row.querySelectorAll('td');
            const id = cells[1].textContent;
            const eid = cells[2].textContent;
            const name = cells[3].textContent;
            const dateToLeave = cells[4].textContent;
            const dateRequested = cells[5].textContent;
            const reason = cells[6].textContent;
            const email = cells[7].textContent;
            const contact = cells[8].textContent;
            const status = cells[9].textContent;

            document.querySelector('#id').value = id;
            document.querySelector('#employee-id').value = eid;
            document.querySelector('#employee-name').value = name;
            document.querySelector('#date-to-leave').value = dateToLeave;
            document.querySelector('#date-requested').value = dateRequested;
            document.querySelector('#reason').value = reason;
            document.querySelector('#email').value = email;
            document.querySelector('#contact-number').value = contact;
            document.querySelector('#status').value = status;
        });
    });

  // TABLE
	$(document).ready(function(){
		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [5] }
			],
			order:[0,'asc']
		});
	})

  $(function(){
        $('#filter-form').submit(function(e){
            e.preventDefault()
            location.href = './?page=leave_request_manager/history&'+$(this).serialize()
        })
    })
</script>