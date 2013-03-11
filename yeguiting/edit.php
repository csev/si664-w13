<?php
session_start();
unset($_SESSION["update"]);
unset($_SESSION["updateerror"]);
require_once "db.php";

if ( isset($_POST['location']) && isset($_POST['latitude']) 
     && isset($_POST['longitude']) && isset($_POST['id']) ) 
{
    $locaiton = mysql_real_escape_string($_POST['location']);
    $latitude = mysql_real_escape_string($_POST['latitude']);
    $longitude = mysql_real_escape_string($_POST['longitude']);
    $id = mysql_real_escape_string($_POST['id']);
    if ($location===''||$latitude===''|| $longtitude==='')
	  {
	  $_SESSION['updateerror']="All values are required.";
	  header('Location:index.php');
	  return;
	  }
	  else if ( !is_numeric($latitude) || ! is_numeric($longtitude) )
	 {
	  $_SESSION['updaterror']="Longitude and Latitude must be numeric";
	  header('Location:index.php');
	  return;
	  }
		else if ($latitude<-90||$latitude>90)
		   {
				  $_SESSION['updateerror']="Latitude must be between -90 and 90";
				  header('Location:index.php');
				  return;
		   }
				 else if ($longtitude<-180||$longtitude>180)
				 {
				  $_SESSION['updateerror']="Longitude must be between -180 and 180";
				  header('Location:index.php');
				  return;
				 }
					  else{
						$sql = "UPDATE geodata SET location='$location', lat='$latitude',
								  lng='$longitude' WHERE id='$id'"; 
						mysql_query($sql);
						$_SESSION["update"] = "Record Updated";
						header( 'Location: index.php' ) ;
						return;
					}
}

$id = mysql_real_escape_string($_GET['id']);
$result = mysql_query("SELECT location,lat,lng,id 
    FROM geodata WHERE id='$id'");
$row = mysql_fetch_row($result);
if ( $row == FALSE ) {
    $_SESSION["updateerror"] = "Updated Error";
    header( 'Location: index.php' ) ;
    return;
}

$location = htmlentities($row[0]);
$latitude = htmlentities($row[1]);
$longitude = htmlentities($row[2]);
$id = htmlentities($row[3]);

echo <<< _END
<p>Edit Geodata</p>
<form method="post">
<p>Location:
<input type="text" name="location" value="$location"></p>
<p>Latitude:
<input type="text" name="latitude" value="$latitude"></p>
<p>Longitude:
<input type="text" name="longitude" value="$longitude"></p>
<input type="hidden" name="id" value="$id">
<p><input type="submit" value="Update"/>
<a href="index.php">Cancel</a></p>
</form>
_END
?>

