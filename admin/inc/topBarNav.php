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
    <!-- Navbar Search -->
    <!-- <li class="nav-item">
      <a class="nav-link" data-widget="navbar-search" href="#" role="button">
      <i class="fas fa-search"></i>
      </a>
      <div class="navbar-search-block">
        <form class="form-inline">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit">
              <i class="fas fa-search"></i>
              </button>
              <button class="btn btn-navbar" type="button" data-widget="navbar-search">
              <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </li> -->
    
    <!-- Messages Dropdown Menu -->
    <li class="nav-item">
      <div class="btn-group nav-link">
            <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
              <span><img src="<?php echo validate_image($_settings->userdata('avatar')) ?>" class="img-circle elevation-2 user-img" alt="User Image"></span>
              <span class="ml-3"><?php echo ucwords($_settings->userdata('firstname').' '.$_settings->userdata('lastname')) ?></span>
              <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu" role="menu">
              <a class="dropdown-item" href="<?php echo base_url.'admin/?page=user' ?>"><span class="fa fa-user"></span> My Account</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="<?php echo base_url.'/classes/Login.php?f=logout' ?>"><span class="fas fa-sign-out-alt"></span> Logout</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
      
    </li>
    <!--  <li class="nav-item">
      <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
      <i class="fas fa-th-large"></i>
      </a>
    </li> -->
  </ul>
</nav>