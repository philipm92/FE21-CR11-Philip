<?php
session_start();
$TABLE = $_SESSION["TABLE"];
// if (isset($_SESSION['user']) != "") {
//     header("Location: ../home.php");
//     exit;
// }

if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

require_once '../components/db_connect.php';

$date = "";
$dateError = "";
$error = false;
if (isset($_POST['btn-adopt'])) {
    echo $date = CleanInput($_POST['date']);

    if (empty($date) || $date == "0000-00-00") {
        $error = true;
        $dateError = "Please enter a valid date.";
    }
}

// $a = new stdClass();
// $c = "c";
// $d = "d";
// $a->head = array($c => $d, $d => $c);
// $a->body = array($c => $d, $d => $c);
// foreach($a->head as $k=>$v) echo $k." ".$v."<br />";
// foreach($a->body as $k=>$v) echo $k." ".$v."<br />";


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
            <div class="d-flex flex-column align-items-center">
                <h2 class='text-center'>Adopt "<?php echo $name ?>"</h2>
                <img class='img-square rounded-circle' src='pictures/<?php echo $picture ?>' alt="<?php echo $name ?>">
            </div>
            <form action="actions/a_adopt.php"  method="post" enctype="multipart/form-data">
                <div class="table-responsive w-50 mx-auto my-1">
                    <table class="table table-hover table-striped">
                        <tr>
                            <th>Name</th>
                            <td><?php echo $name; ?></td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td><?php echo $location; ?></td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td><?php echo $description; ?></td>
                        </tr>                                        
                        <tr>
                            <th>Age</th>
                            <td><?php echo $age; ?></td>
                        </tr>
                        <tr>
                            <th>Size</th>
                            <td><?php echo $size; ?></td>
                        </tr>                    
                        <tr>
                            <th>Hobbies</th>
                            <td><?php echo $hobbies; ?></td>
                        </tr>
                        <tr>
                            <th>Breed</th>
                            <td><?php echo $breed; ?></td>
                        </tr>  
                        <tr>
                            <th>Status</th>
                            <td><?php echo $status; ?></td>
                        </tr>
                        <tr>
                            <th>Pickup On</th>
                            <td>
                                <input class='form-control' type="date"  name="date" value ="<?php echo $date ?>" min = <?php echo date("m/d/Y") ?> />
                        </td>
                        </tr>                                                                  
                        <tr>
                            <input type= "hidden" name= "id" value= "<?php echo $data['id'] ?>" />
                            <input type= "hidden" name= "user" value= "<?php echo $user ?>" />
                            
                            <td><button class="btn btn-success" type= "submit" name="btn-adopt">Adopt Pet</button></td>
                            <td><a href= "home.php"><button class="btn btn-warning" type="button"><i class="fas fa-hand-point-left"></i></button></a></td>
                        </tr>
                    </table>
                </div>
            </form>
        </fieldset>
    </body>
</html>