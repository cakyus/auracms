<?

/**
 * Modul News for AuraCMS v2.0
 * auracms.org
 * 11 December 2006
 * Author:     Arif Supriyanto - arif@ayo.kliksini.com
 *             Iwan Susyanto, S.Si - admin@auracms.org
 *             Rumi Ridwan Sholeh - floodbost@yahoo.com
 *             http://www.auracms.org
 *             http://www.iwan.or.id
 *             http://www.ridwan.or.id
 */

if (!defined('AURACMS_MODULE')) {
    Header("Location: ../index.php");
    exit;
}


$ikon_2 = '';
$ikon_3 = '';

//$index_hal=1;

$_GET['aksi'] = !isset($_GET['aksi']) ? null : $_GET['aksi'];
$_GET['id'] = !isset($_GET['id']) ? null : int_filter($_GET['id']);

$tengah = '';

if($_GET['aksi']==""){
        $tengah.='<div class="border"><table width="100%"><tr><td align="left"><img src="images/warning.gif" border="0"></td><td align="center"><font class="option">Harus Pake Aksi Bung.... Jangan Asal Ketik Ya!</font></td><td align="right"><img src="images/warning.gif" border="0"></td></tr></table></div>';
        $tengah .='<meta http-equiv="refresh" content="3; url=">';
}

