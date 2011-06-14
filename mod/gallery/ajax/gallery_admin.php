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
include '../gallery.config.php';



if (!cek_login () && @$_SESSION['LevelAkses'] != 'Administrator'){
   exit;
}

if (!isset($_SESSION['mod_ajax'])){
exit;	
}
$_GET['order'] = !isset($_GET['order']) ? null : $_GET['order'];

$limit = 15;
$config_album = array('pptabel'=>5,'limit'=>$limit,'tpath'=>$tpath,'tnpath'=>$tnpath);

if (isset ($_GET['order'])) $order = $_GET['order']; else $order = NULL;
if (isset ($_GET['sort'])) $sort = $_GET['sort']; else $sort = NULL;

function hapusquote($t){
$t = str_replace("'","",$t);
//$t = str_replace ("\"",'',$t);
$t = str_replace ('&quot;','',$t);

return $t;	
}

function gallery_url (){
 @$URI = parse_url ($_SERVER["REQUEST_URI"]);
 $arr = explode("&", @$URI['query']); 
      if (is_array($arr)) { 
	      $qs = '';
        for ($i=0;$i<count($arr);$i++) { 
	       
          if (!is_int(strpos($arr[$i],"sort=")) && !is_int(strpos($arr[$i],"order=")) && trim($arr[$i]) != "") {
	          $qs .= $arr[$i]."&"; 
          }
        } 
      } 	
return $qs;	
}


function imagesort($name){
$img = '';
$sort = isset ($_GET['sort']) ? $_GET['sort'] : null;
$order = isset ($_GET['order']) ? $_GET['order'] : null;	
if ($name == $sort){
if ($order == 'asc'){
$img = '<img src="images/bullet_arrow_up.gif" align=absmiddle>';		
}elseif ($order == 'dsc'){
$img = '<img src="images/bullet_arrow_down.gif" align=absmiddle>';
}	
}
return $img;	
}


function gallery_sort ($qs,$order,$fuction_click='bukadata'){
	$id = '<div class="border"><p align="right"><small>Sorting : <a title="Sort Berdasarkan Name '.($order=='asc'?'dsc':'asc').'" style="cursor:pointer" onclick="'.$fuction_click.'(\'' . $qs  . 'sort=name&order='.($order=='asc'?'dsc':'asc').'\')">Name</a> '.imagesort('name').' | <a title="Sort Berdasarkan Modifield '.($order=='asc'?'dsc':'asc').'" style="cursor:pointer" onclick="'.$fuction_click.'(\'' . $qs  . 'sort=modified&order='.($order=='asc'?'dsc':'asc').'\')">Modified</a> '.imagesort('modified').' | <a title="Sort Berdasarkan Size '.($order=='asc'?'dsc':'asc').'" style="cursor:pointer" onclick="'.$fuction_click.'(\'' . $qs  . 'sort=size&order='.($order=='asc'?'dsc':'asc').'\')">Size</a> '.imagesort('size').' | <a title="Sort Berdasarkan View '.($order=='asc'?'dsc':'asc').'" style="cursor:pointer" onclick="'.$fuction_click.'(\'' . $qs  . 'sort=view&order='.($order=='asc'?'dsc':'asc').'\')">view</a> '.imagesort('view').'</small></p></div>';
	return $id;	
} 

function album_sort ($qs,$order,$fuction_click='bukadata'){
	$id = '<div class="border"><p align="right"><small>Sorting : <a title="Sort Berdasarkan Name '.($order=='asc'?'dsc':'asc').'" style="cursor:pointer" onclick="'.$fuction_click.'(\'' . $qs  . 'sort=name&order='.($order=='asc'?'dsc':'asc').'\')">Name</a> '.imagesort('name').' | <a title="Sort Berdasarkan Total Album '.($order=='asc'?'dsc':'asc').'" style="cursor:pointer" onclick="'.$fuction_click.'(\'' . $qs  . 'sort=total&order='.($order=='asc'?'dsc':'asc').'\')">Total</a> '.imagesort('total').'</small></p></div>';
	return $id;	
}




