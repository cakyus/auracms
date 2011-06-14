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

$admin ="<h4 class=\"bg\">Buku Tamu Manager</h4>";

$_GET['action'] = !isset($_GET['action']) ? null : $_GET['action'];
switch($_GET['action']){

default:		
$admin .= <<<ajax

<div id="load" style="display: none; width: 100px; color: #fff;  height: 17px; background-color: red;position:absolute;top:50%;left:50%;padding:2px;"> <span id="loadmessage">Loading</span><span id="ellipsis">...</span></div>
<div id="navtxt" class="navtext" style="visibility:hidden; position:absolute; top:0px; left:-400px; z-index:10000; padding:5px"></div>

<div id="headerajax"></div>
<div id="respon"></div>
<script language="javascript" type="text/javascript" src="js/post.js"></script>
<script language="javascript" type="text/javascript" src="mod/guestbook/js/guestbook_admin.js"></script>
<script language="javascript" type="text/javascript">
//<![CDATA[
window.onload = guestbook.indexs;
//]]>
</script>
<div id="editable" style="position:absolute;display:none;width:300px;"></div>

ajax;
break;


}








echo $admin;
?>