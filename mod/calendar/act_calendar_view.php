<?
if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}

$content = '<h4 class="bg">Calender Event</h4>';

$seldate = (int)int_filter ($_GET['sel_date']);	
$t = getdate($seldate);
$_GET['waktu_akhir'] = isset($_GET['waktu_akhir']) ? $_GET['waktu_akhir'] : null;
$u = getdate((int)int_filter ($_GET['waktu_akhir']));
if (isset ($_GET['sel_date'])){

$content .='<div class="border">';	
$content .= '<table width="100%">';	
	
	
	
 $JUDULCAL = array ();
    $TMPpesan = array() ;
    $awalbulandengannol = $t['mon'] >= 10 ? $t['mon'] : '0'.$t['mon'];
    $varwaktucalender = $t['year'] . '-' . $awalbulandengannol . '-' . $t['mday'];
    
    $awalbulandengannol2 = $u['mon'] >= 10 ? $u['mon'] : '0'.$u['mon'];
    $varwaktucalender2 = $u['year'] . '-' . $awalbulandengannol2 . '-' . $u['mday'];
  

   
    $cekdate = mysql_query ("SELECT `judul`,`waktu_mulai`,`waktu_akhir`,`isi` FROM `tbl_kalender` WHERE `waktu_mulai` = '$varwaktucalender' OR `waktu_akhir` = '$varwaktucalender2' ORDER BY `waktu_mulai`");
    while ($getdate = mysql_fetch_assoc($cekdate)){
	   // print_r($getdate);
	    $WKTMULAI = $getdate['waktu_mulai'];
	    $WKTAKHIR = $getdate['waktu_akhir'];
	    $GTTGL = (int)substr($WKTMULAI, -2, 2);
	    $TGLMULAI[$GTTGL] = $GTTGL; // 
	    $JUDULCAL[$GTTGL] = $getdate['judul'];
	    $idssss =  '<b>'.$getdate['judul'].'</b><br><small>Periode Awal : '.converttgl ($WKTMULAI).'<br>Periode Akhir : '.converttgl ($WKTAKHIR).'</small>
                        <br>'.limitTXT($getdate['isi'],150).'';
	    
	 
	    $content .= '<tr><td style="font-weight:bold;border-bottom:solid 1px #efefef">'.$getdate['judul'].'</td></tr>';
	     $content .= '<tr><td style="color:orange">'.converttgl ($WKTMULAI).' S/D '.converttgl ($WKTAKHIR).'</td></tr>';
	    $content .= '<tr><td style="border-bottom:solid 1px #efefef">'.nl2br($getdate['isi']).'</td></tr>';
    	$content .= '<tr><td>&nbsp;</td></tr>';	
    		
    	
    	
    		
    		
    }


$content .= '</table>';	
$content .= '</div>';	



}

echo $content;

?>

