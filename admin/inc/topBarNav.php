<?php include '../loading-animation.php'; ?>

<style>
  .user-img{
    position: absolute;
    height: 27px;
    width: 27px;
    object-fit: cover;
    left: -7%;
    top: -12%;
  }

  .btn-rounded{
    border-radius: 50px;
  }

  /* LEFT NAVBAR*/
  .left-navbar {
    line-height: 2.2rem;
  }

  .left-navbar-content {
    color: white;
    top: -15%;
  }

  .left-navbar-content:hover {
    color: #e0d7d3;
  }

  /* CURRENT PAGE NAMES */
  .current-page {
    font-weight: bold;
    color: #f5f5f5;
    text-decoration: underline;
  }
  
  .current-page:hover {
    text-decoration: underline;
    /* color: #ff8c00; */
  }

  /* NOTIFICATION */
  .dropdown-menu a {
    display: block;
    padding: .25rem 1rem;
    clear: both;
    font-weight: 400;
    color: #212529;
    text-align: inherit;
    white-space: nowrap;
    background-color: transparent;
    border: 0;
  }

  .container {
    position: relative;
    top: 0.25rem;
    right: 1rem;
    display: inline-block;
  }

  .notification-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: -8px;
    right: -5px;
    margin-right: 15px;
    background-color: red;
    color: white;
    border-radius: 50%;
    width: 16px;
    height: 16px;
    padding: 2px 4px;
    font-size: 9px;
    font-weight: bold;
    text-align: center;
    line-height: 12px;
    z-index: 1;
  }

  .dropdown-toggle {
    position: relative;
  }

  .dropdown-toggle::after {
    display: none;
  }

  .fa-bell {
    font-size: 24px;
    color: white;
    margin-top: 10px;
    margin-right: 15px;
  }

  .dropdown-menu-notif {
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    padding: 0;
    margin: 0;
    box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.2);
    border: none;
    width: 350px;
    text-align: left;
    overflow-y: none;
    max-height: 500px;
  }

    .dropdown-menu-notif li a {
      display: block;
      padding: 10px;
      color: #333;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .dropdown-menu-notif li a:hover {
      background-color: #f5f5f5;
    }

    .dropdown-menu li:last-child {
      border-bottom-right-radius: 10px;
      border-bottom-left-radius: 10px;
    }

    .dropdown-header {
      font-size: 1.2rem;
      text-align: left;
      padding: 1rem 2rem;
      background-color: #f2f2f2;
      color: #2c3e50;
    }

    .dropdown-item {
      display: flex;
      align-items: center;
      padding-top: 1rem;
      padding-bottom: 1rem;
      font-size: 1.1rem;
    }

    .dropdown-item i {
      width: 15%;
      display: inline-block;
      text-align: center;
      padding: 0.5rem;
      margin-right: 1rem;
    }
    
    .dropdown-item span {
      flex: 1;
    }

    .dropdown-divider {
        margin: 0.5rem 0;
    }

  /* GLOBAL ADD BUTTONS */
  .global-add-btn {
    margin-right: 1rem;
    height: 2rem;
    width: 2rem;
    margin-top: 0.3rem;
  }

  .global-add-btn button {
    background-color: #6571ff;
    border: 1px solid #6571ff;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 0 17px 1px rgba(173,181,189,.05), 0 6px 20px 0 rgba(0,0,0,.15);
    height: 1.6rem;
    width: 1.6rem;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0;
  }

  .global-add-btn button i {
    color: white;
    transition: all 0.3s ease-in-out;
    font-size: 1rem;
  }

  .global-add-btn button,
  .global-add-btn button i {
    transition: all 0.3s ease-in-out;
  }

  .global-add-btn button:hover,
  .global-add-btn button:focus {
    transform: scale(1.1);
  }

  .global-add-btn button:focus i,
  .global-add-btn button:hover i {
    transform: scale(0.9);
  }

  .global-add-ddmenu {
    border: none;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    width: 250px;
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
  }

  .global-add-dditem {
    color: #333;
    font-weight: bold;
  }

  .global-add-dditem:hover {
    background-color: #f2f2f2;
  }

  /* Define the animation */
  @keyframes bell-ring {
      0% { transform: rotate(0); }
      10% { transform: rotate(-5deg); }
      20% { transform: rotate(10deg); }
      30% { transform: rotate(-10deg); }
      40% { transform: rotate(5deg); }
      50% { transform: rotate(0); }
      100% { transform: rotate(0); }
  }

  /* Add the animation to the bell icon on hover */
  .animated:hover {
      animation-name: bell-ring;
      animation-duration: 2s;
  }
