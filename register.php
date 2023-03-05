<?php
//include library
require 'includes/library.php';

//make database connection
$pdo = connectDB();

//declare error array
$errors = array();

//create varaibles to hold user information
$firstname = $_POST['firstname'] ?? null;
$lastname = $_POST['lastname'] ?? null;
$username = $_POST['username'] ?? null;
$email1 = $_POST['email1'] ?? null;
$email2 = $_POST['email2'] ?? null;
$password = $_POST['newpass'] ?? null;
$submit =  $_POST['submit'] ?? null;

//if the user has submitted the form
    if (isset($_POST['submit'])) {

        //if there are no errors
        if (count($errors) === 0)
        {
           //hash password
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $password = $hash;

           //build query to insert all user data into database
           $query = "INSERT INTO cois3420_allusers (id, firstname, lastname, username, email, password, token) VALUES (NULL, ?, ?, ?, ?, ?, '')";

           //prepare & execute query
           $stmt = $pdo->prepare($query)->execute([$firstname, $lastname, $username, $email1, $password]);

           //redirect to index and start session
           session_start();
           header("Location:index.php");
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
    <script src="scripts/register.js"></script>
    <script src="scripts/pwdstrength.js"></script>
    <title>Create Account</title>
</head>

<body>
    <!-- Header of Page -->
    <?php include 'includes/header.php'; ?>

    <!-- Navigation bar -->
    <?php include 'includes/nav.php'; ?>

    <!-- Main Body-->
    <main>
        <!-- Create Account Form -->
        <form id="registration" method="post">

            <h1>Create Account</h1>
            <h2>Get started with a free account!</h2>

            <!-- First & Last Name -->
            <div>
                <label for="firstname">Enter First Name</label>
                <input type="text" name="firstname" id="firstname" value="<?php echo $firstname?>" maxlength="50" placeholder="First Name" />
                <span class="error <?= !isset($errors['firstname']) ? 'hidden' : "" ?>">Please enter your firstname</span>
            </div>

            <div>
                <label for="lastname">Enter Last Name</label> 
                <input type="text" name="lastname" id="lastname" value="<?php echo $lastname?>" maxlength="50" placeholder="Last Name" />
                <span class="error <?= !isset($errors['lastname']) ? 'hidden' : "" ?>">Please enter your lastname</span>
            </div>

            <!-- Username -->
            <div>
                <label for="username">Enter A Username</label>
                <input type="text" name="username" id="username" value="<?php echo $username?>" maxlength="20" placeholder="Username" />
                <span class="error <?= !isset($errors['username']) ? 'hidden' : "" ?>">Please enter a username</span>
                <span class="error <?= !isset($errors['exist']) ? 'hidden' : "" ?>">Username is not unique</span>
            </div>

            <!-- Email -->
            <div>
                <label for="email1">Enter Email</label>
                <input type="email" name="email1" id="email1" value="<?php echo $email1?>" placeholder="Email" />
                <span class="error <?= !isset($errors['email1']) ? 'hidden' : "" ?>">Please enter your email</span>
                <span class="error <?= !isset($errors['account']) ? 'hidden' : "" ?>">An account already exists with this email</span>
            </div>
            <div>
                <label for="email2">Enter Email Again</label>
                <input type="email" name="email2" id="email2" value="<?php echo $email2?>" placeholder="Re-Enter Email" />
                <span class="error <?= !isset($errors['email2']) ? 'hidden' : "" ?>">Please re-enter your email</span>
            </div>

            <!-- Password -->
            <div>
                <label for="newpass">Enter A Password</label>
                <input type="password" name="newpass" id="newpass" placeholder="Password" />
                <span class="error <?= !isset($errors['newpass']) ? 'hidden' : "" ?>">Please enter a password</span>
            </div>
            <!-- Password Strength indicator -->
            <div id="passStrength"><span></span></div>

            <!-- Create Account Button -->
            <div>
                <button type="submit" name="submit" id="submit">Create Account</button>
            </div>
        </form>

    </main>

    <?php include 'includes/footer.php'; ?>

</body>

</html>