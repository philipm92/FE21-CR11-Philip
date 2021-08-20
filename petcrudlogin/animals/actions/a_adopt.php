<?php
session_start();

// if (isset($_SESSION['user']) != "") {
//     header("Location: ../home.php");
//     exit;
// }

if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}


require_once '../../components/db_connect.php';
require_once '../../components/file_upload.php';

if ($_POST) {
    $id = $_POST["id"];
    $user = $_POST["user"];
    $book_date = $_POST["book_date"];
    $sql = "INSERT INTO `booking`(`id`, `fk_hotel_id`, `fk_user_id`, `date`) VALUES (NULL, $id, $user, '$book_date')";
    // run query
    $db->query($sql);

    if (1) {
        $class = "success";
        $message = "The room was successfully booked";
        // update hotel table
        $db->query("UPDATE animals SET status = 'adopted' WHERE id = {$id}");
    } 
    // else {
    //     $class = "danger";
    //     $message = "Error while booking the room: <br>" . mysqli_connect_error();
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
        <?php require_once '../../components/boot.php'?> 
    </head>
    <body>
        <div class="container">
            <div class="mt-3 mb-3">
                <h1>Update request response</h1>
            </div>
            <div class="alert alert-<?php echo $class;?>" role="alert">
                <p><?php echo ($message) ?? ''; ?></p>
                <a href='../adopt.php?id=<?=$id;?>'><button class="btn btn-warning" type='button'>Back</button></a>
                <a href='../index.php'><button class="btn btn-success" type='button'>Home</button></a>
            </div>
        </div>
    </body>
</html>