<!-- Bootstrap CSS -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->

<!-- Import Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

  .alert-container {
    position: fixed;
    bottom: 0;
    right: 0;
    margin: 10px;
    z-index: 9999;
  }

  .alert-container .alert {
    display: none;
    position: relative;
    padding: 10px;
    color: #fff;
    font-size: 16px;
    width: 300px;
    opacity: 0.8;
  }

  .alert-container .low-stock-alert {
    background-color: #ff9800;
  }

  .alert-container .out-of-stock-alert {
    background-color: #f44336;
  }

  .alert-container .over-stock-alert {
    background-color: #3E92CC;
  }

  .alert-container .expired-alert {
    background-color: #333333;
  }

  .alert-container .alert i {
    margin-right: 10px;
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
      <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-chart-line text-info" style="font-size:60px"></i></span>
      <div class="info-box-content">
        <a href="<?php echo base_url ?>admin/?page=stockStatus" style="color:black;" > 
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
                  COALESCE((SELECT SUM(quantity) FROM `stockout_list` where item_id = item_list.id),0)) as `available`
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
      <span class="info-box-icon bg-gradient-light elevation-1"><i class="fa fa-angle-double-down text-warning" style="font-size:60px"></i></span>
      <div class="info-box-content">
        <a href="<?php echo base_url ?>admin/?page=stockStatus" style="color:black;" > 
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
                  COALESCE((SELECT SUM(quantity) FROM `stockout_list` where item_id = item_list.id),0)) as `available`
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
      <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-warehouse text-danger"  style="font-size:60px"></i></span>
      <div class="info-box-content">
        <a href="<?php echo base_url ?>admin/?page=stockStatus" style="color:black;" > 
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
                  COALESCE((SELECT SUM(quantity) FROM `stockout_list` where item_id = item_list.id),0)) as `available`
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
      <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-calendar-times"  style="font-size:60px"></i></span>
        <div class="info-box-content">
          <a href="<?php echo base_url ?>admin/?page=stockExpiration" style="color:black;" > 
            <span class= "info-box-text text-right">Total Expired stocks</span>

              <span class="info-box-number text-right h5">
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
                            echo '<span class="text">'.$expired_items_count.'</span>';
                        }
                        ?>
          </span>
        </a>
      </div>
    </div>
  </div>

  
  <!-- POP-UP ALERT BOX -->
  <div class="alert-container">
    <div class="out-of-stock-alert alert" style="<?php if ($outofstock_count == 0) { echo 'display:none;'; } ?>">
      <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <?php echo format_num($outofstock_count); ?> Out of Stock Items
    </div>
    <div class="low-stock-alert alert" style="<?php if ($lowstock_count == 0) { echo 'display:none;'; } ?>">
      <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <?php echo format_num($lowstock_count); ?> Low Stock Items
    </div>
    <div class="over-stock-alert alert" style="<?php if ($overstock_count == 0) { echo 'display:none;'; } ?>">
      <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <?php echo format_num($overstock_count); ?> Over Stock Items
    </div>
    <div class="expired-alert alert" style="<?php if ($expired_items_count == 0) { echo 'display:none;'; } ?>">
      <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <?php echo format_num($expired_items_count); ?> Expired Items
    </div>
  </div>

  <script>
    $(document).ready(function() {
      var overStockCount = <?php echo $overstock_count; ?>;
      var lowStockCount = <?php echo $lowstock_count; ?>;
      var outOfStockCount = <?php echo $outofstock_count; ?>;
      var expiredStockCount = <?php echo $expired_items_count; ?>;

      if (outOfStockCount > 0) {
        $(".out-of-stock-alert").fadeIn().delay(3500).fadeOut();
      }

      if (lowStockCount > 0) {
        setTimeout(function() {
          $(".low-stock-alert").fadeIn().delay(3500).fadeOut();
        }, 500);
      }

      if (overStockCount > 0) {
        setTimeout(function() {
        $(".over-stock-alert").fadeIn().delay(3500).fadeOut();
        }, 1000);
      }

      if (expiredStockCount > 0) {
        setTimeout(function() {
        $(".expired-alert").fadeIn().delay(3500).fadeOut();
        }, 1500);
      }
      
    });
  </script>

</div>

<!-- BAR CHART -->
<div class="container-fluid">
  <div class="row">
      <div class="container-fluid">
        <div class="row">

          <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">TOP STOCK-IN LIST ITEMS</h5>
              </div>
              <div class="card-body">
                <!-- Chart container -->
                <canvas id="top-items-chart"></canvas>
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
                      <th>#</th>
                      <th>Name</th>
                      <th>Category</th>
                      <th>Date Created</th>
                    </tr>
                  </thead>
                  <tbody>
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

                    <?php $id = 1; foreach($recent_items as $item): ?>
                    <tr>
                      <td class="align-middle"><?php echo $id++; ?></td>
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
  </div>
