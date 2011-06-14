<?

if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}

global $style_include;
ob_start();
$style_include[] = <<<AuraCMS

<style type="text/css">
/*<![CDATA[*/
@import url("/mod/mod_hdate_ge/css/style.css");
/*]]>*/
</style>
AuraCMS;

$Iwan = '';
$ztoday = getdate(time() + (3600 * 4)); 
$zyr = $ztoday["year"];
$zd=$ztoday["mday"];
$zm=$ztoday["mon"];
$zy=$zyr;



if ( ( $zy > 1582 ) || ( ( $zy == 1582 )&&( $zm > 10 ) ) || ( ( $zy == 1582 ) && ( $zm == 10 ) && ( $zd > 14 )) )
	{
	$zjd= (int)( ( 1461* ($zy + 4800 + (int)( ($zm-14) /12) ))/4) + (int)((367*($zm-2-12*((int)(($zm-14)/12))))/12)-(int)((3*(int)(( ($zy+4900+(int)(($zm-14)/12))/100)))/4)+$zd-32075;
        }
 else
	{
        $zjd = 367*$zy-(int)((7*($zy+5001+(int)(($zm-9)/7)))/4)+(int)((275*$zm)/9)+$zd+1729777;
	}
		
$zl=$zjd-1948440+10632;
$zn=(int)(($zl-1)/10631);
$zl=$zl-10631*$zn+354;
$zj=((int)((10985-$zl)/5316))*((int)((50*$zl)/17719))+((int)($zl/5670))*((int)((43*$zl)/15238));
$zl=$zl-((int)((30-$zj)/15))*((int)((17719*$zj)/50))-((int)($zj/16))*((int)((15238*$zj)/43))+29;
$zm=(int)((24*$zl)/709);
$zd=$zl-(int)((709*$zm)/24);
$zy=30*$zn+$zj-30;

if($zm==1){ $bulan = "Muharram"; }
if($zm==2){ $bulan = "Safar"; }
if($zm==3){ $bulan = "Rabiul Awal"; }
if($zm==4){ $bulan = "Rabiul Akhir";}
if($zm==5){ $bulan = "Jamadil Awal";}
if($zm==6){ $bulan = "Jamadil Akhir";}
if($zm==7){ $bulan = "Rejab";}
if($zm==8){ $bulan = "Syabaan";}
if($zm==9){ $bulan = "Ramadhan";}
if($zm==10){ $bulan = "Syawal";}
if($zm==11){ $bulan = "Zulkaedah";}
if($zm==12){ $bulan = "Zulhijjah";}

if($ztoday["wday"]==0){ $hari = "Ahad"; }
if($ztoday["wday"]==1){ $hari = "Isnin"; }
if($ztoday["wday"]==2){ $hari = "Selasa"; }
if($ztoday["wday"]==3){ $hari = "Rabu"; }
if($ztoday["wday"]==4){ $hari = "Khamis";}
if($ztoday["wday"]==5){ $hari = "Jumaat";}
if($ztoday["wday"]==6){ $hari = "Sabtu";}

$Iwan .= '<div id="hijriah" style="text-align:center;">
    <div id="bulan">'.$bulan.'</div>
	<div id="hari">'.$zd.'</div>
	<div id="namahari">'.$hari.'</div>
    <div id="tahun">'.$zy.' HIJRIAH</div>
</div>';
echo $Iwan;

$out = ob_get_contents();
ob_end_clean();

?>