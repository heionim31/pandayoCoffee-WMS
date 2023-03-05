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
  }

  .logout-btn {
    opacity: 0.8;
    color: #fff;
    border: 1px solid white;
     margin-top: 6rem;
  }
</style>


<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-white navbar-dark elevation-4 sidebar-no-expand dark-bg">

  <!-- Brand Logo -->
  <a href="<?php echo base_url ?>admin" class="brand-link bg-white text-sm">
    <div class="logos">
      <img src="<?php echo validate_image($_settings->info('logo'))?>" alt="Store Logo" class="brand-image img-circle elevation-3 logo-img" style="width: 55% ; height: 100%; "> 
      <span class="brand-text font-normal shortName"><?php echo $_settings->info('short_name') ?></span>
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

              <li class="nav-item dropdown">
                <a href="./?page=categories" class="nav-link nav-categories">
                  <i class="nav-icon fas fa-th-list"></i>
                  <p style="color:white">
                    Categories
                  </p>
                </a>
              </li> 

              <!-- STOCKS DROP-DOWN -->
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-warehouse"></i>
                  <p style="color:white">
                    Stocks
                    <i class="right fas fa-angle-left"></i>
                    <?php
                      // Count the total number of items with stock status level and expired items
                      $count = 0;

                      // Count items with stock status level
                      $qry_count = $conn->query("SELECT COUNT(*) as count FROM `item_list` i INNER JOIN category_list c ON i.category_id = c.id INNER JOIN stock_notif s ON s.id = 1 WHERE i.delete_flag = 0 AND ((COALESCE((SELECT SUM(quantity) FROM `stockin_list` WHERE item_id = i.id),0) - COALESCE((SELECT SUM(quantity) FROM `stockout_list` WHERE item_id = i.id),0) - COALESCE((SELECT SUM(quantity) FROM `waste_list` WHERE item_id = i.id),0)) <= s.min_stock OR (COALESCE((SELECT SUM(quantity) FROM `stockin_list` WHERE item_id = i.id),0) - COALESCE((SELECT SUM(quantity) FROM `stockout_list` WHERE item_id = i.id),0) - COALESCE((SELECT SUM(quantity) FROM `waste_list` WHERE item_id = i.id),0)) >= s.max_stock)");
                      $count += $qry_count->fetch_assoc()['count'];

                      // Count expired items
                      $qry_count = $conn->query("SELECT COUNT(*) AS count FROM stockin_list WHERE expire_date <= DATE_ADD(NOW(), INTERVAL 1 DAY) AND expire_date != '0000-00-00'");
                      $count += $qry_count->fetch_assoc()['count'];

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
                      <i class="far fa-circle nav-icon"></i>
                      <p style="color:white">Stock Information</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="./?page=stocks" class="nav-link tree-item nav-stocks">
                      <i class="far fa-circle nav-icon"></i>
                      <p style="color:white">Stock Adjustment</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="./?page=setNotif" class="nav-link tree-item nav-setNotif">
                      <i class="far fa-circle nav-icon"></i>
                      <p style="color:white">Set Stock Notification</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="./?page=stockStatus" class="nav-link tree-item nav-stockStatus">
                      <i class="far fa-circle nav-icon"></i>
                      <p style="color:white">Stock Status Level</p>
                      <?php
                        // display the badge element inside the list item
                        $qry_count = $conn->query("SELECT COUNT(*) as count FROM `item_list` i INNER JOIN category_list c ON i.category_id = c.id INNER JOIN stock_notif s ON s.id = 1 WHERE i.delete_flag = 0 AND ((COALESCE((SELECT SUM(quantity) FROM `stockin_list` WHERE item_id = i.id),0) - COALESCE((SELECT SUM(quantity) FROM `stockout_list` WHERE item_id = i.id),0) - COALESCE((SELECT SUM(quantity) FROM `waste_list` WHERE item_id = i.id),0)) <= s.min_stock OR (COALESCE((SELECT SUM(quantity) FROM `stockin_list` WHERE item_id = i.id),0) - COALESCE((SELECT SUM(quantity) FROM `stockout_list` WHERE item_id = i.id),0) - COALESCE((SELECT SUM(quantity) FROM `waste_list` WHERE item_id = i.id),0)) >= s.max_stock)");
                        $count = $qry_count->fetch_assoc()['count'];
                        echo '<span class="badge badge-danger">'.$count.'</span>';
                      ?>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="./?page=stockExpiration" class="nav-link tree-item nav-stockExpiration">
                        <i class="far fa-circle nav-icon"></i>
                        <p style="color:white">Expired Stocks</p>
                        <?php
                        // Count the number of expired items in the database
                        $expired_items_count = $conn->query("
                        SELECT COUNT(*) AS count
                        FROM stockin_list
                        WHERE expire_date <= DATE_ADD(NOW(), INTERVAL 1 DAY) AND expire_date != '0000-00-00'
                        
                        ");
                        $expired_items_count = $expired_items_count->fetch_assoc()['count'];

                        // Display the badge if there are expired items
                        if ($expired_items_count > 0) {
                            echo '<span class="badge badge-danger">'.$expired_items_count.'</span>';
                        }
                        ?>
                      </a>
                  </li>

                </ul>
              </li>



              <!-- REPORTS DROPDOWN -->
              <?php if($_settings->userdata('type') == 1): ?>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-book-open"></i>
                    <p style="color:white">
                      Reports
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>

                  <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                      <a href="./?page=reports/stockin" class="nav-link tree-item nav-reports_stockin">
                        <i class="far fa-circle nav-icon"></i>
                        <p style="color:white">Monthly Stock-In Report</p>
                      </a>
                    </li>

                    <li class="nav-item">
                      <a href="./?page=reports/stockout" class="nav-link tree-item nav-reports_stockout">
                        <i class="far fa-circle nav-icon"></i>
                        <p style="color:white">Monthly Stock-Out Report</p>
                      </a>
                    </li>

                    <li class="nav-item">
                      <a href="./?page=reports/waste" class="nav-link tree-item nav-reports_waste">
                        <i class="far fa-circle nav-icon"></i>
                        <p style="color:white">Monthly Stock-Waste Report</p>
                      </a>
                    </li>
                  </ul>

                </li>

                <li class="nav-header" ><p style="color:white">Maintenance</p></li>

                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=user/list" class="nav-link  nav-user_list" >
                    <i class="nav-icon fas fa-users-cog" ></i>
                    <p style="color:white">
                      User List
                    </p>
                  </a>
                </li>

                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=system_info" class="nav-link nav-system_info">
                    <i class="nav-icon fas fa-tools"></i>
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
    $('.nav-link.active').addClass('bg-gradient-orange')
  })

  // TO SHOW NUMBERS BESIDES DROPDOWN WHEN NOT ACTIVE AND HIDE WHEN THE DROPDOWN IS INACTIVE

</script>