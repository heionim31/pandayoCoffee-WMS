<div class="row">
  <div class="col-md-8">
    <div class="card card-outline rounded-5">
      <div class="card-header">
          <h3 class="card-title mt-2 font-weight-bold">PEDNING LEAVE REQUESTS</h3>
          <div class="card-tools">
            <a href="#" class="btn btn-flat btn-success" onclick="location.href = window.location.href; return false;">
              <span class="fas fa-sync"></span> Refresh
            </a>
            <a href="./?page=leave_request_manager/history" class="btn btn-flat btn-info">
              <span class="fas fa-history"></span> History
            </a>
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
                $qry = pg_query($conn, "SELECT * FROM wh_leave_request WHERE status NOT IN ('Approved', 'Declined') ORDER BY id ASC");
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

  <?php
    if (isset($_POST["approve"])) {
      $id = $_POST["id_approve"];
      $dateApproved = date('Y-m-d');
      $query = "UPDATE wh_leave_request SET status = 'Approved', date_approved = '$dateApproved' WHERE id = $id";
      pg_query($conn, $query);
      echo "<script>
              window.onload = function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Successfully approved the leave request.',
                    showConfirmButton: true
                }).then(function() {
                    location.href = window.location.href;
                });
              };
            </script>";
    } else if (isset($_POST["decline"])) {
      $id = $_POST["id_decline"];
      $declineReason = $_POST["decline_reason"];
      $dateDecline = date('Y-m-d');
      $query = "UPDATE wh_leave_request SET status = 'Declined', decline_reason = '$declineReason', date_decline = '$dateDecline' WHERE id = $id";
      pg_query($conn, $query);
      echo "<script>
              window.onload = function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Successfully declined the leave request.',
                    showConfirmButton: true
                }).then(function() {
                    location.href = window.location.href;
                });
              };
            </script>";
    }
  ?>


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
            <div class="modal-footer d-flex justify-content-center">
              <button type="button" class="btn btn-primary disable_approve" data-toggle="modal" data-target="#confirmModal">Approve</button>
              <button type="button" class="btn btn-dark bg-gradient-danger border disable_decline" data-toggle="modal" data-target="#declineModal" > Decline</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- APPROVE MODAL -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Confirmation</h5>
      </div>
      <form method="POST">
        <div class="modal-body">
          <h4 class="mb-4">Are you sure you want to approve?</h4>
          <div class="form-group" hidden>
            <label for="id_approve">ID</label>
            <input type="text" class="form-control form-control-sm" id="id_approve" name="id_approve" readonly>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" name="approve" class="btn btn-primary">Yes, Approve</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- DECLINE MODAL -->
<div class="modal fade" id="declineModal" tabindex="-1" role="dialog" aria-labelledby="declineModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="declineModalLabel">Reason for Decline</h5>
      </div>
      <form method="POST">
        <div class="modal-body">
        <div class="form-group" hidden>
          <label for="id_decline">ID</label>
          <input type="text" class="form-control form-control-sm" id="id_decline" name="id_decline" readonly>
        </div>
          <textarea class="form-control" id="decline_reason" name="decline_reason" rows="3" placeholder="Enter reason for declining the leave request (e.g. workload conflicts, insufficient coverage, etc.)" required></textarea>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger" name="decline">Decline</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
    // FETCH TABLE DATA
    const rows = document.querySelectorAll('#leave-table tbody tr');
  const approveButton = document.querySelector('.disable_approve');
  const declineButton = document.querySelector('.disable_decline');

  // Disable the buttons initially
  approveButton.disabled = true;
  declineButton.disabled = true;

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

      // Enable the buttons when a row is clicked
      approveButton.disabled = false;
      declineButton.disabled = false;

      document.querySelector('#id').value = id;
      document.querySelector('#id_approve').value = id;
      document.querySelector('#id_decline').value = id;
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
</script>