<?php 
session_start();

if (isset($_SESSION['user']) != "") {
    header("Location: ../home.php");
    exit;
}

if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}
require_once '../components/db_connect.php';
$TABLE = $_SESSION["TABLE"];

if ($_GET['id']) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM $TABLE WHERE id = {$id}" ;
    $result = $db->query($sql);
    $n = $result->numRows();
    $data = $result->fetchArray();
    if ($n == 1) {
        $name = $data['name'];
        $age = $data['age'];
        $breed = $data['breed'];
        $size = $data['size'];
        $picture = $data['picture'];
    } else {
        header("location: error.php");
    }
    $db->close();
} else {
    header("location: error.php");
}  
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Delete Pet</title>
        <?php require_once '../components/bootcss.php'?>
        <link href="../components/style.css" rel="stylesheet">
    </head>
    <body>
        <fieldset>
            <legend class='h2 mb-3'>Delete request <img class='img-thumbnail rounded-circle' src='pictures/<?php echo $picture ?>' alt="<?php echo $name ?>"></legend>
            <h5>You have selected the data below:</h5>
            <table class="table w-75 mt-3">
                <tr>
                    <td> <?php echo $name ?> </td>
                    <td> <?php echo $age ?> </td>
                    <td> <?php echo $breed ?> </td>
                    <td> <?php echo $size ?> </td>
                </tr>
            </table>

            <h3 class="mb-4">Do you really want to delete this pet?</h3>
            <form action ="actions/a_delete.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id ?>" />
                <input type="hidden" name="picture" value="<?php echo $picture ?>" />
                <button class="btn btn-danger" type="submit">Yes, delete it!</button>
                <a href="../home.php"><button class="btn btn-warning" type="button">No, go back!</button></a>
            </form>
        </fieldset>
    </body>
</html>