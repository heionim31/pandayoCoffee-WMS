
<style>
  #system-cover{
    background:white;
    width:100%;
    height:45em;
    object-fit:cover;
    object-position:center center;
  }
</style>
<h1 class="">Welcome, <?php echo $_settings->userdata('firstname')." ".$_settings->userdata('lastname') ?>!</h1>
<hr>
<div class="row">

  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-user-alt" style="font-size:60px"></i></span>
      <div class="info-box-content">
      <a href="<?php echo base_url ?>admin/?page=user/list" style="color:black;"> 
        <span class="info-box-text">Users</span>
        
        <span class="info-box-number text-right h5">
          
         <?php 
            $user = $conn->query("SELECT * FROM users_list")->num_rows;
            echo format_num($user);
          ?>
          <?php ?>
        </span>
        </a>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
 
  <!-- /.col -->

  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-th-list" style="font-size:60px; weigth:60px;"></i></span>
      <div class="info-box-content">
      <a href="<?php echo base_url ?>admin/?page=categories/index" style="color:black;"> 
        <span class="info-box-text">Categories</span>
        <span class="info-box-number text-right h5">
       
          <?php 
            $category = $conn->query("SELECT * FROM category_list where delete_flag = 0 and `status` = 1")->num_rows;
            echo format_num($category);
          ?>
          <?php ?>
        </span>
        </a> 
      </div>
     
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
 
  <!-- /.col -->
  
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-white elevation-1"><i class="fas fa-cubes" style="font-size:60px"></i></span>
      <div class="info-box-content">
      <a href="<?php echo base_url ?>admin/?page=items/index" style="color:black;"> 
        <span class="info-box-text">Items</b></span>
        <span class="info-box-number text-right  h5">
          <?php 
            $items = $conn->query("SELECT id FROM item_list where delete_flag = 0 and `status` = 1")->num_rows;
            echo format_num($items);
          ?>
          <?php ?>
        </span>
        </a>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-warehouse"  style="font-size:60px"></i></span>
      <div class="info-box-content">
        <a href="<?php echo base_url ?>admin/?page=stocks" style="color:black;" > 
          <span class= "info-box-text">Stocks </span>

          <span class="info-box-number text-right h5">
          <?php 
              $stock = $conn->query("SELECT * FROM item_list")->num_rows;
              echo format_num($stock);
            ?>
            <?php ?>
          </span>
        </a>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>

  <!-- /.col -->
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h4><b>Low Stock Goods:</b></h4>
        <hr>
        <div class="list-group rounded-0" id="notif-list">
            
        </div>
    </div>
  </div>
</div>


<div class="container-fluid text-center">
  <img src="<?= validate_image($_settings->info('cover')) ?>" alt="system-cover" id="system-cover" class="img-fluid">
</div>
