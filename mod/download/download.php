<?php

if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}

global $script_include,$style_include;

$script_include[] = <<<EOF
<script type="text/javascript" language="javascript" src="mod/download/ajaxstarrater_v122/js/behavior.js"></script>
<script type="text/javascript" language="javascript" src="mod/download/ajaxstarrater_v122/js/rating.js"></script>

EOF;
$style_include[] = '<link rel="stylesheet" type="text/css" href="mod/download/ajaxstarrater_v122/css/rating.css" />';
$tengah ='<h4 class="bg">Download</h4>';
$tengah .='<table align="center" width="468" cellpadding="0" cellspacing="0" style=" height:62px; border:solid 1px #666666; font-size: 9pt; font-family: Arial; color:#F0F0F0; text-decoration:none; background:#09A7D7; padding: 3px;">
 <tr>
  <td rowspan="3" style=" font-size: 32px; "><span style="color:#FFF">f</span><span style="color:#EEF">l</span><span style="color:#DDF">y</span><span style="color:#FFF">.</span><span style="color:#CDF">c</span><span style="color:#BCE">o</span><span style="color:#FFF">.</span><span style="color:#ACE">u</span><span style="color:#ABE">k</span></td>
   <td colspan=2"" align="center" style="color:#CEF;font-weight:bold;">cheap flights from the uk to the whole world</td>

 </tr>
 <tr>
  <td><a  style="font-size:10px;color:#C0E0FF;text-decoration:none;" title="Click Here"  href="http://www.fly.co.uk/top-deals/au/sydney.html">Click Here</a></td>
  <td><a   style="font-size:10px;color:#C0E0FF;text-decoration:none;" title="Flights to Dubai"  href="http://www.fly.co.uk/top-deals/ae/dubai.html">Flights to Dubai</a></td>
 </tr>
 <tr>
  <td><a  style="font-size:10px;color:#C0E0FF;text-decoration:none;" title="Cheap Flight"  href="http://www.fly.co.uk/top-deals/qa/qatar.html">Cheap Flight</a></td>

  <td><a  style="font-size:10px;color:#C0E0FF;text-decoration:none;" title="Fare Search"  href="http://www.fly.co.uk/top-deals/de/frankfurt.html">Fare Search</a></td>
 </tr>
</table>';
$tengah .= <<<ajax


<div id="load" style="display: none; width: 100px; color: #fff;  height: 17px; background-color: red;position:absolute;top:50%;left:50%;padding:2px;"> Loading<span id="ellipsis">...</span></div>
<div id="headerdownload"></div>
<div id="respon"></div>

<script type="text/javascript" src="mod/download/js/download.js"></script>
<script type="text/javascript">
window.onload = download.indexs;
</script>
ajax;


echo $tengah;

?>