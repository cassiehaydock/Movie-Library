<?php 
//start session
session_start();
//if user is not logged in exit to login
if(!isset($_SESSION['id']))
{
    header("Location:login.php");
    exit();   
}

//create array for errors
$errors = array();

//include library
require 'includes/library.php';

//make database connection
$pdo = connectDB();

//Prepopulate the edit account screen 
//build query to select all info for user withe the id being taking from session
$query = "SELECT * FROM cois3420_allusers WHERE id = ?";

//prepare & execute query
$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['id']]);
$row = $stmt->fetch();

//create variables to contain the info from database or from post
$firstname = $_POST['firstname'] ?? $row['firstname'] ?? '';
$lastname = $_POST['lastname'] ?? $row['lastname'] ?? '';
$username = $_POST['username'] ?? $row['username'] ?? '';
$email1 = $_POST['email1'] ?? $row['email'] ?? '';
$email2 = $_POST['email2'] ?? $row['email'] ?? '';
$password = $_POST['password'] ?? '';
$id = $_SESSION['id'];

//if the user has submitted the form
if (isset($_POST['submit'])) {

    //check that user inputted a firstname
    if (strlen($firstname) === 0) {
        $errors['firstname'] = true;
    }

    //check that user inputted a lastname
    if (strlen($lastname) === 0) {
        $errors['lastname'] = true;
    }

    //check that user inputted a username
    if (strlen($username) === 0) {
      $errors['username'] = true;
    }

    //if the user changes the username check to make sure its unique
    if($username != $row['username'])
    {
        //query to find if there is unique user names
        $sql="SELECT username FROM cois3420_allusers WHERE username = ?";
        $stmt1=$pdo->prepare($sql);
        $stmt1->execute([$username]);

        //if the query returned false, user doesn't exist
        if($stmt1->fetch()){
        $errors['exist'] = true;
        }
    }

    //validate email1
    if (!filter_var($email1, FILTER_VALIDATE_EMAIL)) {
        $errors['email1'] = true;
    }

    //validate email2 to make sure the emails are the same
    if($email1 != $email2)
    {
        $errors['email2'] = true;
    }

    //if there are no errors
    if(count($errors) === 0)
    {
       //build query to uodate account information for a user
       $query = "UPDATE cois3420_allusers SET firstname = ?, lastname = ?, username = ?, email = ? WHERE id = ?";

       //prepare & execute query
       $stmt = $pdo->prepare($query)->execute([$firstname, $lastname, $username, $email1, $id]);

        //check that if a user inputted a password you edit password
        if (strlen($password) !== 0) {
        //hash password
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $password = $hash;

        $query2 = "UPDATE cois3420_allusers SET password = ? WHERE id = ?";
        $stmt2 = $pdo->prepare($query2)->execute([$password, $id]);
        }

       //redirect to index
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
    <title>Edit Account</title> 
</head>

<body>
    <!-- Header of Page -->
    <?php include 'includes/header.php'; ?>

    <!-- Navigation bar -->
    <?php include 'includes/nav.php'; ?>

    <!-- Main Body-->
    <main>
        <!-- Create Account Form -->
        <form id="edit" method="post">

            <h1>Edit Account</h1>

            <!-- First & Last Name -->
            <div>
                <label for="firstname">Edit First Name</label>
                <input type="text" name="firstname" id="firstname" value="<?php echo $firstname?>" maxlength="50" placeholder="First Name" />
                <span class="error <?= !isset($errors['firstname']) ? 'hidden' : "" ?>">Please enter your firstname</span>
            </div>

            <div>
                <label for="lastname">Edit Last Name</label>
                <input type="text" name="lastname" id="lastname" value="<?php echo $lastname?>" maxlength="50" placeholder="Last Name" />
                <span class="error <?= !isset($errors['lastname']) ? 'hidden' : "" ?>">Please enter your lastname</span>
            </div>

            <!-- Username -->
            <div>
                <label for="username">Edit Username</label>
                <input type="text" name="username" id="username" value="<?php echo $username ?>" maxlength="20" placeholder="Username" />
                <span class="error <?= !isset($errors['username']) ? 'hidden' : "" ?>">Please enter a username</span>
                <span class="error <?= !isset($errors['exist']) ? 'hidden' : "" ?>">An account already exists with this username</span>
            </div>

            <!-- Email -->
            <div>
                <label for="email1">Edit Email</label>
                <input type="email" name="email1" id="email1" value="<?php echo $email1 ?>" placeholder="Email" />
                <span class="error <?= !isset($errors['email1']) ? 'hidden' : "" ?>">Please enter your email</span>
            </div>
            <div>
                <label for="email2">Email Again</label>
                <input type="email" name="email2" id="email2" value="<?php echo $email2 ?>" placeholder="Re-Enter Email" />
                <span class="error <?= !isset($errors['email2']) ? 'hidden' : "" ?>">Please re-enter your email</span>
            </div>

            <!-- Password -->
            <div>
                <label for="password">Edit Password</label>
                <input type="password" name="password" id="password" value="<?php echo $password ?>" placeholder="Password" />
                <span class="error <?= !isset($errors['password']) ? 'hidden' : "" ?>">Please enter a password</span>
            </div>

            <!-- Create Account Button -->
            <div>
                <button type="submit" name="submit" id="submit">Update Account</button>
            </div>
        </form>

    </main>

    <?php include 'includes/footer.php'; ?>

</body>

</html>