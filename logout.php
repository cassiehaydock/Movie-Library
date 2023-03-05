<?php
//start session
session_start();

//if the user is not logged in then exit to login
if(!isset($_SESSION['id']))
{
    header("Location:login.php");
    exit();   
}

//delcare error array
$errors = array();

//include library
require 'includes/library.php';

//make database connection
$pdo = connectDB();

//if the user submiited the form
if(isset($_POST['submit'])) {

    //destroy session and redirect to login
    session_destroy();
    header("Location:login.php");
    exit();

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/6245260836.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assn3/styles/main.css">
    <title>Logout</title>
</head>

<body>
    <!-- Header of Page -->
    <?php include 'includes/header.php'; ?>

        <!-- Navigation bar -->
        <?php include 'includes/nav.php'; ?>

        <!-- Main Body -->
        <main>
            <!-- Login Form -->
            <form id="logout" method="post">

                <h1>Logout</h1>

                <!-- Logout Button -->
                <div>
                    <button type="submit" name="submit" value="<?php $submit = true?>" id="submit">Logout</button>
                </div>

            </form>
        </main>

        <?php include 'includes/footer.php'; ?>
</body>

</html>