</div>


<!-- TOP 5 STOCK-IN ITEMS -->
<div class="container-fluid">
  <div class="row">
  
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">Top Stock-In Items</h5>
        </div>
        <div class="card-body">
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Unit</th>
                <th>Total Quantity</th>
                <th>Date Updated</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Execute the SQL query
                $sql = "SELECT item_list.name AS item_name, item_list.unit, 
                        IFNULL(stockin_list_deleted.total_quantity, 0) + IFNULL(stockin_list.total_quantity, 0) AS total_quantity, 
                        MAX(IFNULL(stockin_list.date_updated, stockin_list_deleted.date_updated)) AS date_updated
                        FROM item_list LEFT JOIN (SELECT item_id, SUM(quantity) AS total_quantity, MAX(date_updated) AS date_updated
                        FROM stockin_list_deleted GROUP BY item_id) AS stockin_list_deleted ON item_list.id = stockin_list_deleted.item_id
                        LEFT JOIN (SELECT item_id, SUM(quantity) AS total_quantity, MAX(date_updated) AS date_updated FROM stockin_list 
                        GROUP BY item_id) AS stockin_list ON item_list.id = stockin_list.item_id GROUP BY item_list.id HAVING total_quantity > 0 
                        ORDER BY total_quantity DESC LIMIT 5;";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                  $i = 1;
                  $items = array();
                  while($row = $result->fetch_assoc()) {
                      $item_name = $row['item_name'] ?? 'Unknown';
                      $unit = $row['unit'] ?? 'Unknown';
                      $total_quantity = floor($row['total_quantity']);
                      $date_updated = $row['date_updated'];
              
                      ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $item_name; ?></td>
                        <td><?php echo $unit; ?></td>
                        <td><?php echo $total_quantity; ?></td>
                        <td><?php echo $date_updated; ?></td>
                      </tr>
                      <?php
                      $i++;
              
                      $item = array(
                          'name' => $item_name,
                          'quantity' => (int)$total_quantity
                      );
                      array_push($items, $item);
                  }
              } else {
                  // output no data message
                  ?>
                  <tr>
                    <td colspan='5' class='text-center'>No data available</td>
                  </tr>
                  <?php
              }
              
              ?>
            </tbody>

          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
  // Get the table data
  let items = <?php echo json_encode($items); ?>;

  // Create an array of item names and their quantities
  let itemNames = items.map(item => item.name);
  let itemQuantities = items.map(item => item.quantity);

  // Create a chart using Chart.js
  let ctx = document.getElementById('top-items-chart').getContext('2d');
  let chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: itemNames,
      datasets: [{
        label: 'Top Stock-In Items',
        data: itemQuantities,
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
        ],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true
          }
        }]
      }
    }
  });
</script>


<!-- TOP 5 STOCK-OUT ITEMS -->
<div class="container-fluid">
  <div class="row">

    <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Top Stock-Out Items</h5>
          </div>
          <div class="card-body">
          
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Unit</th>
                <th>Total Quantity</th>
                <th>Date Created</th>
              </tr>
            </thead>
            <tbody>
             <?php
              // Execute the SQL query
              $sql = "SELECT item_list.name AS item_name, item_list.unit, SUM(IFNULL(stockout_list.quantity, 0)) AS total_quantity, MAX(IFNULL(stockout_list.date_updated, 'N/A')) AS date_updated
              FROM item_list
              LEFT JOIN stockout_list ON item_list.id = stockout_list.item_id
              GROUP BY item_list.id
              HAVING total_quantity > 0
              ORDER BY total_quantity DESC
              LIMIT 5;";

              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                // output data of each row
                $i = 1;
                while($row = $result->fetch_assoc()) {
                  $item_name = $row['item_name'] ?? 'Unknown';
                  $unit = $row['unit'] ?? 'Unknown';
                  $total_quantity = floor($row['total_quantity']);
                  $date_updated = $row['date_updated'];

                  ?>
                  <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $item_name; ?></td>
                    <td><?php echo $unit; ?></td>
                    <td><?php echo $total_quantity; ?></td>
                    <td><?php echo $date_updated; ?></td>
                  </tr>
                  <?php
                  $i++;
                }
              } else {
                // output no data message
                ?>
                <tr>
                  <td colspan='5' class='text-center'>No data available</td>
                </tr>
                <?php
              }
              ?>
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


<!-- TABLE FOR STOCK ALERTS -->
    <!-- <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">Stock Alerts</h5>
          <div class="card-tools">
            <a href="./?page=stockStatus" class="btn btn-flat btn-success rounded"></span>View All</a>
          </div>
        </div>
        <div class="card-body">
          <#php
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
          #>

          <#php while ($row = mysqli_fetch_assoc($result)) {
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
          } #>
              
        </div>
      </div>
    </div> -->
    