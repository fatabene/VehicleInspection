<?php
if (isset($_GET['date'])) {
    $date = $_GET['date'];
}

if (isset($_POST['submit'])) {

    $firstname = $_POST['FIRSTNAME'];
    $lastname = $_POST['LASTNAME'];
    $phone = $_POST['PHONE'];

    $conn = new mysqli('192.168.1.88', 'root', '', 'vehicleinspectionsystem', '3306');
    
    if ($conn->connect_errno) {
        echo "Failed to connect to MySQL: " . $conn->connect_error;
        exit();
    }
    

    $sql = "INSERT INTO vehicleinspection_bookings_record(FIRSTNAME, LASTNAME, PHONE, DATE)VALUES('$firstname', '$lastname', ' $phone', '$date')";

    if($conn->query($sql)){
        $message = "<div class='alert alert-success'>Booking successful</div>";
    }else{
        $message = "<div class='alert alert-danger'>Booking failed</div>";
    }
}
?>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <title>Vehicle Inspection Booking System </title>
</head>

<body>
    <div class="container my-4 p-3" style="background-color: #74d2e1;">
        <h1 class="display-1 text-center my-2" style="font-size: 40px; background: #74d2e1; color: #000; border-color: #64e9f1; padding: 6px;">Book your appointment for <?php echo date('m/d/Y', strtotime($date)); ?>: </h1>
        <div class="row">
            <div class="col-md-12">
                <?php echo isset($message) ? $message : '';?>
                <form action="" method="POST" autocomplete="off">
                    <div class="form-group">
                        <label for="">FIRST NAME</label>
                        <input type="text" class="form-control" name="FIRSTNAME" required>
                    </div>
                    <div class="form-group">
                        <label for="">LAST NAME</label>
                        <input type="text" class="form-control" name="LASTNAME" required>
                    </div>
                    <div class="form-group">
                        <label for="">PHONE NUMBER</label>
                        <input type="text" class="form-control" name="PHONE" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Submit</button>
                    <a href="index.php" class="btn btn-primary"> Back</a>
                </form>
            </div>
        </div>
    </div>
</body>