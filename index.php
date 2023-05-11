<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <title>Vehicle Inspection Booking System</title>
  <style>
    .jumbotron {
      background: #17a2b8;
      color: #fff;
      border-color: #64e9f1;
    }

    .display-1 {
      font-size: 40px;
    }
  </style>
</head>

<body>
  <div class="container-fluid p-5">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-primary">
          <h1 class="display-4 text-center">Vehicle Inspection Motor AB</h1>
        </div>
        
        <div class="table-responsive">
        
          <?php
          include 'build.php';
          $dateComponents = getdate();
          if (isset($_GET['month']) && isset($_GET['year'])) {
            $month = $_GET['month'];
            $year = $_GET['year'];
          } else {
            $month = $dateComponents['month'];
            $year = $dateComponents['year'];
          }
          echo build_calendar($month, $year);
     
          ?>
      </div>
</body>