switch (@$_GET['action']){
	
case 'cekdir':
$error = '';
if (!is_writable('../'.$Tpath)) {
$error .= 'Unable to Write <b>mod/gallery/' . $Tpath . '</b><br />';	
}
if (!is_writable('../'.$Tnpath)) {
$error .= 'Unable to Write <b>mod/gallery/' . $Tnpath . '</b><br />';		
}
if (!is_writable('../'.$tmp)) {
$error .= 'Unable to Write <b>mod/gallery/' . $tmp . '</b><br />';		
}
if ($error != '') {
$error .= '<br />Silahkan ganti permission setting directory (CHMOD) 777 Pada masing2 folder tersebut.';	
}
$j = new JSON_obj();
echo $j->encode($error);
break;	

case 'getdesc':
if (!empty($_GET['id'])){
$id = int_filter($_GET['id']);
$qs = mysql_query ("SELECT `desc` FROM `mod_gallery` WHERE `id` = '$id'");
$data = mysql_fetch_assoc($qs);
echo $data['desc'];
exit;
	
}
break;
case 'addcat':
$kategori = cleantext($_POST['kategori']);
$desc = cleantext($_POST['desc']);
$desc = str_replace ("\n"," ",$desc);
$desc = str_replace ("\r\n"," ",$desc);
$desc = str_replace ("\r"," ",$desc);
$desc = str_replace ("\t"," ",$desc);
$open['sukses'] = false;
$open['pesanError'] = '';
if (!empty($kategori) && !empty($desc)){
$qu = mysql_query ("INSERT INTO `mod_gallery_kat` (`name`,`desc`) VALUES ('$kategori','$desc')");
if ($qu){
$open['sukses'] = true;	
}else {
$open['sukses'] = false;
$open['pesanError'] = mysql_error();	
}

}

$j = new JSON_obj();
echo $j->encode($open);
break;	


case 'editcat':

$kategori = cleantext(rawurldecode($_POST['kategori']));
$desc = cleantext(rawurldecode($_POST['desc']));
$desc = str_replace ("\n"," ",$desc);
$desc = str_replace ("\r\n"," ",$desc);
$desc = str_replace ("\r"," ",$desc);
$desc = str_replace ("\t"," ",$desc);
$id = int_filter($_GET['id']);
$open['sukses'] = false;
$open['pesanError'] = '';



if (!empty($kategori) && !empty($desc)){
$qu = mysql_query ("UPDATE `mod_gallery_kat` SET `name`='$kategori',`desc`='$desc' WHERE `id` = '$id'");
if ($qu){
$open['sukses'] = true;	
}else {
$open['sukses'] = false;
$open['pesanError'] = mysql_error();	
}

}

$j = new JSON_obj();
echo $j->encode($open);
break;


case 'editdesc':
$desc = cleantext(urldecode($_POST['desc']));
$desc = str_replace ("\n"," ",$desc);
$desc = str_replace ("\r\n"," ",$desc);
$desc = str_replace ("\r"," ",$desc);
$desc = str_replace ("\t"," ",$desc);

$id = int_filter($_GET['id']);
$open['sukses'] = false;
$open['pesanError'] = '';



if (!empty($desc)){
$qu = mysql_query ("UPDATE `mod_gallery` SET `desc`='$desc' WHERE `id` = '$id'");
if ($qu){
$open['sukses'] = true;	
}else {
$open['sukses'] = false;
$open['pesanError'] = mysql_error();	
}

}

$ref = parse_url(referer_decode($_GET['referer']));
$open['referer'] = $ref['query'];
$j = new JSON_obj();
echo $j->encode($open);
break;


case 'move_items':
$kategori = $_POST['kategori'];

$id = urldecode($_GET['id']);	
$cc = explode (',',$id);
$count = count($cc);
if ($count >= 1){
for($i=0;$i<$count;$i++){
$ID  = $cc[$i];
$cek = mysql_query ("SELECT `kid` FROM `mod_gallery` WHERE `id` = '$ID'");
$data = mysql_fetch_assoc($cek);
$kid = $data['kid'];
$up = mysql_query ("UPDATE `mod_gallery` SET `kid` = '$kategori' WHERE `id` = '$ID'");
$up = mysql_query ("UPDATE `mod_gallery_kat` SET `total` = total+1 WHERE `id` = '$kategori'");
$up = mysql_query ("UPDATE `mod_gallery_kat` SET `total` = total-1 WHERE `id` = '$kid'");


}	
$open['sukses'] = true;
$ref = parse_url(referer_decode($_GET['referer']));
$open['referer'] = $ref['query'];
$j = new JSON_obj();
echo $j->encode($open);
}

break;

	
case 'delcat':
$open['sukses'] = false;
$open['pesanError'] = '';

if (!empty($_GET['id'])){
$kid = $_GET['id'];	
$query = mysql_query ("SELECT `name`,`id` FROM `mod_gallery` WHERE kid='$kid'");	
while ($data = mysql_fetch_array ($query)) {
$name = $data['name'];
$unlink1 = @unlink('../'.$Tpath.$name);
$unlink2 = @unlink('../'.$Tnpath.$name);
}

$query2 = mysql_query ("DELETE FROM `mod_gallery` WHERE kid='".$kid."'");	
$query3 = mysql_query ("DELETE FROM `mod_gallery_kat` WHERE id='".$kid."'");	

if ($query2 or $query3){
$open['sukses'] = true;	
}else {
$open['sukses'] = false;
$open['pesanError'] = 'Data Gagal Di delete '.mysql_error();	
}
}
$j = new JSON_obj();
echo $j->encode($open);
break;	
	
	
		
case 'album':
if (isset($_GET['albums']) && is_numeric($_GET['albums'])){
$albums = $_GET['albums'];	

$query2 = mysql_query ("SELECT count(`id`) AS `total_files` FROM `mod_gallery` WHERE `kid`='$albums'");
$getdata2 = mysql_fetch_assoc($query2);
$jumlah = $getdata2['total_files'];

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
$ddl .= '<a onclick="gallery.bukagallery(\''.$v['link'].'\');" style="cursor:pointer" title="Page '.$v['title'].'">'.$v['title'].'</a> | ';
}
}
unset($pagging);
$pagging = substr($ddl,0,strlen($ddl)-3);
}else {
$pagging = null;	
}


