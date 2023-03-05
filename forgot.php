<?php 

//include library
require 'includes/library.php';
//errors array
$errors = array();

//make database connection
$pdo = connectDB();

//make a variable to hold the email
$email = $_POST['email'] ?? null;

//if the user has submitted the form
if(isset($_POST['submit'])){
 
    //validate email1
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = true;
    }
    else
    {
        //query to check if that email exists in the database
        $query = "SELECT * FROM cois3420_allusers WHERE email = ? ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$email]);
        $row = $stmt->fetch();

        //if theres no id that means there is no user with that email
        if($row === false)
        {
          $errors['notvalid'] = true;
        }
        else
        {
          //get the id of the user from the query where the email matches
          $id = $row['id'];
         }
    }
  
    //if there are no errors send the email to user email pulled from database
    if(count($errors) === 0)
    {  
        //create a randomtoken and add to database for forgotpassword 
        $token = bin2hex(random_bytes(20));
        $query2 = "UPDATE cois3420_allusers SET token = ? WHERE id = ?";
        $stmt2 = $pdo->prepare($query2);
        $stmt2->execute([$token, $id]);

        require_once "Mail.php";  //this includes the pear SMTP mail library
        $from = "Password System Reset <noreply@loki.trentu.ca>";
        $to = $email;  //put user's email here
        $subject = "Reset Password";
        //link to password reset page with token
        $body = "Use this link to reset password: https://loki.trentu.ca/~cassandrahaydock/3420/assignments/assn2/reset.php?token=".$token;
        $host = "smtp.trentu.ca";
        $headers = array ('From' => $from,'To' => $to,'Subject' => $subject);
        $smtp = Mail::factory('smtp',
        array ('host' => $host));
  
        $mail = $smtp->send($to, $headers, $body);
        if (PEAR::isError($mail)) {
        echo("<p>" . $mail->getMessage() . "</p>");
        } 
        else {
            $errors['sent'] = true;
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
    <title>Forgot Password</title>
</head>

<body>
    <!-- Header of Page -->
    <?php include 'includes/header.php'; ?>

    <!-- Navigation bar -->
    <?php include 'includes/nav.php'; ?>

    <!-- Main Body -->
    <main>
        <!-- Forgot password form -->
        <form id="forgotpass" action="?" method="post">

            <h1>Forgot Your Password?</h1>
            <h2>No worries!</h2>

            <!-- Email -->
            <div>
                <label for="email">Enter Email:</label>
                <input type="email" name="email" value="<?= $email ?>" id="email" placeholder="example@gmail.com" />
            </div>
            <span class="error <?= !isset($errors['email']) ? 'hidden' : "" ?>">Please enter your email</span>
            <span class="error <?= !isset($errors['notvalid']) ? 'hidden' : "" ?>">Email does not exist</span>
            <span class="error <?= !isset($errors['sent']) ? 'hidden' : "" ?>">Message Succesfully Sent!</span>

            <!-- Request change button -->
            <div>
                <button type="submit" name="submit" id="submit">Send Request Link</button>
            </div>
        </form>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>

</html>