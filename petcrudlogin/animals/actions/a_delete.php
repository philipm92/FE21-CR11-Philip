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
$TABLE = $_SESSION["TABLE"];
if ($_POST) {
    $id = $_POST['id'];
    echo $picture = $_POST['picture'];
    ($picture =="animal.png")?: unlink("../pictures/$picture");

    # run query
    $db->query("DELETE FROM $TABLE WHERE id = {$id}");
    $result = $db->query("SELECT * FROM `pet_adoption` WHERE `fk_user_id` = {$id}");
    if ($result->numRows() > 0) $db->query("DELETE FROM `pet_adoption` WHERE `fk_user_id` = {$id}");    
    $class = "success";
    $message = "Successfully Deleted!";

    $db->close();
} else {
    header("location: ../error.php");
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Delete</title>
        <?php require_once '../../components/bootcss.php'?>  
        <link href="../../components/style.css" rel="stylesheet"> 
    </head>
    <body>
        <div class="container">
            <div class="mt-3 mb-3">
                <h1>Delete request response</h1>
            </div>
            <div class="alert alert-<?=$class;?>" role="alert">
                <p><?=$message;?></p>
                <a href='../../home.php'><button class="btn btn-success" type='button'>Home</button></a>
            </div>
        </div>
    </body>
</html>