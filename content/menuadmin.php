<?

if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}

if (cek_login ()){

if (session_is_registered ('UserName') && isset ($_SESSION['UserName']) && !empty ($_SESSION['UserName'])  ){
if (session_is_registered ('LevelAkses') &&  $_SESSION['LevelAkses']=="Administrator"){

global $koneksi_db;

$hasil = $koneksi_db->sql_query( "SELECT * FROM admin ORDER BY id ASC" );
$menuadmin = "<ul>";
while ($data = $koneksi_db->sql_fetchrow($hasil)) {

		$target = "?aksi=logout";
		if ($data[2]==$target) {
			$adminmenu = "$target";
			$data[1] = "<b>$data[1]</b>";
		} else {
			$mod = $data['mod'] == 1 ? '&amp;mod=yes' : '';
			$adminmenu = $data['mod'] == 1 ? $adminfile.".php?pilih=".$data['url'].$mod : $adminfile.'.php?pilih='.basename($data['url'],'.php');
			
		}

		$menuadmin.= '<li><a href="'.$adminmenu.'">'.$data[1].'</a></li>';

}
$menuadmin.= "</ul>";


kotakjudul('Admin Menu', $menuadmin);

}elseif (session_is_registered ('LevelAkses') &&  $_SESSION['LevelAkses']=="Editor"){
$secure = 'Welcome   : <b>' . $_SESSION['UserName'] .'</b> <br />Your Level : <b>' . $_SESSION['LevelAkses'] .'</b> <br /> <br />';
$secure .= '<ul><li><a href="'.$url_situs.'/index.php?aksi=logout">LogOut</a>';
$secure .= '<li><a href="'.$url_situs.'/index.php?pilih=user&amp;aksi=change">Change Password</a>';
$secure .= '<li><a href="'.$url_situs.'/'.$adminfile.'.php?pilih=news&amp;mod=yes">Send Article</a>';


kotakjudul("Your Account", $secure);	
}else{
$secure = 'Welcome    : <b>' . $_SESSION['UserName'] .'</b> <br />Your Level : <b>' . $_SESSION['LevelAkses'] .'</b> <br /><br />';
$secure .= '<ul><li><a href="'.$url_situs.'/index.php?aksi=logout">LogOut</a></li>';
$secure .= '<li><a href="'.$url_situs.'/index.php?pilih=user&amp;aksi=change">Change Password</a></li>';
$secure .= '<li><a href="'.$url_situs.'/'.$adminfile.'.php?pilih=news&amp;mod=yes">Send Article</a></li>';
$secure .= '</ul>';


$menuuser = "<ul>";
$hasil = $koneksi_db->sql_query( "SELECT * FROM `menu_users` ORDER BY id ASC" );
while ($data = $koneksi_db->sql_fetchrow($hasil)) {
			$adminmenu = $data['url'];
		$menuuser.= '<li><a href="'.$adminmenu.'">'.$data['menu'].'</a></li>';

}
$menuuser.= "</ul>";



$secure = 'Welcome    : <b>' . $_SESSION['UserName'] .'</b> <br />Your Level : <b>' . $_SESSION['LevelAkses'] .'</b> <br /><br />';
$secure .= $menuuser;
kotakjudul("Your Account", $secure);
}
}
}

?>