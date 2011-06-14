<?php

if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}

ob_start();


	$query = "SELECT * FROM polling WHERE public='ya'";

	
	
	$hasil = @mysql_query($query);
	$data = @mysql_fetch_array($hasil);
	$PID = $data["pid"];
	$PJUDUL = $data["pjudul"];
	$PPILIHAN = explode("#", $data["ppilihan"]);
	$jmlpil = count($PPILIHAN);
	
	if (@mysql_num_rows($hasil) > 0){
@mysql_free_result ($hasil);
	echo  "$PJUDUL<br />";
	echo  '<form method="post" action="index.php?pilih=polling&amp;mod=yes&amp;act=polling_result" name="form">';
	echo  "<table border='0' cellpadding='0' cellspacing='2' width='100%'>";
	echo  "<tr><td width='2%' valign='middle'><input type=\"radio\" name=\"pilihan\" value=\"0\" id=\"pil0\" checked=\"checked\" style=\"border:0;\" /></td><td width=\"98%\"  valign='top'><label for=\"pil0\" style=\"cursor:pointer;font-weight:normal\" onmouseover=\"this.style.textDecoration = 'underline'\" onmouseout=\"this.style.textDecoration = 'none'\"> $PPILIHAN[0]</label></td></tr>\n";
	for($i=1;$i<$jmlpil;$i++)
	{
		echo  "<tr><td width='2%' valign='middle'><input type=\"radio\" name=\"pilihan\" value=\"$i\" id=\"pil$i\" style=\"border:0;\" /></td><td width=\"98%\"><label for=\"pil$i\" style=\"cursor:pointer;font-weight:normal\" onmouseover=\"this.style.textDecoration = 'underline'\" onmouseout=\"this.style.textDecoration = 'none'\" >$PPILIHAN[$i]</label></td></tr>\n";
	}
	echo  "</table>";
	echo  "<input type=\"hidden\" name=\"pid\" value=\"$PID\" />";
	echo  "<div align='center'>";
	echo  "<br />";
	echo  "<input type='submit' name='submit' value='Vote' class='button' />&nbsp;";
	echo  "<input type='submit' name='result' value='Hasil' class='button' />";
	echo  "</div>";
	echo  "</form>";
}else {
echo  "<br />Tidak ada polling yang dipublikasikan<br />";	
}
$out = ob_get_contents();
ob_end_clean();	
?>