<?

@ob_start('ob_gzhandler');
@header("Content-type: text/plain; charset=utf-8;");
@header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
@header("Pragma: no-cache");

include '../../../includes/session.php';
include '../../../includes/config.php';
include '../../../includes/fungsi.php';
include '../../../includes/mysql.php';
include '../../../includes/json.php';


if (!cek_login ()){
    exit;
}
if (!isset($_SESSION['mod_ajax'])){
exit;	
}

function callback($n){
$n = !isset($n) ? "0" : $n;
return $n;	
}
function hapusspasitags($t){
	if (!empty($t)) {
		$tmp = explode(',',$t);
		$new_tmp = array();
		foreach($tmp as $key=>$val) {
		if (empty($val)) continue;
		if (strlen($val) <= 2) continue;
		$new_tmp[] = trim($val);	
		}
		
		$t = implode(',',$new_tmp);
	}
	
	return $t;
}

switch (@$_GET['action']){

case 'HitungArtikelMasuk':
$total = mysql_num_rows(mysql_query("SELECT `id` FROM `artikel` WHERE `publikasi` = 0"));
echo $total;
exit;
break;
	
	
case 'searchComment':
$queryString = '';
if ($_SESSION['LevelAkses'] == 'User'){
$queryString = " AND `artikel`.`user` = '".$_SESSION['UserName']."'";
}	


$search = rawurldecode($_GET['search']);
$query2 = mysql_query ("SELECT count(`komentar`.`id`) AS `total` FROM `komentar` LEFT JOIN `artikel` ON `artikel`.`id`=`komentar`.`artikel` WHERE (`komentar`.`judul` LIKE '%$search%' OR `komentar`.`konten` LIKE '%$search%' OR `komentar`.`user` LIKE '%$search%' OR `komentar`.`email` LIKE '%$search%' OR `komentar`.`ip` LIKE '%$search%' OR `artikel`.`judul` LIKE '%$search%') $queryString");
$getdata2 = mysql_fetch_assoc($query2);
$jumlah = $getdata2['total'];
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
$ddl .= '<a onclick="news.comments(\''.$v['link'].'\');" style="cursor:pointer" title="Page '.$v['title'].'">'.$v['title'].'</a> | ';
}
}
unset($pagging);
$pagging = substr($ddl,0,strlen($ddl)-3);
}else {
$pagging = null;	
}


$query = mysql_query ("SELECT `komentar`.*,`artikel`.`judul` AS `judul_artikel` FROM `komentar` LEFT JOIN `artikel` ON `artikel`.`id`=`komentar`.`artikel` WHERE (`komentar`.`judul` LIKE '%$search%' OR `komentar`.`konten` LIKE '%$search%' OR `komentar`.`user` LIKE '%$search%' OR `komentar`.`email` LIKE '%$search%' OR `komentar`.`ip` LIKE '%$search%' OR `artikel`.`judul` LIKE '%$search%') $queryString ORDER BY `komentar`.`id` DESC LIMIT $offset,$limit");
while ($data = mysql_fetch_assoc($query)){
$open['list'][] = $data;
}


if (!empty($pagging)){
$open['pagging'] = '<div class="border" style="text-align:center">'.$pagging.'</div>';
}else {
$open['pagging'] = '';	
}

$j = new JSON_obj();
echo $j->encode($open);


break;	
	
	
case 'deleteMComment':
if (!empty($_GET['id'])){
if (is_array($_GET['id'])){
	foreach($_GET['id'] as $k=>$v){
	$del = mysql_query ("DELETE FROM `komentar` WHERE `id` = '$v'");
	}
}
}
break;	
		
case 'deleteComment':
if (!empty($_GET['id'])){
$id = int_filter($_GET['id']);
$del = mysql_query ("DELETE FROM `komentar` WHERE `id` = '$id'");
}
break;	
	
	
	
case 'listComment':
$queryString = '';
if ($_SESSION['LevelAkses'] == 'User'){
$queryString = "WHERE `artikel`.`user` = '".$_SESSION['UserName']."'";
}	
if (isset($_GET['o']) && !empty($_GET['id'])){
$id = int_filter($_GET['id']);
$queryString = "WHERE `komentar`.`artikel` = '$id'";
if ($_SESSION['LevelAkses'] == 'User'){
$queryString .= " AND `artikel`.`user` = '".$_SESSION['UserName']."'";
}	
}



