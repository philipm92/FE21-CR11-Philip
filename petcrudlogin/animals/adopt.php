<?php
session_start();
echo $TABLE = $_SESSION["TABLE"];
// if (isset($_SESSION['user']) != "") {
//     header("Location: ../home.php");
//     exit;
// }

if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

require_once '../components/db_connect.php';

$book_date = "";
$dateError = "";
$error = false;
if (isset($_POST['btn-book'])) {
    echo $book_date = CleanInput($_POST['date']);

    if (empty($book_date) || $book_date == "0000-00-00") {
        $error = true;
        $dateError = "Please enter a valid date.";
    }
}
if ($_GET) {
    $id = $_GET["id"];
    $user = $_GET["user"];
    
    $sql = "SELECT * FROM $TABLE WHERE id = ?";
    $result = $db->query($sql, array($id));
    if ($result->numRows() == 1) {
        $data = $result->fetchArray();
        $name = $data["name"];
        $location = $data["location"];
        $description = $data["description"];
        $size = $data["size"];
        $age = $data["age"];
        $hobbies = $data["hobbies"];
        $breed = $data["breed"];
        $status = $data["status"];
        $picture = $data["picture"];

    } else {
        header("location: error.php");
    }
    $db->close();
} else {
    header("location: error.php");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Adopt Pet <?php echo $name ?></title>
        <?php require_once '../components/bootcss.php'?>
        <link href="../components/style.css" rel="stylesheet">
    </head>
    <body>
        <fieldset>
            <legend class='h2'>Book request <img class='img-square rounded-circle' src='pictures/<?php echo $picture ?>' alt="<?php echo $name ?>"></legend>
            <form action="actions/a_adopt.php"  method="post" enctype="multipart/form-data">
                <table class="table d-flex">
                    <tr>
                        <th>Room</th>
                        <td><?php echo $room ?></td>
                    </tr>
                    <tr>
                        <th>Floor</th>
                        <td><?php echo $floor ?></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td><?php echo $description ?></td>
                    </tr>                                        
                    <tr>
                        <th>Price</th>
                        <td><?php echo $price ?>&euro;</td>
                    </tr>
                    <tr>
                        <th>Duration</th>
                        <td><?php echo $duration." ".$weeks ?></td>
                    </tr>                    
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php echo $status;?>
                        </td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>
                            <input class='form-control' type="date"  name="book_date" value ="<?php echo $book_date ?>"/>
                            <span class="text-danger"> <?php echo $dateError; ?> </span>
                        </td>
                    </tr>                    
                    <tr>
                        <input type= "hidden" name= "id" value= "<?php echo $data['id'] ?>" />
                        <input type= "hidden" name= "user" value= "<?php echo $user ?>" />
                        <td><button class="btn btn-success" type= "submit" name="btn-book">Adopt Pet</button></td>
                        <td><a href= "index.php"><button class="btn btn-warning" type="button">Back</button></a></td>
                    </tr>
                </table>
            </form>
        </fieldset>
    </body>
</html>