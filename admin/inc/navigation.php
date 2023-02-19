
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
    width: 80px !important;
    height: 80px !important;
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
      <img src="<?php echo validate_image($_settings->info('logo'))?>" alt="Store Logo" class="brand-image img-circle elevation-3 logo-img" style="width: 50%; height: 100%;">
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

              <li class="nav-item dropdown">
                <a href="./?page=items" class="nav-link nav-items">
                  <i class="nav-icon fa fa-cubes"></i>
                  <p style="color:white">
                    Item List
                  </p>
                </a>
              </li> 

              <li class="nav-item dropdown">
                <a href="./?page=stocks" class="nav-link nav-stocks">
                  <i class="nav-icon fas fa-warehouse"></i>
                  <p style="color:white">
                    Stock Manager
                  </p>
                </a>
              </li>
              
              <li class="nav-item dropdown">
                <a href="./?page=minimum_notif" class="nav-link nav-minimum_stocks">
                <i class="nav-icon fas fa-bell"></i>
                  <p style="color:white">
                    Stock Notifications
                  </p>
                </a>
              </li>

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
</script>