if($_GET['aksi']=="lihat"){

$id = int_filter($_GET['id']);

$hasil  = $koneksi_db->sql_query("SELECT * FROM artikel WHERE id='$id' AND publikasi=1");

$data = $koneksi_db->sql_fetchrow($hasil);

$judulnya = $data['judul'];
$topik = $data['topik'];
$gambar = ($data['gambar'] == '') ? '' : '<img src="'.$data['gambar'].'" border="0" alt="'.$data['judul'].'" style="margin-right:5px; margin-top:5px; padding:3px; float:left;" />';
$hits = $data['hits'];

//title 
$judul_situs = $data['judul'];
$_META['description'] = limittxt(htmlentities(strip_tags($data['konten'])),200);
$_META['keywords'] = empty($data['tags']) ? implode(',',explode(' ',htmlentities(strip_tags($data['judul'])))) : $data['tags'];

if (empty ($judulnya)){
        $tengah.='<div class="error">Access Denied<br /><br />Regard<br /><br />Iwan Susyanto,S.Si<br />iwansusyanto@yahoo.com</font></div>';

          $tengah .='<meta http-equiv="refresh" content="3; url=index.php">';
}else {


$hits = $hits +1;
$updatehits = $koneksi_db->sql_query("UPDATE artikel SET hits='$hits' WHERE id='$id'");

$titlenya = "<span class=\"judul\">$data[judul]</span><br />";

$data[5]=$data['tgl'];
$ket= "<span>$data[5]</span>";
$by = 'Oleh : '.$data['user'].'';
$isinya = '';

$isinya .= $gambar."".gb1($data[konten]);
$isinya .= "<p align=\"right\"><a href=\"recommend-$data[id]-".AuraCMSSEO($data['judul']).".html\">$ikon_2 kirim ke teman</a> | <a href=\"cetak-$data[id]-".AuraCMSSEO($data['judul']).".html\">$ikon_3 versi cetak</a> | Versi PDF</p>";


themenews($id, $titlenya, $ket, $isinya, datetimes($data['tgl']).' - oleh : ' . $data['user']);

$tengah .= '
<div class="border"><div class="sexy-bookmarks">
<ul class="socials">
<li class="sexy-delicious"><a href="http://del.icio.us/post?url=article-'.$data['id'].'-'.AuraCMSSEO($data['judul']).'.html&amp;&title='.$data['judul'].'" target="_blank"></a></li>
<li class="sexy-digg"><a href=" http://digg.com/submit?url=article-'.$data['id'].'-'.AuraCMSSEO($data['judul']).'.html&amp;&title='.$data['judul'].'" target="_blank"></a></li>
<li class="sexy-technorati"><a href=" http://technorati.com/faves?add=article-'.$data['id'].'-'.AuraCMSSEO($data['judul']).'.html&amp;&title='.$data['judul'].'" target="_blank"></a></li>
<li class="sexy-reddit"><a href=" http://www.reddit.com/submit?url=article-'.$data['id'].'-'.AuraCMSSEO($data['judul']).'.html&amp;&title='.$data['judul'].'" target="_blank"></a></li>
<li class="sexy-designfloat"><a href="http://www.designfloat.com/submit.php?url=article-'.$data['id'].'-'.AuraCMSSEO($data['judul']).'.html&amp;&title='.$data['judul'].'" target="_blank"></a></li>
<li class="sexy-facebook"><a href=" http://www.facebook.com/sharer.php?u=article-'.$data['id'].'-'.AuraCMSSEO($data['judul']).'.html&amp;&title='.$data['judul'].'" target="_blank"></a></li>
<li class="sexy-twitter"><a href=" http://twitthis.com/twit?url=article-'.$data['id'].'-'.AuraCMSSEO($data['judul']).'.html&amp;&title='.$data['judul'].'" target="_blank"></a></li>
<li class="sexy-furl"><a href=" http://www.furl.net/storeIt.jsp?u=article-'.$data['id'].'-'.AuraCMSSEO($data['judul']).'.html&amp;&title='.$data['judul'].'" target="_blank"></a></li>
<li class="sexy-syndicate"><a href="rss.php" title="Subscribe to RSS"></a></li>
</ul>
</div></div>';

//Artikel Terkait

$query = $koneksi_db->sql_query( "SELECT * FROM topik WHERE id='$topik'" );
//$jumlah = $koneksi_db->sql_numrows($query);
while ($data1 = $koneksi_db->sql_fetchrow($query)) {
    $rubrik=$data1[1];
}
$hitungjumlah = $koneksi_db->sql_query( "SELECT id FROM artikel WHERE id!='$id' AND publikasi=1 and topik='$topik'");
$jumlah = $koneksi_db->sql_numrows($hitungjumlah);
//if($jumlah>1){

$tengah .='<h4 class="bg">Berita "'.$rubrik.'" Lainnya</h4>';

$tengah .='<div class="border">';
$query2 = $koneksi_db->sql_query( "SELECT id, judul FROM artikel WHERE id!='$id' AND publikasi=1 and topik=$topik ORDER BY tgl ASC LIMIT 5" );
$tengah .= "<table cellspacing=\"4\" cellpadding=\"4\" class=\"tambahan\" width=\"100%\">";
while ($data = $koneksi_db->sql_fetchrow($query2)) {
$id2    = $data[0];
$judul2    = $data[1];
$tengah .= "<tr><td>";
$tengah .= "<img src=\"images/1.gif\" border=\"0\" alt=\"ul\" /><a href=\"article-$id2-".AuraCMSSEO($judul2).".html\">$judul2</a>";
$tengah .= "</td></tr>";
}
$tengah .= "</table>";
$tengah .='</div>';
//}
//End Artikel Terkait




$tengah .= '<div id="load" style="display: none; width: 100px; color: #fff;  height: 17px; background-color: red;position:absolute;top:50%;left:50%;padding:2px;"> <span id="loadmessage">Loading</span><span id="ellipsis">...</span></div>';



$tengah .= '<div id="header_ajax"></div>
<div id="respon"></div>
<div id="responbawah"></div>
<script type="text/javascript">
//<![CDATA[
var ID = '.int_filter($_GET['id']).';
//]]>
</script>
<script language="javascript" src="mod/news/js/komentar.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
//<![CDATA[
onloadfungsi = function (){
komentar.indexs('.int_filter($_GET['id']).');	
};
window.onload = onloadfungsi;
//]]>
</script>
';






} //end if empty



} //end fuction news

