<?php

if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}


//konfigurasi album
$tpath= 'mod/gallery/storeData/normal/';
$tnpath='mod/gallery/storeData/thumb/';



//configurasi upload
$Tpath = 'storeData/normal/';
$Tnpath = 'storeData/thumb/';
$tmp = 'tmp/';


$limit = 12;
$pptabel = 4;
$max_width= 2280;
$max_height=2024;
$max_size=1024000;
$limit_numb1 = 1;
$pph = 5;

$CONFIG['jpeg_qual'] = 100 ;
$CONFIG['thumb_method'] = 'gd2'; 
$CONFIG['thumb_width'] = 80 ;

$CONFIG['resizewidth'] = 400;

$GLOBALS['flood_detect'] = 180;
$zebra_warna1 = '#efefef';
$GLOBALS['zebra_warna'][0] = '#fcfcfc';
$GLOBALS['zebra_warna'][1] = '#efefef';


if (!function_exists('transCAL')){
function transCAL ($time, $jam=false){
$DATE = date ('w j m Y H i s', $time);	
$pecah = explode (' ', $DATE);


     $hari 	    = $pecah[0];
     $tanggal	= $pecah[1];
     $bulan 	= $pecah[2];
     $tahun 	= $pecah[3];
     if ($jam){
     $waktu     = "$pecah[4]:$pecah[5]:$pecah[6]";
 	}else {
	 	$waktu = '';
 	}
     
     switch ($hari) {
       case 0: $hari = "Minggu";
       break;
       case 1: $hari = "Senin";
       break;
       case 2: $hari = "Selasa";
       break;
       case 3: $hari = "Rabu";
       break;
       case 4: $hari = "Kamis";
       break;
       case 5: $hari = "Jumat";
       break;
       case 6: $hari = "Sabtu";
       break;
       }

     switch ($bulan) {
       case "01": $bulan = "Januari";
       break;
       case "02": $bulan = "Februari";
       break;
       case "03": $bulan = "Maret";
       break;
       case "04": $bulan = "April";
       break;
       case "05": $bulan = "Mei";
       break;
       case "06": $bulan = "Juni";
       break;
       case "07": $bulan = "Juli";
       break;
       case "08": $bulan = "Agustus";
       break;
       case "09": $bulan = "September";
       break;
       case "10": $bulan = "Oktober";
       break;
       case "11": $bulan = "November";
       break;
       case "12": $bulan = "Desember";
       break;
       }

return "$hari, $tanggal $bulan $tahun $waktu";	
	
	
}
}
if (!function_exists('multisort')){
function multisort($array, $column, $order = 'asc', $type = SORT_STRING)
{
    if(!isset($array[0][$column]))
    {
        return $array;
    }
    for($i = 0; $i < count($array); $i++)
    {
        $temp[$i] = $array[$i][$column];
    }
    switch($order)
    {
        default:
        case 'asc':
            $order = SORT_ASC;
        break;
        case 'dsc':
        case 'desc':
            $order = SORT_DESC;
        break;
    }
    array_multisort($temp, $order, $type , $array);

    return $array;
}
}


if (!function_exists('fsize')){
function fsize($zahl) {
	//Creates Filesize-Info
	//number_format($_FILES["wcsfile"]["size"] / 1024, 0, ',', '.')." kB)
	if($zahl < 1000) {
		$zahl = $zahl."";
	} else {
		if($zahl < 1048576) {
			$zahl = number_format($zahl/1024, 1, '.', '.')."&nbsp;Kb";
		} else {
			$zahl = number_format($zahl/1048576, 1, '.', '.')."&nbsp;Mb";
		}
	}
	return $zahl;
}
}
function album_split ($chr, $asci='@'){
	if (strrpos($chr,$asci)){
		$nama = substr(strrchr($chr, $asci), 1 );
	}else {
		$nama = $chr;
	}
	
	return $nama;
}
function cekimage($width, $height,$image_max_width,$image_max_height){

$Stop = true;
$lebar = 0;
$tinggi = 0;
if ($width <=$image_max_width or $height <= $image_max_height)
{
	//$testsize = getimagesize ("");
	$lebar = $width;
	$tinggi = $height;
	$Stop = true;
	
}	
	
if ($width >=$image_max_width or $height >= $image_max_height)
{
	//$testsize = getimagesize ("");
	//$lebar = $width;
	//$tinggi = $height;
	$Stop = false;
	
}		


//periksa lebar dan tinggi

if ($width > $height && !$Stop) {
		$lebar = $image_max_width ;
		$tinggi = round ( ceil(($height / $width) * $lebar) , 0);
		$Stop = true;
	}
	
// periksa apakah tinggi lebih besar dari lebar

if  ($height > $width && !$Stop) {
			$tinggi = $image_max_height;
			$lebar =  round ( ceil(($width / $height	) * $tinggi) , 0);
			$Stop = true;
		}
return array ($lebar, $tinggi);

} //--end fungsi


?>