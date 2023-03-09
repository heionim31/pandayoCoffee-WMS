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
      <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-user-alt" style="font-size:60px"></i></span>
      <div class="info-box-content">
      <a href="<?php echo base_url ?>admin/?page=user/list" style="color:black;"> 
        <span class="info-box-text text-right">Total Users</span>
        
        <span class="info-box-number text-right h5">
          
         <?php 
            $user = $conn->query("SELECT * FROM users_list")->num_rows;
            echo format_num($user);
          ?>
          <?php ?>
        </span>
        </a>
      </div>
    </div>
  </div>

  <!-- TOTAL CATEGORIES -->
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-th-list" style="font-size:60px; weigth:60px;"></i></span>
      <div class="info-box-content">
      <a href="<?php echo base_url ?>admin/?page=categories/index" style="color:black;"> 
        <span class="info-box-text text-right">Total Categories</span>
        <span class="info-box-number text-right h5">
          <?php 
            $category = $conn->query("SELECT * FROM category_list where delete_flag = 0 and `status` = 1")->num_rows;
            echo format_num($category);
          ?>
          <?php ?>
        </span>
        </a> 
      </div>
    </div>
  </div>
 
  <!-- TOTAL ITEMS -->
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-cubes" style="font-size:60px"></i></span>
      <div class="info-box-content">
      <a href="<?php echo base_url ?>admin/?page=items/index" style="color:black;"> 
        <span class="info-box-text text-right">Total Items</b></span>
        <span class="info-box-number text-right  h5">
          <?php 
            $items = $conn->query("SELECT id FROM item_list where delete_flag = 0 and `status` = 1")->num_rows;
            echo format_num($items);
          ?>
          <?php ?>
        </span>
        </a>
      </div>
    </div>
  </div>

  <!-- TOTAL OF OVER STOCKS -->
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-warehouse"  style="font-size:60px"></i></span>
      <div class="info-box-content">
        <a href="<?php echo base_url ?>admin/?page=stocks" style="color:black;" > 
          <span class= "info-box-text text-right">Total Over stocks</span>

          <span class="info-box-number text-right h5">
            <?php 
              // Define the query to retrieve the latest stockin_list records for each item_id
              $query = "SELECT item_list.id, item_list.name, 
                  (SELECT min_stock FROM stock_notif LIMIT 1) AS min_stock, 
                  (SELECT max_stock FROM stock_notif LIMIT 1) AS max_stock,
                  (SELECT quantity FROM stockin_list WHERE item_id = item_list.id 
                  ORDER BY date DESC LIMIT 1) AS latest_quantity,
                  (COALESCE((SELECT SUM(quantity) FROM `stockin_list` where item_id = item_list.id),0) - 
                  COALESCE((SELECT SUM(quantity) FROM `stockout_list` where item_id = item_list.id),0) - 
                  COALESCE((SELECT SUM(quantity) FROM `waste_list` where item_id = item_list.id),0)) as `available`
              FROM item_list 
              ORDER BY date_updated DESC";

              // Execute the query
              $result = mysqli_query($conn, $query);

              // Initialize counter for overstock items
              $overstock_count = 0;

              // Loop through each item
              while ($row = mysqli_fetch_assoc($result)) {
                $name = $row['name'];
                $min_stock = $row['min_stock'];
                $max_stock = $row['max_stock'];
                $available_quantity = (int)$row['available'];

                // Check if item is overstocked
                if ($available_quantity >= $max_stock) {
                  $overstock_count++;
                }
              }

              // Output the count of overstock items
              echo format_num($overstock_count);
            ?>
          </span>
        </a>
      </div>
    </div>
  </div>

  <!-- TOTAL OF LOW STOCK -->
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-warehouse"  style="font-size:60px"></i></span>
      <div class="info-box-content">
        <a href="<?php echo base_url ?>admin/?page=stocks" style="color:black;" > 
          <span class= "info-box-text text-right">Total Low stocks</span>

          <span class="info-box-number text-right h5">
            <?php 
              // Define the query to retrieve the latest stockin_list records for each item_id
              $query = "SELECT item_list.id, item_list.name, 
                  (SELECT min_stock FROM stock_notif LIMIT 1) AS min_stock, 
                  (SELECT max_stock FROM stock_notif LIMIT 1) AS max_stock,
                  (SELECT quantity FROM stockin_list WHERE item_id = item_list.id 
                  ORDER BY date DESC LIMIT 1) AS latest_quantity,
                  (COALESCE((SELECT SUM(quantity) FROM `stockin_list` where item_id = item_list.id),0) - 
                  COALESCE((SELECT SUM(quantity) FROM `stockout_list` where item_id = item_list.id),0) - 
                  COALESCE((SELECT SUM(quantity) FROM `waste_list` where item_id = item_list.id),0)) as `available`
              FROM item_list 
              ORDER BY date_updated DESC";

              // Execute the query
              $result = mysqli_query($conn, $query);

              // Initialize counter for low stock items
              $lowstock_count = 0;

              // Loop through each item
              while ($row = mysqli_fetch_assoc($result)) {
                $name = $row['name'];
                $min_stock = $row['min_stock'];
                $max_stock = $row['max_stock'];
                $available_quantity = (int)$row['available'];

                // Check if item is low stocked
                if ($available_quantity <= $min_stock && $available_quantity != 0) {
                  $lowstock_count++;
                }
              }

              // Output the count of low stock items
              echo format_num($lowstock_count);
            ?>
          </span>
        </a>
      </div>
    </div>
  </div>

  <!-- TOTAL OF OUT OF STOCK -->
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-warehouse"  style="font-size:60px"></i></span>
      <div class="info-box-content">
        <a href="<?php echo base_url ?>admin/?page=stocks" style="color:black;" > 
          <span class= "info-box-text text-right">Total Out of stocks</span>

          <span class="info-box-number text-right h5">
            <?php 
              // Define the query to retrieve the latest stockin_list records for each item_id
              $query = "SELECT item_list.id, item_list.name, 
                  (SELECT min_stock FROM stock_notif LIMIT 1) AS min_stock, 
                  (SELECT max_stock FROM stock_notif LIMIT 1) AS max_stock,
                  (SELECT quantity FROM stockin_list WHERE item_id = item_list.id 
                  ORDER BY date DESC LIMIT 1) AS latest_quantity,
                  (COALESCE((SELECT SUM(quantity) FROM `stockin_list` where item_id = item_list.id),0) - 
                  COALESCE((SELECT SUM(quantity) FROM `stockout_list` where item_id = item_list.id),0) - 
                  COALESCE((SELECT SUM(quantity) FROM `waste_list` where item_id = item_list.id),0)) as `available`
              FROM item_list 
              ORDER BY date_updated DESC";

              // Execute the query
              $result = mysqli_query($conn, $query);

              // Initialize counter for Out of Stock items
              $outofstock_count = 0;

              // Loop through each item
              while ($row = mysqli_fetch_assoc($result)) {
                $name = $row['name'];
                $min_stock = $row['min_stock'];
                $max_stock = $row['max_stock'];
                $available_quantity = (int)$row['available'];

                // Check if item is Out of Stocked
                if ($available_quantity == 0 ) {
                  $outofstock_count++;
                }
              }

              // Output the count of Out of Stock items
              echo format_num($outofstock_count);
            ?>
          </span>
        </a>
      </div>
    </div>
  </div>

  <!-- TOTAL OF EXPIRED ITEMS -->
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-warehouse"  style="font-size:60px"></i></span>
      <div class="info-box-content">
        <a href="<?php echo base_url ?>admin/?page=stocks" style="color:black;" > 
          <span class= "info-box-text text-right">Total Out of stocks</span>

          <span class="info-box-number text-right h5">
            <?php 
              // Define the query to retrieve the latest stockin_list records for each item_id
              $query = "SELECT item_list.id, item_list.name, 
                  (SELECT min_stock FROM stock_notif LIMIT 1) AS min_stock, 
                  (SELECT max_stock FROM stock_notif LIMIT 1) AS max_stock,
                  (SELECT quantity FROM stockin_list WHERE item_id = item_list.id 
                  ORDER BY date DESC LIMIT 1) AS latest_quantity,
                  (COALESCE((SELECT SUM(quantity) FROM `stockin_list` where item_id = item_list.id),0) - 
                  COALESCE((SELECT SUM(quantity) FROM `stockout_list` where item_id = item_list.id),0) - 
                  COALESCE((SELECT SUM(quantity) FROM `waste_list` where item_id = item_list.id),0)) as `available`
              FROM item_list 
              ORDER BY date_updated DESC";

              // Execute the query
              $result = mysqli_query($conn, $query);

              // Initialize counter for Out of Stock items
              $outofstock_count = 0;

              // Loop through each item
              while ($row = mysqli_fetch_assoc($result)) {
                $name = $row['name'];
                $min_stock = $row['min_stock'];
                $max_stock = $row['max_stock'];
                $available_quantity = (int)$row['available'];

                // Check if item is Out of Stocked
                if ($available_quantity == 0 ) {
                  $outofstock_count++;
                }
              }

              // Output the count of Out of Stock items
              echo format_num($outofstock_count);
            ?>
          </span>
        </a>
      </div>
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