if($_GET['aksi']=="arsip"){

$topik    = int_filter($_GET['topik']);

if (file_exists("images/5.gif")){
    $ikon_1 = "<img src=/images/5.gif border=0>&nbsp;";
}
if (file_exists("images/6.gif")){
    $ikon_2 = "<img src=/images/6.gif border=0>&nbsp;";
}
if (file_exists("images/7.gif")){
    $ikon_3 = "<img src=images/7.gif border=0>&nbsp;";
}

$hasil = $koneksi_db->sql_query( "SELECT * FROM topik WHERE id=$topik" );
$data = $koneksi_db->sql_fetchrow($hasil);
$rubrik = $data['topik'];
$tengah .='<h4 class="bg">Rubrik : '.$rubrik.'</h4>';

if (empty ($rubrik)){
 $tengah.='<div class="error">Access Denied</div>';
        $tengah .='<meta http-equiv="refresh" content="3; url=/index.php">';
}else {

$limit = 10;
$offset = int_filter(@$_GET['offset']);
$pg        = int_filter(@$_GET['pg']);
$stg    = int_filter(@$_GET['stg']);

$totals = $koneksi_db->sql_query( "SELECT id FROM artikel WHERE publikasi=1 AND topik=$topik" );
$jumlah = $koneksi_db->sql_numrows( $totals );
$a = new paging ($limit);
if ($jumlah>0 ){

$hasil = $koneksi_db->sql_query( "SELECT * FROM artikel WHERE publikasi=1 AND topik=$topik ORDER BY id DESC LIMIT $offset, $limit" );

while ($data = $koneksi_db->sql_fetchrow($hasil)) {

$data[5]= datetimes($data['tgl']);

$tengah .='
<h4 class="bg">'.$data['1'].'</h4>
<div class="news">
<span class="align-justify">'.limitTXT(strip_tags($data[2]),250).'</span>
</div>		
			
<p class="post-footer">					
<a href="article-'.$data[0].'-'.AuraCMSSEO($data[1]).'.html" title="'.$data[1].'" class="readmore">Read more</a>
<span class="comments">By '.$data[3].'</span>
<span class="date">'.$data[5].'</span>	
</p>';

} //end while


if($jumlah>10){
$tengah .='<div class="border">';
$tengah.="<center>";
if (empty($_GET['offset']) and !isset ($_GET['offset'])) {
$offset = 0;

}

if (empty($_GET['pg']) and !isset ($_GET['pg'])) {
$pg = 1;
}

if (empty($_GET['stg']) and !isset ($_GET['stg'])) {
$stg = 1;
}
$tengah.= $a-> getPaging($jumlah, $pg, $stg);
$tengah.="</center>";
$tengah .='</div>';
}

} else{
        $tengah.='<div class="error">Artikel Pada Topik Ini Kosong Or Tidak Ada Topik dengan TopikID = '.$topik.'!</div>';
        $style_include[] ='<meta http-equiv="refresh" content="3; url=index.php" />';
}

} //end if kosong

} //end function arsip

