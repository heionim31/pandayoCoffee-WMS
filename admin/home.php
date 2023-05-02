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

  /* POP-UP ALERTS */
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

  /* WELCOME MESSAGE */
  .alert-success {
    background-color: rgba(40, 167, 69, 0.9);
    border-color: rgba(255, 255, 255, 0.5);
    color: #fff;
  }

  .alert-success h4 {
    font-size: 1.2rem;
  }

  .alert-success .close {
    align-self: center;
    margin-top: -4px;
  }

  /* PIE CHARTS - NO DATA MESSAGE */
  .no-data-message-big {
    font-size: 3em;
    font-weight: bold;
    text-align: center;
    color: #888;
  }
  .no-data-message-big {
    /* transform: rotate(-45deg); */
    margin: 6rem 0 -5rem;
  }

  /* TABLE FOR RECENTLY ADDED */
  .recently-header {
    display: flex;
    align-items: center;
    color: white;
    list-style: none;
  }

  .card-primary:not(.card-outline)>.card-header a.actives {
      color: #1f2d3d;
  }

  .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.actives {
    color: black;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
  }

  /* TOTALS METRICS */
  .info-box {
    /* background: linear-gradient(to right, #1e5799, #2989d8); */
    background-image: linear-gradient(to right, #834d9b 0%, #d04ed6 51%, #834d9b 100%);
    background-size: 200% auto;
    box-shadow: 1 1 3px #000;
    border-radius: 10px;
    height: 100px;
  }

  .info-box-icon {
    color: rgba(0,0,0,0.35);
  }

  .info-box-number {
    font-size: 2rem;
  }

  .info-box:hover .info-box-icon {
    transform: scale(1.1);
    transition: 0.5s;
    transition-delay: 0.1s;
  }

  .info-box-content span {
    color: white;
    text-transform: uppercase;
  }
</style>


<!-- WELCOME MESSAGE -->
<div class="alert alert-success alert-dismissible fade show d-flex align-items-center py-2 px-3" role="alert">
  <div class="flex-grow-1">
      <h4 class="alert-heading mb-0">Welcome, <?php echo $_settings->userdata('fullname'); ?>!</h4>
  </div>
</div>


<!-- ALL DASHBOARD CONTENTS -->
<div class="row">

  <!-- TOTAL ITEMS -->
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      <div class="info-box-content">
        <a href="<?php echo base_url ?>admin/?page=items" style="color:black;"> 
          <span class="info-box-number text-left h5">
            <?php 
              $items = pg_query($conn, "SELECT id FROM wh_item_list WHERE delete_flag = 0 AND status = 1");
              $num_items = pg_num_rows($items);
              echo number_format($num_items);
            ?>
            <?php ?>
          </span>
          <span class="info-box-text text-left">Total Ingredients</b></span>
        </a>
      </div>
      <span class="info-box-icon"><i class="fas fa-chart-line" style="font-size:60px"></i></span>
    </div>
  </div>

  <!-- TOTAL CATEGORIES -->
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      <div class="info-box-content">
        <a href="<?php echo base_url ?>admin/?page=categories" style="color:black;">
          <span class="info-box-number text-left h5">
          <?php 
            $category = pg_query($conn, "SELECT * FROM wh_category_list WHERE status = 1");
            $num_category = pg_num_rows($category);
            echo number_format($num_category);
          ?>
          </span>
          <span class="info-box-text text-left">Total Categories</span>
        </a> 
      </div>
      <span class="info-box-icon"><i class="fas fa-cubes" style="font-size:60px; weigth:60px;"></i></span>
    </div>
  </div>

  <!-- TOTAL UNITS -->
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      <div class="info-box-content">
        <a href="<?php echo base_url ?>admin/?page=units" style="color:black;">
          <span class="info-box-number text-left h5">
            <?php 
              $unit = pg_query($conn, "SELECT * FROM wh_unit_list WHERE status = 1");
              $num_unit = pg_num_rows($unit);
              echo number_format($num_unit);
            ?>
          </span>
          <span class="info-box-text text-left">Total Units</span>
        </a> 
      </div>
      <span class="info-box-icon"><i class="fas fa-balance-scale" style="font-size:60px; weigth:60px;"></i></span>
    </div>
  </div>
 
  <!-- TOTAL USERS -->
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      <div class="info-box-content">
        <a href="<?php echo base_url ?>admin/?page=user/list" style="color:black;"> 
          <span class="info-box-number text-left h5">
            <?php 
              $user = pg_query($conn, "SELECT * FROM users WHERE role IN ('warehouse_manager', 'warehouse_staff')");
              $num_user = pg_num_rows($user);
              echo number_format($num_user);              
            ?>
          </span>
          <span class="info-box-text text-left">Total Users</span>
        </a>
      </div>
      <span class="info-box-icon"><i class="fas fa-users" style="font-size:60px"></i></span>
    </div>
  </div>

  <!-- TOTAL OF IN STOCK -->
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      <div class="info-box-content">
        <a href="<?php echo base_url ?>admin/?page=items" style="color:black;">
          <span class="info-box-number text-left h5">
            <?php 
              $query = "SELECT wh_item_list.id, wh_item_list.name, 
                        (SELECT min_stock FROM wh_stock_notif LIMIT 1) AS min_stock, 
                        (SELECT max_stock FROM wh_stock_notif LIMIT 1) AS max_stock,
                        (SELECT quantity FROM wh_stockin_list WHERE item_id = wh_item_list.id 
                        ORDER BY date DESC LIMIT 1) AS latest_quantity,
                        (COALESCE((SELECT SUM(quantity) FROM wh_stockin_list WHERE item_id = wh_item_list.id),0) - 
                        COALESCE((SELECT SUM(quantity) FROM wh_stockout_list WHERE item_id = wh_item_list.id),0)) as available
                        FROM wh_item_list 
                        WHERE wh_item_list.delete_flag != 1
                        ORDER BY date_updated DESC";

              $result = pg_query($conn, $query);
              $instock_count = 0;

              while ($row = pg_fetch_assoc($result)) {
                  $name = $row['name'];
                  $min_stock = $row['min_stock'];
                  $max_stock = $row['max_stock'];
                  $available_quantity = (int)$row['available'];

                  if ($available_quantity > $min_stock && $available_quantity <= $max_stock ) {
                      $instock_count++;
                  }
              }
              echo format_num($instock_count);
            ?>
          </span>
          <span class= "info-box-text text-left">Total In Stock</span>
        </a>
      </div>
      <span class="info-box-icon"><i class="fas fa-battery-full" style="font-size:60px"></i></span>
    </div>
  </div>

  <!-- TOTAL OF LOW STOCK -->
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      <div class="info-box-content">
        <a href="<?php echo base_url ?>admin/?page=purchasing_request" style="color:black;">
          <span class="info-box-number text-left h5">
            <?php
              $query = "SELECT wh_item_list.id, wh_item_list.name, 
                  (SELECT min_stock FROM wh_stock_notif LIMIT 1) AS min_stock, 
                  (SELECT max_stock FROM wh_stock_notif LIMIT 1) AS max_stock,
                  (SELECT quantity FROM wh_stockin_list WHERE item_id = wh_item_list.id 
                  ORDER BY date DESC LIMIT 1) AS latest_quantity,
                  (COALESCE((SELECT SUM(quantity) FROM wh_stockin_list where item_id = wh_item_list.id),0) - 
                  COALESCE((SELECT SUM(quantity) FROM wh_stockout_list where item_id = wh_item_list.id),0)) as available
              FROM wh_item_list 
              ORDER BY date_updated DESC";

              $result = pg_query($conn, $query);
              $lowstock_count = 0;

              while ($row = pg_fetch_assoc($result)) {
                $name = $row['name'];
                $min_stock = $row['min_stock'];
                $max_stock = $row['max_stock'];
                $available_quantity = (int)$row['available'];

                if ($available_quantity <= $min_stock && $available_quantity != 0) {
                  $lowstock_count++;
                }
              }
              echo format_num($lowstock_count);
            ?>
          </span>
          <span class= "info-box-text text-left">Total Low stocks</span>
        </a>
      </div>
      <span class="info-box-icon"><i class="fas fa-battery-half" style="font-size:60px"></i></span>
    </div>
  </div>

  <!-- TOTAL OF OUT OF STOCK -->
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
      <div class="info-box-content">
        <a href="<?php echo base_url ?>admin/?page=purchasing_request" style="color:black;">
          <span class="info-box-number text-left h5">
            <?php 
              $query = "SELECT wh_item_list.id, wh_item_list.name, 
                        (SELECT min_stock FROM wh_stock_notif LIMIT 1) AS min_stock, 
                        (SELECT max_stock FROM wh_stock_notif LIMIT 1) AS max_stock,
                        (SELECT quantity FROM wh_stockin_list WHERE item_id = wh_item_list.id 
                        ORDER BY date DESC LIMIT 1) AS latest_quantity,
                        (COALESCE((SELECT SUM(quantity) FROM wh_stockin_list WHERE item_id = wh_item_list.id),0) - 
                        COALESCE((SELECT SUM(quantity) FROM wh_stockout_list WHERE item_id = wh_item_list.id),0)) as available
                        FROM wh_item_list 
                        WHERE wh_item_list.delete_flag != 1
                        ORDER BY date_updated DESC";

              $result = pg_query($conn, $query);
              $outofstock_count = 0;

              while ($row = pg_fetch_assoc($result)) {
                  $name = $row['name'];
                  $min_stock = $row['min_stock'];
                  $max_stock = $row['max_stock'];
                  $available_quantity = (int)$row['available'];

                  if ($available_quantity == 0 ) {
                      $outofstock_count++;
                  }
              }
              echo format_num($outofstock_count);
          ?>
          </span>
          <span class= "info-box-text text-left">Total Out of stocks</span>
        </a>
      </div>
      <span class="info-box-icon"><i class="fas fa-battery-empty" style="font-size:60px"></i></span>
    </div>
  </div>

  <!-- TOTAL OF EXPIRED ITEMS -->
  <div class="col-12 col-sm-4 col-md-3">
    <div class="info-box">
        <div class="info-box-content">
          <a href="<?php echo base_url ?>admin/?page=stockExpiration" style="color:black;">
            <span class="info-box-number text-left h5">
              <?php
                $expired_items_count_result = pg_query($conn, "SELECT COUNT(*) AS count
                                FROM wh_stockin_list
                                WHERE expire_date <= CURRENT_DATE + INTERVAL '1 day' AND expire_date IS NOT NULL AND expire_date <> '0001-01-01'");
                $expired_items_count = pg_fetch_assoc($expired_items_count_result)['count'];

                echo '<span class="text">'.($expired_items_count > 0 ? $expired_items_count : 0).'</span>';
              ?>
            </span>
            <span class= "info-box-text text-left">Total Expired stocks</span>
          </a>
        </div>
        <span class="info-box-icon"><i class="fas fa-calendar-times"  style="font-size:60px"></i></span>
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


  <!-- TOP STOCK-IN-ITEMS CHART-->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">TOP STOCK-IN</h5>
      </div>
      <div class="card-body">
        <?php
          // Execute the SQL query
          $sql = "SELECT wh_item_list.name AS item_name, wh_item_list.unit,
                    COALESCE(wh_stockin_list_deleted.total_quantity, 0) + COALESCE(wh_stockin_list.total_quantity, 0) AS total_quantity,
                    MAX(COALESCE(wh_stockin_list.date_updated, wh_stockin_list_deleted.date_updated)) AS date_updated
                    FROM wh_item_list
                    LEFT JOIN (
                        SELECT item_id, SUM(quantity) AS total_quantity, MAX(date_updated) AS date_updated
                        FROM wh_stockin_list_deleted GROUP BY item_id
                    ) AS wh_stockin_list_deleted ON wh_item_list.id = wh_stockin_list_deleted.item_id
                    LEFT JOIN (
                        SELECT item_id, SUM(quantity) AS total_quantity, MAX(date_updated) AS date_updated
                        FROM wh_stockin_list GROUP BY item_id
                    ) AS wh_stockin_list ON wh_item_list.id = wh_stockin_list.item_id
                    GROUP BY wh_item_list.id, wh_stockin_list_deleted.total_quantity, wh_stockin_list.total_quantity
                    HAVING COALESCE(wh_stockin_list_deleted.total_quantity, 0) + COALESCE(wh_stockin_list.total_quantity, 0) > 0
                    ORDER BY total_quantity DESC
                    LIMIT 5;";
                    
          $result = pg_query($conn, $sql);

          if (pg_num_rows($result) > 0) {
            $items_stockin = array();
            while ($row = pg_fetch_assoc($result)) {
                $item_name = $row['item_name'] ?? 'Unknown';
                $unit = $row['unit'] ?? 'Unknown';
                $total_quantity = floor($row['total_quantity']);
                $date_updated = $row['date_updated'];

                $item = array(
                    'name' => $item_name,
                    'quantity' => (int)$total_quantity
                );
                array_push($items_stockin, $item);
            }
          } else {
              echo "<div class='no-data-message-big'>No data to display.</div>";
          }
        ?>
        <canvas id="stock-in-chart" style="height: 158px"></canvas>
      </div>

      
      <!-- SCRIPT FOR TOP STOCK-IN ITEMS -->
      <script>
        // Get the table data
        let stockInItems = <?php echo json_encode($items_stockin); ?>;

        // Create an array of item names and their quantities
        let stockInItemNames = stockInItems.map(item => item.name);
        let stockInItemQuantities = stockInItems.map(item => item.quantity);

        // Create a chart using Chart.js
        let ctx1 = document.getElementById('stock-in-chart').getContext('2d');
        let chart1 = new Chart(ctx1, {
          type: 'pie',
          data: {
            labels: stockInItemNames,
            datasets: [{
              label: 'Total Quantities',
              data: stockInItemQuantities,
              backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(0, 128, 0, 0.2)',
                'rgba(153, 102, 255, 0.2)',
              ],
              borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(50, 205, 50, 1)',
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

    </div>
  </div>


  <!-- TOP STOCK-OUT-ITEMS CHART -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">TOP STOCK-OUT</h5>
      </div>
      <div class="card-body">
        <?php
          $sql = "SELECT wh_item_list.name AS item_name, wh_item_list.unit, SUM(COALESCE(wh_stockout_list.quantity, 0)) AS total_quantity, MAX(wh_stockout_list.date_updated) AS date_updated
          FROM wh_item_list
          LEFT JOIN wh_stockout_list ON wh_item_list.id = wh_stockout_list.item_id
          GROUP BY wh_item_list.id
          HAVING SUM(COALESCE(wh_stockout_list.quantity, 0)) > 0
          ORDER BY SUM(COALESCE(wh_stockout_list.quantity, 0)) DESC
          LIMIT 5;";

          $result = pg_query($conn, $sql);

          if (pg_num_rows($result) > 0) {
            $items_stockout = array();
            while ($row = pg_fetch_assoc($result)) {
                $item_name = $row['item_name'] ?? 'Unknown';
                $unit = $row['unit'] ?? 'Unknown';
                $total_quantity = floor($row['total_quantity']);
                $date_updated = $row['date_updated'];

                $item = array(
                    'name' => $item_name,
                    'quantity' => (int)$total_quantity
                );
                array_push($items_stockout, $item);
            }
          } else {
              echo "<div class='no-data-message-big'>No data to display.</div>";
          }
        ?>
        <canvas id="stock-out-chart" style="height: 158px"></canvas>
      </div>


      <!-- SCRIPT FOR TOP STOCK-OUT ITEMS -->
      <script>
        // Get the table data
        let stockOutItems = <?php echo json_encode($items_stockout); ?>;

        // Create an array of stock-out item names and their quantities
        let stockOutItemNames = stockOutItems.map(item => item.name);
        let stockOutItemQuantities = stockOutItems.map(item => item.quantity);

        // Create a chart using Chart.js
        let ctx2 = document.getElementById('stock-out-chart').getContext('2d');
        let chart2 = new Chart(ctx2, {
          type: 'pie',
          data: {
            labels: stockOutItemNames,
            datasets: [{
              label: 'Total Quantities',
              data: stockOutItemQuantities,
              backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(0, 128, 0, 0.2)',
                'rgba(153, 102, 255, 0.2)',
              ],
              borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(50, 205, 50, 1)',
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

    </div>
  </div>


  <!-- TOP WASTE-ITEMS CHART -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">TOP WASTE</h5>
      </div>
      <div class="card-body">
        <?php
          // Execute the SQL query
          $sql = "SELECT wh_item_list.name AS item_name, wh_item_list.unit, SUM(COALESCE(wh_waste_list.quantity, 0)) AS total_quantity_wasted, MAX(COALESCE(wh_waste_list.date_updated, NULL)) AS date_updated
                  FROM wh_item_list
                  LEFT JOIN wh_waste_list ON wh_item_list.id = wh_waste_list.item_id
                  GROUP BY wh_item_list.id
                  HAVING SUM(COALESCE(wh_waste_list.quantity, 0)) > 0
                  ORDER BY SUM(COALESCE(wh_waste_list.quantity, 0)) DESC
                  LIMIT 5;";

          $result = pg_query($conn, $sql);

          if (pg_num_rows($result) > 0) {
            $items_waste = array();
            while ($row = pg_fetch_assoc($result)) {
                $item_name = $row['item_name'] ?? 'Unknown';
                $unit = $row['unit'] ?? 'Unknown';
                $total_quantity = floor($row['total_quantity_wasted']);
                $date_updated = $row['date_updated'] ?: 'N/A';

                $item = array(
                    'name' => $item_name,
                    'quantity' => (int)$total_quantity,
                    'date_updated' => $date_updated
                );
                array_push($items_waste, $item);
            }
          } else {
              echo "<div class='no-data-message-big'>No data to display.</div>";
          }
        ?>
      <canvas id="waste-chart" style="height: 158px"></canvas>
  </div>


      <!-- SCRIPT FOR TOP WASTE ITEMS -->
      <script>
        // Get the table data
        let wasteItems = <?php echo json_encode($items_waste); ?>;

        // Create an array of item names and their quantities
        let wasteItemNames = wasteItems.map(item => item.name);
        let wasteItemQuantities = wasteItems.map(item => item.quantity);

        // Create a chart using Chart.js
        let ctx3 = document.getElementById('waste-chart').getContext('2d');
        let chart3 = new Chart(ctx3, {
          type: 'pie',
          data: {
            labels: wasteItemNames,
            datasets: [{
              label: 'Total Quantities',
              data: wasteItemQuantities,
              backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(0, 128, 0, 0.2)',
                'rgba(153, 102, 255, 0.2)',
              ],
              borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(50, 205, 50, 1)',
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

    </div>
  </div>


  <!-- TABLE FOR RECENTLY ADDED -->
  <div class="col-md-12">
    <div class="card card-primary card-tabs">
      <div class="card-header recently-header p-0 pt-1">
        <ul class="nav nav-tabs">
          <li class="pt-2 px-4">
            <h5 class="card-title">Recently Added</h5>
          </li>
          <li class="nav-item">
            <a class="nav-link recent-add-nav actives" href="#" data-toggle="tab" data-target="#items-table">Ingredients</a>
          </li>
          <li class="nav-item">
            <a class="nav-link recent-add-nav" href="#" data-toggle="tab" data-target="#categories-table">Categories</a>
          </li>
          <li class="nav-item">
            <a class="nav-link recent-add-nav" href="#" data-toggle="tab" data-target="#units-table">Units</a>
          </li>
        </ul>
      </div>

      <div class="card-body">
        <div class="tab-content">

          <!-- RECNTLY ADDED ITEMS TABLE -->
          <div id="items-table" class="tab-pane fade show active">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">
                  <i class="nav-icon fas fa-chart-line"></i>
                  Recent Ingredient
                </h5>
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
                      $sql = "SELECT * FROM wh_item_list ORDER BY date_created DESC LIMIT 5";
                      $result = pg_query($conn, $sql);
                      
                      // Create an array to store the recent items
                      $recent_items = array();
                      if (pg_num_rows($result) > 0) {
                        while($row = pg_fetch_assoc($result)) {
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
                          $category_query = pg_query($conn, "SELECT name FROM wh_category_list WHERE id = $category_id");
                          $category = pg_fetch_assoc($category_query);
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

          <!-- RECNTLY ADDED CATEGORIES TABLE -->
          <div id="categories-table" class="tab-pane fade">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">
                  <i class="nav-icon fas fa-cubes"></i>
                  Recent Categories
                </h5>
              </div>
              <div class="card-body">
                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Date Created</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      // Retrieve the 5 most recent items
                      $sql = "SELECT * FROM wh_category_list ORDER BY date_created DESC LIMIT 5";
                      $result = pg_query($conn, $sql);
                      
                      // Create an array to store the recent items
                      $recent_categories = array();
                      if (pg_num_rows($result) > 0) {
                        while($row = pg_fetch_assoc($result)) {
                          $recent_categories[] = $row;
                        }
                      }
                    ?>
                    <?php $id = 1; foreach($recent_categories as $categories): ?>
                    <tr>
                      <td class="align-middle"><?php echo $id++; ?></td>
                      <td class="align-middle"><?php echo $categories['name']; ?></td>
                      <td class="align-middle"><?php echo date('Y-m-d h:i A', strtotime($categories['date_created'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- RECNTLY ADDED UNIT TABLE -->
          <div id="units-table" class="tab-pane fade">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">
                  <i class="nav-icon fas fa-balance-scale"></i>
                  Recent Units
                </h5>
              </div>
              <div class="card-body">
                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Abbreviation</th>
                      <th>Date Created</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      // Retrieve the 5 most recent items
                      $sql = "SELECT * FROM wh_unit_list ORDER BY date_created DESC LIMIT 5";
                      $result = pg_query($conn, $sql);
                      
                      // Create an array to store the recent items
                      $recent_units = array();
                      if (pg_num_rows($result) > 0) {
                        while($row = pg_fetch_assoc($result)) {
                          $recent_units[] = $row;
                        }
                      }
                    ?>
                    <?php $id = 1; foreach($recent_units as $units): ?>
                    <tr>
                      <td class="align-middle"><?php echo $id++; ?></td>
                      <td class="align-middle"><?php echo $units['name']; ?></td>
                      <td class="align-middle"><?php echo $units['abbreviation']; ?></td>
                      <td class="align-middle"><?php echo date('Y-m-d h:i A', strtotime($units['date_created'])); ?></td>
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

  <script>
    const tabs = document.querySelectorAll('.recent-add-nav');

    tabs.forEach(tab => {
      tab.addEventListener('click', function() {
        // Remove active class from all tabs
        tabs.forEach(t => t.classList.remove('actives'));

        // Add active class to clicked tab
        this.classList.add('actives');

        // Reset background color for all tabs
        tabs.forEach(t => t.style.backgroundColor = '');

        // Set background color for active tab
        this.style.backgroundColor = 'white';
      });
    });

    $('.recent-add-nav').on('click', function(e) {
      e.preventDefault();
      var target = $(this).data('target');
      $('.tab-pane').removeClass('show active');
      $(target).addClass('show active');
    });
  </script>

</div>


<!-- DASHBOARD IMAGE -->
<!-- 
<div class="container-fluid text-center">
  <img src="<= validate_image($_settings->info('cover')) ?>" alt="system-cover" id="system-cover" class="img-fluid">
</div> -->