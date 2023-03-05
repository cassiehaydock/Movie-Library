<?php 
//start session
session_start(); 

//if user is not logged in exit to login
if(!isset($_SESSION['id']))
{
    header("Location:login.php");
    exit();   
}

//include library
require 'includes/library.php';

//make database connection
$pdo = connectDB();

//create variables for username and password
$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;

//create an array for errors
$errors = array();

//check for errors when form has been submitted
if (isset($_POST['submit'])) {

    //check that user inputted a username
    if (strlen($username) === 0) {
      $errors['username'] = true;
    }
  
    //check that user inputted a password
    if (strlen($password) === 0) {
        $errors['password'] = true;
      }

    //If there are no errors, do database work and delete account
    if (count($errors) === 0) {

        //build query
       $query = "SELECT * FROM cois3420_allusers WHERE username = ?";

       //prepare & execute query
       $stmt = $pdo->prepare($query);
       $stmt->execute([$username]);
       $stmt = $stmt->fetch()['password'];

       //comparing passwords
       if (password_verify($password, $stmt))
       {
          //if theres a cookie set it to one set to expire it
          if(isset($_COOKIE['mysitecookie'])){
            setcookie("mysitecookie","",1);
           }

          //build query to delete from table
          $query = "DELETE FROM cois3420_allusers WHERE username = ?";

          //prepare & execute query
          $stmt = $pdo->prepare($query);
          $stmt->execute([$username]);

          //end session and redirect to login
           header("Location:login.php");
           session_destroy();
           exit();
        }
       else
       {
         $errors['password'] = true;
       }

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
    <script src="scripts/deleteacc.js"></script>
    <title>Delete Account</title>
</head>

<body>
    <!-- Header of Page -->
    <?php include 'includes/header.php'; ?>

    <!-- Navigation bar -->
    <?php include 'includes/nav.php'; ?>

    <!-- Main Body-->
    <main>
        <!-- Create Account Form -->
        <form id="delete" method="post">

            <h1>Delete Account</h1>

            <!-- Username -->
            <div>
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?= $username ?>" maxlength="20" placeholder="Username" />
                <span class="error <?= !isset($errors['username']) ? 'hidden' : "" ?>">Please enter a username</span>
            </div>

            <!-- Password -->
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Password" />
                <span class="error <?= !isset($errors['password']) ? 'hidden' : "" ?>">Please enter a password</span>
            </div>

            <!-- Create Account Button -->
            <div>
                <button type="submit" name="submit" id="submit">Delete Account</button>
            </div>
        </form>

    </main>

    <?php include 'includes/footer.php'; ?>

</body>

</html>