$SQL_SORT = 'ORDER BY id DESC';
$arraysortvalue = array ('name','modified','size','view');
$arrayordervalue = array ('asc'=>'ASC','dsc'=>'DESC');
if (!empty($_GET['sort']) && in_array($_GET['sort'],$arraysortvalue)){
$ordersql = $arrayordervalue[$_GET['order']];
$ordersql = $ordersql != "" ? $ordersql : '';
$SQL_SORT = 'ORDER BY '.$_GET['sort'].' '.$ordersql;
$open['urlsorting'] = array("sort=".$_GET['sort']."&order=".$_GET['order']);
}





$query = mysql_query ("SELECT * FROM mod_gallery WHERE kid='$albums' $SQL_SORT LIMIT $offset,$limit");
//$open = array ();
while ($data = mysql_fetch_array ($query)){
	
	$files = strtolower(album_split($data['name']));
$open['gallery'][] = array ('files'=>$files, 'name' =>$data['name'], 'thumb'=> $data['name'], 'width'=>$data['width'], 'height'=>$data['height'], 'modified'=>$data['modified'], 'size'=>$data['size'], 'desc'=>addslashes($data['desc']), 'view'=>$data['view'], 'id'=>$data['id']);	
	
}


$mysql = mysql_query ("SELECT * FROM `mod_gallery_kat` WHERE `id`='$albums'");
$datasql = mysql_fetch_assoc($mysql);
$open['album'] = array ('name'=>'<a style="cursor:pointer" onclick="gallery.indexs(\'\')">Home</a> &raquo; '.$datasql['name'].'','albumID'=>$albums);
$open['config'] = $config_album;
if (!empty($pagging)){
$open['pagging'] = '<div class="border" style="text-align:center">'.$pagging.'</div>';
}else {
$open['pagging'] = '';	
}

