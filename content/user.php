<?
if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}
$tengah = null;

global $koneksi_db,$error;

if($_GET['aksi']=="register"){

$tengah .='<h4 class="bg">Registrasi User</h4>';

if(isset($_POST['submit'])){
$_POST = array_map('cleantext',$_POST);
$nama         = $_POST['nama'];
$email        = $_POST['email'];
if(!isset($_POST['cekperaturan'])){
$cekperaturan = '0';
}else{
$cekperaturan = $_POST['cekperaturan'];
}
$password     = md5($_POST['password']);
$rpassword    = md5($_POST['rpassword']);
$country      = cleantext($_POST['country']);
$confirm_code = md5(uniqid(rand()));

$mail_blocker = explode(",", $mail_blocker);
	foreach ($mail_blocker as $key => $val) {
		if ($val == strtolower($email) && $val != "") $error .= "Given E-Mail the address is forbidden to use!<br />";
}
$name_blocker = explode(",", $name_blocker);
	foreach ($name_blocker as $key => $val) {
		if ($val == strtolower($nama) && $val != "") $error .= "Named it is forbidden to use!<br />";
}

if (!$nama || preg_match("/[^a-zA-Z0-9_-]/", $nama)) $error .= "Error: Karakter Username tidak diizinkan kecuali a-z,A-Z,0-9,-, dan _<br />";
if (strlen($nama) > 10) $error .= "Username Terlalu Panjang Maksimal 10 Karakter<br />";
if (strrpos($nama, " ") > 0) $error .= "Username Tidak Boleh Menggunakan Spasi";
if ($koneksi_db->sql_numrows($koneksi_db->sql_query("SELECT user FROM useraura WHERE user='$nama'")) > 0) $error .= "Error: Username ".$nama." sudah terdaftar , silahkan ulangi.<br />";
if ($koneksi_db->sql_numrows($koneksi_db->sql_query("SELECT user FROM temp_useraura WHERE user='$nama'")) > 0) $error .= "Error: Username ".$nama." sudah terdaftar , silahkan ulangi.<br />";
if ($koneksi_db->sql_numrows($koneksi_db->sql_query("SELECT email FROM useraura WHERE email='$email'")) > 0) $error .= "Error: Email ".$email." sudah terdaftar , silahkan ulangi.<br />";
if ($koneksi_db->sql_numrows($koneksi_db->sql_query("SELECT email FROM temp_useraura WHERE email='$email'")) > 0) $error .= "Error: Email ".$email." sudah terdaftar , silahkan ulangi.<br />";
if (!$nama)  $error .= "Error: Formulir Nama belum diisi , silahkan ulangi.<br />";
if ($cekperaturan != '1') $error .= "You should be agree with rules and conditions of use!<br />";
if (!$nama)  $error .= "Error: Formulir Nama belum diisi , silahkan ulangi.<br />";
if (empty($_POST['password']))  $error .= "Error: Formulir Password belum diisi , silahkan ulangi.<br />";
if ($_POST['password'] != $_POST['rpassword'])  $error .= "Password and Retype Password Not Macth.<br />";
if (empty($country))  $error .= "Error: Formulir Negara belum diisi , silahkan ulangi.<br />";
if (!is_valid_email($email)) $error .= "Error: E-Mail address invalid!<br />";
if ($_POST['gfx_check'] != $_SESSION['Var_session'] or !isset($_SESSION['Var_session'])) {$error .= "Security Code Invalid <br />";}


if ($error){
        $tengah.='<div class="error">'.$error.'</div>';
}else{
	$hasil1 = $koneksi_db->sql_query("INSERT INTO useraura (user, email, password , level, tipe, negara)VALUES('$nama', '$email', '$password','User','aktif', '$country')" );

        if($hasil1){
            $subject  ="Your Account Information";
            $header   = $email_master;
            $message  ="Your Account \r\n";
            $message .="<br /><br />";
            $message .="Username : ".$nama." <br>Password : ".$_POST['password']."";
            $message .="<br /><br />Please Don't Replay This Email, this is Automatic Email Because You Register in AuraCMS Member<br /><br />";
            $message .="<br /><br /><br />Regard:<br /><br />Webmaster<br />";
            $sentmail = mail_send($email, $header, $subject, $message, 1, 1);

        $tengah.='<div class="sukses">Please Login With Your Username and Your Password</div>';
		unset($_POST);

        }
}

}

$nama         = !isset($nama) ? '' : $nama;
$email        = !isset($email) ? '' : $email;
$password     = !isset($passwordn) ? '' : $password;
$rpassword    = !isset($rpassword) ? '' : $rpassword;
$country      = !isset($country) ? '' : $country;

$checkperaturan = isset($_POST['cekperaturan']) ? ' checked="checked"' : '';

$tengah .='<div class="border">';
$tengah .='
<p>Nikmati aneka fasilitas yang tersedia di Portal ini dengan menjadi member.
Untuk menjadi members, Anda hanya perlu melakukan registrasi dengan mengisi form
singkat berikut ini.</p>
<p>Masukkan user name atau login name yang diinginkan, lalu masukkan pula email
Anda. Selanjutnya, ke alamat email Anda, akan dikirim Aktivasi untuk login.</p>
<table width="100%" border="0"  cellpadding="0" cellspacing="0">
<tr>
<td><form method="post" action="">
<table width="100%" border="0" cellspacing="4" cellpadding="0">
<tr>
<td colspan="3"><strong>Sign up</strong></td>
</tr>
<tr>
<td>Username</td>
<td>:</td>
<td><input name="nama" type="text" size="30" value="'.cleantext(stripslashes(@$_POST['nama'])).'" /></td>
</tr>
<tr>
<td>E-mail</td>
<td>:</td>
<td><input name="email" type="text" size="30" value="'.cleantext(stripslashes(@$_POST['email'])).'" /></td>
</tr>
<tr>
<td>Password</td>
<td>:</td>
<td><input name="password" type="password" size="30" /></td>
</tr>
<tr>
<td>ReType Password</td>
<td>:</td>
<td><input name="rpassword" type="password" size="30" /></td>
</tr>
<tr>
<td>Country</td>
<td>:</td>
<td><input name="country" type="text" size="30" value="'.cleantext(stripslashes(@$_POST['country'])).'" /></td>
</tr>';
if (extension_loaded("gd")) {
$tengah .= '
<tr>
<td>Security Code</td>
<td>:</td>
<td><img src="includes/code_image.php" border="1" alt="Security Code" /></td>
</tr>
<tr>
<td>Type Code</td>
<td>:</td>
<td><input name="gfx_check" type="text" size="10"  maxlength="6" /></td>
</tr>';
}
$tengah .= '
<tr>
<td valign="top">Peraturan</td>
<td valign="top">:</td>
<td><textarea cols="40" rows="10">
Common rules of a portal
1. Our portal is opened for visiting by all interested person. To use all size of services of a site, it is necessary for you to register.
2. The user of a portal can become any person, agreed to observe the given rules.
3. Each participant of dialogue has the right to confidentiality of the information on. Therefore do not discuss financial, family and other interests of participants without the permission on it the participant.
4. Call on a site occurs on "you". It is not the disrespectful or unfriendly sign in relation to the interlocutor.
5. Our portal - postmoderated. The information placed on a site, preliminary is not viewed and not edited, but administration and moderators reserve the right to itself to make it later.
6. All messages mirror only opinions of their authors.
7. The order on a portal is watched by moderators. They have the right to edit, delete messages and to close subjects in sections inspected by them.
8. Before creation of a new subject at a forum, it is recommended to take advantage of search. Probably question which you wish to set, was already discussed. If you have troubleshot by own strength, please, write about it, with the instruction of how you have made it. If wish to close the subject or the message, inform on it to the moderator.
9. Create new subjects only in appropriate sections. If the subject does not approach under one of sections or you doubt of correctness of the choice - create it in section of a forum "Bulletin board".
10. Before sending messages or to use services of a portal, you are obliged to familiarize with the common rules, and also rules of that department closely.
11. In case of rough violations of rules, the manager reserves the right to itself to eliminate the user from a site without warnings. Repeated registration of the user in cases of deleting is eliminated.
12. The manager reserves the right to itself to change the given rules without the prior notification. All changes inure from the moment of their publication.
13. The information and links are presented exclusively in the educational purposes and intended only for satisfaction of curiosity of visitors.
14. You undertake to not apply the received information with a view of, prohibited FC the Russian Federation and norms of international law.
15. Authors of the given site do not carry the responsibility for possible consequences of usage of the information and links.
16. If you do not agree with the above-stated requirements, in that case you should leave our site immediately.\n
On a site it is forbidden\n
1. To break subjects of forums and sections.
2. To create subjects which recently were already discussed in the same forum.
3. To create the same subject in several sections.
4. To create subjects with empty names.
5. To use not normative lexicon, rough expressions in relation to the interlocutor, to offend national or religious feelings of interlocutors, and also to write messages capital letters.
6. To place advertising. Advertising the link to a promoted site, with the address or without also is considered, or a homepage in the signature.
7. To expose cracks, serial numbers to programs or already cracked programs. Also it is forbidden to leave links to them.
8. To write messages, which do not carry the helpful information (flood, offtop) in subject sections.
9. To discuss and condemn operations of moderators and administrations, it is possible only in personal correspondence or in the complaint, the routed administration of a portal.
</textarea></td>
</tr>
<tr>
<td></td>
<td>:</td>
<td><input type="checkbox" name="cekperaturan" value="1" id="setuju"'.$checkperaturan.' /> <label for="setuju">I agree to the terms set out in this license.</label></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><input type="submit" name="submit" value="Submit" /> &nbsp;
<input type="reset" name="Reset" value="Reset" /></td>
</tr>
</table>
</form></td>
</tr>
</table>';
$tengah .='</div>';

}

