<?php

if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}

$tengah ='<h4 class="bg">Link Situs2 Penting</h4>';
$tengah.= '<table align="center" width="468" cellpadding="0" cellspacing="0" style=" height:62px; border:solid 1px #666666; font-size: 9pt; font-family: Arial; color:#F0F0F0; text-decoration:none; background:#09A7D7; padding: 3px;">
 <tr>
  <td rowspan="3" style=" font-size: 32px; "><span style="color:#FFF">f</span><span style="color:#EEF">l</span><span style="color:#DDF">y</span><span style="color:#FFF">.</span><span style="color:#CDF">c</span><span style="color:#BCE">o</span><span style="color:#FFF">.</span><span style="color:#ACE">u</span><span style="color:#ABE">k</span></td>
   <td colspan=2"" align="center" style="color:#CEF;font-weight:bold;">cheap flights from the uk to the whole world</td>

 </tr>
 <tr>
  <td><a  style="font-size:10px;color:#C0E0FF;text-decoration:none;" title="Flights to Colombo"  href="http://www.fly.co.uk/top-deals/lk/colombo.html">Flights to Colombo</a></td>
  <td><a   style="font-size:10px;color:#C0E0FF;text-decoration:none;" title="Johannesburg Flights"  href="http://www.fly.co.uk/top-deals/za/johannesburg.html">Johannesburg Flights</a></td>
 </tr>
 <tr>
  <td><a  style="font-size:10px;color:#C0E0FF;text-decoration:none;" title="Cheap Flights to Sardinia"  href="http://www.fly.co.uk/top-deals/it/sardinia.html">Cheap Flights to Sardinia</a></td>

  <td><a  style="font-size:10px;color:#C0E0FF;text-decoration:none;" title="Cheap Flights to Dubai"  href="http://www.fly.co.uk/top-deals/ae/dubai.html">Cheap Flights to Dubai</a></td>
 </tr>
</table>';
$tengah .= <<<ajax
<div id="load" style="display: none; width: 100px; color: #fff;  height: 17px; background-color: red;position:absolute;top:50%;left:50%;padding:2px;"> Loading<span id="ellipsis">...</span></div>
<div id="headerlink"></div>
<div id="respon"></div>

<script type="text/javascript" src="mod/links/js/link.js"></script>
<script type="text/javascript">
window.onload = weblink.links;
</script>
ajax;


echo $tengah;

?>