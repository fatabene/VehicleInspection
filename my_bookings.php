<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <title>Responsive Example</title>
  <style>
    .custom-card {
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Responsive Example</h1>

    <div class="row">
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card custom-card">
          <img src="image1.jpg" class="card-img-top" alt="Image 1">
          <div class="card-body">
            <h5 class="card-title">Card 1</h5>
            <p class="card-text">This is a sample card.</p>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card custom-card">
          <img src="image2.jpg" class="card-img-top" alt="Image 2">
          <div class="card-body">
            <h5 class="card-title">Card 2</h5>
            <p class="card-text">This is another sample card.</p>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card custom-card">
          <img src="image3.jpg" class="card-img-top" alt="Image 3">
          <div class="card-body">
            <h5 class="card-title">Card 3</h5>
            <p class="card-text">This is a third sample card.</p>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card custom-card">
          <img src="image4.jpg" class="card-img-top" alt="Image 4">
          <div class="card-body">
            <h5 class="card-title">Card 4</h5>
            <p class="card-text">This is the final sample card.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
  
<?php
$host = 'containers-us-west-145.railway.app';
$port = '7013';
$user = 'root';
$password = '3tJjuqr3nLekRPJu1thG';
$database = 'railway';

$conn = new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: " . $conn->connect_error;
    exit();
}

$currentMonth = date('m');

$query = "SELECT * FROM vehicleinspection_bookings_record WHERE MONTH(DATE) = '$currentMonth'";
$result = $conn->query($query);
?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <title>My Bookings</title>
</head>
<body>
    <div class="container my-4 p-3" style="background-color: #74d2e1;">
        <h1 class="display-1 text-center my-2" style="font-size: 40px; background: #74d2e1; color: #000; border-color: #64e9f1; padding: 6px;">My Bookings</h1>
        <div class="row">
            <div class="col-md-12">
                <?php
                if ($result && $result->num_rows > 0) {
                    echo "<table class='table'>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Phone Number</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . date('m/d/Y', strtotime($row['DATE'])) . "</td>
                                <td>" . $row['FIRSTNAME'] . "</td>
                                <td>" . $row['LASTNAME'] . "</td>
                                <td>" . $row['PHONE'] . "</td>
                                <td><a href='cancel_booking.php?id=" . $row['ID'] . "' class='btn btn-danger'>Cancel</a></td>
                            </tr>";
                    }

                    echo "</tbody></table>";
                } else {
                    echo "<div class='alert alert-info'>No bookings found.</div>";
                }

                $conn->close();
                ?>
                <a href="index.php" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</body>
</html>