if($_GET['aksi']=="forgotpass"){

$tengah .='<h4 class="bg">Lost Password ?</h4>';


if(isset($_POST['submit'])){
$kode = $_POST['kode'];
$user = cleartext($_POST['user']);
if($kode){
    if (!$user)  $error .= "Error: Formulir Usernama belum diisi , silahkan ulangi.<br />";
    if ($error){
        $tengah .='<div class="error">'.$error.'</div>';
    }else{
    $result = $koneksi_db->sql_query("SELECT email,password FROM useraura WHERE user='$user'");
    $host_name = getenv("REMOTE_ADDR");
    list($email, $password) = $koneksi_db->sql_fetchrow($result);
    $areyou = substr($password, 0, 5);

            if ($areyou==$kode) {

                $newpass= gen_pass(10);
                $message = "
                Here is your new password to be used at $judul_situs -  $url_situs<br /><br />
                User name: $user<br />Password: $newpass<br /><br />
                Your IP address is $host_name<br /><br />
                Thank you for joining our portal,<br />
                $judul_situs<br /><br />";
                $header     = $email_master;
                $subject= $judul_situs."- News Password for $user";
                $sentmail = mail_send($email, $header, $subject, $message, 1, 1);
                $cryptpass = md5($newpass);
                $hasil  =$koneksi_db->sql_query("UPDATE useraura SET password='$cryptpass' WHERE user='$user'");
                if($hasil){
        $tengah.='<div class="sukses">Your new password for '.$user.' has been sent to your email.<br />Check your email and use the new password to enjoy this portal.</div>';
        $style_include[] ='<meta http-equiv="refresh" content="3; url=index.php" />';
                }else {
        $tengah.='<div class="error">Could not update user entry. Please Contact the Administrator</div>';

                }
            }else{
        $tengah.='<div class="error">Your Code Is Wrong</div>';

            }



    }

}else{

    if (!$user)  $error .= "Error: Formulir Usernama belum diisi , silahkan ulangi.<br />";
    if ($error){
        $tengah.='<div class="error">'.$error.'</div>';

    }else{

     $result = $koneksi_db->sql_query("SELECT email, password FROM useraura WHERE user='$user'");
     $jumlah = $koneksi_db->sql_numrows($koneksi_db->sql_query("SELECT user FROM useraura WHERE user='$user'"));

            if($jumlah<1) {
        $tengah.='<div class="error">Sorry, no member with that name</div>';
            } else {

                $host_name = getenv("REMOTE_ADDR");
                list($email, $password) = $koneksi_db->sql_fetchrow($result);
                $areyou = substr($password, 0, 5);

                $message = "
                The following is Confirmation Code for your request to create new password for '$user'  at $url_situs .<br /><br />
                Confirmation Code: $areyou <br /><br />
                If you didn't ask for the Confirmation Code, just delete this email. Otherwise, use it to create new password.<br /><br />
                User who request for Confirmation Code is:<br />
                User name: $user<br />
                IP address: $host_name <br /><br />

                Thank you,<br />
                Webmaster<br /><br />";


                $subject= $judul_situs." - Confirmation Code";
                $header     = $email_master;
                $sentmail = mail_send($email, $header, $subject, $message, 1, 1);

        $tengah.='<div class="sukses"><b>Confirmation Code</b><br /><br />The confirmation code for <b>'.$user.'</b> has been sent.<br />Check your email right away</div>';
                $style_include[] ='<meta http-equiv="refresh" content="3; url=" />';

            }

    }

}

}
$tengah .='<div class="border">';
$tengah .='
<p>Lost your password? No problem. The following two steps process will create new password for you.</p>
<p>First, enter your user name bellow, and click Send Password. We will send an email to your email address that is already in our database, to confirm your request for new password.</p>
<p>Next, open the email, find the Confirmation Code, and once again enter your user name and the Confirmation Code to the form below, and  click Send Password.</p>
<p>Finish, your new password will be sent to you.</p>

<form action="#" method="post">
<table>
<tr><td>User Name:</td><td><input type="text" name="user" size="26" maxlength="25" /></td></tr>
<tr><td>Confirmation Code:</td><td><input type="text" name="kode" size="5" maxlength="6" /></td></tr>
<tr><td>&nbsp;</td><td>
<input type="submit" name="submit" value="Send Password" /></td></tr>
</table>
</form>';
$tengah .='</div>';


}


