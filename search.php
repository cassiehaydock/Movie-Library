<?php 
//start session
session_start();
//if user is not logged in redirect to login
if(!isset($_SESSION['id']))
{
    header("Location:login.php");
    exit();   
}

//include library
require 'includes/library.php';

//create errors array
$errors = array();

//make database connection
$pdo = connectDB();

//get the user id from session
$Userid = $_SESSION['id'];
//set a variable for search
$search = $_POST['find'] ?? null;

//if the user has submitted the form
if(isset($_POST['submit'])){
 
   //check to make sure user acctually entered text to search 
   if (strlen($search) === 0){
     $errors['find'] = true;
   }

   //filter the search
   $search = filter_var($search, FILTER_SANITIZE_STRING);
 
   //if there is no errors
   if(count($errors) === 0)
   { 

     //query for dynamic showing of movies
     $query = "SELECT id, title, cover, url FROM cois3420_movies WHERE Userid = '$Userid ' AND title LIKE ?";
     $stmt = $pdo->prepare($query);
     //the % means that it will return any title contains the $search string
     $stmt->execute(['%'.$search.'%']);

   }
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
    <title>Search</title>
</head>

<body>
    <!-- Header of Page -->
    <?php include 'includes/header.php'; ?>

    <!-- Navigation bar -->
    <?php include 'includes/nav.php'; ?>

    <!-- Main Body -->
    <main>
        <form id="search" method="post">

            <h1>Search</h1>

            <!-- Search input and label -->
            <div>
                <label for="find">Search</label>
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="search" required name="find" id="find" value="<?=$search?>" placeholder="Grown Ups"/>
            </div>

            <!-- search -->
            <div>
                <button type="submit" name="submit" id="submit">Search</button>
            </div>

            <?php if(isset($_POST['submit'])): ?>
            <div class="movies">
                <!-- for each movie in the database for the user display them -->
                <?php foreach($stmt as $row): ?>
                <div>
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
                    <h2><?php echo "$row[title]"?></h2>
                    <!-- make the editvid, deletevid, and details button contain the movie id in the url -->
                    <a href="editvid.php?<?php echo "id=$row[id]"?>"><i class="fa-solid fa-pencil" title="edit video"></i></a>
                    <a href="deletevid.php?<?php echo "id=$row[id]"?>"><i class="fa-solid fa-trash-can" title="delete video"></i></a>
                    <a href="details.php?<?php echo "id=$row[id]"?>"><i class="fa-solid fa-eye" title="details"></i></a>
                </div>
                </div>
                <?php endforeach ?>
            </div>
            <?php endif ?>
            
        </form>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>

</html>