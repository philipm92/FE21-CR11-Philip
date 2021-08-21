<?php 
session_start();
require_once '../components/db_connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

// $_SESSION["TABLE"] = "animals";
$TABLE = $_SESSION["TABLE"];

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

$show_all_btn = '';
if (isset($_GET["senior_age"])) {   
    $sql = "SELECT id, name, picture, description, age, location, size FROM $TABLE WHERE age>=8 ORDER BY size, name;";
    list($thead, $tbody) = CreateTableOverview($sql);
    $show_all_btn = "<a href='index.php?showall=1'><button type='button' class='btn btn-secondary'>Show all</button>
</a>";
} //elseif (isset($_GET["showall"])) list($thead, $tbody) = CreateTableOverview($sql);
else {
    $sql = "SELECT id, name, picture, description, age, location, size FROM $TABLE ORDER BY size, name;";
    list($thead, $tbody) = CreateTableOverview($sql);
}


$db->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PHP CRUD</title>
        <?php require_once '../components/bootcss.php'?>
        <link href="../components/style.css" rel="stylesheet">
    </head>
<body>
    <div class="container-fluid my-3">    
        <div class='mb-3 d-flex flex-row justify-content-evenly'>
            <h2 class="text-center">Pets for Adoption</h2>
            <a href="index.php?senior_age=1">
                <button type='button' class='btn btn-secondary'>Sort Seniors</button>
            </a>
            <?php echo $show_all_btn ?>            
        </div>
        <div class="table-responsive mx-auto table-width">
            <table class='table table-hover table-striped'>
                <thead class='table-success'>
                    <tr>
                        <?= $thead; ?>
                    </tr>
                </thead>
                <tbody>
                    <?= $tbody; ?>
                </tbody>
            </table>
        </div>
        <a href= "../home.php"><button class="btn btn-warning" type="button"><i class="fas fa-home"></i></button></a>
    </div>

    <?php require_once '../components/bootjs.php'?>
</body>
</html>