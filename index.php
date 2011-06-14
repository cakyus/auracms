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
	class microTimer {
    function start() {
        global $starttime;
        $mtime = microtime ();
        $mtime = explode (' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;
    }
    function stop() {
        global $starttime;
        $mtime = microtime ();
        $mtime = explode (' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;
        $totaltime = round (($endtime - $starttime), 5);
        return $totaltime;
    }
}


include 'includes/session.php';
@header("Content-type: text/html; charset=utf-8;");
ob_start("ob_gzhandler");
session_register ('mod_ajax');
$_SESSION['mod_ajax'] = true;

$timer = new microTimer;
$timer->start();



if (file_exists("install.php")){
header ("location:install.php");
}

define('AURACMS_MODULE', true);
define('AURACMS_CONTENT', true);
include "includes/config.php";
include "includes/mysql.php";
include "includes/template.php";
global $judul_situs,$theme;
$_GET['aksi'] = !isset($_GET['aksi']) ? null : $_GET['aksi'];
$_GET['mod'] = !isset($_GET['mod']) ? null : $_GET['mod'];
$_GET['pilih'] = !isset($_GET['pilih']) ? null : $_GET['pilih'];
$_GET['act'] = !isset($_GET['act']) ? null : $_GET['act'];

/*
if ($COOKIE['stats'] != 'okdech'){
include 'includes/statistik.inc.php';
stats();
setcookie('stats', 'okdech', time()+ 3600);	
}
*/

if(isset($_GET['lang'])){

$style_include[] = <<<Iwan
<script type="text/javascript" src="//www.google.com/jsapi"></script>
    <script type="text/javascript">

    google.load("language", "1");

    function initialize() {
      var text = document.getElementById("text").innerHTML;
      google.language.detect(text, function(result) {
        if (!result.error && result.language) {
	  google.language.translate(text, result.language, "en",
	                            function(result) {
	    var translated = document.getElementById("translation");
	    if (result.translation) {
	      translated.innerHTML = result.translation;
            }
          });
        }
      });
    }
    google.setOnLoadCallback(initialize);

    </script>

Iwan;

}

$old_modules = !isset($old_modules) ? null : $old_modules;


ob_start();

$script_include[] = '';

switch($_GET['mod']) {
	
case 'yes':
	if (file_exists('mod/'.$_GET['pilih'].'/'.$_GET['pilih'].'.php') 
		&& !isset($_GET['act']) 
		&& !preg_match('/\.\./',$_GET['pilih'])) {
		include 'mod/'.$_GET['pilih'].'/'.$_GET['pilih'].'.php';
	} 	else if (file_exists('mod/'.$_GET['pilih'].'/act_'.$_GET['act'].'.php') 
				&& !preg_match('/\.\./',$_GET['pilih'])
				&& !preg_match('/\.\./',$_GET['act'])
				) 
				{
				include 'mod/'.$_GET['pilih'].'/act_'.$_GET['act'].'.php';
			
				} else {
				header("location:index.php");
				exit;
				 } 
break;	
	
default:
	if (!isset($_GET['pilih'])) {
		include 'content/normal.php';
	} else if (file_exists('content/'.$_GET['pilih'].'.php') && !preg_match("/\.\./",$_GET['pilih'])){
		include 'content/'.$_GET['pilih'].'.php';	
	} else {
		header("location:index.php");
		exit;		
	}
break;	
}

$content = ob_get_contents();
ob_end_clean();


///// left side /////////////////////
ob_start();
//Blok Menu
include "content/menuadmin.php";
include "content/menu.php";
include "content/topikbox.php";
echo "<!-- modul //-->";
modul(0);
echo "<!-- blok kiri //-->";
//blok(0);
echo "<!-- akhir blok //-->";
$leftside = ob_get_contents();
ob_end_clean(); 
///// left side /////////////////////


///// right side /////////////////////
if (!isset($index_hal)){
ob_start();
//include "content/cari.php";
echo "<!-- modul -->";
modul(1);
echo "<!-- blok kanan -->";
//blok(1);
$rightside = ob_get_contents();
ob_end_clean(); 
} else {
$style_include[] = '
<style type="text/css">
/*<![CDATA[*/
#main {
float: left;
margin-left: 0;
padding: 0;
width:72%;
}
/*]]>*/
</style>
';	
}
///// right side /////////////////////

if ($_GET['aksi'] == 'logout') {
logout ();
}

$style_include_out = !isset($style_include) ? '' : implode("",$style_include);
$script_include_out = !isset($script_include) ? '' : implode("",$script_include);
$rightside = !isset($rightside) ? '' : $rightside;
$leftside = !isset($leftside) ? '' : $leftside;

$define = array ('leftside'    => $leftside,
				 'url'     => $url_situs,
				 'content'     => $content,
				 'rightside'  => $rightside,
				 'judul_situs' => $judul_situs,
				 'style_include' => $style_include_out,
				 'script_include' => $script_include_out,
				 'meta_description' => $_META['description'],
				 'meta_keywords' => $_META['keywords'],
				 'timer' => $timer->stop()
                );
                
$tpl = new template ('themes/'.$theme.'/'.$theme.'.html');
$tpl-> define_tag($define);

$tpl-> cetak();
?>