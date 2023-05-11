<?php
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

    $firstDayOfMonth = mktime(0, 0, 0, (int)$month, 1, $year);
    $numberDays = date('t', $firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];

    $calendar = "<table class='table table-bordered'>";
    $calendar .= "<center><h2 class='mb-4'>Open Monday to Friday 9am - 5pm</h2>";
    $calendar .= "<center><a href='my_bookings.php' class='btn btn-primary'>Mina bokningar</a>";
    $calendar .= "<a class='btn btn-xs btn-primary' href='?month=" . date('m', mktime(0, 0, 0, (int)$month - 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0,  (int)$month - 1, 1, $year)) . "'>Previous Month</a> ";
    $calendar .= " <a class='btn btn-xs btn-success' href='?month=" . date('m') . "&year=" . date('Y') . "'>Current Month</a> ";
    $calendar .= "<a class='btn btn-xs btn-primary' href='?month=" . date('m', mktime(0, 0, 0,  (int)$month + 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0,  (int)$month + 1, 1, $year)) . "'>Next Month</a></center><br>";
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
            $calendar .= "<td class='crossed' ><h4>$currentDay</h4>";
        } elseif (in_array($date, $bookings)) {
            $calendar .= "<td class='$today'><h4>$currentDay</h4> <button class='btn btn-danger btn-xs'> <span class='glyphicon glyphicon-lock
            '></span> Already Booked</button>";
        } else {
            $calendar .= "<td class='$today'><h4>$currentDay</h4> <a href='book.php?date=" . $date . "' class='btn btn-info btn-xs'> <span class='glyphicon glyphicon-ok'></span> Book Now</a>";
        }

        $calendar .= "</td>";
        $currentDay++;
        $dayOfWeek++;
    }
 
    if ($dayOfWeek != 7) {

        $remainingDays = 7 - $dayOfWeek;
        for ($l = 0; $l < $remainingDays; $l++) {
            $calendar .= "<td class='empty'></td>";
        }
    }

    $calendar .= "</tr>";
    $calendar .= "</table>";
    echo $calendar;
}
