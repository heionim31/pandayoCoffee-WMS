
<style>
  #system-cover{
    background:white;
    width:100%;
    height:45em;
    object-fit:cover;
    object-position:center center;
  }

  table {
    font-size: 12px;
  }
  
  th, td {
    padding: 0.20rem;
    text-align: center;
  }
</style>

<h1 class="text-white text-center">Welcome, <?php echo $_settings->userdata('firstname')." ".$_settings->userdata('lastname') ?>!</h1>
<hr>

<div class="row">

  <!-- TOTAL USERS -->
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      
      <div class="info-box-content">
        <a href="<?php echo base_url ?>admin/?page=user/list" style="color:black;"> 
          
          <span class="info-box-number h5">
            <?php 
                $user = $conn->query("SELECT * FROM users_list")->num_rows;
                echo format_num($user);
              ?>
          </span>
          <span class="info-box-text">Users</span>

        </a>
      </div>
      <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-user-alt" style="font-size:60px"></i></span>

    </div>
  </div>


  <!-- TOTAL CATEGORIES -->
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      
      <div class="info-box-content">
        <a href="<?php echo base_url ?>admin/?page=categories/index" style="color:black;"> 
          
          <span class="info-box-number h5">
            <?php 
              $category = $conn->query("SELECT * FROM category_list where delete_flag = 0 and `status` = 1")->num_rows;
              echo format_num($category);
              ?>
          </span>
          <span class="info-box-text">Categories</span>

        </a> 
      </div>
      <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-th-list" style="font-size:60px; weigth:60px;"></i></span>

    </div>
  </div>
 

  <!-- TOTAL ITEMS -->
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      
      <div class="info-box-content">
        <a href="<?php echo base_url ?>admin/?page=items/index" style="color:black;"> 
          
          <span class="info-box-number h5">
            <?php 
              $items = $conn->query("SELECT id FROM item_list where delete_flag = 0 and `status` = 1")->num_rows;
              echo format_num($items);
            ?>
          </span>
          <span class="info-box-text">Items</b></span>

        </a>
      </div>
      <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-cubes" style="font-size:60px"></i></span>

    </div>
  </div>


  <!-- TOTAL STOCKS -->
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      
      <div class="info-box-content">
        <a href="<?php echo base_url ?>admin/?page=stocks" style="color:black;" > 
          
          <span class="info-box-number h5">
            <?php 
              $stock = $conn->query("SELECT * FROM item_list")->num_rows;
              echo format_num($stock);
            ?>
          </span>
          <span class= "info-box-text">Stocks </span>

        </a>
      </div>
      <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-warehouse"  style="font-size:60px"></i></span>
      
    </div>
  </div>
</div>


  <!-- STOCK NOTIF -->
  <!-- <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h4><b>Low Stock Goods:</b></h4>
        <hr>
        <div class="list-group rounded-0" id="notif-list">
            <php foreach($notif as $row): ?>
                <div class="list-group-item list-group-item-action bg-danger rounded-0 border border-light"><?= $row['name'] ?> has only <b><?= $row['quantity'] ?></b> Stock Left.</div>
            <php endforeach; ?>
        </div>
    </div>
  </div>
</div> -->





<div class="container-fluid">
  <div class="row">

  <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">&nbsp;</h5>
        </div>
        <div class="card-body">
          &nbsp;
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">&nbsp;</h5>
        </div>
        <div class="card-body">
          &nbsp;
        </div>
      </div>
    </div>

    
    <?php
      // Retrieve the 5 most recent items
      $sql = "SELECT * FROM item_list ORDER BY date_created DESC LIMIT 5";
      $result = $conn->query($sql);
      
      // Create an array to store the recent items
      $recent_items = array();
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $recent_items[] = $row;
        }
      }
    ?>

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">Recently Added Items</h5>
        </div>
        <div class="card-body">
          <table class="table table-hover table-bordered">
            <thead>
              <tr class="table-active">
                <th>Name</th>
                <th>Category</th>
                <th>Date Created</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($recent_items as $item): ?>
              <tr>
                <td class="align-middle"><?php echo $item['name']; ?></td>
                <td class="align-middle">
                  <?php 
                    // Retrieve the category name based on the category ID
                    $category_id = $item['category_id'];
                    $category_query = $conn->query("SELECT name FROM category_list WHERE id = $category_id");
                    $category = $category_query->fetch_assoc();
                    echo $category['name']; 
                  ?>
                </td>
                <td class="align-middle"><?php echo date('Y-m-d H:i:s', strtotime($item['date_created'])); ?></td>

              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    

  </div>
</div>


<!-- <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">Top 5 Popular Stock-Out Products</h5>
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            <php
              // Query to get top 5 popular stock-out products
              $sql = "SELECT stockout_list.item_name, SUM(stockout_list.quantity) AS total_quantity, MAX(stockout_list.date_updated) AS latest_date_updated
                      FROM stockout_list
                      GROUP BY stockout_list.item_name
                      ORDER BY total_quantity DESC, latest_date_updated DESC
                      LIMIT 5";
              
              // Execute the query and fetch the results into an array
              $result = mysqli_query($conn, $sql);
              $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

              // Loop through the array and display the data in HTML
              foreach ($rows as $row) {
                echo '<li class="list-group-item">' . $row['item_name'] . '<span class="badge badge-secondary ml-2">' . $row['total_quantity'] . '</span><span class="badge badge-secondary ml-2">' . $row['latest_date_updated'] . '</span></li>';
              }
            ?>
          </ul>
        </div>
      </div>
    </div> -->

<!-- <div class="container mt-4">
  <h3>Stock Level</h3>
  <table class="table table-striped table-bordered mt-4">
    <thead>
      <tr>
        <th>Item Name</th>
        <th>Current Stock Level</th>
        <th>Reorder Level</th>
      </tr>
    </thead>
    <tbody>
      <php
        // Your PHP code to fetch and display the data from the database will go here
      ?>
    </tbody>
  </table>
</div> -->













<!-- DASHBOARD IMAGE -->
<!-- 
<div class="container-fluid text-center">
  <img src="<= validate_image($_settings->info('cover')) ?>" alt="system-cover" id="system-cover" class="img-fluid">
</div> -->
