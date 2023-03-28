<?php
  require_once('sess_auth.php');
?>


<head>
  <!-- Set the character encoding of the page -->
  <meta charset="utf-8">

  <!-- Set the viewport width and initial zoom level -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Set the title of the page -->
  <title>
      <?php 
          // Display the site title, followed by a separator if available,
          // and then the site name
          echo $_settings->info('title') != false ? $_settings->info('title').' | ' : '' 
      ?>
      <?php echo $_settings->info('name') ?>
  </title>

  <!-- NEW CDN LINK -->
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->


  <!-- Set the favicon of the page -->
  <link rel="icon" href="<?php echo validate_image($_settings->info('logo')) ?>" />

  <!-- Import the Source Sans Pro font from Google Fonts -->
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback"> -->

  <!-- Import Font Awesome icons -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/fontawesome-free/css/all.min.css">
  
  <!-- Import Ionicons -->
  <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->

  <!-- Import the Tempusdominus Bootstrap 4 datetime picker styles -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

  <!-- Import the DataTables styles -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

  <!-- Import the Select2 styles -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

  <!-- Import the iCheck styles -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">

  <!-- Import the JQVMap styles -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/jqvmap/jqvmap.min.css">

  <!-- Import the Theme style -->
  <link rel="stylesheet" href="<?php echo base_url ?>dist/css/adminlte.css">

  <!-- Import custom CSS styles -->
  <link rel="stylesheet" href="<?php echo base_url ?>dist/css/custom.css">

  <!-- Import the overlayScrollbars styles -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

  <!-- Import the Daterange picker styles -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/daterangepicker/daterangepicker.css">

  <!-- Import the Summernote styles -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/summernote/summernote-bs4.min.css">

  <!-- Import the SweetAlert2 styles -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

  <!-- Define a CSS animation for Chart.js -->
  <style type="text/css">
    /* CSS styles for Chart.js */
    /* Define keyframe animation for Chart.js */
    @keyframes chartjs-render-animation{
      from { opacity: .99 }
      to { opacity: 1 }
    }
    
    /* Define class for chart render monitor */
    .chartjs-render-monitor {
      animation: chartjs-render-animation 1ms;
    }
    
    /* Define classes for chart size monitor */
    .chartjs-size-monitor,
    .chartjs-size-monitor-expand,
    .chartjs-size-monitor-shrink {
      /* Set positioning properties */
      position: absolute;
      direction: ltr;
      left: 0;
      top: 0;
      right: 0;
      bottom: 0;
      /* Hide overflow */
      overflow: hidden;
      /* Disable pointer events */
      pointer-events: none;
      /* Set visibility to hidden */
      visibility: hidden;
      /* Set z-index to -1 */
      z-index: -1;
    }
    
    /* Define style for chart size monitor expand class */
    .chartjs-size-monitor-expand > div {
      position: absolute;
      width: 1000000px;
      height: 1000000px;
      left: 0;
      top: 0;
    }
    
    /* Define style for chart size monitor shrink class */
    .chartjs-size-monitor-shrink > div {
      position: absolute;
      width: 200%;
      height: 200%;
      left: 0;
      top: 0;
    }
  </style>

  <!-- Include jQuery library -->
  <script src="<?php echo base_url ?>plugins/jquery/jquery.min.js"></script>

  <!-- Include jQuery UI library -->
  <script src="<?php echo base_url ?>plugins/jquery-ui/jquery-ui.min.js"></script>

  <!-- Include SweetAlert2 library -->
  <script src="<?php echo base_url ?>plugins/sweetalert2/sweetalert2.min.js"></script>

  <!-- Include Toastr library -->
  <script src="<?php echo base_url ?>plugins/toastr/toastr.min.js"></script>

  <!-- Set base URL variable -->
  <script>
    var _base_url_ = '<?php echo base_url ?>';
  </script>

  <!-- Include main script file -->
  <script src="<?php echo base_url ?>dist/js/script.js"></script>

  <!-- Latest Bootstrap's JavaScript plugins -->
  <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->


</head>