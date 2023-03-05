<?php 
//check if user is logged int
session_start();

//if user is not logged in exit to login
if(!isset($_SESSION['id']))
{
    header("Location:login.php");
    exit();   
}

//make errors array
$errors = array();

//include library
require 'includes/library.php';

//make database connection
$pdo = connectDB();

//build query to select all infomartion for a specifc movie (id) which passed through the URL
//and accessed through the GET array, and the Userid which is taken from the session
$query = "SELECT * FROM cois3420_movies WHERE Userid = ? AND id = ?";

//prepare & execute query
$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['id'], $_GET['id']]);
$row = $stmt->fetch();

//if the user has submited the form
if (isset($_POST['submit'])){
     //build query to delete from table
     $query = "DELETE FROM cois3420_movies WHERE id = ?";

     //prepare & execute query
     $stmt = $pdo->prepare($query);
     $stmt->execute([$_GET['id']]);

     //redirect to index
     header("Location:index.php");
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
    <script src="scripts/deletevid.js"></script>
    <title>Delete Video</title>
</head>

<body>
    <!-- Header of Page -->
    <?php include 'includes/header.php'; ?>

    <!-- Navigation bar -->
    <?php include 'includes/nav.php'; ?>

    <!-- Main Body-->
    <main>
        <!-- Delete Video Form -->
        <form id="deletevid" method="post">

            <h1>Delete Video</h1>

            <div>
                    <?php if(isset($row['cover'])): ?>
                    <img src="/~cassandrahaydock/www_data/<?=$row['cover']?>" height="250" width="180" alt=""/>
                    <?php elseif(isset($row['url'])): ?>
                    <img src="<?=$row['url']?>" height="250" width="180" alt=""/>
                    <?php else: ?>
                    <img src="./imgs/nocover.jpg" height="250" width="180" alt=""/>
                    <?php endif ?>
                 <h2><?= "$row[title]"?></h2>
            </div>

            <!-- Create Account Button -->
            <div>
                <button type="submit" name="submit" id="submit">Delete Video</button>
            </div>
        </form>

    </main>

    <?php include 'includes/footer.php'; ?>

</body>

</html>