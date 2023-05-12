<style>
  .sidebar a.nav-link.active{
    color:#fff !important;
  }

  .logos {
    display: flex;
    align-items: center;
    flex-direction: column;
    height: 5%; 
  }

  .logos .logo-img {
    max-width: 100px;
    height: 100px !important;
    max-height: unset;
  }

  .shortName {
    margin-top: 0.3rem;
    margin-bottom: -0.5rem;
  }

  .nav-mt {
    margin-top: 35%;
    margin-bottom: 20%;
  }

  .logout-btn {
    opacity: 0.8;
    color: #fff;
    border: 1px solid white;
     /* margin-top: 6rem; */
  }

  li p {
    font-size: 0.9rem;
  }

</style>


<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-white navbar-dark elevation-4 sidebar-no-expand dark-bg">

  <!-- Brand Logo -->
  <a href="<?php echo base_url ?>admin" class="brand-link bg-white text-sm">
    <div class="logos">
      <img src="<?php echo validate_image($_settings->info('logo'))?>" alt="Store Logo" class="brand-image img-circle elevation-3 logo-img" style="width: 55% ; height: 100%; "> 
      <span class="h6 brand-text font-bold shortName mt-3"><?php echo $_settings->info('short_name') ?></span>
    </div>
  </a>

  </br>
  
  <!-- Sidebar -->
  <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden">

    <div class="os-resize-observer-host observed">
      <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
    </div>

    <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
      <div class="os-resize-observer"></div>
    </div>

    <div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 646px;"></div>

    <div class="os-padding  nav-mt">
      <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
        <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">

          <!-- Sidebar user panel (optional) -->
          <div class="clearfix"></div>

          <!-- Sidebar Menu -->
          <nav class="mt-1">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-compact nav-flat nav-child-indent nav-collapse-hide-child" data-widget="treeview" role="menu" data-accordion="false">

              <li class="nav-item dropdown">
                <a href="./" class="nav-link nav-home">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p style="color:white">
                    Dashboard
                  </p>
                </a>
              </li> 

              <?php if($_settings->userdata('role') == 'warehouse_manager'): ?>
              <li class="nav-item dropdown">
                <a href="./?page=categories" class="nav-link nav-categories">
                  <i class="nav-icon fas fa-cubes"></i>
                  <p style="color:white">
                    Categories
                  </p>
                </a>
              </li>
              <?php endif; ?>

              <?php if($_settings->userdata('role') == 'warehouse_manager'): ?>
              <li class="nav-item dropdown">
                <a href="./?page=units" class="nav-link nav-units">
                  <i class="nav-icon fas fa-balance-scale"></i>
                  <p style="color:white">
                    Units
                  </p>
                </a>
              </li> 
              <?php endif; ?>

              <!-- STOCKS DROP-DOWN -->
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-box-open"></i>
                  <p style="color:white">
                    Stock Manager
                    <i class="right fas fa-angle-left"></i>
                    <?php
                      // Count the total number of items with stock status level and expired items
                      $count = 0;

                      // Count expired items
                      $qry_count = pg_query($conn, "SELECT COUNT(*) AS count FROM wh_stockin_list WHERE (expire_date <= NOW() + INTERVAL '1 DAY' AND expire_date != '0001-01-01') OR (expire_date IS NULL)");
                      $count += pg_fetch_assoc($qry_count)['count'];

                      // Display the total count if there are items with stock status level or expired items
                      if ($count > 0) {
                          echo '<span class="badge badge-danger">'.$count.'</span>';
                      }
                      ?>
                  </p>
                </a>

                <ul class="nav nav-treeview" style="display: none;">
                  <li class="nav-item">
                    <a href="./?page=items" class="nav-link tree-item nav-items">
                      <i class="fas fa-chart-line nav-icon"></i>
                      <p style="color:white">Inventory Items</p>
                    </a>
                  </li>

                  <?php if($_settings->userdata('role') == 'warehouse_manager'): ?>
                    <li class="nav-item">
                      <a href="./?page=set_notification" class="nav-link tree-item nav-set_notification">
                        <i class="fas fa-envelope nav-icon"></i>
                        <p style="color:white">Quantity Range</p>
                      </a>
                    </li>
                  <?php endif; ?>

                  <li class="nav-item">
                    <a href="./?page=stockExpiration" class="nav-link tree-item nav-stockExpiration">
                        <i class="fas fa-calendar-times nav-icon"></i>
                        <p style="color:white">Expiry Tracking</p>
                        <?php
                        // Count the number of expired items in the database
                        $expired_items_count_query = "
                            SELECT COUNT(*) AS count
                            FROM wh_stockin_list
                            WHERE expire_date <= NOW() + INTERVAL '7 DAY' AND expire_date IS NOT NULL
                        ";
                        $expired_items_count_result = pg_query($conn, $expired_items_count_query);
                        $expired_items_count = pg_fetch_assoc($expired_items_count_result)['count'];

                        // Display the badge if there are expired items
                        if ($expired_items_count > 0) {
                            echo '<span class="badge badge-danger">'.$expired_items_count.'</span>';
                        }
                        ?>
                    </a>
                  </li>

                </ul>
              </li>


              <!-- REQUEST DROP-DOWN -->
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-file-alt"></i>
                  <p style="color:white">
                    Stock Requests
                    <i class="right fas fa-angle-left"></i>
                    <?php
                      // Count the total number of sales and purchasing requests
                      $count_query = "SELECT SUM(count) as total FROM (
                                      SELECT COUNT(*) as count FROM ingredient_request WHERE status NOT IN ('Approved', 'Received', 'Declined')
                                      UNION ALL
                                      SELECT COUNT(*) as count FROM wh_item_list i 
                                        INNER JOIN wh_category_list c ON i.category_id = c.id 
                                        INNER JOIN wh_stock_notif s ON s.id = 1 
                                        WHERE i.delete_flag = 0 
                                        AND ((COALESCE((SELECT SUM(quantity) FROM wh_stockin_list WHERE item_id = i.id),0)) <= s.min_stock 
                                        OR (COALESCE((SELECT SUM(quantity) FROM wh_stockin_list WHERE item_id = i.id),0)) > s.max_stock)
                                      ) as subquery";
                      $count_result = pg_query($conn, $count_query);
                      $total = pg_fetch_assoc($count_result)['total'];

                      // Display the badge element with the total count
                      if ($total > 0) {
                        echo '<span class="badge badge-danger">'.$total.'</span>';
                      }
                    ?>
                  </p>
                </a>

                <ul class="nav nav-treeview" style="display: none;">
                  <li class="nav-item">
                    <a href="./?page=sales_request" class="nav-link tree-item nav-sales_request">
                      <i class="fas fa-cash-register nav-icon"></i>
                      <p style="color:white">Sales Request</p>
                      <?php
                        // Count the number of leave requests
                        $count_query = "SELECT COUNT(*) as count FROM ingredient_request WHERE status NOT IN ('Approved', 'Received', 'Declined')";
                        $count_result = pg_query($conn, $count_query);
                        $count = pg_fetch_assoc($count_result)['count'];

                        // Display the badge element with the total count
                        echo '<span class="badge badge-danger">'.$count.'</span>';
                      ?>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="./?page=purchasing_request" class="nav-link tree-item nav-purchasing_request">
                      <i class="fas fa-clipboard-list nav-icon"></i>
                      <p style="color:white">Purchasing Request</p>
                      <?php
                        // Count the items marked as overstock, lowstock, and out of stock
                        $count_query = "SELECT COUNT(*) as count FROM wh_item_list i 
                                        INNER JOIN wh_category_list c ON i.category_id = c.id 
                                        INNER JOIN wh_stock_notif s ON s.id = 1 
                                        WHERE i.delete_flag = 0 
                                        AND ((COALESCE((SELECT SUM(quantity) FROM wh_stockin_list WHERE item_id = i.id),0)) <= s.min_stock 
                                        OR (COALESCE((SELECT SUM(quantity) FROM wh_stockin_list WHERE item_id = i.id),0)) > s.max_stock)";
                        $count_result = pg_query($conn, $count_query);
                        $count = pg_fetch_assoc($count_result)['count'];

                        // Display the badge element with the total count
                        echo '<span class="badge badge-danger">'.$count.'</span>';
                      ?>
                    </a>
                  </li>
                </ul>
              </li>

              
              <!-- REPORTS DROPDOWN -->
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-chart-bar"></i>
                    <p style="color:white">
                      Monthly Reports
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>

                  <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                      <a href="./?page=reports/stockin" class="nav-link tree-item nav-reports_stockin">
                        <i class="far fa-calendar-alt nav-icon"></i>
                        <p style="color:white">Monthly Stock-In</p>
                      </a>
                    </li>

                    <li class="nav-item">
                      <a href="./?page=reports/stockout" class="nav-link tree-item nav-reports_stockout">
                        <i class="fas fa-table nav-icon flex-direction: column" ></i>
                        <p style="color:white">Monthly Stock-Out</p>
                      </a>
                    </li>

                    <li class="nav-item">
                      <a href="./?page=reports/waste" class="nav-link tree-item nav-reports_waste">
                        <i class="fas fa-trash-alt nav-icon"></i>
                        <p style="color:white">Monthly Waste</p>
                      </a>
                    </li>
                  </ul>
                </li>


              <?php if($_settings->userdata('role') !== 'warehouse_manager'): ?>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=leave_request_staff" class="nav-link  nav-leave_request_staff" >
                    <i class="nav-icon fas fa-calendar-alt flex-direction: column" ></i>
                    <p style="color:white">
                      File a Leave
                    </p>
                  </a>
                </li>
              <?php endif; ?>


              <?php if($_settings->userdata('role') == 'warehouse_manager'): ?>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=leave_request_manager" class="nav-link  nav-leave_request_manager">
                    <i class="nav-icon fas fa-calendar-alt flex-direction: column"></i>
                    <p style="color:white">Leave Requests</p>
                    <?php
                      // Count the number of leave requests
                      $count_query = "SELECT COUNT(*) as count FROM wh_leave_request WHERE status = 'Pending'";
                      $count_result = pg_query($conn, $count_query);
                      $count = pg_fetch_assoc($count_result)['count'];

                      if ($count > 0) {
                        // Display the badge element with the total count
                        echo '<span class="badge badge-danger">'.$count.'</span>';
                      }
                    ?>
                  </a>
                </li>
              <?php endif; ?>

              
              <?php if($_settings->userdata('role') == 'warehouse_manager'): ?>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=user/list" class="nav-link  nav-user_list" >
                    <i class="nav-icon fas fa-users flex-direction: column" ></i>
                    <p style="color:white">
                      Users List
                    </p>
                  </a>
                </li>

                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=system_info" class="nav-link nav-system_info">
                    <i class="nav-icon fas fa-cog"></i>
                    <p style="color:white">
                      System Information
                    </p>
                  </a>
                </li>
              <?php endif; ?>

            </ul>
            
          </nav>
        </div>
      </div>
    </div>

    
    <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
      <div class="os-scrollbar-track">
        <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
      </div>
    </div>

    <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
      <div class="os-scrollbar-track">
        <div class="os-scrollbar-handle" style="height: 55.017%; transform: translate(0px, 0px);"></div>
      </div>
    </div>

    <div class="os-scrollbar-corner"></div>
  </div>
</aside>
     
     
<script>
  $(document).ready(function(){
    var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
    page = page.replace(/\//g,'_');
    console.log(page, $('.nav-link.nav-'+page)[0])

    if($('.nav-link.nav-'+page).length > 0){
            $('.nav-link.nav-'+page).addClass('active')

      if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
          $('.nav-link.nav-'+page).addClass('active')
        $('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
      }

      if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
        $('.nav-link.nav-'+page).parent().addClass('menu-open')
      }
    }
    
    // ACTIVE HOVER
    // $('.nav-link.active').addClass('bg-gradient-orange')
    // $('.nav-link.active').css('background-color', '#cc891d');
    // $('.nav-link.active').css('background-image', 'linear-gradient(to right, #cc891d 70%, #f5d68e 100%)');
    $('.nav-link.active').css('background-image', 'linear-gradient(to right,  #8B4513 20%, #f7b360  90%)');

  })
</script>