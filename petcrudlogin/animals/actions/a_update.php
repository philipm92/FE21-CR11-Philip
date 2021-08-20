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
    $id = $_POST["id"];
    $name = CleanInput($_POST["name"]);
    $location = CleanInput($_POST["location"]);
    $description = CleanInput($_POST["description"]);
    $size = CleanInput($_POST["size"]);
    $age = CleanInput($_POST["age"]);
    $hobbies = CleanInput($_POST["hobbies"]);
    $breed = CleanInput($_POST["breed"]);
    $status = CleanInput($_POST["status"]);
    //variable for upload pictures errors is initialized
    $uploadError = '';

    $picture = file_upload($_FILES['picture'], "animals"); //file_upload() called  
    if($picture->error===0){
        ($_POST["picture"]=="animal.png")?: unlink("../pictures/$_POST[picture]");       
        $sql = "UPDATE `animals` SET `name`=?,`location`=?,`description`=?,`size`=?,`age`=?,`hobbies`=?,`breed`=?,`status`=?,`picture`=? WHERE id = {$id}";
        $db->query($sql, array($name, $location, $description, $size, $age, $hobbies, $breed, $status, $picture->fileName));
    }else{
        $sql = "UPDATE `animals` SET `name`=?,`location`=?,`description`=?,`size`=?,`age`=?,`hobbies`=?,`breed`=?,`status`=? WHERE id = {$id}";
        $db->query($sql, array($name, $location, $description, $size, $age, $hobbies, $breed, $status));
    }
    #echo $sql; 

    // check for status and delete entry accordingly
    if ($_POST["old_status"] == "booked" && $status == "available") $db->query("DELETE FROM booking WHERE `fk_hotel_id` = {$id}");

    if (1) {
        $class = "success";
        $message = "The record was successfully updated";
        $uploadError = ($picture->error !=0)? $picture->ErrorMessage :'';
    } 
    // else {
    //     $class = "danger";
    //     $message = "Error while updating record : <br>" . mysqli_connect_error();
    //     $uploadError = ($picture->error !=0)? $picture->ErrorMessage :'';
    // }
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
                <h1>Update request response</h1>
            </div>
            <div class="alert alert-<?php echo $class;?>" role="alert">
                <p><?php echo ($message) ?? ''; ?></p>
                <p><?php echo ($uploadError) ?? ''; ?></p>
                <a href='../update.php?id=<?=$id;?>'><button class="btn btn-warning" type='button'>Back</button></a>
                <a href='../../home.php'><button class="btn btn-success" type='button'>Home</button></a>
            </div>
        </div>
    </body>
</html>