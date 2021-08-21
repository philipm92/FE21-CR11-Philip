<?php
session_start();
require_once 'components/db_connect.php';
// if session is not set this will redirect to login page
if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
//if session user exist it shouldn't access dashboard.php
if (isset($_SESSION["user"])) {
    header("Location: home.php");
    exit;
}

$_SESSION["TABLE"] = "animals";
$TABLE = $_SESSION["TABLE"];

// automize generating of the table head
$thead = ""; // get the column names from the hotel room db-table
$sql = "SHOW COLUMNS FROM $TABLE";
$result = $db->query($sql);
$num_tab_col = 1; // paying homage
foreach ($result->fetchAll() as $row) {
    if ($row["Field"] == "id") continue;
    #$change_an = ($row["Field"] == "picture") ? "style='text-align:left;'" : '';
    $thead .= "<th>".ucfirst($row["Field"])."</th>";
    $num_tab_col++;
}
$thead .= "<th>Action</th>";


$sql = "SELECT * FROM $TABLE";
$result = $db->query($sql);
$tbody = ''; //this variable will hold the body for the table
$n = $result->numRows();
if ($n > 0) {   
    foreach ($result->fetchAll() as $row) {
        // if ($row["id"]) continue;
        $tbody .= "<tr>"; 
        foreach ($row as $key => $value) {
            if ($key == "id") {
                $tbody .= "</tr>";
                continue;
            }
            $fileExtension = strtolower(pathinfo($value,PATHINFO_EXTENSION));
            if (in_array($fileExtension, $filesAllowed)) $tbody .= "<td><img class='img-fluid' src='$TABLE/pictures/" .$value."' /></td>";
            elseif ($value == "adopted" && isset($_SESSION['user'])) $tbody .= "<td colspan='2' class='text-muted'><em>not available</em></td>";
            else $tbody .= "<td>$value</td>";
        }

        $tbody .= "
            <td>
                <a href='$TABLE/update.php?id=" .$row['id']."'><button class='btn btn-primary btn-sm m-2 m-md-1' type='button'><i class='fas fa-edit'></i></button></a>
                <a href='$TABLE/delete.php?id=" .$row['id']."'><button class='btn btn-danger btn-sm m-2 m-md-1' type='button'><i class='fas fa-trash-alt'></i></button></a>
            </td>";
       
        $tbody .= "</tr>"; 
        // delete empty table rows
        $tbody = str_replace("<tr></tr>", '', $tbody);
    };
} else {
    $tbody =  "<tr><td colspan='".$num_tab_col."'><center>No Data Available </center></td></tr>";
}



$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adm-DashBoard</title>
    <?php require_once 'components/bootcss.php'?>
    <link href="components/style.css" rel="stylesheet">
</head>
<body>
<div class="container mt-2">
    <div class="row">
        <div class="col-2">
            <img class="userImage" src="pictures/admavatar.png" alt="Admin Avatar">
            <p>Administrator</p>
            <a href="logout.php?logout">Sign Out</a>
            <!-- <a href="animals/index.php">Pets Overview</a> -->
        </div>
        <div class="col-10 mt-2">
            <div class="d-flex flex-row justify-content-evenly">
                <h2 class='text-center'>Edit Animals</h2>
                <a href= "animals/create.php"><button class='btn btn-primary'type="button" >Add Animal</button></a>
            </div>
            <div class="table-responsive">
                <table class='table table-hover table-striped'>
                    <thead class='table-success'>
                        <?= $thead ?>
                    </thead>
                    <tbody>
                    <?=$tbody?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

  
</div>

<?php require_once 'components/bootjs.php';
?>
</body>
</html>