<?
include "../../includes/session.php";
include "../../includes/config.php";
include "../../includes/fungsi.php";
include "../../includes/mysql.php";




function tanggal_simpan_shoutbox ($timeplus = 0, $bahasa='indonesia'){
	
if ($bahasa == 'indonesia'){
	
$hari_arr = Array ('Minggu',
				   'Senin',
				   'Selasa',
				   'Rabu',
				   'Kamis',
				   'Jumat',
				   'Sabtu'
				   );	
$bulan_arr = Array ('Januari',
					'Februari',
					'Maret',
					'April',
					'Mei',
					'Juni',
					'Juli',
					'Agustus',
					'September',
					'Oktober',
					'November',
					'Desember'
					);	
	
}elseif ($bahasa == 'english') {
$hari_arr = Array ('Sunday',
				   'Monday',
				   'Tuesday',
					'Wednesday',
					'Thursday',
					'Friday',
					'Saturday'
				   );	
$bulan_arr = Array ('January',
					'February',
					'March',
					'April',
					'May',
					'June',
					'July',
					'August',
					'September',
					'October',
					'November',
					'December'
					);		
	
	
	
	
	
}
//yanggal.............
//18060
     $hari 	    = date('w', time() + $timeplus);
     $tanggal	= date('d', time() + $timeplus);
     $bulan 	= date('m', time() + $timeplus);
     $tahun 	= date('Y', time() + $timeplus);
     
     switch ($hari) {
       case 0: $hari = $hari_arr[0];
       break;
       case 1: $hari = $hari_arr[1];
       break;
       case 2: $hari = $hari_arr[2];
       break;
       case 3: $hari = $hari_arr[3];
       break;
       case 4: $hari = $hari_arr[4];
       break;
       case 5: $hari = $hari_arr[5];
       break;
       case 6: $hari = $hari_arr[6];
       break;
       }

     switch ($bulan) {
       case "01": $bulan = $bulan_arr[0];
       break;
       case "02": $bulan = $bulan_arr[1];
       break;
       case "03": $bulan = $bulan_arr[2];
       break;
       case "04": $bulan = $bulan_arr[3];
       break;
       case "05": $bulan = $bulan_arr[4];
       break;
       case "06": $bulan = $bulan_arr[5];
       break;
       case "07": $bulan = $bulan_arr[6];
       break;
       case "08": $bulan = $bulan_arr[7];
       break;
       case "09": $bulan = $bulan_arr[8];
       break;
       case "10": $bulan = $bulan_arr[9];
       break;
       case "11": $bulan = $bulan_arr[10];
       break;
       case "12": $bulan = $bulan_arr[11];
       break;
       }

//fungsi untuk jam
$jam = date ('H', time () + $timeplus);
$menit = date ('i' , time() + $timeplus);
$detik = date ('s' , time() + $timeplus);

$pukul ="$jam:$menit:$detik";
$waktu = "$hari, $tanggal $bulan $tahun  $pukul";

return $waktu;

}



$error = '';
$kkode = false;
if ( @$_POST['keykodes']!= @$_SESSION['Var_session'] or !isset($_SESSION['Var_session'])){
	$error .= '<li>Key Kode salah</li>';
	$kkode = true;
	}
	
if (empty($_POST['nama'])){
	$error .= '<li>Silahkan Isi Nama nya</li>';
	
	}	
	
	
if (empty($_POST['yousay'])){
	$error .= '<li>Silahkan Isi Pesan nya</li>';
	
	}
	
if (cek_posted('shoutbox')){
	$error .= '<li>Anda Sudah Memposting, Tunggu beberapa Menit Lagi</li>';
}	
	
	
	
	
if(!empty($_POST['nama']) && !empty($_POST['yousay']) && preg_match('/^[._a-z0-9-]+[._a-z0-9- ]+$/i', $_POST['nama']) && $kkode == false && !cek_posted('shoutbox')){

global $koneksi_db,$maxadmindata;




$ip_adr = cleartext(@$_SERVER["HTTP_X_FORWARDED_FOR"]);
if (@$_SERVER["HTTP_X_FORWARDED_FOR"] == ''){
$ip_adr = @$_SERVER["REMOTE_ADDR"];	
}
$agent_Usr = cleartext(@$_SERVER["HTTP_USER_AGENT"]);
$ket = "$ip_adr|$agent_Usr";
$DatE = tanggal_simpan_shoutbox ();
$name = cleantext($_POST['nama']);
$email = cleantext($_POST['email']);
$yousay = cleantext($_POST['yousay']);
$valid_mail = "^([._a-z0-9-]+[._a-z0-9-]*)@(([a-z0-9-]+\.)*([a-z0-9-]+)(\.[a-z]{2,3}))$";
if (!eregi($valid_mail, $email)){
$email = '';	
}

		$perintah1="INSERT INTO shoutbox (waktu, nama, email, isi, ket) VALUES ('$DatE', '$name', '$email', '$yousay', '$ket')";
		$hasil = @mysql_query( $perintah1 );

		if ($hasil){
			posted('shoutbox');
			@header ("location: shoutbox.php");
			exit;

		}

}else {
	echo '
	<style>
	body,p,font,li {
		font-family:verdana,tahoma,arial;
		font-size:10px;
	}
	</style>
	';
	echo $error;
	echo "<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"5;URL=./shoutbox.php\">";
}
?>