if($_GET['aksi']=="change"){

if (!cek_login ()){
   $tengah .='<p class="judul">Access Denied !!!!!!</p>';
   
}else{

global $koneksi_db,$PHP_SELF,$theme,$error;

$tengah .='<h4 class="bg">Edit User</h4>';

if (isset($_POST["submit"])) {

$user		   = $_SESSION['UserName'];
$email	      = text_filter($_POST['email']);
$password0 = md5($_POST["password0"]);
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];

$hasil = $koneksi_db->sql_query( "SELECT password,email FROM useraura WHERE user='$user'" );
while ($data = $koneksi_db->sql_fetchrow($hasil)){
	$password=$data['password'];
	$email0=$data['email'];
}

if (!$password0)  $error .= "Error: Please enter your Old Password!<br />";
if (!$password1)  $error .= "Error: Please enter new password!<br />";
if (!$password2)  $error .= "Error: Please retype your your new password!<br />";
if (!is_valid_email($email)) $error .= "Error, E-Mail address invalid!<br />";
if ($password0 != $password)  $error .= "Invalid old pasword, silahkan ulangi lagi.<br />";
if ($password1 != $password2)   $error .= "New password dan retype berbeda, silahkan ulangi.<br />";


if ($error) {
$tengah.='<div class="error">'.$error.'</div>';
} else {

$password3=md5($password1);
$hasil = $koneksi_db->sql_query( "UPDATE useraura SET email='$email', password='$password3' WHERE user='$user'" );

$tengah.='<div class="sukses"><b>Infromasi Admin telah di updated</b><br />Silahkan <a href="?aksi==logout" target="_top">Logout</a> kemudian <a href="?pilih=login" target="_top">Login</a> lagi!</div>';
}

}

$user =  $_SESSION['UserName'];
$hasil =  $koneksi_db->sql_query( "SELECT * FROM useraura WHERE user='$user'" );
while ($data = $koneksi_db->sql_fetchrow($hasil)) {
	$id=$data[0];
	$user=$data[1];
	$email=$data[3];
}


$tengah .='<div class="border">';
$tengah .='

<form method="post" action="#">
    <table>
        <tr>
            <td>Username</td>
            <td>'.$user.'</td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input type="text" size="30" name="email" value="'.$email.'" /></td>
        </tr>
        <tr>
            <td>Old Password</td>
            <td><input type="password" size="10" name="password0" /></td>
        </tr>
        <tr>
            <td>New Password</td>
            <td><input type="password" size="10" name="password1" /></td>
        </tr>
        <tr>
            <td>Retype New Password</td>
            <td><input type="password" size="10" name="password2" /></td>
        </tr>
        <tr>
            <td></td><td colspan="2">
            <input type="hidden" name="id" value="'.@$UserId.'" />
            <input type="hidden" name="user" value="'.@$user.'" />
            <input type="submit" name="submit" value="Update" />
            </td>
        </tr>
    </table>
</form> ';
$tengah .='</div>';



}
}

echo $tengah;


?>