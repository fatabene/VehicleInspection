<?php
session_start();

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

    return; // Stop execution of the code
}

// Continue with building the calendar if the user is logged in

// Display the logout button
echo "<form method='POST' class='text-center'>
        <button type='submit' name='logout' class='btn btn-primary'>Logout</button>
    </form>";

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
    $month = date('m');
    $year = date('Y');

    $firstDayOfMonth = mktime(0, 0, 
