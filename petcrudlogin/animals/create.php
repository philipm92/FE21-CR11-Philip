<?php
session_start();
require_once '../components/db_connect.php';
require_once '../components/file_upload.php';

if (isset($_SESSION['user']) != "") {
    header("Location: ../home.php");
    exit;
}

if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

$TABLE = $_SESSION["TABLE"];
$status_list = CreateSelectDropDown("SHOW COLUMNS FROM $TABLE LIKE 'status'");
$size_list = CreateSelectDropDown("SHOW COLUMNS FROM $TABLE LIKE 'size'");

$error = false;
$name = $location = $description = $size = $age = $hobbies = $breed = $status = $picture = '';
$nameError = $locationError = $descriptionError = $sizeError = $ageError = $hobbiesError = $breedError = $statusError = $picError = '';
if (isset($_POST['btn-add-pet'])) {
    $name = CleanInput($_POST["name"]);
    $location = CleanInput($_POST["location"]);
    $description = CleanInput($_POST["description"]);
    $size = CleanInput($_POST["size"]);
    $age = CleanInput($_POST["age"]);
    $hobbies = CleanInput($_POST["hobbies"]);
    $breed = CleanInput($_POST["breed"]);
    $status = CleanInput($_POST["status"]);
    
    $uploadError = '';
    $picture = file_upload($_FILES['picture']);

    // basic name validation
    if (empty($name)) {
        $error = true;
        $nameError = "Please enter a pet name";
    } else if (strlen($name) < 2) {
        $error = true;
        $nameError = "Pet name must have at least 2 characters.";
    } else if (!preg_match("/^[a-zA-Z]+$/", $name)) {
        $error = true;
        $nameError = "Pet name must contain only letters and no spaces.";
    } 
    
    // basic location validation
    if (empty($location)) {
        $error = true;
        $locationError = "Location cannot be empty";
    }

    // basic description validation
    if (empty($description)) {
        $error = true;
        $descriptionError = "Description cannot be empty";
    }

    // basic hobbies validation
    if (empty($hobbies)) {
        $error = true;
        $hobbiesError = "Hobbies cannot be empty";
    }

    // basic breed validation
    if (empty($breed)) {
        $error = true;
        $breedError = "Breed cannot be empty";
    }

    //basic age validation
    if (!filter_var($age, FILTER_VALIDATE_INT)) {
        $error = true;
        $ageError = "Age must be a number";
    }
    
    // if there's no error, continue to signup
    if (!$error) {
        $sql = "INSERT INTO `animals`(`id`, `picture`, `name`, `location`, `description`, `size`, `age`, `hobbies`, `breed`, `status`) VALUES (?,?,?,?,?,?,?,?,?,?);";
        $result = $db->query($sql, array(NULL, $picture->fileName, $name, $location, $description, $size, $age, $hobbies, $breed, $status));
        
        $errTyp = "success";
        $errMSG = "The pet below was successfully created <br />
        <table class='table w-50'>
            <tr>
                <td> $name </td>
                <td> $age </td>
                <td> $breed </td>
                <td> $size </td>
            </tr>
        </table><hr>";
        $uploadError = ($picture->error !=0)? $picture->ErrorMessage :'';

    }

}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php require_once '../components/bootcss.php'?>
        <link href="../components/style.css" rel="stylesheet">
        <title>PHP CRUD  |  Add Animal</title>
    </head>
    <body>
        <fieldset>
            <h2 class="text-center">Add New Animal</h2>                       
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off" enctype="multipart/form-data">
                <div class="table-responsive mx-auto w-75">
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
                    <table class="table table-hover table-striped mx-auto">
                        <tr>
                            <th>Name</th>
                            <td>
                                <input class="form-control" type="text"  name="name" placeholder = "Name" />
                                <span class="text-danger"> <?php echo $nameError; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td>
                                <input class="form-control" type="text" name="location" placeholder = "Location" />
                                <span class="text-danger"> <?php echo $locationError; ?> </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>
                                <input class="form-control" type="text"  name="description" placeholder = "Description" />
                                <span class="text-danger"> <?php echo $descriptionError; ?></span>
                            </td>
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
                            <td>
                                <input class="form-control" type= "number" name="age" step="any"  placeholder="Age" />
                                <span class="text-danger"> <?php echo $ageError; ?></span>
                            </td>
                        </tr>                    
                        <tr>
                            <th>Hobbies</th>
                            <td>
                                <input class="form-control" type="text"  name="hobbies" placeholder ="Hobbies" />
                                <span class="text-danger"> <?php echo $hobbiesError; ?></span>
                            </td>
                        </tr>    
                        <tr>
                            <th>Breed</th>
                            <td>
                                <input class="form-control" type="text"  name="breed" placeholder ="Breed" />
                                <span class="text-danger"> <?php echo $breedError; ?></span>
                            </td>
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
                            <td>
                                <input class="form-control" type="file" name="picture" />
                                <span class="text-danger"> <?php echo $picError ; ?></span>
                            </td>
                        </tr> 
                        <tr>
                            <td>
                                <button class='btn btn-success' type="submit" name="btn-add-pet">
                                    <strong>Add</strong>
                                    <i class="fas fa-paw mx-1"></i>
                                </button>
                            </td>
                            <td><a href="../home.php"><button class='btn btn-warning' type="button"><i class="fas fa-home"></i></button></a></td>
                        </tr>
                    </table>
                </div>
            </form>
        </fieldset>
    </body>
</html>