<?php
//include library
require 'includes/library.php';

//make database connection
$pdo = connectDB();

//declare an erroe array
$errors = array();

//if there is a cookie then set prepoukate username
if(isset($_COOKIE['mysitecookie'])){
    $username=$_COOKIE['mysitecookie'];
   }
//else make username empty
else
{
    $username=null;
}

//set variables for password and remember me
$password = $_POST['password'] ?? null;
$remember = $_POST['remember'] ?? null;

//check for errors when form has been submitted
if (isset($_POST['submit'])) {

    //set username to the username user inputted
    $username = $_POST['username'];

    //check that user inpuuted a username
    if (strlen($username) === 0) {
      $errors['username'] = true;
    }
  
    //check that user inputted a password
    if (strlen($password) === 0) {
        $errors['password'] = true;
      }

    //if there is no errors
    if (count($errors) === 0) {

       //build query to check if username and password exist in database
       $query = "SELECT * FROM cois3420_allusers WHERE username = ?";

       //prepare & execute query
       $stmt = $pdo->prepare($query);
       $stmt->execute([$username]);
       $row = $stmt->fetch();

       if($row == false){
        $errors['nousername'] = true;
       }
       else
       {
          //comparing passwords
         if (password_verify($password, $row['password']))
          {
            //check if remember box was checked
            if ($remember !== null) {
               setcookie("mysitecookie",$username,time()+60*60*24*30*12);
            }

            //start session to indicate login
            session_start();
            //put the username and id into session array
            $_SESSION['username'] = $row['username'];
            $_SESSION['id'] = $row['id'];
            header("Location:index.php");
            exit();
           }
          //if theres is something wrong then echo error
         else
         {
          $errors['wrong'] = true;
         }
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
    <title>Login</title>
</head>

<body>
    <!-- Header of Page -->
    <?php include 'includes/header.php'; ?>

        <!-- Navigation bar -->
        <?php include 'includes/nav.php'; ?>

        <!-- Main Body -->
        <main>
            <!-- Login Form -->
            <form id="login" method="post">
                <h1>Login</h1>

                <!-- Username -->
                <div>
                    <label for="username">Username</label>
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="username" id="username" value="<?php echo $username?>"/>
                    <span class="error <?= !isset($errors['username']) ? 'hidden' : "" ?>">Please enter your username</span>
                </div>

                <!-- Password -->
                <div>
                    <label for="password">Password</label>
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" id="password" />
                    <span class="error <?= !isset($errors['password']) ? 'hidden' : "" ?>">Please enter your password</span>  
                    <span class="error <?= !isset($errors['wrong']) ? 'hidden' : "" ?>">Either Username Or Password is Wrong</span>
                    <span class="error <?= !isset($errors['nousername']) ? 'hidden' : "" ?>">Either Username Or Password is Wrong</span>
                </div>

                <!-- Remember Me -->
                <div>
                    <input type="checkbox" name="remember" id="remember" value="Y">
                    <label for="remember">Remember Me</label>
                </div>

                <!-- Link to forgot.php -->
                <div>
                    <a href="forgot.php">forgot password?</a>
                </div>

                <!-- Login Button -->
                <div>
                    <button type="submit" name="submit" value="<?php $submit = true?>" id="submit">Login</button>
                </div>
            </form>
        </main>

        <?php include 'includes/footer.php'; ?>
</body>

</html>