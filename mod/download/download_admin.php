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
include '../../includes/search.lib.php';



if (!cek_login ()){
   exit;
}


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
$query1 = mysql_query("SELECT `date` FROM `$tabel` WHERE `date` >= '$waktu' AND $kategori='$kid'");	
	
if (mysql_num_rows ($query1) > 0){
return "<font color=red><b><sup>New</sup></b></font>";
}else {
return '';	
}	
mysql_free_result ($query1);	
}
switch (@$_GET['action']){
case 'additems':
$judul = cleantext($_POST['judul']);
$url = cleantext($_POST['url']); 
$kid = $_POST['kid'];
$keterangan = cleantext($_POST['keterangan']); 
$size = cleantext($_POST['size']); 


$open['sukses'] = false;
$open['error'] = '';
$date = time ();
$insert = mysql_query ("INSERT INTO `mod_download` (`judul`,`url`,`kid`,`keterangan`,`size`,`date`) VALUES ('$judul','$url','$kid','$keterangan','$size','$date')");
if ($insert) {$open['sukses'] = true;}
else {$open['sukses'] = false; $open['error'] = mysql_error(); }
$j = new JSON_obj();
echo $j->encode($open);

break;


case 'addcat':
$kategori = cleantext($_POST['kategori']);
$keterangan = cleantext($_POST['keterangan']); 
$open['sukses'] = false;
$open['error'] = '';
$insert = mysql_query ("INSERT INTO `mod_cat_download` (`kategori`,`keterangan`) VALUES ('$kategori','$keterangan')");
if ($insert) {$open['sukses'] = true;}
else {$open['sukses'] = false; $open['error'] = mysql_error(); }
$j = new JSON_obj();
echo $j->encode($open);
break;	
	
	
	
case 'loadscript':
$kid = $_GET['kid'];
$loop = '';
$query = mysql_query ("SELECT `kategori`,`kid` FROM `mod_cat_download` ORDER BY `kategori`");
while ($data = mysql_fetch_assoc($query)){
}
echo '
var out = \'\';	';	

$query = mysql_query ("SELECT `kategori`,`kid` FROM `mod_cat_download` ORDER BY `kategori`");
while ($data = mysql_fetch_assoc($query)){
if ($kid == $data['kid']){
$selected = ' selected';	
}else {
$selected = '';		
}
	
echo '
out += \'<option value="'.$data['kid'].'"'.$selected.'>'.$data['kategori'].'</option>\';';	

	
}

	
break;

case 'delete_items':
$open['sukses'] = false;
$open['pesanError'] = '';
if (!empty($_GET['id'])){
$id = $_GET['id'];
$newId = rawurldecode($id);
$tmpId = explode(',',$newId);

foreach ($tmpId as $k=>$v){
$qu = mysql_query("DELETE FROM `mod_download` WHERE `id` = '$v'");
if ($qu){
$open['deleted'][] = $v;	
}

}


$j = new JSON_obj();
echo $j->encode($open);
	
}

break;

case 'listkategori':
$query = mysql_query ("SELECT `kategori`,`kid` FROM `mod_cat_download` ORDER BY `kategori`");
echo '<select name="kid" size=1>';
echo '<option value="">--pilih--</option>';	
while ($data = mysql_fetch_assoc($query)){
echo '<option value="'.$data['kid'].'">'.$data['kategori'].'</option>';	
}
echo '</select>';
break;
	
	
case 'loadkategori':
$query = mysql_query ("SELECT `kategori`,`kid` FROM `mod_cat_download` ORDER BY `kategori`");
while ($data = mysql_fetch_assoc($query)){
$open['list'][] = array ('kategori'=>$data['kategori'],'kid'=>$data['kid']);	
	
}
$j = new JSON_obj();
echo $j->encode($open);
break;	
	
	
case 'editisi':
if (!empty($_GET['id'])){
$id = $_GET['id'];	
$field = $_POST['field'];
$textedit = $_POST['textedit'];
$query = mysql_query ("UPDATE `mod_download` SET `$field` = '$textedit' WHERE `id` = '$id'");

if ($query){
$open['error'] = false;
$open['errorpesan'] = '';	
	
}else {
$open['error'] = true;
$open['errorpesan'] = mysql_error();	
	
}
$q = mysql_query ("SELECT `$field` FROM `mod_download` WHERE `id` = '$id'");
$data = mysql_fetch_assoc($q);
$open['edited'] = $data[$field];	
$j = new JSON_obj();
echo $j->encode($open);	

}

break;	
	
	
case 'deletecat':
if (!empty($_GET['id'])){
$id = $_GET['id'];	
$del = mysql_query ("DELETE FROM `mod_cat_download` WHERE `kid` = '$id'");
$del = mysql_query ("DELETE FROM `mod_download` WHERE `kid` = '$id'");
}

break;	
	
	
case 'editket':
$id = $_GET['id'];
$desc = cleantext($_POST['desc']);
$update = mysql_query ("UPDATE `mod_cat_download` SET `keterangan` = '$desc' WHERE `kid` = '$id'");
if ($update){
$open['error'] = false;
$open['errorpesan'] = '';
}else {
$open['error'] = true;
$open['errorpesan'] = mysql_error();

}

$cek = mysql_query ("SELECT `keterangan` FROM  `mod_cat_download` WHERE `kid` = '$id'");
$data = mysql_fetch_assoc($cek);

$open['keterangan'] = $data['keterangan'];	
$j = new JSON_obj();
echo $j->encode($open);	
break;

case 'editkat':
$id = $_GET['id'];
$desc = cleantext($_POST['desc']);
$update = mysql_query ("UPDATE `mod_cat_download` SET `kategori` = '$desc' WHERE `kid` = '$id'");
if ($update){
$open['error'] = false;
$open['errorpesan'] = '';
}else {
$open['error'] = true;
$open['errorpesan'] = mysql_error();

}

$cek = mysql_query ("SELECT `kategori` FROM  `mod_cat_download` WHERE `kid` = '$id'");
$data = mysql_fetch_assoc($cek);

$open['keterangan'] = $data['kategori'];	
$j = new JSON_obj();
echo $j->encode($open);	
break;

case 'broken':
if (!empty($_GET['id'])){
$id = $_GET['id'];
$update = mysql_query ("UPDATE `mod_download` SET `broken` = broken+1 WHERE `id` = '$id'");
}
break;

case 'go':
if (!empty($_GET['id'])){
$id = $_GET['id'];
$update = mysql_query ("UPDATE `mod_download` SET `hit` = hit+1 WHERE `id` = '$id'");
$q = mysql_query("SELECT * FROM `mod_download` WHERE `id` = '$id'");
$data = mysql_fetch_assoc($q);
$url = $data['url'];
$open['url'] = $url;
$j = new JSON_obj();
echo $j->encode($open);		
}
break;
	

	
	
	
case 'cari':

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
	  				FROM `mod_download` where MATCH(judul,keterangan,url) AGAINST ('$search'  IN BOOLEAN MODE)
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
$ddl .= '<a onclick="download.cariclick(\''.$v['link'].'\');" style="cursor:pointer" title="Page '.$v['title'].'">'.$v['title'].'</a> | ';
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
		
$hasil4 = mysql_query  ("SELECT `mod_download`.*,`mod_cat_download`.`kategori`,
		 		MATCH(`mod_download`.`judul`,`mod_download`.`keterangan`,`mod_download`.`url`) AGAINST ('$search'  IN BOOLEAN MODE) AS score 
	  			FROM `mod_download` LEFT JOIN `mod_cat_download` ON `mod_cat_download`.`kid`=`mod_download`.`kid` where MATCH(`mod_download`.`judul`,`mod_download`.`keterangan`,`mod_download`.`url`) AGAINST ('$search'  IN BOOLEAN MODE) 
	  			 ORDER BY score DESC
	 			LIMIT $offset, $limit");	
	 			
if ($jumlah > 0){
$open['finds'] = true;
$open['caption'] = 'Ditemukan <b>'.$jumlah.'</b> Download Dengan Kata Kunci : <b>'.$search.'</b>';	






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
$open['List'][] = array ('judul'=>$JUDUL,'keterangan'=>$KETERANGAN,'url'=>$data['url'],'hit'=>$data['hit'],'date'=>date('Y-m-d H:i:s',$data['date']),'id'=>$data['id'],'kid'=>$data['kid'],'kategori'=>$data['kategori'],'size'=>$data['size'],'newlinks'=>cek_baru ($data['id'],1209600, 'id', 'mod_link'));	
}
}else {
$open['finds'] = false;
$open['caption'] = 'Tidak Ditemukan  Dengan Kata Kunci : <b>'.$search.'</b>';		
}	 			
	
$j = new JSON_obj();
echo $j->encode($open);		
}

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
	
