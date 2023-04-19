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
              $pageTitle = 'Stock Adjustment';
              break;
            case 'stocks':
              $pageTitle = 'Stock Adjustment';
              break;
            case 'setNotif':
              $pageTitle = 'Stock Alert Notifications';
              break;
            case 'pos-request':
              $pageTitle = 'Stock Sales Request';
              break;
            case 'stockStatus':
              $pageTitle = 'Stock Purchasing Request';
              break;
            case 'stockExpiration':
              $pageTitle = 'Stock Expiration';
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
              $pageTitle = 'User List';
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
    </script>


    <!-- NOTIFICATION -->
    <div class="dropdown">
      <a class="dropdown-toggle" href="#" role="button" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="far fa-bell fa-lg" aria-hidden="true"></i>
          <?php
          // Count the number of expired items in the database
          // Count the number of expired items in the database
          $expired_items_count = pg_query($conn, "SELECT COUNT(*) AS count FROM wh_stockin_list 
          WHERE expire_date <= NOW() + INTERVAL '1 DAY'
          AND expire_date IS NOT NULL 
          AND expire_date <> '0001-01-01'");
          $expired_items_count = pg_fetch_assoc($expired_items_count)['count'];


          $query = "SELECT COUNT(*) AS count FROM wh_item_list i 
                    INNER JOIN wh_category_list c ON i.category_id = c.id 
                    INNER JOIN wh_stock_notif s ON s.id = 1 
                    WHERE i.delete_flag = 0 
                    AND ((COALESCE((SELECT SUM(quantity) FROM wh_stockin_list WHERE item_id = i.id),0) - 
                            COALESCE((SELECT SUM(quantity) FROM wh_stockout_list WHERE item_id = i.id),0) - 
                            COALESCE((SELECT SUM(quantity) FROM wh_waste_list WHERE item_id = i.id),0)) < s.min_stock OR 
                            (COALESCE((SELECT SUM(quantity) FROM wh_stockin_list WHERE item_id = i.id),0) - 
                            COALESCE((SELECT SUM(quantity) FROM wh_stockout_list WHERE item_id = i.id),0) - 
                            COALESCE((SELECT SUM(quantity) FROM wh_waste_list WHERE item_id = i.id),0)) > s.max_stock)";

          // Execute the query
          $result = pg_query($conn, $query);

          // Get the count of overstock items
          $count = pg_fetch_assoc($result)['count'];

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
              <span class="ml-3"><?php echo ucwords($_settings->userdata('fullname')) ?></span>
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