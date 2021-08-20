<?php
session_start();
require_once 'components/db_connect.php';
require_once 'components/file_upload.php';
// if session is not set this will redirect to login page
if( !isset($_SESSION['adm']) && !isset($_SESSION['user']) ) {
    header("Location: index.php");
    exit;
   }
   
$backBtn = '';
//if it is a user it will create a back button to home.php
if(isset($_SESSION["user"])){
    $backBtn = "home.php";    
}
//if it is a adm it will create a back button to dashboard.php
if(isset($_SESSION["adm"])){
    $backBtn = "dashBoard.php";    
}

//fetch and populate form
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM user WHERE id = {$id}";
    $result = $db->query($sql);
    if ($result->numRows() == 1) {
        $data = $result->fetchArray();
        $f_name = $data['first_name'];
        $l_name = $data['last_name'];
        $email = $data['email'];
        $phone = $data['phone_number'];
        $address = $data['address'];
        $picture = $data['picture'];       
    }   
}

//update
$class = 'd-none';
if (isset($_POST["submit"])) {
    $f_name = $_POST['first_name'];
    $l_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $picture = $_POST['picture']; 
    $id = $_POST['id'];
    //variable for upload pictures errors is initialized
    $uploadError = '';    
    $pictureArray = file_upload($_FILES['picture']); //file_upload() called
    $picture = $pictureArray->fileName;
    if ($pictureArray->error === 0) {       
        ($_POST["picture"] == "avatar.png") ?: unlink("pictures/{$_POST["picture"]}");
        $sql = "UPDATE `user` `first_name`=?,`last_name`=?,`email`=?,`phone_number`=?,`address`=?, picture=? WHERE id=?";
        $db->query($sql, array($f_name, $l_name, $email, $phone, $address, $pictureArray->fileName,$id));
    } else {
        $sql = "UPDATE `user` `first_name`=?,`last_name`=?,`email`=?,`phone_number`=?,`address`=? WHERE id=?";
        $db->query($sql, array($f_name, $l_name, $email, $phone, $address,$id));
    }
    
    $class = "alert alert-success";
    $message = "The record was successfully updated";
    $uploadError = ($pictureArray->error != 0) ? $pictureArray->ErrorMessage : '';
    header("refresh:3;url=update.php?id={$id}");

}

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Edit User</title>
   <?php require_once 'components/bootcss.php'?>
   <link href="components/style.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="<?php echo $class; ?>" role="alert">
        <p><?php echo ($message) ?? ''; ?></p>
        <p><?php echo ($uploadError) ?? ''; ?></p>       
    </div>
    
        <h2>Update</h2>        
        <img class='img-thumbnail rounded-circle' src='pictures/<?php echo $data['picture'] ?>' alt="<?php echo "$data[first_name] $data[last_name]" ?>">
        <form  method="post" enctype="multipart/form-data">
            <table class="table">
                <tr>
                    <th>First Name</th>
                    <td><input class="form-control" type="text"  name="first_name" placeholder ="First Name" value="<?php echo $f_name ?>"  /></td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td><input class="form-control" type= "text" name="last_name"  placeholder="Last Name" value ="<?php echo $l_name ?>" /></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><input class="form-control" type="email" name="email" placeholder= "Email" value= "<?php echo $email ?>" /></td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td><input class="form-control" type="tel" name="phone_number" placeholder= "Phone Number" value= "<?php echo $phone ?>" /></td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><input class="form-control" type="text" name="address" placeholder= "Address" value= "<?php echo $address ?>" /></td>
                </tr>                
                <tr>
                    <th>Picture</th>
                    <td><input class="form-control" type="file" name="picture" /></td>
                </tr>
                <tr>
                    <input type= "hidden" name= "id" value= "<?php echo $data['id'] ?>" />
                    <input type= "hidden" name= "picture" value= "<?php echo $picture ?>" />
                    <td><button name="submit" class="btn btn-success" type= "submit">Save Changes</button></td>
                    <td><a href= "<?php echo $backBtn?>"><button class="btn btn-warning" type="button">Back</button></a></td>
                </tr>
            </table>
        </form>    
</div>
</body>
</html>

