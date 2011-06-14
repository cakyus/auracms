<?

if (!defined('AURACMS_CONTENT')) {
	Header("Location: ../index.php");
	exit;
}

if (!cek_login ()){
        $tengah.='<table width="100%" border="0" cellspacing="0" cellpadding="0" class="middle"><tr><td><table width="100%" class="bodyline"><tr><td align="left"><img src="images/warning.gif" border="0"></td><td align="center"><font class="option">You Must Login First If Not Yet Have Account Please Register</font></td><td align="right"><img src="images/warning.gif" border="0"></td></tr></table></td></tr></table>';
	$tengah.='<meta http-equiv="refresh" content="3; url=?pilih=user&aksi=register">';
}else{

if (session_is_registered ('LevelAkses') &&  $_SESSION['LevelAkses']=="Editor"){

if($editor ==1){
$admin .= <<<IWANSUSYANTOCAKEP
<!-- tinyMCE -->
<script language="javascript" type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "simple"
	});
</script>
<!-- /tinyMCE -->
IWANSUSYANTOCAKEP;
}


if($_GET['aksi']==""){
global $koneksi_db,$translateKal,$theme;

echo "<p class=judul>Artikel Masuk dan belum divalidasi</p>";
$perintah="SELECT * FROM artikel WHERE publikasi=0 ORDER BY id DESC";
$hasil = $koneksi_db->sql_query( "SELECT * FROM artikel WHERE publikasi=0 ORDER BY id DESC" );
$jumlah = $koneksi_db->sql_numrows( $hasil );

if ($jumlah<1){
	$admin .= "<b> Tidak Ada Artikel Masuk</b>";
}


$warna="class=formulir";
$admin .='<table width="100%" cellspacing="0" cellpadding="4">';
while ($data = mysql_fetch_row($hasil)) {
		$wcell = (!$wcell)?"$warna":'';
		$admin .='<tr '.$wcell.'><td><p><b>'.asli($data[1]).'</b><br>';
		$tgl=explode(",",$data[5]);
		$tgl[0]=strtr($tgl[0],$translateKal);
		$tgl[2]=strtr($tgl[2],$translateKal);
		$data_tgl="$tgl[0], $tgl[1] $tgl[2] $tgl[3]";
		$admin .='<span>'.$data_tgl.' - by : <a href="mailto:'.$data[4].'">'.$data[3].'</a></span></p></td>';
		$admin .='<td><a href="?pilih=editor&aksi=del&id='.$data[0].'">delete</a> - <a href="?pilih=editor&aksi=edit&id='.$data[0].'">edit</a> - ';
		$admin .='<a href="?pilih=editor&aksi=approv&id='.$data[0].'">approve</a></td></tr>';

	}
$admin .='</table>';
}


if($_GET['aksi']=="approv"){
global $koneksi_db,$judul_situs,$PHP_SELF;

    $id     = int_filter($_GET['id']);
    $sekarang=date("D,d,M,y");
	$skrg=explode(",",$sekarang);
	$skrg[0]=strtr($skrg[0],$translateKal);
	$skrg[2]=strtr($skrg[2],$translateKal);
	$tgl="$skrg[0], $skrg[1] $skrg[2] $skrg[3]";
    $hasil = $koneksi_db->sql_query("UPDATE artikel SET publikasi='1',tgl='$tgl' WHERE id='$id'");
    if($hasil){
    $tengah.='<table width="100%" border="0" cellspacing="0" cellpadding="0" class="middle"><tr><td><table width="100%" class="bodyline"><tr><td align="left"><img src="images/info.gif" border="0"></td><td align="center"><font class="option"><b><br />Artikel telah di posting. <br /></font></td><td align="right"><img src="images/info.gif" border="0"></td></tr></table></td></tr></table>';
    $tengah.='<meta http-equiv="refresh" content="3; url=?pilih=editor">';
    }

}

