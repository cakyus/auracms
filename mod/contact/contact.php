<?php

/**
 * Modul Contact Us for AuraCMS v2.0
 * auracms.org
 * 11 December 2006
 * Author: 	Iwan Susyanto, S.Si - admin@auracms.org
 *		Arif Supriyanto - arif@ayo.kliksini.com
 *		Rumi Ridwan Sholeh - floodbost@yahoo.com
 * 		http://www.auracms.org
 * 		http://www.semarang.tk
 *		http://www.iwan.or.id
 *		http://www.ridwan.or.id
 *		http://www.auracms.opensource-indonesia.com
 *
 */

if (!defined('AURACMS_CONTENT')) {
	Header("Location: ../index.php");
	exit;
}


//$index_hal=1;

$tengah ='<h4 class="bg">Contact Form</h4>';


if (isset($_POST['submit'])) {

    $nama = text_filter($_POST['nama']);
    $email = text_filter($_POST['email']);
    $pesan = nl2br(text_filter($_POST['pesan'], 2));
    $error = '';
    if (!is_valid_email($email)) $error .= "Error: E-Mail address invalid!<br />";
    $gfx_check = $_POST['gfx_check'];
        if (!$nama)  $error .= "Error: Please enter your name!<br />";
        if (!$pesan) $error .= "Error: Please enter a message!<br />";

   // $code = substr(hexdec(md5("".date("F j")."".$_POST['random_num']."".$sitekey."")), 2, 6);
if ($gfx_check != $_SESSION['Var_session'] or !isset($_SESSION['Var_session'])) {$error .= "Security Code Invalid <br />";}
if (cek_posted('contact')){
	$error .= 'Anda Telah Memposting, Tunggu beberapa Saat';
}

	if ($error) {
        $tengah.='<div class="error">'.$error.'</div>';
	} else {
		$subject = "AuraCMS - Contact Form";
$msg = "
$judul_situs - Contact Form

Nama Pengirim: $nama <br />
Email Pengirim: $email <br />
Pesan: $pesan
";
	    mail_send($email_master, $email, $subject, $msg, 1, 1);
	    Posted('contact');

        $tengah.='<div class="sukses">Thank you, mail has been sent!</div>';

unset($nama);
unset($email);
unset($pesan);

      }

}

$nama = !isset($nama) ? '' : $nama;
$email = !isset($email) ? '' : $email;
$pesan = !isset($pesan) ? '' : $pesan;


$tengah .='<div class="border">';
$tengah .= "
<p>
Anda bisa menghubungi kami melalui formulir yang disediakan di bawah ini.
Semua pesan yang Anda tulis disini dikirim ke email kami.
<br />
Terimakasih.
</p>
<br /><br />
<form method=\"post\" action=\"#\">

<table border=\"0\"  cellpadding=\"0\" cellspacing=\"4\" align=\"center\">
  <tr>
    <td valign=\"top\">Your Name</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\"><input type=\"text\" name=\"nama\"  size=\"25\" value=\"".$nama."\" /></td>
  </tr>
  <tr>
    <td valign=\"top\">Your Email</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\"><input type=\"text\" name=\"email\"  size=\"25\" value=\"".$email."\" /></td>
  </tr>
  <tr>
    <td valign=\"top\">Message</td>
    <td valign=\"top\">:</td>
    <td valign=\"top\"><textarea name=\"pesan\"  cols=\"40\" rows=\"10\">".$pesan."</textarea></td>
  </tr>";

  if (extension_loaded("gd")) {
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
    <td valign=\"top\"><input type=\"submit\" name=\"submit\" value=\"Submit\" /></td>
  </tr>
</table>
</form>";
/*$tengah .='</td></tr></table></td></tr></table>';*/
$tengah .='</div>';


echo $tengah;

?>
