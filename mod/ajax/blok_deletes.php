<?

include '../../includes/session.php';
include '../../includes/config.php';
include '../../includes/fungsi.php';
include '../../includes/mysql.php';

if (!cek_login ()){
  echo 'error.. login kembali';
   exit;
}else{

if (session_is_registered ('LevelAkses') &&  $_SESSION['LevelAkses']=="Administrator"){

if (isset ($_GET['id'])){
$id = int_filter($_GET['id']);	
$query = @mysql_query("DELETE FROM blok WHERE id='$id'");
if ($query) echo '';
else {
echo 'Data Gagal Didelete';
echo '<br>'.mysql_error();
}}
}else{
echo 'Error';
}

}

?>