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

if ($_GET['id']) {
    $TABLE = $_SESSION["TABLE"];
    $id = $_GET['id'];
    $sql = "SELECT * FROM $TABLE WHERE id = {$id}";
    $result = $db->query($sql);
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
        $status_list = CreateSelectDropDown("SHOW COLUMNS FROM $TABLE LIKE 'status'", $size);
        $size_list = CreateSelectDropDown("SHOW COLUMNS FROM $TABLE LIKE 'size'", $size);

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
        <title>Edit Pet</title>
        <?php require_once '../components/bootcss.php'?>
        <link href="../components/style.css" rel="stylesheet">
    </head>
    <body>
        <fieldset>
            <div class="d-flex flex-column justify-content-center align-items-center">
                <h2 class="text-center">Update request</h2>
                <img class='img-square rounded-circle m-2' src='pictures/<?php echo $picture ?>' alt="<?php echo $name ?>">
            </div>
            <form action="actions/a_update.php"  method="post" enctype="multipart/form-data">
                <!-- `picture`, `name`, `location`, `description`, `size`, `age`, `hobbies`,  -->
                <div class="table-responsive mx-auto w-75">
                    <table class="table table-hover table-striped mx-auto">
                        <tr>
                            <th>Name</th>
                            <td><input class="form-control" type="text"  name="name" placeholder ="Name" value="<?php echo $name ?>"  /></td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td><input class="form-control" type="text" name="location" placeholder ="Location" value="<?php echo $location ?>"  /></td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td><input class="form-control" type="text"  name="description" placeholder ="Description" value="<?php echo $description ?>"  /></td>
                        </tr>                                        
                        <tr>
                            <th>Size</th>
                            <td>
                                <select class="form-select" name="size" aria-label="Default select example">
                                    <?php echo $size_list;?>
                                </select>
                            </td>                        
                        </tr>
                        <tr>
                            <th>Age</th>
                            <td><input class="form-control" type= "number" name="age" step="any"  placeholder="Age" value ="<?php echo $age ?>" /></td>
                        </tr>                    
                        <tr>
                            <th>Hobbies</th>
                            <td><input class="form-control" type="text"  name="hobbies" placeholder ="Description" value="<?php echo $hobbies ?>"  /></td>
                        </tr>    
                        <tr>
                            <th>Breed</th>
                            <td><input class="form-control" type="text"  name="breed" placeholder ="Breed" value="<?php echo $breed ?>"  /></td>
                        </tr>                         
                        <tr>
                            <th>Status</th>
                            <td>
                                <select class="form-select" name="status" aria-label="Default select example">
                                    <?php echo $status_list;?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Picture</th>
                            <td><input class="form-control" type="file" name="picture" /></td>
                        </tr>                        
                        <tr>
                            <input type= "hidden" name= "id" value= "<?php echo $data['id'] ?>" />
                            <input type= "hidden" name="picture" value= "<?php echo $data['picture'] ?>" />
                            <input type= "hidden" name= "old_status" value= "<?php echo $data['status'] ?>" />
                            <td><button class="btn btn-success" type= "submit"><i class="fas fa-save"></i></button></td>
                            <td><a href= "../home.php"><button class="btn btn-warning" type="button">Back</button></a></td>
                        </tr>
                    </table>
                </div>
            </form>
        </fieldset>
    </body>
</html>