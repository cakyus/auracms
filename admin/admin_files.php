<?
if (!defined('AURACMS_admin')) {
	Header("Location: ../index.php");
	exit;
}

function format_size($file){
	$get_file_size = filesize("./files/$file");
	$get_file_size = number_format($get_file_size / 1024,1);
	return "$get_file_size KB";
}

if (!cek_login ()){
	Header("Location: index.php");
	exit;
}else{

$admin ='<h4 class="bg">File Manager </h4>';
$admin .='<div class="border"><a href="?pilih=admin_files"><b>Home</b></a> | <a href="admin.php?pilih=admin_files&amp;aksi=add"><b>Upload File</b></a></div>';
$admin .='<div class="left_message">
<span style="text-decoration:underline">Catatan:</span><br />
Gunakan url seperti dibawah ini untuk menyisipkan image di artikel atau halaman web: <br /><b>"files/nama_file.extension"</b><br /><span style="text-decoration:underline">contoh:</span> <br />&lt;img src="files/goldentop.jpg" alt="" border="0" /&gt;
</div>';

if (session_is_registered ('LevelAkses') &&  $_SESSION['LevelAkses']=="Administrator"  OR $_SESSION['LevelAkses']=="Editor"){
if($_GET['aksi'] == ''){
$admin .= '
<table cellspacing="0" style="width:100%">
<tbody id="rowbody">
	<tr bgcolor="#d1d1d1">
	<th style="text-align:left;padding:10px 5px 10px 10px;border-left:1px solid #ccc;border-top:1px solid #ccc;">Nama File</th>
	<th style="text-align:center;padding:10px 5px 10px 5px;border-top:1px solid #ccc;border-left:1px solid #ccc;width:100px;">Ukuran</th>
	<th colspan="2" style="text-align:center;padding:10px 5px 10px0 5px;border-right:1px solid #ccc;border-top:1px solid #ccc;border-left:1px solid #ccc;width:160px;">Aksi</th>
	</tr>';
$rep=opendir('./files/');
$warna = '';
$no = 1;
while ($file = readdir($rep)) {
	if($file != '..' && $file !='.' && $file !=''){
		if (is_dir($file)){
			continue;
		}else {
			if ($file !='index.php'){
			$warna = empty($warna) ? 'bgcolor="#efefef"' : '';
			$admin .= '
			<tr id="'.$warna.'">
			<td style="text-align:left;padding:10px;border-left:1px solid #ccc;border-top:1px solid #ccc;">'.$file.'</td>
			<td style="text-align:center;padding:5px;border-top:1px solid #ccc;border-left:1px solid #ccc;width:100px;">'.format_size($file).'</td>
			<td style="text-align:center;padding:2px;border-top:1px solid #ccc;border-left:1px solid #ccc;"><a href="files/'.$file.'" target="_blank">View</a></td>
			<td style="text-align:center;padding:2px;border-right:1px solid #ccc;border-top:1px solid #ccc;border-left:1px solid #ccc;"><a href="?pilih=admin_files&amp;aksi=hapus&amp;nama='.$file.'">Hapus</a></td>
			</tr>';
			$warna = empty($warna) ? 'bgcolor="#efefef"' : '';
			}
		}
	}
$no++;
}
closedir($rep);
clearstatcache();
$admin .= '<tr><td colspan="4" style="width:25px;text-align:center;padding:5px;border-top:1px solid #ccc;">&nbsp;</td></tr></tbody></table>';
	
	
}
if ($_GET['aksi']=='add'){

global $max_size,$allowed_exts,$allowed_mime;

if (isset($_POST['submit'])) {

    $image_name=$_FILES['image']['name'];
    $image_size=$_FILES['image']['size'];
	$error = '';
	if ($image_name == ''){
		$error .= 'Silahkan pilih file image yang akan diupload dari harddisk Anda, silahkan ulangi.<br />';
	}

    if ($error){
        $admin.='<div class="error">'.$error.'</div>';
        $style_include[] ='<meta http-equiv="refresh" content="3; url=?pilih=admin_files" />';
    }elseif (@copy($_FILES['image']['tmp_name'], "./files/".$image_name)){

        //unlink($image);
        $admin.='<div class="sukses">Upload file '.$image_name.' berhasil!</div>';
        $style_include[] ='<meta http-equiv="refresh" content="3; url=?pilih=admin_files" />';
	}else{

        $admin.='<div class="error">'.$image_name.' tidak bisa diupload, silahkan ulangi.</div>';
        $style_include[] ='<meta http-equiv="refresh" content="3; url=?pilih=admin_files" />';

    }


}


$admin .='<div class="border">
<form method="post" enctype="multipart/form-data" action="">
<b>File Uploader</b><br /><input type="hidden" name="MAX_FILE_SIZE" value="500000" />
<input type="file" name="image" size="40" /><br /><br />
<input type="submit" name="submit" value="Upload" />
</form></div>';
}

if ($_GET['aksi']=='hapus'){
    $nama = $_GET['nama'];
	if ($nama){
	unlink ("./files/".$nama);
    }
    $admin.='<div class="sukses">File <b>'.$nama.'</b> telah di delete!</div>';
    $style_include[] ='<meta http-equiv="refresh" content="3; url=?pilih=admin_files" />';
}

}else{
	Header("Location: index.php");
	exit;
}

echo $admin;

}
?>