$query2 = mysql_query ("SELECT count(`komentar`.`id`) AS `total` FROM `komentar` LEFT JOIN `artikel` ON `artikel`.`id`=`komentar`.`artikel` $queryString");
$getdata2 = mysql_fetch_assoc($query2);
$jumlah = $getdata2['total'];
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
$ddl .= '<a onclick="news.comments(\''.$v['link'].'\');" style="cursor:pointer" title="Page '.$v['title'].'">'.$v['title'].'</a> | ';
}
}
unset($pagging);
$pagging = substr($ddl,0,strlen($ddl)-3);
}else {
$pagging = null;	
}


$query = mysql_query ("SELECT `komentar`.*,`artikel`.`judul` AS `judul_artikel` FROM `komentar` LEFT JOIN `artikel` ON `artikel`.`id`=`komentar`.`artikel` $queryString ORDER BY `komentar`.`id` DESC LIMIT $offset,$limit");
while ($data = mysql_fetch_assoc($query)){
$open['list'][] = $data;
}


if (!empty($pagging)){
$open['pagging'] = '<div class="border" style="text-align:center">'.$pagging.'</div>';
}else {
$open['pagging'] = '';	
}

$j = new JSON_obj();
echo $j->encode($open);

break;	
	
	
	
case 'delete_artikel':
if (isset($_GET['id'])){
$open['error'] = false;
$open['errorpesan'] = '';	
if (is_array($_GET['id'])){
$qSQL = '';
if ($_SESSION['LevelAkses'] == 'User'){
$qSQL = "AND `user` = '".$_SESSION['UserName']."'";
}	
foreach ($_GET['id'] as $k=>$v){
$query = mysql_query ("DELETE FROM `artikel` WHERE `id` = '$v' $qSQL");	
if ($query) {$query3 = mysql_query ("DELETE FROM `komentar` WHERE `artikel` = '$v'");}
}	

}	
	
	
	
	
	
$j = new JSON_obj();
echo $j->encode($open);	
	
}
break;

case 'editsaved':
if (isset($_GET['id']) && !empty($_GET['id'])){
	
$qSQL = '';	
if ($_SESSION['LevelAkses'] == 'User'){
$qSQL = "AND `user` = '".$_SESSION['UserName']."'";
}		
	
	
	
$id = int_filter($_GET['id']);
$_POST = array_map('decodeURIComponent',$_POST);
$judul = cleantext($_POST['judul']);
$topik = cleantext($_POST['topik']);
$gambar = cleantext($_POST['gambar']);
$tags = cleantext(hapusspasitags(trim($_POST['tags'])));
$konten = $_POST['konten'];
$open['error'] = false;
$open['errorpesan'] = '';

$update = mysql_query ("UPDATE `artikel` SET `gambar`='$gambar',`judul`='$judul',`topik`='$topik',`konten`='$konten',`tags`='$tags' WHERE `id` = '$id' $qSQL");

if (!$update){
$open['error'] = true;
$open['errorpesan'] = 'Error: '.mysql_error();	
}


$j = new JSON_obj();
echo $j->encode($open);	

}


break;


case 'addsaved':
$_POST = array_map('decodeURIComponent',$_POST);
$judul = cleantext($_POST['judul']);
$topik = cleantext($_POST['topik']);
$gambar = cleantext($_POST['gambar']);
$tags = cleantext(hapusspasitags(trim($_POST['tags'])));
$konten = $_POST['konten'];
$open['error'] = false;
$open['errorpesan'] = '';
$user = $_SESSION['UserName'];
$artikel_yg_diconfirm = 1;
if ($_SESSION['LevelAkses'] == 'User'){
$artikel_yg_diconfirm = 0;	
}
$time = date('Y-m-d H:i:s');
$insert = mysql_query ("INSERT INTO `artikel` (`judul`,`topik`,`konten`,`tgl`,`publikasi`,`user`,`tags`,`gambar`) VALUES ('$judul','$topik','$konten','$time','$artikel_yg_diconfirm','$user','$tags','$gambar')");
if ($insert) {
$open['judul'] = $judul;
$open['byte'] = round(strlen($konten) / 1024, 2) . ' kilo';
}
else {
$open['error'] = true;
$open['errorpesan'] = 'Error: '.mysql_error();	
}


