<?php 
//include library
require 'includes/library.php';
//errors array
$errors = array();

//make database connection
$pdo = connectDB();

//declare variables for password checking
$password1 = $_POST['newpass1'] ?? null;
$password2 = $_POST['newpass2'] ?? null;

//if the user has submitted the form
if(isset($_POST['submit'])){

    //get the token from the URL
    $token = $_GET['token'];

    //chekc if the password has been entered once
    if (strlen($password1) === 0) {
        $errors['newpass1'] = true;
    }

    //check if password has been entered again
    if (strlen($password2) === 0) {
        $errors['newpass2'] = true;
    }

    //check if passwords are the same
    if($password1 != $password2)
    {
         $errors['match'] = true;
    }

    //if no errors
    if(count($errors) === 0)
    { 
        //hash password
        $hash = password_hash($password1, PASSWORD_DEFAULT);
        $password1 = $hash;

       //build query to update password
       $query = "UPDATE cois3420_allusers SET password = ? WHERE token = ?";

       //prepare & execute query
       $stmt = $pdo->prepare($query)->execute([$password1, $token]);

       //redirect to index
       header("Location:login.php");
       exit();
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
    <title>Password Reset</title>
</head>

<body>
    <!-- Header of Page -->
    <?php include 'includes/header.php'; ?>

    <!-- Main Body -->
    <main>
        <!-- Forgot password form -->
        <form id="passreset" method="post">

            <h1>Password Reset</h1>

            <!-- Password  -->
            <div>
                <label for="newpass1">Enter A New Password</label>
                <input type="password" name="newpass1" id="newpass1" placeholder="New Password"/>
            </div>
            <span class="error <?= !isset($errors['newpass1']) ? 'hidden' : "" ?>">Please enter a password</span>

            <div>
                <label for="newpass2">Confirm Password</label>
                <input type="password" name="newpass2" id="newpass2" placeholder="New Password"/>
            </div>
            <span class="error <?= !isset($errors['newpass2']) ? 'hidden' : "" ?>">Please enter a password</span>
            <span class="error <?= !isset($errors['match']) ? 'hidden' : "" ?>">Passwords don't match</span>

            <!-- Request change button -->
            <div>
                <button type="submit" name="submit" id="submit">Send Request Link</button>
            </div>
        </form>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>

</html>