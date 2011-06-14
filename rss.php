<?
/**
 * AuraCMS v2.2
 * auracms.org
 * December 03, 2007 07:29:56 AM 
 * Author: 	Arif Supriyanto     - arif@ayo.kliksini.com  - +622470247569
 *		Iwan Susyanto, S.Si - admin@auracms.org      - 0281 327 575 145
 *		Rumi Ridwan Sholeh  - floodbost@yahoo.com    - 0817 208 401
 * 		http://www.auracms.org
 *		http://www.iwan.or.id
 *		http://www.ridwan.or.id
 */

ob_start();
header("content-type: text/xml; charset=utf-8");

include "includes/config.php";
include "includes/mysql.php";
include 'includes/feedcreator.class.php'; 

global $koneksi_db;
$_GET['aksi'] = isset($_GET['aksi']) ? $_GET['aksi'] : 'rss20';
$rss = new UniversalFeedCreator(); 
$rss->useCached(); 
$rss->title 		= $judul_situs; 
$rss->description 	= $slogan; 
$rss->link 		= $url_situs; 
$rss->feedURL 		= $url_situs."/".$_SERVER['PHP_SELF'];
$rss->syndicationURL 	= $url_situs; 
$rss->cssStyleSheet 	= NULL; 

$image = new FeedImage(); 
$image->title 		= $slogan; 
$image->url 		= "$url_situs/images/browser-48x48.png"; 
$image->link 		= $url_situs; 
$image->description 	= "Feed provided by AuraCMS. Click to visit."; 

$rss->image = $image; 

// Ngambil dari database 

$hasil = $koneksi_db->sql_query( "SELECT * FROM artikel WHERE publikasi=1 ORDER BY id DESC LIMIT 10" );

while ($data = $koneksi_db->sql_fetchrow($hasil)) {

	$tanggal  = $data['tgl'];		
	$judulnya = $data[1];
	$isinya   = $data[2];
	$id	  = $data[0];
	$author   = $data[3];

	$item = new FeedItem(); 
	$item->title 		= $judulnya;
	$item->link 		= $url_situs."-article-".$id."-".AuraCMSSEO($judulnya).".html";
	$item->description 	= limitTXT(strip_tags($isinya),250); 	
	$item->date   = strtotime($tanggal); 
	$item->source = $url_situs;
	$item->author = $author;
		 
	$rss->addItem($item); 

} 

// valid format strings are: RSS0.91, RSS1.0, RSS2.0, PIE0.1 (deprecated),
// MBOX, OPML, ATOM, ATOM10, ATOM0.3, HTML, JS


$rss->outputFeed("RSS2.0");

?>