<?

@header('Content-type: text/plain');
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

include '../../includes/session.php';
include '../../includes/config.php';
include '../../includes/fungsi.php';
include '../../includes/mysql.php';
include '../../includes/json.php';

if (!cek_login ()){
    exit;
}
if ($_SESSION['LevelAkses'] != 'Administrator') {
	exit;
}

if (!isset($_SESSION['mod_ajax'])){
exit;	
}


switch (@$_GET['action']){
case 'setting':
if (isset($_POST['submit'])) {
$limit = int_filter($_POST['limit']);
$char = int_filter($_POST['char']);
$input = serialize(array('limit'=>$limit,'char'=>$char));
$insert = mysql_query("UPDATE `bukutamu_config` SET `config` = '$input' WHERE `id` = '1'");	
}
$query = mysql_query("SELECT * FROM `bukutamu_config` WHERE `id` = '1'");
$data = mysql_fetch_assoc($query);

$j = new JSON_obj();
echo $j->encode(unserialize($data['config']));	
break;	
case 'deleted':
if (isset($_GET['id'])){
if (is_array($_GET['id'])){
foreach ($_GET['id'] as $k=>$v){
$del = mysql_query ("DELETE FROM `bukutamu` WHERE `id` = '$v'");	
}	
	
}
}
break;

case 'tanggapan_saved':
if (!empty($_GET['id'])){
$id = $_GET['id'];
$tanggapan = rawurldecode($_GET['tanggapan']);
$query = mysql_query ("UPDATE `bukutamu` SET `jawab` = '$tanggapan' WHERE `id` = '$id'");	
//$open['errorpesan'] = mysql_error();
//$j = new JSON_obj();
//echo $j->encode($open);		
}
break;


case 'tanggapan':
$id = $_GET['id'];
$query = mysql_query ("SELECT * FROM `bukutamu` WHERE `id` = '$id'");
$nowtotalalbum = mysql_num_rows($query);
$data = mysql_fetch_array ($query);
	
	
$data_buku = explode(" ",$data['komentar']);
$data['komentar']="";
for ($i=0; $i<count($data_buku); $i++){

        if (strlen($data_buku[$i]) >= 20) {
            $data_buku[$i] = wordwrap($data_buku[$i], 30, " ", TRUE);
        }
        $data['komentar'] .= $data_buku[$i]." ";
}	
	
	
	
	
$open['bukutamuList'] = array ('id'=>$data['id'],'sekarang'=>$data['sekarang'],'nama'=>$data['nama'],'email'=>$data['email'],'homepage'=>$data['homepage'],'alamat'=>$data['alamat'],'komentar'=>sensor(gb2($data['komentar'])),'jawab'=>$data['jawab']);	




$j = new JSON_obj();
echo $j->encode($open);	

break;
	
default:
$query1 = mysql_query ("SELECT count(`id`) AS `total` FROM `bukutamu`");
$getdata1 = mysql_fetch_assoc($query1);
$jumlah = $getdata1['total'];
$getconfig = mysql_query("SELECT * FROM `bukutamu_config` WHERE `id` = '1'");
$dataconfig = mysql_fetch_assoc($getconfig);
$gbconfig = unserialize($dataconfig['config']);
$limit = empty($gbconfig['limit']) ? 5 : $gbconfig['limit'];
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
$ddl .= '<a onclick="guestbook.indexs(\''.$v['link'].'\');" style="cursor:pointer" title="Page '.$v['title'].'">'.$v['title'].'</a> | ';
}
}
unset($pagging);
$pagging = substr($ddl,0,strlen($ddl)-3);
}else {
$pagging = null;	
}



$query = mysql_query ("SELECT * FROM `bukutamu` ORDER BY `id` DESC LIMIT ".$offset.",$limit");
$nowtotalalbum = mysql_num_rows($query);
while ($data = mysql_fetch_array ($query)){
	
	
$data_buku = explode(" ",$data['komentar']);
$data['komentar']="";
for ($i=0; $i<count($data_buku); $i++){

        if (strlen($data_buku[$i]) >= 20) {
            $data_buku[$i] = wordwrap($data_buku[$i], 30, " ", TRUE);
        }
        $data['komentar'] .= $data_buku[$i]." ";
}	
	
	
	
	
$open['bukutamuList'][] = array ('id'=>$data['id'],'sekarang'=>$data['sekarang'],'nama'=>$data['nama'],'email'=>$data['email'],'homepage'=>$data['homepage'],'alamat'=>$data['alamat'],'komentar'=>sensor(gb2($data['komentar'])),'jawab'=>$data['jawab']);	
}

if (!empty($pagging)){
$open['paging'] = $pagging;	
}else {
$open['paging'] = '';	
}

$j = new JSON_obj();
echo $j->encode($open);	



break;
}



  
?>