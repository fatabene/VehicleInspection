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

if (isset($_GET['id'])) {
    $bookingId = $_GET['id'];

    $deleteQuery = "DELETE FROM vehicleinspection_bookings_record WHERE ID = '$bookingId'";

    if ($conn->query($deleteQuery)) {
        $message = "<div class='alert alert-success'>Booking successfully canceled.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Failed to cancel booking.</div>";
    }
} else {
    $message = "<div class='alert alert-danger'>Invalid booking ID.</div>";
}

$conn->close();
?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <title>Cancel Booking</title>
</head>
<body>
    <div class="container my-4 p-3" style="background-color: #74d2e1;">
        <h1 class="display-1 text-center my-2" style="font-size: 40px; background: #74d2e1; color: #000; border-color: #64e9f1; padding: 6px;">Cancel Booking</h1>
        <div class="row">
            <div class="col-md-12">
                <?php echo isset($message) ? $message : ''; ?>
                <a href="my_bookings.php" class="btn btn-primary">Back to My Bookings</a>
            </div>
        </div>
    </div>
</body>
</html>