$j = new JSON_obj();
echo $j->encode($open);	



break;

case 'addTopik':
if ($_SESSION['LevelAkses'] == 'User'){
$open['error'] = true;
$open['errorpesan'] = 'Tidak Diizinkan';
$j = new JSON_obj();
echo $j->encode($open);
exit;
}

$_POST = array_map('decodeURIComponent',$_POST);
$topik = $_POST['topik'];
$desc = $_POST['desc'];
$open['error'] = false;
$open['errorpesan'] = '';

$insert = mysql_query ("INSERT INTO `topik` (`topik`,`ket`) VALUES ('$topik','$desc')");

if (!$insert){
$open['error'] = true;
$open['errorpesan'] = 'Error: '.mysql_error();	
}

$j = new JSON_obj();
echo $j->encode($open);
break;
	
case 'getNews':
if (isset($_GET['id']) && !empty($_GET['id'])){
$id = int_filter($_GET['id']);

$qSQL = '';	
if ($_SESSION['LevelAkses'] == 'User'){
$qSQL = "AND `user` = '".$_SESSION['UserName']."'";
}	


$query = mysql_query ("SELECT * FROM `artikel` WHERE `id` = '$id' $qSQL");
$data = mysql_fetch_assoc($query);

$query2 = mysql_query ("SELECT `id`,`topik` FROM `topik` ORDER BY `topik`");
while ($getsql = mysql_fetch_assoc($query2)){
$data['daftarTopik'][] = $getsql;
}


$j = new JSON_obj();
echo $j->encode($data);	
	
}

break;

case 'editTopik':
if (isset($_GET['id'])){

if ($_SESSION['LevelAkses'] == 'User'){
$open['error'] = true;
$open['errorpesan'] = 'Tidak Diizinkan';
$j = new JSON_obj();
echo $j->encode($open);
exit;
}		
	
	
$open['error'] = false;
$open['errorpesan'] = '';	
$id = $_GET['id'];
$topik = cleantext(rawurldecode($_POST['topik']));
$ket = cleantext(rawurldecode($_POST['ket']));
$update = mysql_query ("UPDATE `topik` SET `topik`='$topik',`ket`='$ket' WHERE `id` = '$id'");
if (!$update){
$open['error'] = true;
$open['errorpesan'] = 'Error: '.mysql_error();	
}

$j = new JSON_obj();
echo $j->encode($open);	
}

break;

case 'delTopik':
if (isset($_GET['id'])){
	
if ($_SESSION['LevelAkses'] == 'User'){
$open['error'] = true;
$open['errorpesan'] = 'Tidak Diizinkan';
$j = new JSON_obj();
echo $j->encode($open);
exit;
}	
	
	
	
$open['error'] = false;
$open['errorpesan'] = '';	
$id = $_GET['id'];
$cek = mysql_query ("SELECT `id` FROM `artikel` WHERE `topik` = '$id'");
while ($del = mysql_fetch_assoc($cek)){
$IDartikel = $del['id'];
$delete = mysql_query ("DELETE FROM `komentar` WHERE `artikel` = '$IDartikel'");
}
$delete = mysql_query ("DELETE FROM `artikel` WHERE `topik` = '$id'");
$delete = mysql_query ("DELETE FROM `topik` WHERE `id` = '$id'");

$j = new JSON_obj();
echo $j->encode($open);	
	
}

break;

case 'listTopik':

$query2 = mysql_query ("SELECT `topik`.*,COUNT(`artikel`.`topik`) AS `totalArtikel` FROM `topik` LEFT JOIN `artikel` ON `artikel`.`topik`=`topik`.`id` GROUP BY (`topik`.`topik`)");
while ($getsql = mysql_fetch_assoc($query2)){
$data['daftarTopik'][] = $getsql;
}


$j = new JSON_obj();
echo $j->encode($data);	

break;
	
case 'getTopik':
$query2 = mysql_query ("SELECT `id`,`topik` FROM `topik` ORDER BY `topik`");
while ($getsql = mysql_fetch_assoc($query2)){
$data['daftarTopik'][] = $getsql;
}