$num = mysql_query("SELECT kid FROM `mod_download` WHERE `kid` = '$kid'");
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
$ddl .= '<a onclick="download.bukakategori(\''.$v['link'].'\');" style="cursor:pointer" title="Page '.$v['title'].'">'.$v['title'].'</a> | ';
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


$query = mysql_query ("SELECT `mod_download`.*,`mod_cat_download`.`kategori` FROM `mod_download` LEFT JOIN `mod_cat_download` ON `mod_cat_download`.`kid`=`mod_download`.`kid` WHERE `mod_download`.`kid` = '$kid' ORDER BY `mod_download`.`id` DESC LIMIT $offset,$limit");
while ($data = mysql_fetch_assoc($query)){

$ceknew = empty($ceknew) ? '' : $ceknew;
$open['List'][] = array ('judul'=>$data['judul'],'keterangan'=>$data['keterangan'],'url'=>$data['url'],'hit'=>$data['hit'],'date'=>date('Y-m-d H:i:s',$data['date']),'size'=>$data['size'],'id'=>$data['id'],'kid'=>$data['kid'],'kategori'=>$data['kategori'],'newlinks'=>cek_baru ($data['id'],1209600, 'id', 'mod_download'));	
}

if (!empty($pagging)){
$open['paging'] = $pagging;	
}else {
$open['paging'] = '';	
}

$query = mysql_query ("SELECT `kategori` FROM `mod_cat_download` WHERE `kid` = '$kid'");
$getsql = mysql_fetch_assoc($query);
$open['kategori'] = array ($getsql['kategori'].' ('.$jumlah.')');
$j = new JSON_obj();
echo $j->encode($open);		

}


break;	
	

default:



$num = mysql_query("SELECT kid FROM `mod_cat_download`");
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


$query = mysql_query ("SELECT * FROM `mod_cat_download` ORDER BY `kid` DESC LIMIT $offset,$limit");
while ($data = mysql_fetch_assoc($query)){
$query2 = mysql_num_rows(mysql_query ("SELECT `id` FROM `mod_download` WHERE `kid` = '".$data['kid']."'"));
$ceknew = cek_baru ($data['kid'],1209600, 'kid', 'mod_download');
$ceknew = empty($ceknew) ? '' : $ceknew;
$open['List'][] = array ('total'=>$query2,'kategori'=>$data['kategori'],'keterangan'=>$data['keterangan'],'kid'=>$data['kid'],'newLink'=>$ceknew);	
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