</style>


<!-- NAVBAR --> 
<nav class="main-header navbar navbar-expand navbar-new-grey text-sm">

  <!-- LEFT NAVBAR -->
  <ul class="navbar-nav left-navbar">
    <li class="nav-item">
      <a class="nav-link left-navbar-content" data-widget="pushmenu" href="#" role="button"><i class="fa">&#xf0c9;</i></a>
    </li>

    <!-- CURRENT PAGE NAMES -->
    <li class="nav-item d-none d-sm-inline-block">
      <?php
        $pageTitle = 'Dashboard';
        $dashboardUrl = '#';
        $dashboardText = '';

        if (isset($_GET['page'])) {
          switch ($_GET['page']) {
            case 'categories':
              $pageTitle = 'Categories';
              break;
            case 'units':
              $pageTitle = 'Units';
              break;
            case 'items':
              $pageTitle = 'Inventory Items';
              break;
            case 'stocks':
              $pageTitle = 'Stock Adjustment';
              break;
            case 'set_notification':
              $pageTitle = 'Quantity Range';
              break;
            case 'sales_request':
              $pageTitle = 'Pending Sales Request';
              break;
            case 'sales_request/history':
              $pageTitle = 'Sales Request History';
              break;
            case 'stocks/stockout_adjustment':
              $pageTitle = 'Request Adjustment';
              break;
            case 'purchasing_request':
              $pageTitle = 'Purchasing Request';
              break;
            case 'purchasing_request/history':
              $pageTitle = 'Purchasing Request History';
              break;
            case 'stocks/stockin_adjustment':
              $pageTitle = 'Request Adjustment';
              break;
            case 'leave_request_manager':
              $pageTitle = 'Pending Leave Requests';
              break;
            case 'leave_request_manager/history':
              $pageTitle = 'Leave Request History';
              break;
            case 'leave_request_staff':
              $pageTitle = 'File Leave Request';
              break;
            case 'stocks/stockin_adjustment':
              $pageTitle = 'Request Adjustment';
              break;
            case 'stockExpiration':
              $pageTitle = 'Expiry Tracking';
              break;
            case 'reports/stockin':
              $pageTitle = 'Monthly Stock-In Reports';
              break;
            case 'reports/stockout':
              $pageTitle = 'Monthly Stock-Out Reports';
              break;
            case 'reports/waste':
              $pageTitle = 'Monthly Waste Reports';
              break;
            case 'user/list':
              $pageTitle = 'Users List';
              break;
            case 'user':
              $pageTitle = 'Edit Profle Information';
              break;
            case 'user/manage_user':
              $pageTitle = 'View Account Information';
              break;
            case 'system_info':
              $pageTitle = 'System Information';
              break;
          }

          if ($pageTitle !== 'Dashboard') {
            $dashboardUrl = './';
            $dashboardText = 'Dashboard /';
          }
        }
      ?>

      <a class="left-navbar-content <?php echo $pageTitle === 'Dashboard' ? 'current-page' : ''; ?>" href="<?php echo $dashboardUrl; ?>"><?php echo $dashboardText; ?></a>
      <a class="left-navbar-content <?php echo $pageTitle !== 'Dashboard' ? 'current-page' : ''; ?>" href="<?php echo $pageTitle === 'Dashboard' ? './' : './?page=' . ($_GET['page'] ?? ''); ?>">
        <?php echo $pageTitle; ?>
      </a>
    </li>
  </ul>


  <!-- RIGHT NAVBAR -->
  <ul class="navbar-nav ml-auto">

    <!-- GLOBAL ADD BUTTONS -->
    <div class="dropdown global-add-btn">
      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-plus"></i>
      </button>
      <div class="dropdown-menu global-add-ddmenu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item global-add-dditem" href="javascript:void(0)" id="sc-new-item"><i class="fas fa-plus-square"></i> Item</a>
        <a class="dropdown-item global-add-dditem" href="javascript:void(0)" id="sc-new-category"><i class="fas fa-plus-square"></i> Category</a>
        <a class="dropdown-item global-add-dditem" href="javascript:void(0)" id="sc-new-unit"><i class="fas fa-plus-square"></i> Unit</a>
      </div>
    </div>


    <!-- NOTIFICATION -->
    <div class="dropdown">
      <a class="dropdown-toggle" href="#" role="button" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="far fa-bell fa-lg animated" aria-hidden="true"></i>
          <?php
            // Count the number of expired items in the database
            $expired_items_count = pg_query($conn, "SELECT COUNT(*) AS count FROM wh_stockin_list 
            WHERE expire_date <= NOW() + INTERVAL '7 DAY'
            AND expire_date IS NOT NULL 
            AND expire_date <> '0001-01-01'");
            $expired_items_count = pg_fetch_assoc($expired_items_count)['count'];

            // Count the number of items with quantity alerts
            $query = "SELECT COUNT(*) AS count FROM wh_item_list i 
                      INNER JOIN wh_category_list c ON i.category_id = c.id 
                      INNER JOIN wh_stock_notif s ON s.id = 1 
                      WHERE i.delete_flag = 0 
                      AND ((COALESCE((SELECT SUM(quantity) FROM wh_stockin_list WHERE item_id = i.id),0)) <= s.min_stock 
                                        OR (COALESCE((SELECT SUM(quantity) FROM wh_stockin_list WHERE item_id = i.id),0)) > s.max_stock)";

            // Execute the query
            $result = pg_query($conn, $query);

            // Get the count of overstock items
            $count = pg_fetch_assoc($result)['count'];

            // Count the number of pending ingredient requests
            $pending_requests_count = pg_query($conn, "SELECT COUNT(*) AS count FROM ingredient_request WHERE status IN ('Pending', 'Preparing')");
            $pending_requests_count = pg_fetch_assoc($pending_requests_count)['count'];

            // Count the number of pending leave requests
            $pending_leave_count = pg_query($conn, "SELECT COUNT(*) AS count FROM wh_leave_request WHERE status = 'Pending'");
            $pending_leave_count = pg_fetch_assoc($pending_leave_count)['count'];

            // Count the total number of items in the dropdown menu
            $dropdown_items = array();
            if ($expired_items_count > 0) {
              $dropdown_items[] = '<a class="dropdown-item" href="./?page=stockExpiration"><i class="fas fa-calendar-times text-danger"></i> ' . $expired_items_count . ' Item Expiration Alerts</a>';
            }
            if ($count > 0) {
              $dropdown_items[] = '<a class="dropdown-item" href="./?page=purchasing_request"><i class="fas fa-clipboard-list text-warning"></i> ' . $count . ' Item Quantity Alerts</a>';
            }
            if ($pending_requests_count > 0) {
              $dropdown_items[] = '<a class="dropdown-item" href="./?page=sales_request"><i class="fas fa-cash-register text-info"></i> ' . $pending_requests_count . ' Pending Sales Requests</a>';
            }

            if($_settings->userdata('role') == 'warehouse_manager'){
              if ($pending_leave_count > 0) {
                $dropdown_items[] = '<a class="dropdown-item" href="./?page=leave_request_manager"><i class="fas fa-calendar-alt text-dark"></i> ' . $pending_leave_count . ' Pending Leave Requests</a>';
              }      
            }      
            $dropdown_count = count($dropdown_items);

            // Output the count in the notification badge
            if ($dropdown_count > 0) {
              if ($dropdown_count > 9) {
                echo '<span class="notification-badge">9+</span>';
              } else {
                echo '<span class="notification-badge">' . $dropdown_count . '</span>';
              }
            }
          ?>
      </a>

      <div class="dropdown-menu dropdown-menu-notif">
        <h6 class="dropdown-header">Notifications <?php echo '(' . $dropdown_count . ')'; ?></h6>
        <?php
          if ($dropdown_count == 0) {
            echo '<span class="dropdown-item">No new notification</span>';
          } else {
            foreach ($dropdown_items as $item) {
              echo $item . '<div class="dropdown-divider"></div>';
            }
          }
        ?>
      </div>

    </div>

    
   <!-- PROFILE DROPDOWN -->
    <?php
      // Retrieve user id based on fullname from users table
      $fullname = ucwords($_settings->userdata('fullname'));
      $query = "SELECT id FROM users WHERE fullname = '$fullname'";
      $result = pg_query($conn, $query);
      $user = pg_fetch_assoc($result);
      $user_id = $user['id'];

      // Retrieve latest time_in based on user id from hr_employee_logs table
      $query = "SELECT time_in FROM hr_employee_logs WHERE employeeid = '$user_id' ORDER BY time_in DESC LIMIT 1";
      $result = pg_query($conn, $query);
      $log = pg_fetch_assoc($result);
      $time_in = $log['time_in'];
    ?>
    <li class="nav-item">
      <div class="btn-group nav-link">
        <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
          <?php 
            $userImgUrl = $_settings->userdata('imgurl');
            $userAvatar = validate_image($userImgUrl) ? $userImgUrl : $_settings->userdata('avatar');
          ?>
          <span><img src="<?php echo $userAvatar ?>" class="img-circle elevation-2 user-img" alt="User Image"></span>
          <span class="ml-3"><?php echo ucwords($_settings->userdata('fullname')) ?></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu dropdown-menu-right">
          <div class="card" id="user-profile-card" style="width: 18rem;">
            <img src="<?php echo $userAvatar ?>" class="card-img-top mx-auto d-block mt-3" alt="User Image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
            <div class="card-body d-flex flex-column align-items-center">
              <?php 
                $role = $_settings->userdata('role');
                if ($role == 'warehouse_manager') {
                  $displayRole = 'Manager';
                } else if ($role == 'warehouse_staff') {
                  $displayRole = 'Staff';
                } else {
                  $displayRole = $role;
                }
              ?>
              <h5 class="card-title text-bold"><?php echo ucwords($_settings->userdata('fullname')) ?></h5>
              <h5 class="card-title text-mute mt-1">(<?php echo $displayRole ?>)</h5>
            </div>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-center" href="<?php echo base_url.'admin/?page=user' ?>"><span class="fa fa-user"></span> Edit Profile</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-center text-danger" href="<?php echo base_url.'/classes/Login.php?f=logout' ?>"><span class="fa fa-power-off"></span> Logout</a>
          </div>
          <div class="dropdown-menu-info">
            <?php if ($time_in): ?>
            <p class="text-center mb-0"><small>Time In: <?php echo date('Y-m-d H:i:s', strtotime($time_in)) ?></small></p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </li>

  </ul>
</nav>

<script>
      $(document).ready(function(){
        // CREATE ITEM
        $('#sc-new-item').click(function(){
          uni_modal("<i class='far fa-plus-square'></i> Add New Item ","items/manage_item.php")
        })

        // CREATE CATEGORY 
        $('#sc-new-category').click(function(){
          uni_modal("<i class='far fa-plus-square'></i> Add New Category ","categories/manage_category.php");
        });
      
        // CREATE UNIT
        $('#sc-new-unit').click(function(){
          uni_modal("<i class='far fa-plus-square'></i> Add New Units ","units/manage_unit.php")
        })
      });

      document.addEventListener('ready', function() {
        var dropdownButton = document.querySelector('.dropdown-toggle');
        var userProfileCard = document.querySelector('#user-profile-card');
        
        dropdownButton.addEventListener('click', function() {
          userProfileCard.classList.toggle('d-none');
        });
      });
    </script>

<!-- 
  <div class="dropdown-divider"></div>
  <a class="dropdown-item" href="<?php echo base_url.'/classes/Login.php?f=logout' ?>"><span class="fas fa-sign-out-alt"></span> Sign Out</a> 
  </div> -->
