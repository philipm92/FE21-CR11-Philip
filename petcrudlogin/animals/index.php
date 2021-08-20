<?php 
session_start();
require_once '../components/db_connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

$_SESSION["TABLE"] = "animals";
$TABLE = $_SESSION["TABLE"];

// automize generating of the table head
$thead = ""; // get the column names from the hotel room db-table
$num_tab_col = 2; // paying homage

// $sql = "SHOW COLUMNS FROM $TABLE";
// $result = $db->query($sql);
// $num_tab_col = 1; // paying homage
// foreach ($result->fetchAll() as $row) {
//     if ($row["Field"] == "id") continue;
//     #$change_an = ($row["Field"] == "picture") ? "style='text-align:left;'" : '';
//     $thead .= "<th>".ucfirst($row["Field"])."</th>";
//     $num_tab_col++;
// }
// $thead .= "<th>Action</th>";

$sql = "SELECT id, name, picture, description, age FROM $TABLE";
$result = $db->query($sql);
$tbody = ''; //this variable will hold the body for the table
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
            if (in_array($fileExtension, $filesAllowed)) $tbody .= "<td><img class='img-thumbnail' src='pictures/" .$value."' /></td>";
            elseif ($value == "adopted") $tbody .= "<td colspan='2' class='text-muted'><em>not available</em></td>";
            else $tbody .= "<td>$value</td>";
        }
        // build modal trigger button
        $unique_modalID = $row["name"]."_".$_SESSION['user'];
        $tbody .= "<td><span class='m-0 btn btn-warning' data-bs-toggle='modal' data-bs-target='#".$unique_modalID."'><i class='fas fa-info-circle'></td></span></td>";
        
        $modal_info_str = '';

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
              <a href='$TABLE/adopt.php?id=$row[id]'><button type='button' class='btn btn-warning'>Adopt</button></a>
            </div>
          </div>
        </div>
        </div>                  
        ";       

        $tbody .= "</tr>"; 
        $first = FALSE;
    }
    $tbody = str_replace("<tr></tr>", '', $tbody);
    $thead .= "<th>More</th>";
    $thead .= "<th>Adopt</th>";
} else {
    $tbody =  "<tr><td colspan='".$num_tab_col."'><center>No Data Available </center></td></tr>";
}

// $book_string = "<td>-</td>";
// $qry = "SELECT hotel.id as 'hotel_id', hotel.description, hotel.room as 'room', hotel.floor, booking.date as 'booking_date', hotel.picture as 'room_image', user.id FROM user JOIN booking ON booking.fk_user_id = user.id JOIN hotel ON hotel.id = booking.fk_hotel_id WHERE user.status != 'adm' AND user.id = $row[id];";
// $res = $db->query($qry);

// if ($res->numRows() > 0) {
//     foreach ($res->fetchAll() as $booking) {
//         $unique_modalID = $booking["room"]."_".$row["id"];
//         $unique_modalID = preg_replace("/\s+/", '_', $unique_modalID);
//         #echo $unique_modalID."<br />";
//         $modal_table_str = "
//         <table class='table d-flex justify-content-center'>
//             <tr>
//                 <img class='m-1 img-thumbnail rounded-circle' src='products/pictures/$booking[room_image]' alt='$booking[room]'>
//             </tr>
//             <tr>
//                 <th>Floor</th>
//                 <td>$booking[floor]</td>
//             </tr>
//             <tr>
//                 <th>Description</th>
//                 <td>$booking[description]</td>
//             </tr>                                                            
//             <tr>
//                 <th>Booked for</th>
//                 <td>$booking[booking_date]</td>
//             </tr>
//         </table>                
//         ";

//         // build modal trigger button
//         $book_string .= "<p><span class='m-0 btn btn-warning' data-bs-toggle='modal' data-bs-target='#".$unique_modalID."'>".$booking["room"]."</span></p>";
//         // build modal window
//         $book_string .= "
//         <div class='modal fade' id='".$unique_modalID."' tabindex='-1' aria-labelledby='".$unique_modalID."Label' aria-hidden='true'>
//         <div class='modal-dialog'>
//           <div class='modal-content'>
//             <div class='modal-header'>
//               <h5 class='modal-title text-center' id='".$unique_modalID."Label'>".$booking["room"]."</h5>
//               <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
//             </div>
//             <div class='modal-body'>
//               ".$modal_table_str."
//             </div>
//             <div class='modal-footer my-0 mx-auto'>
//               <a href='products/update.php?id=$booking[hotel_id]'><button type='button' class='btn btn-warning'>Make changes</button></a>
//             </div>
//           </div>
//         </div>
//         </div>                  
//         ";
//     }
// } else $book_string .= "-";

// $book_string .= "</td>";


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
        <div class='mb-3'>
            <?php if (isset($_SESSION['adm'])) { ?>
            <a href= "create.php"><button class='btn btn-primary'type="button" >Add Animal</button></a>
            <?php } ?>
        </div>
        <h2 class="text-center">Pets for Adoption</h2>
        <div class="table-responsive mx-auto w-75">
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