if($_GET['aksi']=="pesan"){

$id = int_filter($_GET['id']);
if (!$_GET['op']) {
    $perintah="SELECT user,email FROM artikel WHERE id='$id' AND publikasi=1";
} else {
    $perintah="SELECT user,email FROM komentar WHERE id='$id'";
}

$hasil = $koneksi_db->sql_query( $perintah );
while ($data = mysql_fetch_array($hasil)) {
$kontributor = $data['email'];
$nama_kon = $data['user'];
}

$tengah .='<h4 class="bg">Kirim Pesan Ke : '.$nama_kon.'</h4>';

if (isset($_POST['submit'])) {

    $nama = text_filter($_POST['nama']);
    $email = text_filter($_POST['email']);
    $subyek = text_filter($_POST['subyek']);
    $pesan = nl2br(text_filter($_POST['pesan'], 2));
    checkemail($email);
    $gfx_check = intval($_POST['gfx_check']);
    if (!$nama)  $error .= "Error: Please enter your name!<br />";
    if (!$pesan) $error .= "Error: Please enter a message!<br />";
    if (!$subyek) $error .= "Error: Please enter a Subject!<br />";

    $code = substr(hexdec(md5("".date("F j")."".$_POST['random_num']."".$sitekey."")), 2, 6);
    if (extension_loaded("gd") AND $code != $_POST['gfx_check']) $error .= "Error: Security Code Invalid<br />";


    if ($error) {
        $tengah.='<div class="border"><table width="100%"><tr><td align="left"><img src="images/warning.gif" border="0"></td><td align="center"><div class="error">'.$error.'</div></td><td align="right"><img src="images/warning.gif" border="0"></td></tr></table></div>';

    } else {
            $subject = "$sitename - Contact Form";
            $msg = "$sitename - Contact Form<br /><br />Nama Pengirim: $nama<br />Email Pengirim: $email<br /><br />Pesan: $pesan";
            mail_send($kontributor, "From: $nama - $email", $subyek, $pesan, 1, 1);
        $tengah.='<div class="border"><table width="100%"><tr><td align="left"><img src="images/info.gif" border="0"></td><td align="center"><div class="sukses"><p>Pesan Anda telah dikirim ke teman Anda.<br>Terima kasih mau mendistribusikan artikel di situs ini.</p></div></td><td align="right"><img src="images/info.gif" border="0"></td></tr></table></div>';

            $tengah .='<meta http-equiv="refresh" content="3; url=?pilih=news&amp;mod=yes&aksi=lihat&amp;id='.$id.'">';
    }
}
$tengah .='<div class="border">';
$tengah .= "<p />Anda ingin mengirim pesan kepada ".$nama_kon.".<br />";
$tengah .= "Silahkan isi formulir dibawah ini:<br /><br />";

$nama = !isset($nama) ? '' : $nama;
$email = !isset($email) ? '' : $email;
$subyek = !isset($subyek) ? '' : $subyek;
$pesan = !isset($pesan) ? '' : $pesan;
$op = !isset($_GET['op']) ? '' : $_GET['op'];


$tengah .= "
<form method=\"POST\" action=\"\">

<table border=\"0\"  cellpadding=\"3\" cellspacing=\"0\" align=\"center\">
  <tr>
    <td valign=\"top\">Your Name</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\"><input type=\"text\" name=\"nama\" style=\"width:300px\" size=\"50\" value=\"".$nama."\"></td>
  </tr>
  <tr>
    <td valign=\"top\">Your Email</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\"><input type=\"text\" name=\"email\" style=\"width:300px\" size=\"50\" value=\"".$email."\"></td>
  </tr>
    <tr>
    <td valign=\"top\">Subject</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\"><input type=\"text\" name=\"subyek\" style=\"width:300px\" size=\"50\" value=\"".$subyek."\"></td>
  </tr>
  <tr>
    <td valign=\"top\">Message</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\"><textarea name=\"pesan\"  id=\"pesan\" cols=\"50\" rows=\"10\" >".$pesan."</textarea></td>
  </tr>";

  if (extension_loaded("gd")) {
      $random_num = gen_pass(10);
$tengah .= "
  <tr>
    <td valign=\"top\">Security Code</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\"><img src=\"?code=gfx&random_num=$random_num\" border=\"1\" alt=\"Security Code\"></td>
  </tr>
  <tr>
    <td valign=\"top\">Type Code</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\"><input type=\"text\" name=\"gfx_check\" size=\"10\" maxlength=\"6\"><input type=\"hidden\" name=\"random_num\" value=\"$random_num\"></td>
  </tr>";

}
$tengah .= "
  <tr>
    <td valign=\"top\"></td>
    <td valign=\"top\"></td>
    <td valign=\"top\"></td>
  </tr>
  <tr>
    <td valign=\"top\"></td>
    <td valign=\"top\"></td>
    <td valign=\"top\"><input type=\"hidden\" name=\"op\" value=\"$op\" /><input type=\"submit\" name=\"submit\" value=\"Submit\"></td>
  </tr>
</table>
</form>";
$tengah .='</div>';


}


