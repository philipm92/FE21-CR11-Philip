<?php
session_start();
require_once 'components/db_connect.php';

// if adm will redirect to dashboard
if (isset($_SESSION['adm'])) {
    header("Location: dashboard.php");
    exit;
}
// if session is not set this will redirect to login page
if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$_SESSION["TABLE"] = "animals";
$TABLE = $_SESSION["TABLE"];
$thead = '';
$sql = "SHOW COLUMNS FROM $TABLE";
$result = $db->query($sql);
$num_tab_col = 1; // paying homage
foreach ($result->fetchAll() as $row) {
    if ($row["Field"] == "id" || $row["Field"] == "status") continue;
    $thead .= "<th>".ucfirst($row["Field"])."</th>";
    $num_tab_col++;
}
$thead .= "<th>Action</th>";

$tbody = "";
# fill table with adopted animals
$sql = "SELECT $TABLE.*
FROM `user`
JOIN `pet_adoption` ON `pet_adoption`.`fk_user_id` = `user`.`id`
JOIN $TABLE ON $TABLE.`id` = `pet_adoption`.`fk_animal_id`
WHERE `user`.`id` = ? ORDER BY $TABLE.`size`, $TABLE.`name`;";

$result = $db->query($sql, array($_SESSION["user"]));
$tbody = ''; //this variable will hold the body for the table
$n = $result->numRows();
if ($n > 0) {   
    foreach ($result->fetchAll() as $row) {
        // if ($row["id"]) continue;
        $tbody .= "<tr>"; 
        foreach ($row as $key => $value) {
            if ($key == "id") $hotel_id = (int)$value;
            if ($key == "id" || $key == "status") {
                continue;
            }
            $fileExtension = strtolower(pathinfo($value,PATHINFO_EXTENSION));
            if (in_array($fileExtension, $filesAllowed)) $tbody .= "<td><img class='img-flud img-thumbnail' src='animals/pictures/" .$value."' /></td>";
            elseif ($key == "duration") $tbody .= "<td>" .$value." week".(($value > 1 ? 's' : ''))."</td>";
            elseif ($key == "price") $tbody .= "<td>" .$value."&euro;</td>";
            else $tbody .= "<td>$value</td>";
        }

        if (isset($hotel_id)) {
            $tbody .= "
                    <td>
                    <a href='products/cancel.php?id=".$hotel_id."'><button class='btn btn-danger btn-sm' type='button'>Return</button></a>
                    </td>";
        }
       
        $tbody .= "</tr>"; 
        // delete empty table rows
        $tbody = str_replace("<tr><tr>", '', $tbody);
    };
} else {
    $tbody =  "<tr><td colspan='".$num_tab_col."'><center>No Animals Adopted So Far!</center></td></tr>";
}

// select logged-in users details - procedural style
$res = $db->query("SELECT * FROM user WHERE id=?", array($_SESSION['user']));
$row = $res->fetchArray();
$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - <?php echo $row['first_name']; ?></title>
    <?php require_once 'components/bootcss.php'?>
    <link href="components/style.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="hero p-2">
        <img class="" src="pictures/<?php echo $row['picture']; ?>" alt="<?php echo $row['first_name']." ".$row['last_name']; ?>">
        <p class="text-white" >Hi <?php echo $row['first_name']." ".$row['last_name']; ?></p>
    </div>
    <p class="my-2">
        <a href="logout.php?logout">Sign Out</a>
        <a href="update.php?id=<?php echo $_SESSION['user'] ?>">Update your profile</a>
        <a href="animals/index.php">Adopt a pet</a>
    </p>

    <p class='h2 text-center'>Your adopted animals!</p>
    <div class="table-responsive mx-auto table-width">
        <table class='table table-hover table-striped'
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
    
</div>
<?php require_once "components/bootjs.php" ?>
</body>
</html>