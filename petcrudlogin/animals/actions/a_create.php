<?php
session_start();

if (isset($_SESSION['user']) != "") {
    header("Location: ../../home.php");
    exit;
}

if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit;
}

require_once '../../components/db_connect.php';
require_once '../../components/file_upload.php';


if ($_POST) {   
    $name = $_POST["name"];
    $location = $_POST["location"];
    $description = $_POST["description"];
    $size = $_POST["size"];
    $age = $_POST["age"];
    $hobbies = $_POST["hobbies"];
    $breed = $_POST["breed"];
    $status = $_POST["status"];    
    $uploadError = '';
    //this function exists in the service file upload.
    $picture = file_upload($_FILES['picture'], "animals");  
   
    $sql = "INSERT INTO `animals`(`id`, `picture`, `name`, `location`, `description`, `size`, `age`, `hobbies`, `breed`, `status`) VALUES (?,?,?,?,?,?,?,?,?,?);";
    $result = $db->query($sql, array(NULL, $picture, $name, $location, $description, $size, $age, $hobbies, $breed, $status));
    
    $class = "success";
    $message = "The entry below was successfully created <br />
    <table class='table w-50'>
        <tr>
            <td> $name </td>
            <td> $age </td>
        </tr>
    </table><hr>";
    $uploadError = ($picture->error !=0)? $picture->ErrorMessage :'';


    $db->close();
} else {
    header("location: ../error.php");
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Update</title>
        <?php require_once '../../components/bootcss.php'?>
        <link href="../../components/style.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="mt-3 mb-3">
                <h1>Create request response</h1>
            </div>
            <div class="alert alert-<?=$class;?>" role="alert">
                <p><?php echo ($message) ?? ''; ?></p>
                <p><?php echo ($uploadError) ?? ''; ?></p>
                <a href='../../home.php'><button class="btn btn-primary" type='button'>Home</button></a>
            </div>
        </div>
    </body>
</html>