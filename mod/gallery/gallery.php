<?
if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}
$style_include[] = '
<style type="text/css">
/*<![CDATA[*/
@import url( themes/'.$theme.'/css/gallery.css );
/*]]>*/
</style>
';

//$index_hal=1;

	

echo '<h4 class="bg">Album Photo</h4>';

echo <<<ajax
<div id="load" style="display: none; width: 100px; color: #fff;  height: 17px; background-color: red;position:absolute;top:50%;left:50%;padding:2px;"> Loading<span id="ellipsis">...</span></div>
<div id="respon"></div>
<div id="navtxt" class="navtext" style="visibility:hidden; position:absolute; top:0px; left:-400px; z-index:10000; padding:5px"></div>
<script language="javascript" type="text/javascript" src="mod/gallery/js/gallery.js"></script>
<script language="javascript" type="text/javascript" src="js/alttxt.js"></script>

<script type="text/javascript">
/*<![CDATA[*/
onloadfungsi = function (){
bukadata('');
onload();		
}
window.onload = onloadfungsi();
/*]]>*/
</script>
ajax;

?>