<div class="container-fluid">
  <div class="row">

    <!-- TABLE FOR STOCK ALERTS -->
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">Stock Alerts</h5>
          <div class="card-tools">
            <a href="./?page=stockStatus" class="btn btn-flat btn-success"></span>View All</a>
          </div>
        </div>
        <div class="card-body">
          <?php
            // Define the query to retrieve the latest stockin_list records for each item_id
            $query = "SELECT item_list.id, item_list.name, 
                (SELECT min_stock FROM stock_notif LIMIT 1) AS min_stock, 
                (SELECT max_stock FROM stock_notif LIMIT 1) AS max_stock,
                (SELECT quantity FROM stockin_list WHERE item_id = item_list.id 
                ORDER BY date DESC LIMIT 1) AS latest_quantity,
                (COALESCE((SELECT SUM(quantity) FROM `stockin_list` WHERE item_id = item_list.id),0) - 
                COALESCE((SELECT SUM(quantity) FROM `stockout_list` WHERE item_id = item_list.id),0)) AS `available`
            FROM item_list 
            ORDER BY date_updated DESC LIMIT 5";

            // Execute the query
            $result = mysqli_query($conn, $query);
          ?>

          <?php while ($row = mysqli_fetch_assoc($result)) {
            $name = $row['name'];
            $min_stock = $row['min_stock'];
            $max_stock = $row['max_stock'];
            $available_quantity = (int)$row['available'];

            // Initialize variables
            $message = '';
            $class = '';

            if ($available_quantity == 0) {
              $message = "Out of Stock: " . $name . " is currently out of stock. Please consider ordering more.";
              $class = "alert alert-danger";
            } elseif ($available_quantity <= $min_stock ) {
              $message = "Low Stock: Only " . $available_quantity . " of " . $name . " is available. Please consider ordering more.";
              $class = "alert alert-warning";
            } elseif ($available_quantity >= $max_stock) {
              $message = "Over Stock: You have " . $available_quantity . " too many " . $name . ". Consider reducing your order to save costs.";
              $class = "alert alert-info";
            }

            // Output row with status message
            echo '<div class="' . $class . '">' .
                '<strong>' . $message . '</strong>' .
                '</div>';
          } ?>
              
        </div>
      </div>
    </div>
    
    <!-- TABLE FOR RECENTLY ADDED ITEMS -->
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">Recently Added Items</h5>
        </div>
        <div class="card-body">
          <table class="table table-hover table-striped">
            <thead>
              <tr>
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
                <td class="align-middle"><?php echo date('Y-m-d h:i A', strtotime($item['date_created'])); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- DASHBOARD IMAGE -->
<!-- 
<div class="container-fluid text-center">
  <img src="<= validate_image($_settings->info('cover')) ?>" alt="system-cover" id="system-cover" class="img-fluid">
</div> -->
