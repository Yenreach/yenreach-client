<?php include "../../config/connect.php";
session_start();
?>

<!DOCTYPE html>
<html>
<head>

</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
  <div class="spinner-border text-success" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
<?php
$session_id=$_SESSION['tid'];

$user_query = mysqli_query($link, "select * from user where id = '$session_id'")or die(mysqli_error());
$row = mysqli_fetch_array($user_query);
$role = $row['role'];
session_destroy();
echo '<br>';
echo'<span class="itext" style="color: #FF0000">Logging Out. Please Wait!...</span>';
echo '<meta http-equiv="refresh" content="2;url=../auth.php">';
?>
</div>
</body>
</html>
