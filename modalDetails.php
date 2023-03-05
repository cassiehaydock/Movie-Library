<?php
//start session
session_start();
//check if user is logged in if not exit to login
if(!isset($_SESSION['id']))
{
    header("Location:login.php");
    exit();   
}

//include library
require 'includes/library.php';

//make database connection
$pdo = connectDB();

//build query to select all info for a specifc movie(id) for a specific user(Userid)
$query = "SELECT * FROM cois3420_movies WHERE Userid = ? AND id = ?";

//prepare & execute query
$stmt = $pdo->prepare($query);
//session id is the userid and get id is from the URL to identify movie
$stmt->execute([$_SESSION['id'], $_GET['id']]);
$row = $stmt->fetch();

//get video types and explode into an array
$videotype = $row['videotype'];
$videotypept2 = explode(',', $videotype);

?>

    <!-- Main Body -->
    <main class="details">
        <div>
            <h1>Movie Details</h1>

            <div>
                    <?php if(isset($row['cover'])): ?>
                    <img src="/~cassandrahaydock/www_data/<?=$row['cover']?>" height="250" width="180" alt=""/>
                    <?php elseif(isset($row['url'])): ?>
                    <img src="<?=$row['url']?>" height="250" width="180" alt=""/>
                    <?php else: ?>
                    <img src="./imgs/nocover.jpg" height="250" width="180" alt=""/>
                    <?php endif ?>
            </div>

            <div>
                 <h2><?= "$row[title]"?></h2>
            </div>
        </div>

        <div>
            <div>
                <!-- up until the rating amount show a star -->
                 <?php for($i = 0; $i < $row['rating']; $i++)
                 {
                    echo '<i class="fa-solid fa-star"></i>';
                 }
                 ?>
            </div>

            <div>
                <!-- foreach videotype in the array echo it out with a checkmark icon -->
                <?php foreach($videotypept2 as $print)
                {
                    echo '<i class="fa-solid fa-square-check"></i>';
                    echo $print;
                }
                ?>
            </div>

            <!-- get each peice of information from databse using $row and echo it -->
            <div>MPAA : <?= $row['mpaa']?></div>
            <div>Year : <?= $row['years']?></div>
            <div>Theatrical Release: <?= $row['releases'] ?></div>
            <div>DVD/Streaming Release: <?= $row['streaming'] ?> </div>
            <div>Run Time(hours): <?= $row['runtime'] ?> </div>
            <div>Studio: <?= $row['studio'] ?></div>
            <div>Actors: <?= $row['actors'] ?></div>
            <div>Genres: <?= $row['genre'] ?></div>
            <div><?= $row['summary'] ?></div>
        </div>

        <div><i id="close" class="fa-solid fa-square-xmark"></i></div>

    </main>
