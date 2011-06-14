<?
if (!defined('AURACMS_admin')) {
    Header("Location: ../index.php");
    exit;
}
if (!cek_login ()){
   /*warning("Access Denied!.... You Must Login First","index.php", 3, 2);*/
   exit;
}






$index_hal = 1;
$script_filename = dirname($_SERVER["SCRIPT_NAME"]) .'/mod/gallery/gallery_upload.php';
$admin ="<h4 class=\"bg\">Gallery PHoto</h4>";
$style_include[] = '
<style type="text/css">
/*<![CDATA[*/
@import url( themes/'.$theme.'/css/gallery.css );
/*]]>*/
</style>
<link rel="stylesheet" href="mod/gallery/fancy.css" type="text/css" />
';
$_GET['action'] = !isset($_GET['action']) ? null : $_GET['action'];
switch($_GET['action']){

default:		
$admin .= <<<ajax


<div id="load" style="display: none; width: 100px; color: #fff;  height: 17px; background-color: red;position:absolute;top:50%;left:50%;padding:2px;"> <span id="loadmessage">Loading</span><span id="ellipsis">...</span></div>
<div id="navtxt" class="navtext" style="visibility:hidden; position:absolute; top:0px; left:-400px; z-index:10000; padding:5px"></div>

<div id="headergallery"></div>
<div id="respon"></div>
<div id="responbawah"></div>
<script language="javascript" type="text/javascript" src="mod/gallery/fancy/js/mootools.v1.11.js"></script>
<script language="javascript" type="text/javascript" src="mod/gallery/fancy/js/FancyUpload2.js"></script>
<script language="javascript" type="text/javascript" src="mod/gallery/js/admin_gallery.js"></script>
<script language="javascript" type="text/javascript" src="js/alttxt.js"></script>

<script language="javascript" type="text/javascript">
/*<![CDATA[*/
var urluploaded = '$script_filename';
onloadfungsi = function (){
gallery.indexs('');
onload();		
}

window.onload = onloadfungsi();
/*]]>*/
</script>
<div id="editgallery" style="position:absolute;display:none;width:300px;"></div>

ajax;
break;


}








echo $admin;
?>