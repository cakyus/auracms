<?

$imgsecurity = '<img src="includes/code_image.php?random_num='.rand(1,100).'" alt="Case Sensitive"/>';

$content = <<<form
<form name="formshout" method="post" target="shout" action="mod/shoutbox/shoutact.php" name="shoutbox">
<table border="0" width="100%">
	<tr>
		<td width="5%">Nama*</td>
		<td width="1%">:</td>
		<td width="10%"><input onblur="nama.style.color='#6A8FB1'; this.className='inputblur'" onfocus="nama.style.color='#FB6101'; this.className='inputfocus'" type="text" name="nama" size="12" /></td>
	</tr>
	<tr>
		<td width="5%">Email*</td>
		<td width="1%">:</td>
		<td width="10%"><input onblur="email.style.color='#6A8FB1'; this.className='inputblur'" onfocus="email.style.color='#FB6101'; this.className='inputfocus'" type="text" name="email" size="12" /></td>
	</tr>
	<tr>
		<td colspan=3>Pesan*<br /><textarea onblur="yousay.style.color='#6A8FB1'; this.className='inputblur'" onfocus="yousay.style.color='#FB6101'; this.className='inputfocus'" rows="2" cols="20" name="yousay" style="overflow-y:auto;" id="tofield"></textarea></td>
	</tr>
	<tr>
		<td colspan=3><span id="shoutboxcaptcha">$imgsecurity</span></td>
	</tr>
	<tr>
		<td colspan=3>keykodes*<br />
		<input onblur="keykodes.style.color='#6A8FB1'; this.className='inputblur'" onfocus="keykodes.style.color='#FB6101'; this.className='inputfocus'" type="text" name="keykodes" size="12" /></td>
	</tr>
	<tr>
		<td><input type="button" value="Kirim" onclick="cleartext();"></td>
		<td width="1%">&nbsp;</td>
		<td width="10%">&nbsp;</td>
	</tr>
</table>	  
</form>
form;


echo $content; 


?>