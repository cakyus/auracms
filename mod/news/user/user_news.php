<?
if (!defined('AURACMS_admin')) {
	Header("Location: ../index.php");
	exit;
}

if (!cek_login ()){
   $admin .='<p class="judul">Access Denied !!!!!!</p>';
   exit;
}


$index_hal = 1;


$admin = '<h4 class="bg">News Administration</h4>';

$style_include[] = <<<style
<style type="text/css">
@import url("mod/news/css/news.css");
</style>

style;

$JS_SCRIPT = <<<js
<!-- TinyMCE -->
<script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/init.js"></script>

<script type="text/javascript">
if (typeof tinyMCE == 'object') {
tinyMCE.init({
plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template"
});
}
</script>
<!-- /TinyMCE -->
js;
$script_include[] = $JS_SCRIPT;


$admin .= <<<ajax

<div id="load" style="display: none; width: 100px; color: #fff; height: 17px; background-color: red;position:absolute;top:50%;left:50%;padding:2px;"> <span id="loadmessage">Loading</span><span id="ellipsis">...</span></div>
<div id="navtxt" class="navtext" style="visibility:hidden; position:absolute; top:0px; left:-400px; z-index:10000; padding:5px"></div>

<div id="header_ajax"></div>
<div id="respon"></div>
<div id="responbawah"></div>
<script language="javascript" type="text/javascript" src="js/post.js"></script>
<script language="javascript" type="text/javascript" src="mod/news/js/header1.js"></script>
<script language="javascript" type="text/javascript" src="mod/news/js/admin_news.js"></script>


<script language="javascript" type="text/javascript">
onloadfungsi = function (){
news.indexs();	
};
window.onload = news.indexs;
</script>
<div id="editable" style="position:absolute;display:none;width:300px;"></div>

ajax;


echo $admin;
?>