$gallerysort = gallery_sort (gallery_url(),$_GET['order'],'gallery.bukagallery');

$open['sorting'] = array('url'=>$gallerysort);
$open['offset'] = array('integer'=>$offset);
$open['referer'] = referer_encode();
$open['config'] = $config_album;



$j = new JSON_obj();
echo $j->encode($open);
	
}

break;

case 'delete_items':
$open['sukses'] = false;
$open['pesanError'] = '';
if (!empty($_GET['id'])){
$id = rawurldecode($_GET['id']);	
$cc = explode (',',$id);
$count = count($cc);
if ($count >= 1){
for($i=0;$i<$count;$i++){
$q = mysql_query ("SELECT `name`,`kid` FROM `mod_gallery` WHERE `id` = '".$cc[$i]."'");
$data = mysql_fetch_assoc($q);
$file = $data['name'];
$kid = $data['kid'];
$unlink1 = @unlink('../'.$Tpath.$file);
$unlink2 = @unlink('../'.$Tnpath.$file);
$del = mysql_query ("DELETE FROM `mod_gallery` WHERE `id` = '".$cc[$i]."'");
$del = mysql_query ("UPDATE `mod_gallery_kat` SET `total` = total-1 WHERE `id` = '$kid'");	


}	
$open['sukses'] = true;
}	

$ref = parse_url(referer_decode($_GET['referer']));
$open['referer'] = $ref['query'];
$j = new JSON_obj();
echo $j->encode($open);	
}

break;
	
case 'listkategori':
$open['select'] = array();
$hasil2 = mysql_query("SELECT * FROM `mod_gallery_kat` ORDER BY name");
while($data = mysql_fetch_array($hasil2))
 {

$open['select'][] = array ('kategori'=>$data['name'],'id'=>$data['id'],'total'=>$data['total']);
}
$j = new JSON_obj();
echo $j->encode($open);	
break;	
  

default:

$query1 = mysql_query ("SELECT count(`id`) AS `total_album` FROM `mod_gallery_kat`");
$getdata1 = mysql_fetch_assoc($query1);
$jumlah = $getdata1['total_album'];

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
$ddl .= '<a onclick="gallery.indexs(\''.$v['link'].'\');" style="cursor:pointer" title="Page '.$v['title'].'">'.$v['title'].'</a> | ';
}
}
unset($pagging);
$pagging = substr($ddl,0,strlen($ddl)-3);
}else {
$pagging = null;	
}

$SQL_SORT = 'ORDER BY name';
$arraysortvalue = array ('name','total');
$arrayordervalue = array ('asc'=>'ASC','dsc'=>'DESC');
if (!empty($_GET['sort']) && in_array($_GET['sort'],$arraysortvalue)){
$ordersql = $arrayordervalue[$_GET['order']];
$ordersql = $ordersql != "" ? $ordersql : '';
$SQL_SORT = 'ORDER BY '.$_GET['sort'].' '.$ordersql;
}

$open['albums'] = array();
$query = mysql_query ("SELECT * FROM `mod_gallery_kat` $SQL_SORT LIMIT ".$offset.",$limit");
$nowtotalalbum = mysql_num_rows($query);
while ($data = mysql_fetch_array ($query)){
$open['albums'][] = array ('name' =>htmlentities(hapusquote($data['name'])), 'albums'=> $data['id'], 'images'=>$data['total'], 'desc'=>htmlentities(hapusquote($data['desc'])));	
}




	
	$merge_album['gallery'] = $open['albums'];
		
	
	$merge_album['config'] = $config_album;
	if (!empty($pagging)){
	$merge_album['pagging'] = '<div class="border" style="text-align:center">'.$pagging.'</div>';
	}else {
	$merge_album['pagging'] = '';	
	}
	
	
	$gallerysort = album_sort (gallery_url(),$order,'gallery.indexs');

$merge_album['sorting'] = array('url'=>$gallerysort);
$merge_album['referer'] = referer_encode();
	
$j = new JSON_obj();
echo $j->encode($merge_album);	
break; 
}

  
?>