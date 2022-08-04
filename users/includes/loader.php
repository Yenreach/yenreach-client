<?php include "../../config/connect.php";?>

<!DOCTYPE html>
<html>
<head>
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
  <div class="spinner-border text-success" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
<?php
session_start();
echo '<br>';
echo'<span class="itext" style="color: #00C853">Logging IN. Please Wait!...</span>';
$_SESSION['tid'] = $_GET['tid'];
$username = $_GET['tid'];
$query = mysqli_query($link, "SELECT * FROM `users` WHERE '$username' IN(id)") or die(mysqli_error($link));
$row = mysqli_fetch_array($query);
if($row['admin']=="1"){
echo '<meta http-equiv="refresh" content="3;url=../admin/dashboard.php?tid='.$_SESSION['tid'].'">';
}
else { echo '<meta http-equiv="refresh" content="2;url=../profile.php?tid='.$_SESSION['tid'].'">' ;}
?>
</div>

<script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
</body>
</html>
