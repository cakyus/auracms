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



if (!isset($_SESSION['mod_ajax'])){
exit;	
}

$_GET['sort'] = !isset($_GET['sort']) ? null : $_GET['sort'];


$config_album = array('pptabel'=>$pptabel,'limit'=>$limit,'tpath'=>$tpath,'tnpath'=>$tnpath);

if (isset ($_GET['order'])) $order = $_GET['order']; else $order = NULL;
if (isset ($_GET['sort'])) $sort = $_GET['sort']; else $sort = NULL;



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
	
case 'album':
if (isset($_GET['albums']) && is_numeric($_GET['albums'])){
$albums = int_filter($_GET['albums']);	

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
$ddl .= '<a onclick="bukagallery(\''.$v['link'].'\');" style="cursor:pointer" title="Page '.$v['title'].'">'.$v['title'].'</a> | ';
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





$query = mysql_query ("SELECT * FROM `mod_gallery` WHERE kid='$albums' $SQL_SORT LIMIT $offset,$limit");
//$open = array ();
while ($data = mysql_fetch_array ($query)){
	
	$files = strtolower(album_split($data['name']));
$open['gallery'][] = array ('files'=>$files, 'name' =>$data['name'], 'thumb'=> $data['name'], 'width'=>$data['width'], 'height'=>$data['height'], 'modified'=>$data['modified'], 'size'=>$data['size'], 'desc'=>addslashes($data['desc']), 'view'=>$data['view'], 'id'=>$data['id']);	
	
}


$mysql = mysql_query ("SELECT * FROM `mod_gallery_kat` WHERE `id`='$albums'");
$datasql = mysql_fetch_assoc($mysql);
$open['album'] = array ('name'=>'<a style="cursor:pointer" onclick="bukadata(\'\')">Home</a> &raquo; '.$datasql['name'].'','albumID'=>$albums);
$open['config'] = $config_album;


if (!empty($pagging)){
$open['pagging'] = '<div class="border" style="text-align:center">'.$pagging.'</div>';
}else {
$open['pagging'] = '';	
}

$gallerysort = gallery_sort (gallery_url(),@$_GET['order'],'bukagallery');

$open['sorting'] = array('url'=>$gallerysort);
$open['offset'] = array('integer'=>$offset);




$j = new JSON_obj();
echo $j->encode($open);
	
}

break;
	
	
		
case 'detail':
if (isset($_GET['id'])){
$id = int_filter($_GET['id']);
$albums = int_filter($_GET['albums']);
$SQL_SORT = 'ORDER BY id DESC';
$arraysortvalue = array ('name','modified','size','view');
$arrayordervalue = array ('asc'=>'ASC','dsc'=>'DESC');
if (!empty($_GET['sort']) && in_array($_GET['sort'],$arraysortvalue)){
$ordersql = $arrayordervalue[$_GET['order']];
$ordersql = $ordersql != "" ? $ordersql : '';
$SQL_SORT = 'ORDER BY '.$_GET['sort'].' '.$ordersql;
$open['urlsorting'] = array("sort=".$_GET['sort']."&order=".$_GET['order']);

}

$limitimage = 3;
$image = int_filter($_GET['image']);
$image = empty($image) ? 0 : $image;
if ($image <= 0){
$offsetimage = 0;
$limitimage = 2;
}else {
$image = $image - 1;	
}


$query = mysql_query ("SELECT * FROM `mod_gallery`  WHERE `kid` = '$albums' $SQL_SORT LIMIT $image,$limitimage");
$i = 0;
while ($data = mysql_fetch_assoc($query)){
$files = strtolower(album_split($data['name']));
$dimensi = cekimage($data['width'],$data['height'],400,400);
$open['gallery'][$i] = array ('files'=>$files, 'name' =>$data['name'], 'thumb'=> $data['name'], 'width'=>$data['width'], 'height'=>$data['height'], 'modified'=>$data['modified'], 'size'=>$data['size'], 'desc'=>$data['desc'], 'view'=>$data['view'], 'id'=>$data['id'],'w'=>$dimensi[0],'h'=>$dimensi[1]);	
if ($id == $data['id']){
$nowimage = $i;
}	
$i++;	
}

$nowimage = !isset($nowimage) ? 1 : $nowimage;

//print_r($open);exit;
//fungsi untuk biar ga acak2an kl di view berdaasarkan view
if ($_GET['sort'] != 'view'){
$update = mysql_query ("UPDATE `mod_gallery` SET view=view+1 WHERE `id`='$id'");
}


$query1 = mysql_query ("SELECT * FROM `mod_gallery` WHERE `kid` = '$albums'");
$getdata1 = mysql_fetch_assoc($query1);
$jumlah = mysql_num_rows($query1);

unset($getdata1);
$query = mysql_query ("SELECT * FROM `mod_gallery_kat` WHERE `id` = '$albums'");
$getdata1 = mysql_fetch_assoc($query);
$open['boxheader'] = array ('<a style="cursor:pointer" onclick="bukadata(\'\')">Home</a> &raquo; <a style="cursor:pointer" onclick="bukagallery(\'action=album&albums='.$getdata1['id'].'\')">'.$getdata1['name'].'</a>');

$open['total'] = array ($jumlah);
$open['now'] = array ($nowimage);
$open['image'] = array ($_GET['image']);
$open['albums'] = array ($albums);
$open['config'] = $config_album;

$j = new JSON_obj();
echo $j->encode($open);
}
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
$ddl .= '<a onclick="bukadata(\''.$v['link'].'\');" style="cursor:pointer" title="Page '.$v['title'].'">'.$v['title'].'</a> | ';
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

$open['albums'] = array ();
$query = mysql_query ("SELECT * FROM `mod_gallery_kat` $SQL_SORT LIMIT ".$offset.",$limit");
$nowtotalalbum = mysql_num_rows($query);
while ($data = mysql_fetch_array ($query)){
$open['albums'][] = array ('name' =>$data['name'], 'albums'=> $data['id'], 'images'=>$data['total'], 'desc'=>addslashes($data['desc']));	
}




	
	$merge_album['gallery'] = $open['albums'];
		
	
	$merge_album['config'] = $config_album;
	if (!empty($pagging)){
	$merge_album['pagging'] = '<div class="border" style="text-align:center">'.$pagging.'</div>';
	}else {
	$merge_album['pagging'] = '';	
	}
	
	
	$gallerysort = album_sort (gallery_url(),$order,'bukadata');

$merge_album['sorting'] = array('url'=>$gallerysort);
	
$j = new JSON_obj();
echo $j->encode($merge_album);	
break; 
}

  
?>