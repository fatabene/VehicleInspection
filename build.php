<?php
session_start();

function build_calendar($month, $year)
{
    // Existing code for building the calendar...

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

    // Existing code for retrieving bookings and rendering the calendar...
}

// Call the build_calendar function with the appropriate arguments
$currentMonth = date('m');
$currentYear = date('Y');
build_calendar($currentMonth, $currentYear);
?>
