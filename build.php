<?php
session_start();

function build_calendar($month, $year)
{
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

    // Check if the user is logged in
    $isLoggedIn = isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true;

    // Check if the user submitted the login form
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Check if the submitted credentials are correct
        if ($username === 'admin' && $password === 'admin') {
            $_SESSION['isLoggedIn'] = true;
            $isLoggedIn = true;
        } else {
            echo "<div class='alert alert-danger'>Invalid username or password.</div>";
        }
    }

    // Check if the user clicked the logout button
    if (isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        $isLoggedIn = false;
    }

    // Display the login form if the user is not logged in
    if (!$isLoggedIn) {
        echo "<div class='container'>
                <h1 class='display-4 text-center'>Login</h1>
                <div class='row justify-content-center'>
                    <div class='col-md-4'>
                        <form method='POST'>
                            <div class='form-group'>
                                <label for='username'>Username</label>
                                <input type='text' class='form-control' name='username' id='username' required>
                            </div>
                            <div class='form-group'>
                                <label for='password'>Password</label>
                                <input type='password' class='form-control' name='password' id='password' required>
                            </div>
                            <button type='submit' class='btn btn-primary'>Login</button>
                        </form>
                    </div>
                </div>
            </div>";

        return; // Stop execution of the function
    }

    // Continue with building the calendar if the user is logged in

    // Display the logout button
    echo "<form method='POST' class='text-center'>
            <button type='submit' name='logout' class='btn btn-primary'>Logout</button>
        </form>";

    $stmt = $conn->prepare("SELECT * FROM vehicleinspection_bookings_record WHERE MONTH(DATE) = ? AND YEAR(DATE) = ?");
    $stmt->bind_param('ss', $month, $year);
    $bookings = array();
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row['DATE'];
            }

            $stmt->close();
        }
    }

    $daysOfWeek = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday(closed)', 'Sunday(closed)');
    $month = date('m');
    $year = date('Y');

    $firstDayOfMonth = mktime(0, 0, 0,
    $firstDayOfMonth = mktime(0, 0, 0, (int)$month, 1, $year));
    $numberDays = date('t', $firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];

    $calendar = "<table class='table table-bordered'>";
    $calendar .= "<center><h2 class='mb-4'>Open Monday to Friday 9am - 5pm</h2>";
    $calendar .= "<center><a href='my_bookings.php' class='btn btn-primary'>Mina bokningar</a>";
    $calendar .= "<center><h2 class='mb-4'></h2>";
    $calendar .= "<a class='btn btn-xs btn-primary' href='?month=" . date('m', mktime(0, 0, 0, (int)$month - 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, (int)$month - 1, 1, $year)) . "'>Previous Month</a> ";
    $calendar .= " <a class='btn btn-xs btn-success' href='?month=" . date('m') . "&year=" . date('Y') . "'>Current Month</a> ";
    $calendar .= "<a class='btn btn-xs btn-primary' href='?month=" . date('m', mktime(0, 0, 0, (int)$month + 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, (int)$month + 1, 1, $year)) . "'>Next Month</a></center><br>";
    //$calendar .= "<center><a href='my_bookings.php' class='btn btn-primary'>Mina bokningar</a>";
    $calendar .= "<left><h3 class='mb-4'>$monthName $year</h3>";

    $calendar .= "<tr>";
    foreach ($daysOfWeek as $day) {
        $calendar .= "<th  class='header'>$day</th>";
    }

    $currentDay = 1;
    $calendar .= "</tr><tr>";

    if ($dayOfWeek > 0) {
        for ($k = 0; $k < $dayOfWeek; $k++) {
            $calendar .= "<td  class='empty'></td>";
        }
    }

    $month = str_pad($month, 2, "0", STR_PAD_LEFT);

    while ($currentDay <= $numberDays) {

        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";

        $today = $date == date('Y-m-d') ? "today" : "";

        if ($dayOfWeek == 5 || $dayOfWeek == 6) {
            $calendar .= "<td class='weekend'><h4>$currentDay</h4></td>";
        } elseif ($date < date('Y-m-d')) {
            $calendar .= "<td class='crossed'><h4>$currentDay</h4>";
        } elseif (in_array($date, $bookings)) {
            $calendar .= "<td class='$today'><h4>$currentDay
            // Display booked date with a link to booking details
            

            // Add any additional styling or information for booked dates
            // For example, you can add a badge indicating the booking status
            // $calendar .= "<span class='badge badge-primary'>Booked</span></h4>";
        } else {
            // Display available date for booking
            $calendar .= "<td class='$today'><h4>$currentDay</h4>";

            // Add a button to book the date
            $calendar .= "<a href='booking.php?date=$date' class='btn btn-primary'>Book Now</a></td>";
        }

        $calendar .= "</td>";

        $currentDay++;
        $dayOfWeek++;
    }

    if ($dayOfWeek != 7) {
        $remainingDays = 7 - $dayOfWeek;
        for ($i = 0; $i < $remainingDays; $i++) {
            $calendar .= "<td class='empty'></td>";
        }
    }

    $calendar .= "</tr>";
    $calendar .= "</table>";

    return $calendar;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Vehicle Inspection Booking Calendar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .today {
            background-color: #f0ad4e !important;
        }

        .crossed {
            text-decoration: line-through;
            color: #ccc;
        }

        .weekend {
            background-color: #f7f7f7;
        }

        .empty {
            background-color: #eaeaea;
        }

        .header {
            background-color: #337ab7;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="display-4 text-center">Vehicle Inspection Booking Calendar</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php
                $dateComponents = getdate();
                if (isset($_GET['month']) && isset($_GET['year'])) {
                    $month = $_GET['month'];
                    $year = $_GET['year'];
                } else {
                    $month = $dateComponents['mon'];
                    $year = $dateComponents['year'];
                }
                echo build_calendar($month, $year);
                ?>
            </div>
        </div>
    </div>
</body>

</html>
