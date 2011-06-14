<?
/**
 * 
 * auracms.org
 * June 13, 2007 09:51:10 AM 
 * Author:    Arif Supriyanto - arif@ayo.kliksini.com
 *            Iwan Susyanto, S.Si - admin@auracms.org
 *            Rumi Ridwan Sholeh - floodbost@yahoo.com
 *            http://www.auracms.org
 *            http://www.semarang.tk
 *            http://www.iwan.or.id
 *            http://www.auracms.opensource-indonesia.com
 *
 */

if (!defined('AURACMS_MODULE')) {
    Header("Location: ../index.php");
    exit;
}

//$index_hal=1;
$tengah ='<h4 class="bg">Guestbook</h4>';



$tengah .= <<<ajax
<div id="load" style="display: none; width: 100px; color: #fff;  height: 17px; background-color: red;position:absolute;top:50%;left:50%;padding:2px;"> <span id="loadmessage">Loading</span><span id="ellipsis">...</span></div>
<div id="headerbukutamu"></div>
<div id="respon"></div>

<script language="javascript" type="text/javascript" src="mod/guestbook/js/guestbook.js"></script>
<script language="javascript" type="text/javascript">
//<![CDATA[
window.onload = bukutamu;
//]]>
</script>
ajax;

echo $tengah;
?>

