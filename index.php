<?php
//start session
session_start();
//if user is not logged in redirect to login
if(!isset($_SESSION['id']))
{
    header("Location:login.php");
    exit();   
}

//variable to hold user id from session
$Userid = $_SESSION['id'];
//variable offset to get from url for previous and next buttons
$offset = $_GET['offset'] ?? 0;
//variable to identify which page for previous and next buttons
$page = $_GET['page'] ?? 1;

//include library
require 'includes/library.php';

//make database connection
$pdo = connectDB();

//query for dynamic showing of movies
//depending on offset is where the page starts with the movie and then shows
//only four per page 
$query = "SELECT id, title, cover, url FROM cois3420_movies WHERE Userid = '$Userid' LIMIT ?, 5";
$stmt = $pdo->prepare($query);
$stmt->execute([$offset]);

//get number of movies a user has
$query2 = "SELECT COUNT(id) FROM cois3420_movies WHERE Userid = '$Userid'";
$stmt2 = $pdo->prepare($query2);
$stmt2->execute();
$save = $stmt2->fetchColumn();
//divide by offset(5) to know how many pages the user needs to have
$numPages = $save / 5;
//round qoutient 
$numPages = ceil($numPages);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/6245260836.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assn3/styles/main.css">
    <script src="scripts/detailsmodal.js"></script>
    <title>Main Page</title>
</head>

<body>
       <?php include 'includes/header.php'; ?>

       <?php include 'includes/nav.php'; ?>

        <!-- Main Body -->
        <main>
            <h1>My Library</h1>

            <div class="movies">
                <!-- for each movie in the database for the user display them -->
                <?php foreach($stmt as $row): ?>
                <div>
                    <!-- build the path for the cover -->
                    <?php if(isset($row['cover'])): ?>
                    <img src="/~cassandrahaydock/www_data/<?=$row['cover']?>" height="250" width="180" alt=""/>
                    <?php elseif(isset($row['url'])): ?>
                    <img src="<?=$row['url']?>" height="250" width="180" alt=""/>
                    <?php else: ?>
                    <img src="./imgs/nocover.jpg" height="250" width="180" alt=""/>
                    <?php endif ?>
                    <!-- get cover title -->
                    <h2><?="$row[title]"?></h2>
                    <!-- make the editvid, deletevid, and details button contain the movie id in the url -->
                    <a href="editvid.php?<?="id=$row[id]"?>"><i class="fa-solid fa-pencil" title="edit video"></i></a>
                    <a href="deletevid.php?<?="id=$row[id]"?>"><i class="fa-solid fa-trash-can" title="delete video"></i></a>
                    <a class="detailslink" href="modalDetails.php?<?="id=$row[id]"?>"><i class="fa-solid fa-eye" title="details"></i></a>  
                </div>
                <?php endforeach ?>
                <div class="modal" id="mods"></div>
            </div>

            <div class="traverse">
                <!-- if the offset is not zero meaning we are not on the first page show a previous button -->
                <?php if($offset != 0): ?>
                    <!-- minus four from offset to go back a page -->
                    <a href="index.php?offset=<?=$offset - 5?>&page=<?=$page - 1?>">Previous</a>
                <?php endif ?>
                <!-- if the page number is not equal to the max number of pages the user needs AND if there is more then one page show next button -->
                <?php if($page != $numPages && $numPages != 0): ?>
                    <!-- add four to offset to go to next page -->
                    <a href="index.php?offset=<?=$offset + 5?>&page=<?=$page + 1?>">Next</a>
                <?php endif ?>
            </div>
        </main>

        <?php include 'includes/footer.php';?>
</body>

</html>