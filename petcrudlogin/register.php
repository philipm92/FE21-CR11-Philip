<?php
session_start(); // start a new session or continues the previous
if (isset($_SESSION['user']) != "") {
    header("Location: home.php"); // redirects to home.php
}
if (isset($_SESSION['adm']) != "") {
    header("Location: dashboard.php"); // redirects to home.php
}
require_once 'components/db_connect.php';
require_once 'components/file_upload.php';
$error = false;
$fname = $lname = $email = $pass = $phone = $address = $picture = '';
$fnameError = $lnameError = $emailError = $passError = $phoneError = $addressError = $picError = '';

if (isset($_POST['btn-signup'])) {
    // sanitize user input to prevent sql injection
    $fname = CleanInput($_POST['fname']);
    $lname = CleanInput($_POST['lname']);
    $email = CleanInput($_POST['email']);
    $pass = CleanInput($_POST['pass']);
    $phone = CleanInput($_POST['phone']);
    $address = CleanInput($_POST['address']);
   

    $uploadError = '';
    $picture = file_upload($_FILES['picture']);

    // basic first name validation
    if (empty($fname)) {
        $error = true;
        $fnameError = "Please enter your full first name";
    } else if (strlen($fname) < 2) {
        $error = true;
        $fnameError = "First name must have at least 2 characters.";
    } else if (!preg_match("/^[a-zA-Z]+$/", $fname)) {
        $error = true;
        $fnameError = "First name must contain only letters and no spaces.";
    }

    // basic last name validation
    if (empty($lname)) {
        $error = true;
        $fnameError = "Please enter your full surname";
    } else if (strlen($lname) < 2) {
        $error = true;
        $fnameError = "Surname must have at least 2 characters.";
    } else if (!preg_match("/^[a-zA-Z]+$/", $lname)) {
        $error = true;
        $fnameError = "Surname must contain only letters and no spaces.";
    }   
    //basic email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter valid email address.";
    } else {
        // checks whether the email exists or not
        $query = "SELECT email FROM user WHERE email='$email'";
        $result = $db->query($query);
        $count = $result->numRows();
        if ($count != 0) {
            $error = true;
            $emailError = "Provided Email is already in use.";
        }
    }

    // basic address validation
    if (empty($address)) {
        $error = true;
        $fnameError = "Please enter your full address.";
    }

    // basic phone number validation
    if (empty($phone)) {
        $error = true;
        $fnameError = "Please enter your phone number.";
    } else if (!ValidatePhoneNumber($phone)) {
        $error = true;
        $fnameError = "Please enter a valid phone number.";        
    }

    // password validation
    if (empty($pass)) {
        $error = true;
        $passError = "Please enter password.";
    } else if (strlen($pass) < 4) {
        $error = true;
        $passError = "Password must have at least 4 characters.";
    }

    // password hashing for security
    $pass_hashed = password_hash($pass, PASSWORD_BCRYPT);
    // if there's no error, continue to signup
    if (!$error) {
        $query = "INSERT INTO `user` (`id`, `first_name`, `last_name`, `email`, `password`, `phone_number`, `address`, `picture`, `status`) VALUES (?,?,?,?,?,?,?,?,?);";
        #echo "<br />".$fname." ".$lname." ".$email." ".$pass_hashed." ".$phone." ".$address." ".$picture->fileName, "user";
        # run query
        $db->query($query, array(NULL, $fname, $lname, $email, $pass_hashed, $phone, $address, $picture->fileName, "user"));
        $errTyp = "success";
        $errMSG = "Successfully registered, you may login now";
        $uploadError = ($picture->error != 0) ? $picture->ErrorMessage : '';

    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registration System</title>
    <?php require_once 'components/bootcss.php'?>
    <link href="components/style.css" rel="stylesheet">
</head>
<body>
<div class="container">
   <form class="w-75" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off" enctype="multipart/form-data">
            <h2>Sign Up.</h2>
            <hr/>
            <?php
            if (isset($errMSG)) {
            ?>
            <div class="alert alert-<?php echo $errTyp ?>" >
                         <p><?php echo $errMSG; ?></p>
                         <p><?php echo $uploadError; ?></p>
            </div>

            <?php
            }
            ?>
            <div class="d-flex my-2">
                <input type ="text"  name="fname"  class="form-control w-50 me-1"  placeholder="First name" maxlength="50" value="<?php echo $fname ?>"  />
                <span class="text-danger"> <?php echo $fnameError; ?> </span>

                <input type ="text"  name="lname"  class="form-control w-50 ms-1"  placeholder="Surname" maxlength="50" value="<?php echo $lname ?>"  />
                <span class="text-danger"> <?php echo $lnameError; ?> </span>
            </div>
            <div class="d-flex my-2">
                 <input type="email" name="email" class="form-control w-50 me-1" placeholder="Enter Your Email" maxlength="40" value ="<?php echo $email ?>"  />
                 <span  class="text-danger"> <?php echo $emailError; ?> </span>

                 <input type="password" name="pass" class="form-control w-50 ms-1" placeholder="Enter Password" maxlength="15"  />
                 <span class="text-danger"> <?php echo $passError; ?> </span>
            </div>

            <div class="d-flex my-2">
                 <input type="text" name="address" class="form-control w-50 me-1" placeholder="Enter Your Address" maxlength="40" value ="<?php echo $address ?>"  />
                 <span  class="text-danger"> <?php echo $addressError; ?> </span>

                 <input type="tel" name="phone" class="form-control w-50 ms-1" placeholder="Enter Phone Number" maxlength="15" value ="<?php echo $phone ?>" />
                 <span class="text-danger"> <?php echo $phoneError; ?> </span>
            </div>

            <input class='form-control' type="file" name="picture" >
            <span class="text-danger"> <?php echo $picError; ?> </span>
            <hr/>
            <button type="submit" class="btn btn-block btn-primary" name="btn-signup">Sign Up</button>
            <hr/>
            <a href="index.php">Sign in Here...</a>
   </form>
   </div>
   <?php require_once 'components/bootjs.php' ?>
</body>
</html>