if($_GET['aksi']=="edit"){

$index_hal=1;
global $koneksi_db,$translateKal,$theme;
$id     = int_filter($_GET['id']);

if($_POST['submit']){


    $konten = $_POST['konten'];
	$topik  = $_POST['topik'];
	$judul = text_filter($_POST['judul']);

    if (!$konten) $error .= "Error: Please enter a message!<br />";
    if (!$judul) $error .= "Error: Please enter a Title!<br />";
    if (!$topik) $error .= "Error: Please enter a Topic!<br />";

    $sekarang=date("D,d,M,y");
	$skrg=explode(",",$sekarang);
	$skrg[0]=strtr($skrg[0],$translateKal);
	$skrg[2]=strtr($skrg[2],$translateKal);
	$tgl="$skrg[0], $skrg[1] $skrg[2] $skrg[3]";

    if ($error){
        $admin .='<table width="100%" border="0" cellspacing="0" cellpadding="0" class="middle"><tr><td><table width="100%" class="bodyline"><tr><td align="left"><img src="images/warning.gif" border="0"></td><td align="center"><font class="option">'.$error.'</font></td><td align="right"><img src="images/warning.gif" border="0"></td></tr></table></td></tr></table>';
        $tengah.='<meta http-equiv="refresh" content="3; url=">';
    }else{
	    $hasil = $koneksi_db->sql_query( "UPDATE artikel SET judul='$judul', konten='$konten', publikasi=1,tgl='$tgl', topik='$topik' WHERE id='$id'" );
        if($hasil){
            $tengah.='<table width="100%" border="0" cellspacing="0" cellpadding="0" class="middle"><tr><td><table width="100%" class="bodyline"><tr><td align="left"><img src="images/info.gif" border="0"></td><td align="center"><font class="option"><b><br />Artikel telah di posting. <br /></font></td><td align="right"><img src="images/info.gif" border="0"></td></tr></table></td></tr></table>';
            $tengah.='<meta http-equiv="refresh" content="3; url=?pilih=editor">';
        }
    }
}else{

$hasil = $koneksi_db->sql_query( "SELECT * FROM artikel WHERE id=$id ORDER BY id DESC" );
while ($data = $koneksi_db->sql_fetchrow($hasil)) {
	$id=$data[0];
	$judul=$data[1];
	$konten=$data[2];
	$user=$data[3];
	$email=$data[4];
	$tgl=$data[5];
	$topik=$data[7];
	}
$admin .='<p class="judul">Edit Artikel Masuk</p><br />';
$admin .="
<form method=\"POST\" action=\"\">

<table border=\"0\"  cellpadding=\"3\" cellspacing=\"0\" align=\"center\">
  <tr>
    <td valign=\"top\">Sender Name</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\">".$user."</td>
  </tr>
  <tr>
    <td valign=\"top\">Sender Email</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\">".$email."</td>
  </tr>
  <tr>
    <td valign=\"top\">Pilih Topik</td>
    <td valign=\"top\"></td>
    <td valign=\"top\">
    <select name=\"topik\">";


    $hasil = $koneksi_db->sql_query( "SELECT * FROM topik ORDER BY id" );
    while ($data = $koneksi_db->sql_fetchrow($hasil)) {
    $pilihan = ($data[0]==$topik)?"selected":'';
    $admin .= "<option value=$data[0] $pilihan>$data[1]";

    }

$admin .="
    </select>
    </td>
  </tr>
  <tr>
    <td valign=\"top\">Title</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\"><input type=\"text\" name=\"judul\" style=\"width:300px\" size=\"50\" value=\"judul\"></td>
  </tr>
  <tr>
    <td valign=\"top\">Message</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\"><textarea rows=\"20\" name=\"konten\"  cols=\"60\">".gb1($konten)."</textarea></td>
  </tr>
  <tr>
    <td valign=\"top\"></td>
    <td valign=\"top\"></td>
    <td valign=\"top\"></td>
  </tr>
  <tr>
    <td valign=\"top\"></td>
    <td valign=\"top\"></td>
    <td valign=\"top\"><input type=\"submit\" name=\"submit\" value=\"Submit\"></td>
  </tr>
</table>
</form>";

}

}

if($_GET['aksi']=="del"){
	global $koneksi_db;
    $id     = int_filter($_GET['id']);
    $hasil = $koneksi_db->sql_query("DELETE FROM artikel WHERE id='$id'");
    if($hasil){
    $tengah.='<table width="100%" border="0" cellspacing="0" cellpadding="0" class="middle"><tr><td><table width="100%" class="bodyline"><tr><td align="left"><img src="images/info.gif" border="0"></td><td align="center"><font class="option"><b><br />Artikel telah di delete!  <br /></font></td><td align="right"><img src="images/info.gif" border="0"></td></tr></table></td></tr></table>';
    $tengah.='<meta http-equiv="refresh" content="3; url=?pilih=editor">';
    }

}

}else{
        $tengah.='<table width="100%" border="0" cellspacing="0" cellpadding="0" class="middle"><tr><td><table width="100%" class="bodyline"><tr><td align="left"><img src="images/warning.gif" border="0"></td><td align="center"><font class="option">Access Denied!.... Your Level Not Much For Access This File</font></td><td align="right"><img src="images/warning.gif" border="0"></td></tr></table></td></tr></table>';
	    $tengah.='<meta http-equiv="refresh" content="3; url=">';
}

echo $admin;
}
?>