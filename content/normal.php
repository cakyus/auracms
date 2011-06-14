<?
if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}
$tengah = '';
if (cek_login ()){

$user = $_SESSION['UserName'];
$hasil = $koneksi_db->sql_query( "SELECT * FROM user WHERE user='$user'" );
$data = mysql_fetch_assoc($hasil);
$user = $data['user'];

$tengah .='<br /><div class="border">';
$tengah .= "<h4>Anda masih dalam keadaan login sebagai : $user</h4>";
$tengah .= "<p>Jangan lupa untuk <a href=\"?aksi=logout\"><b>logout</b></a>, sebelum meninggalkan situs ini!</p>";
$tengah .='</div>';
}
global $koneksi_db, $maxkonten;

$perintah="SELECT * FROM halaman WHERE id=1";
$hasil = $koneksi_db->sql_query( $perintah );
$tengah .='<div class="border">';
$data = $koneksi_db->sql_fetchrow($hasil);
$tengah .= "<h4>$data[1]</h4>";
$tengah .= $data[2];
$tengah .='</div>';

$tengah .= <<<Iwan
<div class="rounded-grey-tab tab-panel  whoisTabsDedicated">
			   <div class="tab-panel-header unselectable tab-panel-header-plain">
				  <div class="tab-strip-wrap">
					 <ul class="tab-strip tab-strip-top">
						<li id="dedicated-view" class="tab-strip-active">
						   <a onclick="switchWhoisTab('dedicated');" class="tab-right"><em class="tab-left"><span class="tab-strip-inner"><span class="tab-strip-text">Promo Hosting</span></span></em></a>
						</li>
						<li id="benefit-view" class="">
						   <a onclick="switchWhoisTab('benefit');" class="tab-right"><em class="tab-left"><span class="tab-strip-inner"><span class="tab-strip-text">Keuntungan & Tambahan</span></span></em></a>
						</li>
						<li class="">
						<div class="clear"> </div>
					 </li></ul>
				  </div>
				  <div class="tab-panel-bwrap">
					 <div class="tab-panel-body tab-panel-body-top">
						<div class="tab-tl">
						   <div class="tab-tr">
							  <div class="tab-tc"></div>
						   </div>
						</div>
						<div class="tab-bwrap">
						   <div class="tab-ml">
							  <div class="tab-mr">
								 <div class="tab-mc">
								 <div class="tab-body-container">
										  <div class="wide-100">
Iwan;
										   $hasila = $koneksi_db->sql_query( "SELECT * FROM `halaman` WHERE `id`='6'" );
										   $hasilb = $koneksi_db->sql_query( "SELECT * FROM `halaman` WHERE `id`='7'" );
										   			   
										   $dataa 		= $koneksi_db->sql_fetchrow($hasila) ;
										   $datab 		= $koneksi_db->sql_fetchrow($hasilb) ;
										   
$tengah .= '
										  	<div id="whois-tab-dedicated" class="whois-tabbed float-content whois-tab-active" style="display: block;">
											'.$dataa['konten'].'											
										  	</div>
										  	<div id="whois-tab-benefit" class="whois-tabbed float-content" style="display: none;">
											'.$datab['konten'].'											
										  	</div>
										  	<div id="whois-tab-addition" class="whois-tabbed float-content" style="display: none;">
										  	'.$datac['konten'].'
											</div>
										  </div>
								 </div>
								 </div>
							   </div>
							</div>
							<div class="tab-bl">
							   <div class="tab-br">
								  <div class="tab-bc"></div>
							   </div>
							</div>
						 </div>
					 </div>
				  </div>
			   </div>

			</div>';
$tengah .='';

//beritahangat
global $koneksi_db,$maxdata, $maxkonten;

$hasil = $koneksi_db->sql_query( "SELECT * FROM `artikel` WHERE publikasi= 1 ORDER BY `id` DESC LIMIT $maxdata" );


while ($data = $koneksi_db->sql_fetchrow($hasil)) {

$data[5]= datetimes($data[5]);
$gambar = ($data['gambar'] == '') ? '' : '<img src="'.$data['gambar'].'" border="0" alt="'.$data['judul'].'" width="90" height="70" style="margin-right:5px; margin-top:5px; padding:3px; float:left;" />';

$tengah .='
<div id="translation"></div>
<h4 class="bg">'.$data['1'].'</h4>
<div id="news" class="news">
<span class="align-justify">'.$gambar.''.limitTXT(strip_tags($data[2]),300).'</span>
</div>		
		
<p class="post-footer">					
<a href="article-'.$data[0].'-'.AuraCMSSEO($data[1]).'.html" title="'.$data[1].'" class="readmore">Read more</a>
<span class="comments">Hits ('.$data[9].')</span>
<span class="date">'.$data[5].'</span>	
</p>';

}

echo $tengah;


?>