$j = new JSON_obj();
echo $j->encode($data);	

break;

case 'topikDetail':
if (!empty($_GET['id'])){

$qSQL = '';	
if ($_SESSION['LevelAkses'] == 'User'){
$qSQL = "AND `artikel`.`user` = '".$_SESSION['UserName']."'";
}	
	
	
	
$id = $_GET['id'];
$query2 = mysql_query ("SELECT count(`id`) AS `total` FROM `artikel` WHERE `artikel`.`topik` = '$id' $qSQL");
$getdata2 = mysql_fetch_assoc($query2);
$jumlah = $getdata2['total'];
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
$ddl .= '<a onclick="news.indexs(\''.$v['link'].'\');" style="cursor:pointer" title="Page '.$v['title'].'">'.$v['title'].'</a> | ';
}
}
unset($pagging);
$pagging = substr($ddl,0,strlen($ddl)-3);
}else {
$pagging = null;	
}


$query = mysql_query ("SELECT `artikel`.`id`,`artikel`.`judul`,`artikel`.`user`,`artikel`.`tgl`,`artikel`.`hits`,`topik`.`topik`,COUNT(`komentar`.`artikel`) AS `totalkomentar` FROM `artikel` LEFT JOIN `topik` ON `topik`.`id`=`artikel`.`topik` LEFT JOIN `komentar` ON `komentar`.`artikel`=`artikel`.`id`  WHERE `artikel`.`topik` = '$id' $qSQL GROUP BY `artikel`.`id` DESC LIMIT $offset,$limit");
while ($data = mysql_fetch_assoc($query)){
$open['list'][] = array ('id'=>$data['id'],'judul'=>$data['judul'],'user'=>$data['user'],'tgl'=>$data['tgl'],'hits'=>$data['hits'],'topik'=>$data['topik'],'komentar'=>$data['totalkomentar']);
}


if (!empty($pagging)){
$open['pagging'] = '<div class="border" style="text-align:center">'.$pagging.'</div>';
}else {
$open['pagging'] = '';	
}

$j = new JSON_obj();
echo $j->encode($open);
}
break;


case 'cariartikel':

$qSQL = '';
if ($_SESSION['LevelAkses'] == 'User'){
$qSQL = "AND `artikel`.`user` = '".$_SESSION['UserName']."'";
}


$search = rawurldecode($_GET['search']);
$query2 = mysql_query ("SELECT count(`artikel`.`id`) AS `total` FROM `artikel` WHERE (`artikel`.`judul` LIKE '%$search%' OR `artikel`.`konten` LIKE '%$search%') $qSQL");
$getdata2 = mysql_fetch_assoc($query2);
$jumlah = $getdata2['total'];
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
$ddl .= '<a onclick="news.indexs(\''.$v['link'].'\');" style="cursor:pointer" title="Page '.$v['title'].'">'.$v['title'].'</a> | ';
}
}
unset($pagging);
$pagging = substr($ddl,0,strlen($ddl)-3);
}else {
$pagging = null;	
}


$query = mysql_query ("SELECT `artikel`.`id`,`artikel`.`judul`,`artikel`.`user`,`artikel`.`tgl`,`artikel`.`hits`,`topik`.`topik`,COUNT(`komentar`.`artikel`) AS `komentar` FROM `artikel` LEFT JOIN `topik` ON `topik`.`id`=`artikel`.`topik` LEFT JOIN `komentar` ON `komentar`.`artikel`=`artikel`.`id` WHERE (`artikel`.`judul` LIKE '%$search%' OR `artikel`.`konten` LIKE '%$search%' OR `artikel`.`user` LIKE '%$search%') $qSQL GROUP BY `artikel`.`id` DESC LIMIT $offset,$limit");
while ($data = mysql_fetch_assoc($query)){
//$open['list'][] = array ('id'=>$data['id'],'judul'=>$data['judul'],'user'=>$data['user'],'tgl'=>$data['tgl'],'hits'=>$data['hits'],'topik'=>$data['topik'],'komentar'=>$data['totalkomentar']);
$data = array_map('callback',$data);
$open['list'][] = $data;
}


