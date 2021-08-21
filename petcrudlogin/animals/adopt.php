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

function CreateTableOverview($sql) {
    global $db;
    global $filesAllowed;
    global $TABLE;
    $result = $db->query($sql);
    $tbody = ''; //this variable will hold the body for the table
    $thead = ''; // get the column names from the hotel room db-table
    $num_tab_col = 2; // paying homage
    $first = TRUE;
    if ($result->numRows() > 0) {   
        foreach ($result->fetchAll() as $row) {
            $tbody .= "<tr>"; 
            foreach ($row as $key => $value) {
                if ($key == "id") {
                    $tbody .= "</tr>";
                    continue;
                }
                if ($first) {
                    $thead .= "<th>".ucfirst($key)."</th>";
                    $num_tab_col++;
                }
                $fileExtension = strtolower(pathinfo($value,PATHINFO_EXTENSION));
                if (in_array($fileExtension, $filesAllowed)) $tbody .= "<td><img class='img-thumbnail' src='pictures/" .$value."' alt='".$row["name"]."' /></td>";
                elseif ($value == "adopted") $tbody .= "<td colspan='2' class='text-muted'><em>not available</em></td>";
                else $tbody .= "<td>$value</td>";
            }
            // build modal trigger button
            $unique_modalID = $row["name"].$row["id"].$row["age"];
            $unique_modalID = preg_replace("/\s+/", '_', $unique_modalID); //replace any space with "_"
    
            $tbody .= "<td><span class='m-0 btn btn-warning' data-bs-toggle='modal' data-bs-target='#".$unique_modalID."'><i class='fas fa-info-circle'></td></span></td>";
            
            $additional_info = $db->query("SELECT hobbies, breed FROM $TABLE WHERE id=?", array($row["id"]))->fetchArray();
            $modal_info_str = "
            <div class='card'>
                <img class='card-img-top' src='pictures/" .$row["picture"]."' alt='".$row["name"]."' />
                <div class='card-body pb-0'>
                    <h5 class='card-title text-center'>".$row["name"]."</h5>
                    <q class='card-text'><em>".$row["description"]."</em></q>
                    <p class='card-text'><strong>Hobbies: </strong>".$additional_info["hobbies"]."</p>
                    <p class='card-text mb-1'><strong>Location: </strong>".$row["location"]."</p>
                </div>
                <div class='card-footer d-flex flex-row justify-content-between'>
                    <p class='text-muted'><strong>Age: </strong>".$row["age"]."</p>
                    <p class='text-muted'><strong>Size: </strong>".$row["size"]."</p>
                    <p class='text-muted'><strong>Breed: </strong>".$additional_info["breed"]."</p>
                </div>
            </div>        
            ";
    
            // build modal window
            $tbody .= "
            <div class='modal fade' id='".$unique_modalID."' tabindex='-1' aria-labelledby='".$unique_modalID."Label' aria-hidden='true'>
            <div class='modal-dialog'>
              <div class='modal-content'>
                <div class='modal-header'>
                  <h5 class='modal-title text-center' id='".$unique_modalID."Label'>".$row["name"]."</h5>
                  <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                  ".$modal_info_str."
                </div>
                <div class='modal-footer my-0 mx-auto'>
                  <a href='adopt.php?id=".$row['id']."&user=".$_SESSION['user']."'><button type='button' class='btn btn-primary'>Take me home</button></a>
                </div>
              </div>
            </div>
            </div>                  
            ";
    
            // adopt button
            $tbody .= "<td><a href='adopt.php?id=".$row['id']."&user=".$_SESSION['user']."'><button type='button' class='btn btn-primary'>Take me home</button></a></td>";

            $tbody .= "</tr>"; 
            $first = FALSE;
        }
        $tbody = str_replace("<tr></tr>", '', $tbody);
        $thead .= "<th>More</th>";
        $thead .= "<th>Adopt</th>";
    } else {
        $tbody =  "<tr><td colspan='".$num_tab_col."'><center>No Data Available </center></td></tr>";
    }
    return array($thead, $tbody);
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
                            <td><a href= "index.php"><button class="btn btn-warning" type="button"><i class="fas fa-hand-point-left"></i></button></a></td>
                        </tr>
                    </table>
                </div>
            </form>
        </fieldset>
    </body>
</html>