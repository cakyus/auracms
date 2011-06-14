<?php
// *********************************************************
// File votepolling.php
// File untuk memproses dan menampilkan hasil jejak pendapat
// *********************************************************

if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}

//$index_hal = 1;


$pid = int_filter(@$_POST['pid']);
$pilihan = int_filter(@$_POST['pilihan']);

$cetak['tengah'] = '<h4 class="bg">Jajak Pendapat</h4>';



$sekarang_timeout = time ();
$vote_lebih2x = false;	
if(isset($_POST['submit']))
{
	//setcookie("COOKIE_VOTE", "vote", time()+3600);
	
	
	
	
	
	$query1 = "SELECT * FROM polling WHERE pid='$pid'";
	if(cek_posted('polling_result.php'))
	{
	
	$vote_lebih2x = true;	
		
		
	}
	else
	{
		
		posted('polling_result.php');
		//---- baca data polling
		$hasil = mysql_query($query1);
		$data = mysql_fetch_array($hasil);
		$PJAWABAN_TMP = explode("#", $data["pjawaban"]);
		$jmljwb = count($PJAWABAN_TMP);
		$PJAWABAN_TMP[$pilihan]++;
		$PJAWABAN = '';
		for($i=0;$i<$jmljwb;$i++)
		{
			$PJAWABAN .= $PJAWABAN_TMP[$i] . "#";
		}
		$PJAWABAN = substr_replace($PJAWABAN, "", -1, 1);
		//-----------------------------------------------
	
		//---- simpan data terbaru
		$query2 = "UPDATE `polling` SET `pjawaban`='$PJAWABAN' WHERE `pid`='$pid'";
		mysql_query($query2);

		// ----------------------------------------------------------------------
	}
}




if (isset ($_POST['pid'])){


$pid = int_filter ($_POST['pid']);
//$type_poll = 'chart';
$data_s = mysql_fetch_array( mysql_query("SELECT * FROM polling WHERE pid='$pid'"));


//tampilkan data terbaru
$hasil = mysql_query("SELECT * FROM polling WHERE pid='$pid'");

$data = mysql_fetch_array($hasil);
$PJUDUL = $data["pjudul"];
$PPILIHAN = explode("#", $data["ppilihan"]);
$PJAWABAN = explode("#", $data["pjawaban"]);
$jmlpil = count($PPILIHAN);
$JMLVOTE = 0;
for($i=0;$i<$jmlpil;$i++)
{
	$JMLVOTE = $JMLVOTE + $PJAWABAN[$i];
}
// Jika tidak ada vote, tetapkan jumlah vote = 1 untuk menghindari pembagian dengan nol
if($JMLVOTE == 0)
{
	$JMLVOTE = 1;
}

$cetak['tengah'].='<div class="border" style="text-align:center;">';
$cetak['tengah'].='
    <link type="text/css" rel="stylesheet" href="mod/polling/css/table2chart.css">
<h4>'.$PJUDUL.'</h4>Total Voting : '.$JMLVOTE.'</div>
<div class="border" style="text-align:center;">
    <div id="bd">
        <div class="yui-b">
            <table class="tochart" summary="'.$PJUDUL.'" align="center">
              <caption>AuraCMS Polling</caption>
              <thead>
                <tr><th scope="col">AuraCMS Polling</th><th scope="col">Amount</th></tr>
              </thead>
              <tbody>';
	foreach ($PJAWABAN as $key => $val) {
                $cetak['tengah'].='<tr><td>'.$PPILIHAN[$key].'</td><td>'.$PJAWABAN[$key].'</td></tr>';
        }
$cetak['tengah'].='
              </tbody>
            </table>

        </div>
    </div>
<!-- <script src="mod/polling/js/table2chart.js"></script> -->
</div>
';


//include "includes/libchart/classes/libchart.php";
/*
	$chart = new PieChart(450, 250);
	$chart = new VerticalBarChart();

	$dataSet = new XYDataSet();
	foreach ($PJAWABAN as $key => $val) {
	$dataSet->addPoint(new Point($PPILIHAN[$key]." (".$PJAWABAN[$key].")", $PJAWABAN[$key]));
        }
	$chart->setDataSet($dataSet);
	$chart->setTitle($PJUDUL);
	$chart->render("images/generated/demo3.png");

 $cetak['tengah'] .='<div class="border"><img alt="Pie chart"  src="images/generated/demo3.png" /></div>';
*/

/*
	$chart = new VerticalBarChart(420, 300);
	$dataSet = new XYDataSet();
	foreach ($PJAWABAN as $key => $val) {
	$dataSet->addPoint(new Point($PPILIHAN[$key]." (".$PJAWABAN[$key].")", $PJAWABAN[$key]));
        }
	$chart->setDataSet($dataSet);
	$chart->setTitle($PJUDUL);
	$chart->render("images/generated/demo1.png");

 $cetak['tengah'] .='<div class="border"><img alt="Pie chart"  src="images/generated/demo1.png" /></div>';
*/

if ($vote_lebih2x){
$cetak['tengah'].= '<div class="error">Anda Telah Melakukan Vote Lebih dari sekali</div>';	
}


}else {
        $cetak['tengah'] .='<div class="error">Maaf Tidak ada Polling</div>';

}

echo $cetak['tengah'];
?>