if(@$_GET['aksi']=="recommend"){

$id = int_filter($_GET['id']);

$tengah .='<h4 class="bg">Kirim Artikel Ke Teman</h4>';

if (isset($_POST['submit'])) {

global $judul_situs, $slogan, $url_situs,  $error;
    $yemail = text_filter($_POST['yemail']);
    $femail = text_filter($_POST['femail']);
    $pesan = text_filter($_POST['pesan']);
    if (!is_valid_email($yemail)) {$error .= "your email invalid, Please use the standard format (admin@domain.com)<br />";}
 	if (!is_valid_email($femail)) {$error .= "Friend email invalid, Please use the standard format (admin@domain.com)<br />";}
    $yname = text_filter($_POST['yname']);
    $fname = text_filter($_POST['fname']);
        if (!$fname)  $error .= "Error: Please enter your Frind's Name!<br />";
        if (!$yname)  $error .= "Error: Please enter your Name!<br />";

    $gfx_check = intval($_POST['gfx_check']);
    //$code = substr(hexdec(md5("".date("F j")."".$_POST['random_num']."".$sitekey."")), 2, 6);
    //if (extension_loaded("gd") AND $code != $_POST['gfx_check']) $error .= "Error: Security Code Invalid<br />";
    if ($_POST['gfx_check'] != $_SESSION['Var_session'] or !isset($_SESSION['Var_session'])) {$error .= "Error: Security Code Invalid <br />";}


if ($error){
        $tengah.='<div class="error" style="width:80%">'.$error.'</div>';
}else{


        $subject = "Ada artikel bagus di $url_situs";
        $full_pesan = "Hallo,\n\nBerikut ini ada artikel yang bagus untuk dibaca,
        <br />Artikel dengan judul : $judul_artikel, silahkan klik aja <a href='$url_situs/?pilih=news&amp;mod=yes&aksi=lihat&id=$id'>$url_situs/?pilih=news&amp;mod=yes&aksi=lihat&id=$id</a>.
        <br />
        <br />
        $pesan
        <br />
        <br />Terima kasih.";
        mail_send($femail, $yemail, $subject, $full_pesan, 0, 3);
        $tengah.='<div class="sukses"><p>Pesan Anda telah dikirim ke teman Anda.<br />Terima kasih mau mendistribusikan artikel di situs ini.</p></div>';
        $tengah .='<meta http-equiv="refresh" content="3; url=?pilih=news&amp;mod=yes&aksi=lihat&id='.$id.'">';

}
}
$perintah="SELECT judul FROM artikel WHERE id='$id' AND publikasi=1";
$hasil = mysql_query( $perintah );

while ($data = mysql_fetch_array($hasil)) {
$judul_artikel = $data['judul'];
}


$tengah .='<div class="border">';
$tengah .="
<form method=\"post\" action=\"#\">
<table border=\"0\"  cellpadding=\"3\" cellspacing=\"0\" align=\"center\">
  <tr>
    <td colspan=\"3\"><p />Anda ing memberitahu teman Anda tentang artikel ini yang berjudul : <b>".$judul_artikel."</b>.<br /><br /></td>
  </tr>
  <tr>
    <td valign=\"top\">Your Name</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\"><input type=\"text\" name=\"yname\" style=\"width:150px\" size=\"50\" /></td>
  </tr>
  <tr>
    <td valign=\"top\">Your E-mail</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\"><input type=\"text\" name=\"yemail\" style=\"width:150px\" size=\"50\" /></td>
  </tr>
  <tr>
    <td valign=\"top\">Your Friend's Name</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\"><input type=\"text\" name=\"fname\" style=\"width:150px\" size=\"50\" /></td>
  </tr>
  <tr>
    <td valign=\"top\">Your Friend's E-Mail</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\"><input type=\"text\" name=\"femail\" style=\"width:150px\" size=\"50\" /></td>
  </tr>
  <tr>
    <td valign=\"top\">Message (option)</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\"><textarea name=\"pesan\"  cols=\"50\" rows=\"10\" style=\"width:250px\"></textarea></td>
  </tr>";


if (extension_loaded("gd")) {
$random_num = gen_pass(10);
$tengah .= "
  <tr>
    <td valign=\"top\">Security Code</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\"><img src=\"includes/code_image.php\" border=\"1\" alt=\"Security Code\" /></td>
  </tr>
  <tr>
    <td valign=\"top\">Type Code</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\"><input type=\"text\" name=\"gfx_check\" size=\"10\" maxlength=\"6\" /></td>
  </tr>";

}
$tengah .= "
  <tr>
    <td valign=\"top\"></td>
    <td valign=\"top\"></td>
    <td valign=\"top\"></td>
  </tr>
  <tr>
    <td valign=\"top\"></td>
    <td valign=\"top\"></td>
    <td valign=\"top\"><input type=\"hidden\" name=\"id\" value=\"$id\" /><input type=\"submit\" name=\"submit\" value=\"Submit\" /></td>
  </tr>
</table>
</form>";
$tengah .='</div>';
}

echo $tengah;


?>