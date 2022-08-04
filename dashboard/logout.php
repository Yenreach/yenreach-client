<?php include "../config/connect.php";?>
<?php session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
  <div class="spinner-border text-success" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
<?php
echo '<br>';
echo'<span class="itext" style="color: #00C853">Logging Out. Please Wait!...</span>';

session_destroy();
echo '<meta http-equiv="refresh" content="2;url=../index.php">';

?>
</div>

<script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
</body>
</html>
