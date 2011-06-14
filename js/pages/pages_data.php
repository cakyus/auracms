<?

@ob_start('ob_gzhandler');
@header("Content-type: text/plain; charset=utf-8;");
@header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
@header("Pragma: no-cache");

include '../../includes/session.php';
include '../../includes/config.php';
include '../../includes/fungsi.php';
include '../../includes/mysql.php';
include '../../includes/json.php';

if (!cek_login ()){
   	echo'<div class="error">You Must Login First If Not Yet Have Account Please Register</div>';
}else{

if (session_is_registered ('LevelAkses') &&  $_SESSION['LevelAkses']=="Administrator"){


if (!isset($_SESSION['mod_ajax'])){
exit;	
}


switch (@$_GET['action']){
	
case 'add':
$_POST = array_map ('decodeURIComponent',$_POST);
$judul = $_POST['judul'];
$konten = $_POST['konten'];
$open['error'] = false;
$open['errorpesan'] = '';
if (!empty($judul) && !empty($konten)){
$query = mysql_query ("INSERT INTO `halaman` (`judul`,`konten`) VALUES ('$judul','$konten')");
if ($query){
$open['error'] = false;
}else {
$open['error'] = true;
$open['errorpesan'] = mysql_error();	
}
	
}

$j = new JSON_obj();
echo $j->encode($open);	

break;



case 'edit_saved':
if (!empty($_GET['id'])){
$id = int_filter($_GET['id']);
$_POST = array_map ('decodeURIComponent',$_POST);
$open['error'] = false;
$open['errorpesan'] = '';

$judul = cleantext($_POST['judul']);
$content = $_POST['konten'];
$up = mysql_query ("UPDATE `halaman` SET `judul` = '$judul',`konten` = '$content' WHERE `id` = '$id'");
if ($up){
$open['error'] = false;
}else {
$open['error'] = true;
$open['errorpesan'] = mysql_error();	
}
	
	
$j = new JSON_obj();
echo $j->encode($open);	
}

break;


case 'edit':
if (!empty($_GET['id'])){
$id = int_filter($_GET['id']);	
$query = mysql_query ("SELECT * FROM `halaman` WHERE `id` = '$id'");	
$data = mysql_fetch_assoc($query);
$open['judul'] = $data['judul'];	
$open['konten'] = $data['konten'];	
	
	
$j = new JSON_obj();
echo $j->encode($open);	
}
break;
	
	
case 'delete':
if(!empty($_GET['id']) && $_GET['id'] != 1){
$id = $_GET['id'];
$qs = mysql_query ("DELETE FROM `halaman` WHERE `id` = '$id'");	
}
break;	
default:
$query2 = mysql_query ("SELECT count(`id`) AS `total_files` FROM `halaman`");
$getdata2 = mysql_fetch_assoc($query2);
$jumlah = $getdata2['total_files'];
$limit = 10;
$pembagian = new paging ($limit);
if (empty($_GET['offset']) and !isset ($_GET['offset'])) {
$offset = 0;
}else {
$offset = int_filter($_GET['offset']);	
}

if (empty($_GET['pg']) and !isset ($_GET['pg'])) {
$pg = 1;
}else {
$pg = int_filter($_GET['pg']);	
}
if (empty($_GET['stg']) and !isset ($_GET['stg'])) {
$stg = 1;
}else {
$stg = int_filter($_GET['stg']);		
}

$pagging = $pembagian-> getPagingajax($jumlah, $pg, $stg);
if (is_array($pagging)){
$ddl = '';
foreach ($pagging as $k=>$v){
if ($v['link'] == ""){
$ddl .= $v['title'].' | ';
}else {
$ddl .= '<a onclick="pages.indexs(\''.$v['link'].'\');" style="cursor:pointer" title="Page '.$v['title'].'">'.$v['title'].'</a> | ';
}
}
unset($pagging);
$pagging = substr($ddl,0,strlen($ddl)-3);
}else {
$pagging = null;	
}


$hasil = mysql_query("SELECT * FROM `halaman` ORDER BY `id` LIMIT $offset,$limit");
while ($data = mysql_fetch_assoc($hasil)){
$open['pages'][] = array ('id'=>$data['id'],'judul'=>$data['judul']);	
}


if (!empty($pagging)){
$open['pagging'] = '<div class="border" style="text-align:center">'.$pagging.'</div>';
}else {
$open['pagging'] = '';	
}

$j = new JSON_obj();
echo $j->encode($open);
break;
}

}else{
	echo'<div class="error">Access Denied!.... Your Level Not Much For Access This File</div>';

}

}


  
?>