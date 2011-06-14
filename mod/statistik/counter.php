<?
if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}

//Set Variables

//================= No need to change anything after here  ========================

$IPnum = "0.0.0.0"; //Set as a String
$userStatus = 0;
$maxadmindata = !isset($maxadmindata) ? 5 : $maxadmindata;
// Get the current IP number ------------------------------
$IPnum = getenv("REMOTE_ADDR");

//Get stored IP's from a file --------------------------------

//Compare it to the ones stored in ip_data.dat ---

$perintah = "SELECT * FROM usercounter WHERE id=1";

$hasil = mysql_query( $perintah );
$total = mysql_num_rows($hasil);
if ($total <= 0){
$upDate = mysql_query ("INSERT INTO `usercounter` (`id`,`ip`,`counter`,`hits`) VALUES ('1','$IPnum','1','1')");	
$hasil = mysql_query( $perintah );
}
while ($data = mysql_fetch_row($hasil)) {
	$IPdata=$data[1];
	$theCount=$data[2];
	$hits=$data[3];
}


$IParray = explode("-",$IPdata); //Make array of IPs

// Start comparing IPs

for($ipCount=0;$ipCount<count($IParray);$ipCount++){

	if($IParray[$ipCount]==$IPnum){$userStatus = 1;}//Been before                                 

}// End for loop

// OK it's a new visitor
// Store the IP number in case they ever come back.
// The counter, give it one.

$IPdata="";

if($userStatus == 0){
		$IPdata="$IPnum-";
		for ($i=0; $i<$maxadmindata; $i++):
			$IPdata .= "$IParray[$i]-";		
		endfor;

		$theCount++;

		$perintah="UPDATE usercounter SET ip='$IPdata',counter='$theCount' WHERE id=1";
		$hasil = mysql_query( $perintah );


}

$hits++;
$perintah="UPDATE usercounter SET hits='$hits' WHERE id=1";
$hasil = mysql_query( $perintah );

?>