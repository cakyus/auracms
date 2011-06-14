<?
if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}


//shoutbox
$shoutbox = <<<shout
<table style="width:100%"><tr><td>
<iframe src="mod/shoutbox/shoutbox.php" width="100%" height="150" frameborder="0" marginwidth="0" name="shout" marginheight="0">
Your browser does not support inline frames or 
is currently configured not to display inline frames.
</iframe>
</td></tr></table>
<script language="Javascript" type="text/javascript">
//<![CDATA[
function loadimagechaptchashoutbox() {
document.getElementById('shoutboxcaptcha').innerHTML = '<img src="includes/code_image.php?rand='+Math.random()+'" alt="Case Sensitive"/>';	
}
function cleartext() {
document.formshout.submit();
document.formshout.nama.value='';
document.formshout.email.value='';
document.formshout.yousay.value='';
setTimeout("loadimagechaptchashoutbox()",3000);
}
//]]>

</script>
<script language="javascript" type="text/javascript">
//<![CDATA[
var xmlhttps = false;
try {
xmlhttps = new ActiveXObject("Msxml2.XMLHTTP");
} catch (e) {
try {
xmlhttps = new ActiveXObject("Microsoft.XMLHTTP");
} catch (E) {
xmlhttps = false;
}
}
if (!xmlhttps && typeof XMLHttpRequest != 'undefined') {
xmlhttps = new XMLHttpRequest();
}
function makerequest_shout(serverPage, objID) {
	document.getElementById(objID).innerHTML = '<img src="images/indicator.gif" align="absmiddle" alt=""/> Loading Data..';
var obj = document.getElementById(objID);
serverPage += '?'+Math.random();
xmlhttps.open("get", serverPage,true);
xmlhttps.onreadystatechange = function() {
if (xmlhttps.readyState == 4 && xmlhttps.status == 200) {
obj.innerHTML = xmlhttps.responseText;
}
}
xmlhttps.send(null);
}
//]]>
</script>
<div id="id_shoutbox">
&raquo; <a href="javascript:makerequest_shout('mod/shoutbox/shoutform.php','id_shoutbox');">Isi Shoutbox</a>
</div>
shout;

ob_start();
echo $shoutbox;
$out = ob_get_contents();
ob_end_clean();
?>