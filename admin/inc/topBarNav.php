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

  .topBarContent {
    color: white;
    top: -15%;
  }

  .topBarContent:hover {
    color: #e0d7d3;
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
  
</style>


<!-- Navbar --> 
<nav class="main-header navbar navbar-expand navbar-new-grey text-sm">

  <!-- Left navbar links -->
  <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link topBarContent" data-widget="pushmenu" href="#" role="button"><i class="fa">&#xf0c9;</i></a>
      </li>

      <!-- Display Current Page Name -->
      <li class="nav-item d-none d-sm-inline-block">
        <?php 
          $pageTitle = '';
          if(isset($_GET['page'])) {
            switch ($_GET['page']) {
              case 'categories':
                $pageTitle = 'Categories';
                break;
              case 'items':
                $pageTitle = 'Stock Information';
                break;
              case 'stocks':
                $pageTitle = 'Stock Adjustment';
                break;
              case 'setNotif':
                $pageTitle = 'Set Stock Notifications';
                break;
              case 'stockStatus':
                $pageTitle = 'Stock Status Level';
                break;
                case 'stockExpiration':
                  $pageTitle = 'Expired Stocks';
                  break;
              case 'reports/stockin':
                $pageTitle = 'Reports / Stock-In Reports';
                break;
              case 'reports/stockout':
                $pageTitle = 'Reports / Stock-Out Reports';
                break;
              case 'reports/waste':
                $pageTitle = 'Reports / Waste Reports';
                break;
              case 'user/list':
                $pageTitle = 'User List';
                break;
              case 'system_info':
                $pageTitle = 'System Information';
                break;
              default:
                $pageTitle = 'Dashboard';
            }
          } else {
            $pageTitle = 'Dashboard';
          }
        ?>

        <a class="topBarContent" href="./?page=<?php echo isset($_GET['page']) ? $_GET['page'] : 'dashboard'; ?>">
          <?php echo $pageTitle; ?>
        </a>
      </li>
  </ul>


  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">

    <!-- NOTIFICATION -->
    <div class="dropdown">
      <a class="dropdown-toggle" href="#" role="button" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="far fa-bell fa-lg" aria-hidden="true"></i>
          <?php
              // Count the number of expired items in the database
              $expired_items_count = $conn->query("SELECT COUNT(*) AS count FROM stockin_list 
                                                  WHERE expire_date <= DATE_ADD(NOW(), INTERVAL 1 DAY)
                                                  AND expire_date != '0000-00-00'");
              $expired_items_count = $expired_items_count->fetch_assoc()['count'];

              // Define the query to retrieve the total overstock items
              $query = "SELECT COUNT(*) AS count FROM `item_list` i 
                          INNER JOIN category_list c ON i.category_id = c.id 
                          INNER JOIN stock_notif s ON s.id = 1 
                          WHERE i.delete_flag = 0 
                          AND ((COALESCE((SELECT SUM(quantity) FROM `stockin_list` WHERE item_id = i.id),0) - 
                                COALESCE((SELECT SUM(quantity) FROM `stockout_list` WHERE item_id = i.id),0) - 
                                COALESCE((SELECT SUM(quantity) FROM `waste_list` WHERE item_id = i.id),0)) <= s.min_stock OR 
                                (COALESCE((SELECT SUM(quantity) FROM `stockin_list` WHERE item_id = i.id),0) - 
                                COALESCE((SELECT SUM(quantity) FROM `stockout_list` WHERE item_id = i.id),0) - 
                                COALESCE((SELECT SUM(quantity) FROM `waste_list` WHERE item_id = i.id),0)) >= s.max_stock)";

              // Execute the query
              $result = mysqli_query($conn, $query);

              // Get the count of overstock items
              $count = mysqli_fetch_assoc($result)['count'];

              // Count the total number of items in the dropdown menu
              $dropdown_items = array();
              if ($expired_items_count > 0) {
                $dropdown_items[] = '<a class="dropdown-item" href="./?page=stockExpiration"><i class="fas fa-exclamation-circle text-danger"></i> ' . $expired_items_count . ' Items Expired Alerts</a>';
              }
              if ($count > 0) {
                $dropdown_items[] = '<a class="dropdown-item" href="./?page=stockStatus"><i class="fas fa-exclamation-triangle text-warning"></i> ' . $count . ' Items Quantity Alerts</a>';
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
    <li class="nav-item">
      <div class="btn-group nav-link">
            <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
              <span><img src="<?php echo validate_image($_settings->userdata('avatar')) ?>" class="img-circle elevation-2 user-img" alt="User Image"></span>
              <span class="ml-3"><?php echo ucwords($_settings->userdata('firstname').' '.$_settings->userdata('lastname')) ?></span>
              <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu" role="menu">
              <a class="dropdown-item" href="<?php echo base_url.'admin/?page=user' ?>"><span class="fa fa-user"></span> Profile</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="<?php echo base_url.'/classes/Login.php?f=logout' ?>"><span class="fas fa-sign-out-alt"></span> Sign Out</a>
            </div>
        </div>
    </li>

  </ul>
</nav>