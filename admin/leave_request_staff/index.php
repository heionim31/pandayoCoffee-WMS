<div class="row">
    <div class="col-md-8">
        <div class="card card-outline rounded-5 card-dark">
            <div class="card-header">
                <h3 class="card-title">Request Leave History</h3>
                <div class="card-tools">
                    <a href="#" class="btn btn-flat btn-success" onclick="location.href = window.location.href; return false;">
                        <span class="fas fa-sync"></span> Refresh
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <table id="leave-table" class="table table-hover table-striped table-bordered text-center">
                        <thead>
                            <tr>
                            <th>#</th>
                            <th>Leave Date</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i = 1;
                                if(isset($_POST['id'])){
                                $id = $_POST['id'];
                                $query = "DELETE FROM wh_leave_request WHERE id = $id";
                                $result = pg_query($conn, $query);
                                if($result) {
                                    echo "<script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Success!',
                                                text: 'Leave request has been cancelled!',
                                                showConfirmButton: false,
                                                timer: 1500
                                            });
                                        </script>";
                                }
                                }
                                $qry = pg_query($conn, "SELECT * from wh_leave_request WHERE employeeid = '".$_settings->userdata('id')."' ORDER by id DESC");

                                while($row = pg_fetch_assoc($qry)):
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><?= $row['from_date'] . ' - ' . $row['to_date'] ?></td>
                                <td><?= $row['reason'] ?></td>
                                <td><?= $row['status'] ?></td>
                                <td align="center">
                                <?php if($row['status'] != 'Approved' && $row['status'] != 'Declined'): ?>
                                    <form method="post" onsubmit="return confirmDelete(event)">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button class="btn btn-flat btn-danger btn-xs bg-gradient-danger" type="submit"><i class="fa fa-times"></i></button>
                                    </form>
                                <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-4">
        <div class="card card-outline rounded-5 card-dark">
            <div class="card-header">
                <h3 class="card-title">File a Leave</h3>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <?php
                        if (isset($_POST['approve'])) {
                            $employee_id = pg_escape_string($conn, $_POST['employee_id']);
                            $department = pg_escape_string($conn, $_POST['department']);
                            $employee_name = pg_escape_string($conn, $_POST['employee_name']);
                            $email = pg_escape_string($conn, $_POST['email']);
                            $contact_number = pg_escape_string($conn, $_POST['contact_number']);
                            $reason = pg_escape_string($conn, $_POST['reason']);
                            $from = pg_escape_string($conn, $_POST['from']);
                            $to = pg_escape_string($conn, $_POST['to']);

                            $result = pg_query_params($conn, "INSERT INTO wh_leave_request (employeeid, name, email, contact, reason, from_date, to_date, status, date_requested) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)", array($employee_id, $employee_name, $email, $contact_number, $reason, $from, $to, 'Pending', date('Y-m-d')));
                            
                            if ($result) {
                                echo '<script>
                                Swal.fire({
                                    icon: "success",
                                    title: "Success!",
                                    text: "Leave request has been sent.",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            </script>';
                             }
                        }
                    ?>
                    <form class="mt-5 mt-md-0" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employee-id">Employee ID</label>
                                    <input type="text" class="form-control form-control-sm" id="employee-id" name="employee_id" value="<?php echo ucwords($_settings->userdata('id')) ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="department">Department</label>
                                    <input type="text" class="form-control form-control-sm" id="department" name="department" value="<?php echo ucwords($_settings->userdata('department')) ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="employee-name">Employee Name</label>
                            <input type="text" class="form-control form-control-sm" id="employee-name" name="employee_name" value="<?php echo ucwords($_settings->userdata('fullname')) ?>" readonly>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control form-control-sm" id="email" name="email" value="<?php echo ucwords($_settings->userdata('email')) ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_number">Contact Number</label>
                                    <input type="number" class="form-control form-control-sm" id="contact_number" name="contact_number" value="<?php echo ucwords($_settings->userdata('contact')) ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="reason">Reason</label>
                            <textarea rows="4" class="form-control form-control-sm" id="reason" name="reason" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="from">From</label>
                                    <input type="date" class="form-control form-control-sm" id="fromDate" name="from" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="to">To</label>
                                    <input type="date" class="form-control form-control-sm" id="toDate" name="to" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12  d-flex justify-content-center">
                                <span id ="leaveDateMsg" class="alert-msg text-red text-center"></span>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <button id="approveBtn" class="btn btn-dark bg-gradient-success border" name="approve"> Send Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
  function confirmDelete(event) {
    event.preventDefault();
    Swal.fire({
      icon: 'warning',
      title: 'Cancel Leave Request?',
      text: 'You will not be able to recover this leave request!',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, cancel it!'
    }).then((result) => {
      if (result.isConfirmed) {
        event.target.submit();
      }
    });
  }
</script>

<script>
    const fromInput = document.getElementById('fromDate');
    const toInput = document.getElementById('toDate');
    const reasonInput = document.getElementById('reason');
    const sendRequestBtn = document.getElementById('approveBtn');
    const leaveDateMsg = document.getElementById('leaveDateMsg');

    // disable the send request button by default
    sendRequestBtn.disabled = true;

    // add event listeners to the form inputs
    fromInput.addEventListener('change', validateForm);
    toInput.addEventListener('change', validateForm);
    reasonInput.addEventListener('input', validateForm);

    function validateForm() {
        // check if the reason, from date, and to date inputs have values
        const reasonValue = reasonInput.value.trim();
        const fromValue = fromInput.value.trim();
        const toValue = toInput.value.trim();

        if (reasonValue !== '' && fromValue !== '' && toValue !== '') {
            // enable the send request button
            sendRequestBtn.disabled = false;

            // check if the to date is less than the from date
            const fromDate = new Date(fromValue);
            const toDate = new Date(toValue);

            if (toDate <= fromDate) {
                leaveDateMsg.innerText = 'The "To" date must be greater than "From" date.';
                sendRequestBtn.disabled = true;
            } else {
                // check if the to date and from date are greater than the current date
                const currentDate = new Date();
                if (fromDate < currentDate && toDate < currentDate) {
                    leaveDateMsg.innerText = 'The "From" and "To" dates must be greater than the current date.';
                    sendRequestBtn.disabled = true;
                } else if (fromDate < currentDate) {
                    leaveDateMsg.innerText = 'The "From" date must be greater than the current date.';
                    sendRequestBtn.disabled = true;
                } else if (toDate < currentDate) {
                    leaveDateMsg.innerText = 'The "To" date must be greater than the current date.';
                    sendRequestBtn.disabled = true;
                } else {
                    leaveDateMsg.innerText = '';
                }
            }
        } else {
            // disable the send request button
            sendRequestBtn.disabled = true;
            leaveDateMsg.innerText = '';
        }
    }

</script>



<script>
	$(document).ready(function(){
		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [3] }
			],
			order:[0,'asc']
		});
	})
</script>