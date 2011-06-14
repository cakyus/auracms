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

if (!isset($_SESSION['mod_ajax'])){
exit;	
}



if (isset ($_GET['pg'])) $pg = int_filter ($_GET['pg']); else $pg = NULL;
if (isset ($_GET['stg'])) $stg = int_filter ($_GET['stg']); else $stg = NULL;
if (isset ($_GET['offset'])) $offset = int_filter ($_GET['offset']); else $offset = NULL;
if (isset ($_GET['id'])) $id = int_filter ($_GET['id']); else $id = NULL;
if (isset ($_GET['kid'])) $kid = int_filter ($_GET['kid']); else $kid = NULL;

function cek_baru ($kid, $expire=2073600, $kategori='kid', $tabel){
$waktu = time () - $expire;
$query1 = mysql_query("SELECT `date` FROM `$tabel` WHERE `date` >= '$waktu' AND $kategori='$kid' AND `public` = 1");	
	
if (mysql_num_rows ($query1) > 0){
return "<font color=red><b><sup>New</sup></b></font>";
}else {
return '';	
}	
mysql_free_result ($query1);	
}
switch (@$_GET['action']){

case 'cari':
include '../../includes/search.lib.php';

$search = $_GET['search'];

$synonym_list='';
$stopword_list[] = '&';
$stopword_list[] = ',';
$stopword_list[] = '@';
$stopword_list[] = '#';
$stopword_list[] = '"';
$stopword_list[] = '\'';
$stopword_list[] = '?';
$stopword_list[] = '!';
$stopword_list[] = '*';
$search = clean_words('search', $search, $stopword_list, $synonym_list);



if (!empty($search)){
	
$num = mysql_query("SELECT id,
		 			MATCH(judul,keterangan,url) AGAINST ('$search'  IN BOOLEAN MODE) AS score 
	  				FROM `mod_link` where MATCH(judul,keterangan,url) AGAINST ('$search'  IN BOOLEAN MODE) AND public=1 
	  				");
	  
	  
$jumlah = mysql_num_rows ($num);
mysql_free_result ($num);

$limit = 8;
$pembagian = new paging ($limit);

$pagging = $pembagian-> getPagingajax($jumlah, $pg, $stg);
if (is_array($pagging)){
$ddl = '';
foreach ($pagging as $k=>$v){
if ($v['link'] == ""){
$ddl .= $v['title'].' | ';
}else {
$ddl .= '<a onclick="weblink.cariclick(\''.$v['link'].'\');" style="cursor:pointer" title="Page '.$v['title'].'">'.$v['title'].'</a> | ';
}
}
unset($pagging);
$pagging = substr($ddl,0,strlen($ddl)-3);
}else {
$pagging = null;	
}

if (!empty($pagging)){
$open['paging'] = $pagging;	
}else {
$open['paging'] = '';	
}

if (empty($offset)) {
$offset = 0;
}
		
$hasil4 = mysql_query  ("SELECT judul,
								keterangan,
								url,
								hit,
								date,
								broken,
								public,
								kid,
								id,
		 		MATCH(judul,keterangan,url) AGAINST ('$search'  IN BOOLEAN MODE) AS score 
	  			FROM `mod_link` where MATCH(judul,keterangan,url) AGAINST ('$search'  IN BOOLEAN MODE)  AND public=1 
	  			 ORDER BY score DESC
	 			LIMIT $offset, $limit");	
	 			
if ($jumlah > 0){
$open['finds'] = true;
$open['caption'] = 'Ditemukan <b>'.$jumlah.'</b> Links Dengan Kata Kunci : <b>'.$search.'</b>';	
while ($data = mysql_fetch_assoc($hasil4)){
///// fungsi hightlight
//////////////////////////////////////////////////////////////////////////////////////////////////////////	
$highlight = $search;

if (isset($search)){
	// Split words and phrases
	$words = explode(' ', trim(htmlspecialchars(urldecode($search))));
$highlight_match = '';
	for($i = 0; $i < sizeof($words); $i++)
	{
		if (trim($words[$i]) != '')
		{
			$highlight_match .= (($highlight_match != '') ? '|' : '') . str_replace('*', '\w*', phpbb_preg_quote($words[$i], '#'));
		}
	}
	unset($words);
	
}	
	
$JUDUL = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace('#(" . $highlight_match . ")#i', '<span style=\"color:orange\">\\\\1</span>', '\\0')", '>' . $data['judul'] . '<'), 1, -1));
$KETERANGAN = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace('#(" . $highlight_match . ")#i', '<span style=\"color:orange\">\\\\1</span>', '\\0')", '>' . $data['keterangan'] . '<'), 1, -1));
$ceknew = empty($ceknew) ? '' : $ceknew;
$open['List'][] = array ('judul'=>$JUDUL,'keterangan'=>$KETERANGAN,'url'=>$data['url'],'hit'=>$data['hit'],'date'=>date('Y-m-d H:i:s',$data['date']),'id'=>$data['id'],'newlinks'=>cek_baru ($data['id'],1209600, 'id', 'mod_link'));	
}
}else {
$open['finds'] = false;
$open['caption'] = 'Tidak Ditemukan  Dengan Kata Kunci : <b>'.$search.'</b>';		
}	 			
	
$j = new JSON_obj();
echo $j->encode($open);		
}

break;	
	
	
	
case 'add':


$error = '';
if (empty($_POST['judul'])) $error .= 'Error: Please enter field Judul!<br \>';
if (!validate_url($_POST['url'])) $error .= 'Error: Invalid URL<br \>';

$parseurl = parse_url($_POST['url']);
$host = $parseurl['host'];
if (mysql_num_rows(mysql_query("SELECT `url` FROM `mod_link` WHERE `url` LIKE '%$host%'")) > 0) {
	$error .= 'Error: URL tersebut sudah ada<br \>';
}

if (empty($_POST['kategori'])) $error .= 'Error: Please Select Kategori<br \>';
if (empty($_POST['keterangan'])) $error .= 'Error: Please Enter Keterangan<br \>';
if ($_POST['kode'] != $_SESSION['Var_session'] or !isset($_SESSION['Var_session'])) $error .= 'Error: Security Code not Match<br \>';
if (time () < @$_SESSION['posted_link']) {
	 $selisih = (@$_SESSION['posted_link'] - time ());
	$error .= "Please Wait " . (int)date ('i',$selisih) . " Menit, " . date ('s',$selisih) . " detik";
	}

if ($error != ''){
$open['error'] = true;
$open['pesanError'] = $error;
	
}else {
$open['error'] = false;	
$judul = cleantext($_POST['judul']);	
$url = cleantext($_POST['url']);	
$kategori = cleantext($_POST['kategori']);	
$keterangan = cleantext($_POST['keterangan']);	

//is_valid_email
    if (validate_url($url)){
	$url =  'http://' . eregi_replace ('http://', '' , $url);
	}
	else {
	$url = '';
	}


$query = mysql_query ("INSERT INTO `mod_link` (`judul`,`url`,`kid`,`keterangan`,`public`,`date`) VALUES ('$judul','$url','$kategori','$keterangan',0,'".time()."')");
if ($query) {
$open['error'] = false;	
$open['pesanError'] = '';
session_register('posted_link');
$_SESSION['posted_link'] = time() + 60 * 10;	
}else {
$open['error'] = true;
$open['pesanError'] = 'Gagal Memasukkan Data Kedalam Database';	
}
	
}

$j = new JSON_obj();
echo $j->encode($open);	
break;	
	
	
case 'showkategori':

$query = mysql_query ("SELECT * FROM `mod_cat_link` ORDER BY `kategori`");
while ($data = mysql_fetch_assoc($query)){
$open['List'][] = array ('kategori'=>$data['kategori'],'id'=>$data['kid']);	
}
$j = new JSON_obj();
echo $j->encode($open);	
break;

case 'detail':

if (isset($_GET['id'])){
$kid = int_filter($_GET['id']);	
	
$num = mysql_query("SELECT kid FROM `mod_link` WHERE `kid` = '$kid' AND public = 1");
$jumlah = mysql_num_rows ($num);
mysql_free_result ($num);	

$limit = 10;
$pembagian = new paging ($limit);

$pagging = $pembagian-> getPagingajax($jumlah, $pg, $stg);
if (is_array($pagging)){
$ddl = '';
foreach ($pagging as $k=>$v){
if ($v['link'] == ""){
$ddl .= $v['title'].' | ';
}else {
$ddl .= '<a onclick="weblink.bukakategori(\''.$v['link'].'\');" style="cursor:pointer" title="Page '.$v['title'].'">'.$v['title'].'</a> | ';
}
}
unset($pagging);
$pagging = substr($ddl,0,strlen($ddl)-3);
}else {
$pagging = null;	
}


if (empty($offset)) {
$offset = 0;
}


$query = mysql_query ("SELECT * FROM `mod_link` WHERE `kid` = '$kid' AND public = 1 ORDER BY `id` DESC LIMIT $offset,$limit");
while ($data = mysql_fetch_assoc($query)){

$ceknew = empty($ceknew) ? '' : $ceknew;
$open['List'][] = array ('judul'=>$data['judul'],'keterangan'=>$data['keterangan'],'url'=>$data['url'],'hit'=>$data['hit'],'date'=>date('Y-m-d H:i:s',$data['date']),'id'=>$data['id'],'newlinks'=>cek_baru ($data['id'],1209600, 'id', 'mod_link'));	
}

if (!empty($pagging)){
$open['paging'] = $pagging;	
}else {
$open['paging'] = '';	
}

$query = mysql_query ("SELECT `kategori` FROM `mod_cat_link` WHERE `kid` = '$kid'");
$getsql = mysql_fetch_assoc($query);
$open['kategori'] = array ($getsql['kategori'].' ('.$jumlah.')');
$j = new JSON_obj();
echo $j->encode($open);		
	
	
	
	
	
	
	
	
	
	
	
}






break;	
	
	
	
	
		
default:



$num = mysql_query("SELECT kid FROM `mod_cat_link`");
$jumlah = mysql_num_rows ($num);
mysql_free_result ($num);	

$limit = 10;
$pembagian = new paging ($limit);

$pagging = $pembagian-> getPagingajax($jumlah, $pg, $stg);
if (is_array($pagging)){
$ddl = '';
foreach ($pagging as $k=>$v){
if ($v['link'] == ""){
$ddl .= $v['title'].' | ';
}else {
$ddl .= '<a onclick="weblink.links(\''.$v['link'].'\');" style="cursor:pointer" title="Page '.$v['title'].'">'.$v['title'].'</a> | ';
}
}
unset($pagging);
$pagging = substr($ddl,0,strlen($ddl)-3);
}else {
$pagging = null;	
}


if (empty($offset)) {
$offset = 0;
}


$query = mysql_query ("SELECT * FROM `mod_cat_link` ORDER BY `kid` DESC LIMIT $offset,$limit");
while ($data = mysql_fetch_assoc($query)){
$query2 = mysql_num_rows(mysql_query ("SELECT `id` FROM `mod_link` WHERE `kid` = '".$data['kid']."'"));
$ceknew = cek_baru ($data['kid'],1209600, 'kid', 'mod_link');
$ceknew = empty($ceknew) ? '' : $ceknew;
$open['ListLink'][] = array ('total'=>$query2,'kategori'=>$data['kategori'],'keterangan'=>$data['keterangan'],'kid'=>$data['kid'],'newLink'=>$ceknew);	
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