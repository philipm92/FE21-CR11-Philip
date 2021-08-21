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

$TABLE = $_SESSION["TABLE"];
if ($_POST) {
    $id = $_POST["id"];
    $user = $_POST["user"];
    $date = $_POST["date"];
    $sql = "INSERT INTO `pet_adoption`(`id`, `fk_user_id`, `fk_animal_id`, `date`) VALUES (?,?,?,?)";
    // run query
    $db->query($sql, array(NULL, $user, $id, $date));
    $name = $db->query("SELECT name FROM $TABLE WHERE id=?", array($id))->fetchArray()["name"];
    
    $class = "success";
    $message = "Pet \"$name\" was successfully adopted";
    // update animals table
    $db->query("UPDATE $TABLE SET status = ? WHERE id = ?", array('adopted', $id));
 

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
                <h1>Adopt request response</h1>
            </div>
            <div class="alert alert-<?php echo $class; ?>" role="alert">
                <p><?php echo ($message) ?? ''; ?></p>
                <!-- <a href='../adopt.php?id=<?=$id;?>'><button class="btn btn-warning" type='button'><i class="fas fa-hand-point-left"></i></button></a> -->
                <a href='../../index.php'><button class="btn btn-warning" type='button'><i class="fas fa-home"></i></button></a>
            </div>
        </div>
    </body>
</html>