if (!empty($pagging)){
$open['pagging'] = '<div class="border" style="text-align:center">'.$pagging.'</div>';
}else {
$open['pagging'] = '';	
}

$j = new JSON_obj();
echo $j->encode($open);
break;

case 'getArtikelMasukApprove':

if ($_SESSION['LevelAkses'] == 'User'){
exit;
}


if (!empty($_GET['id'])){
$id = int_filter($_GET['id']);
$update = mysql_query ("UPDATE `artikel` SET `publikasi` = 1 WHERE `id` = '$id'");	
}
break;

case 'getArtikelMasuk':
if ($_SESSION['LevelAkses'] == 'User'){
exit;
}
$query2 = mysql_query ("SELECT count(`artikel`.`id`) AS `total` FROM `artikel` WHERE `artikel`.`publikasi` = 0");
$getdata2 = mysql_fetch_assoc($query2);
$jumlah = $getdata2['total'];
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
$ddl .= '<a onclick="news.indexs(\''.$v['link'].'\');" style="cursor:pointer" title="Page '.$v['title'].'">'.$v['title'].'</a> | ';
}
}
unset($pagging);
$pagging = substr($ddl,0,strlen($ddl)-3);
}else {
$pagging = null;	
}


$query = mysql_query ("SELECT `artikel`.`id`,`artikel`.`judul`,`artikel`.`user`,`artikel`.`tgl`,`artikel`.`hits`,`topik`.`topik`,COUNT(`komentar`.`artikel`) AS `komentar` FROM `artikel` LEFT JOIN `topik` ON `topik`.`id`=`artikel`.`topik` LEFT JOIN `komentar` ON `komentar`.`artikel`=`artikel`.`id` WHERE `artikel`.`publikasi` = 0 GROUP BY `artikel`.`id` DESC LIMIT $offset,$limit");
while ($data = mysql_fetch_assoc($query)){
//$open['list'][] = array ('id'=>$data['id'],'judul'=>$data['judul'],'user'=>$data['user'],'tgl'=>$data['tgl'],'hits'=>$data['hits'],'topik'=>$data['topik'],'komentar'=>$data['totalkomentar']);
$data = array_map('callback',$data);
$open['list'][] = $data;
}


if (!empty($pagging)){
$open['pagging'] = '<div class="border" style="text-align:center">'.$pagging.'</div>';
}else {
$open['pagging'] = '';	
}

$j = new JSON_obj();
echo $j->encode($open);
break;

	
default:
$qSQL = '';
$publikasi = "`artikel`.`publikasi` = 1";
if ($_SESSION['LevelAkses'] == 'User'){
$publikasi = "";
$qSQL = "`artikel`.`user` = '".$_SESSION['UserName']."'";
}

$query2 = mysql_query ("SELECT count(`artikel`.`id`) AS `total` FROM `artikel` WHERE $publikasi $qSQL");
$getdata2 = mysql_fetch_assoc($query2);
$jumlah = $getdata2['total'];
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
$ddl .= '<a onclick="news.indexs(\''.$v['link'].'\');" style="cursor:pointer" title="Page '.$v['title'].'">'.$v['title'].'</a> | ';
}
}
unset($pagging);
$pagging = substr($ddl,0,strlen($ddl)-3);
}else {
$pagging = null;	
}


$query = mysql_query ("SELECT `artikel`.`id`,`artikel`.`judul`,`artikel`.`user`,`artikel`.`tgl`,`artikel`.`hits`,`topik`.`topik`,COUNT(`komentar`.`artikel`) AS `komentar` FROM `artikel` LEFT JOIN `topik` ON `topik`.`id`=`artikel`.`topik` LEFT JOIN `komentar` ON `komentar`.`artikel`=`artikel`.`id` WHERE $publikasi $qSQL GROUP BY `artikel`.`id` DESC LIMIT $offset,$limit");
while ($data = mysql_fetch_assoc($query)){
//$open['list'][] = array ('id'=>$data['id'],'judul'=>$data['judul'],'user'=>$data['user'],'tgl'=>$data['tgl'],'hits'=>$data['hits'],'topik'=>$data['topik'],'komentar'=>$data['totalkomentar']);
$data = array_map('callback',$data);
$open['list'][] = $data;
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



?>