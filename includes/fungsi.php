<?

if (!defined('AURACMS_FUNC')) {
	Header("Location: index.php");
    exit;
}
function transcal(){

}
function kotakjudul($title, $content) {
    global  $theme;
    $thefile = addslashes(file_get_contents("themes/".$theme."/boxmenu.html"));
    $thefile = "\$r_file=\"".$thefile."\";";
    eval($thefile);
    echo $r_file;
}


function modul($posisi){
    global $koneksi_db,$STYLE_INCLUDE,$SCRIPT_INCLUDE;
    		$total = 0;
    		$numb = 0;
    	if (isset($_GET['pilih'])) {
	    	$pilih = mysql_escape_string(strip_tags($_GET['pilih']));
	    	$numb = mysql_num_rows(mysql_query("SELECT `id` FROM `actions` WHERE `modul` = '$pilih'"));
	    	$modulku = mysql_query("SELECT * FROM `actions` LEFT JOIN `modul` ON (`modul`.`id` = `actions`.`modul_id`) WHERE `actions`.`modul` = '$pilih' AND `actions`.`posisi` = '$posisi' ORDER BY `actions`.`order`");
	    	$total = mysql_num_rows($modulku);
	    	while($viewmoduls = mysql_fetch_assoc($modulku)) {
		    	
		    		 if (file_exists($viewmoduls['isi']) && $viewmoduls['type'] == 'module'){
                    include $viewmoduls['isi'];
                    
                	 	kotakjudul($viewmoduls['modul'], @$out,'');
	                   	$out = '';
            	}
            	if ($viewmoduls['type'] == 'block') {
	            	kotakjudul($viewmoduls['modul'], $viewmoduls['isi'],'');
            	}
	    		}
	    
    	}
	
    	if ($total == 0 && $numb == 0) {
    $modulku = $koneksi_db->sql_query( "SELECT * FROM modul WHERE published= 1 AND posisi= '$posisi' ORDER BY ordering" );
    	
                while ($viewmodul = $koneksi_db->sql_fetchrow($modulku)) {
	                if (file_exists($viewmodul['isi']) && $viewmodul['type'] == 'module'){
                    include $viewmodul['isi'];
                	 	kotakjudul($viewmodul['modul'], @$out,'');
	                   	$out = '';
            	}
            	if ($viewmodul['type'] == 'block') {
	            	kotakjudul($viewmodul['modul'], $viewmodul['isi'],'');
            	}
                	
                    
                }
          
            }  
               
}
function blok($posisi){
    global $koneksi_db;
	
                $modulku = $koneksi_db->sql_query( "SELECT * FROM blok WHERE published=1 AND posisi=$posisi ORDER BY ordering" );

                while ($viewmodul = $koneksi_db->sql_fetchrow($modulku)) {

                  kotakjudul($viewmodul['1'], $viewmodul['2'],'');

                }
                
}

function strip_ext($name){
            $ext = strrchr($name, '.');
            if($ext !== false) {
            $name = substr($name, 0, -strlen($ext));
            }
        return $name;
}

    /* Verifikasi kode HTML */

function gb($string) {

        $string = stripslashes(nl2br($string));

        return($string);

}

function gb0($string) {

        $string = stripslashes(nl2br($string));
        $string = htmlspecialchars($string);
        return($string);

}


function gb1($string) {

        $string = nl2br($string);
        return($string);

}


function gb2($string) {

        $string = htmlspecialchars($string);
        $string = nl2br($string);
        return($string);

}

function hlm($string) {

        $string = stripslashes($string);
        return($string);

}

function nohtml($string) {

        $string = stripslashes(htmlspecialchars($string));
        return($string);

}

function asli($string) {

        $string = htmlspecialchars($string);
        return($string);

}



function themenews($id, $title, $ket, $content, $author='') {
    global $theme;
    $thefile = addslashes(file_get_contents("themes/".$theme."/theme-news.html"));
    $thefile = "\$r_file=\"".$thefile."\";";
    eval($thefile);
    echo $r_file;
}


// Format Password
function gen_pass($m) {
    $m = intval($m);
    $pass = "";
    for ($i = 0; $i < $m; $i++) {
        $te = mt_rand(48, 122);
        if (($te > 57 && $te < 65) || ($te > 90 && $te < 97)) $te = $te - 9;
        $pass .= chr($te);
    }
    return $pass;
}

switch(isset($_REQUEST['code'])) {
    case "gfx":
    $code = substr(hexdec(md5("".date("F j")."".$_REQUEST["random_num"]."".$sitekey."")), 2, 6);
        $image = ImageCreateFromJpeg("images/code_bg.jpg");
        $text_color = ImageColorAllocate($image, 100, 100, 100);
        Header("Content-type: image/jpeg");
        ImageString($image, 5, 12, 2, $code, $text_color);
        ImageJpeg($image, "", 50);
        ImageDestroy($image);
        exit;
        break;
}



// HTML and Word filter
function text_filter($message, $type="") {

    if (intval($type) == 2) {
        $message = htmlspecialchars(trim($message), ENT_QUOTES);
    } else {
        $message = strip_tags(urldecode($message));
        $message = htmlspecialchars(trim($message), ENT_QUOTES);
    }
   
    return $message;
}


// Mail check
function checkemail($email) {
    global $error;
    $email = strtolower($email);
    if ((!$email) || ($email=="") || (!preg_match("/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$/", $email))) $error .= "<center>Error, E-Mail address invalid!<br />Please use the standard format (<b>admin@domain.com</b>)</center>";
    if ((strlen($email) >= 4) && (substr($email, 0, 4) == "www.")) $error .= "<center>Error, E-Mail address invalid!<br />Please remove the beginning (<b>www.</b>)</center>";
    if (strrpos($email, " ") > 0) $error .= "<center>Error, E-Mail address invalid!<br />Please do not use spaces.</center>";
    return $error;
}

// Mail send
function mail_send($email, $smail, $subject, $message, $id="", $pr="") {
    $email = text_filter($email);
    $smail = text_filter($smail);
    $subject = text_filter($subject);
    $id = intval($id);
    $pr = (!$pr) ? "3" : "".intval($pr)."";
    $message = (!$id) ? "".$message."" : "".$message."<br /><br />IP: ".getenv("REMOTE_ADDR")."<br />User agent: ".getenv("HTTP_USER_AGENT")."";
    $mheader = "MIME-Version: 1.0\n"
    ."Content-Type: text/html; charset=utf-8\n"
    ."Reply-To: \"$smail\" <$smail>\n"
    ."From: \"$smail\" <$smail>\n"
    ."Return-Path: <$smail>\n"
    ."X-Priority: $pr\n"
    ."X-Mailer: AuraCMS v2.0 Mailer\n";
    @mail($email, $subject, $message, $mheader);
}


  

class paging_a { 
    function paging_a ($limit, $file) { 
      $this->rowperpage = $limit; 
      $this->pageperstg = 5; 
      $this->files = $file;
       
    } 
    
    
    function getPaging($jumlah, $pg, $stg) { 
	    if (!isset ($pg,$stg)){
	  		$pg = 1;
	  		$stg = 1;
  		}
  		$parse_url = array ();
  		$parse_url = parse_url ($_SERVER['REQUEST_URI']);
  		if (!isset($parse_url['query'])) $parse_url['query'] = '';
      $arr = explode("&", $parse_url['query']); 
      if (is_array($arr)) { 
	      $qs = '';
        for ($i=0;$i<count($arr);$i++) { 
          if (!is_int(strpos($arr[$i],"pg=")) && !is_int(strpos($arr[$i],"stg=")) && !is_int(strpos($arr[$i],"offset="))&& trim($arr[$i]) != "") {
	          $qs .= $arr[$i]."&"; 
          }
        } 
      } 
      
    
      if ($this->rowperpage<$jumlah) { 
        $allpage = ceil($jumlah/$this->rowperpage); 
        $allstg  = ceil($allpage/$this->pageperstg); 
        $minpage = (($stg-1)*$this->pageperstg)+1; 
        $maxpage = $stg*$this->pageperstg;
        if ($maxpage>$allpage) $maxpage = $allpage; 
        if ($allpage>1) {
	         if (($pg-1) == 1){
		            $newoffset = 0;
		            
	            } else {
		           $newoffset = (($pg-2)*$this->rowperpage);
	            } 
          $rtn  = "<table cellpadding=2 cellspacing=0><tr align=center valign=middle><td class=\"smallbody\">"; 
          if ($stg>1) $rtn .= "<a class=\"nextstage\" href=\"".$this->files."?".$qs."pg=".($minpage-1)."&stg=".($stg-1). "&offset=". $newoffset ."\">&laquo;&laquo;&laquo;</a> | "; 
          if ($pg>1) { 
            if ($pg==$minpage) {
	            if (($pg-1) == 1){
		            $newoffset = 0;
		            
	            } else {
		           $newoffset = (($pg-2)*$this->rowperpage);
	            }
              $rtn .= "<a class=\"nextpage\" href=\"".$this->files."?".$qs."pg=".($pg-1)."&stg=".($stg-1). "&offset=".$newoffset."\">&laquo; Previous</a> | "; 
            } else { 
	            if (($pg-1) == 1){
		            $newoffset = 0;
		            
	            } else {
		           $newoffset = (($pg-2)*$this->rowperpage);
	            }
              $rtn .= "<a class=\"nextpage\" href=\"".$this->files."?".$qs."pg=".($pg-1)."&stg=$stg&offset=".$newoffset."\">&laquo; Previous</a> | "; 
            } 
          } 
          for ($i=$minpage;$i<=$maxpage;$i++) {
	          
            if ($i==$pg) { 
              $rtn .= "<b>$i</b> | "; 
            } else { 
	            if  ($i==1) {
		         $newoffset = 0;   
	          }else {
		          $newoffset = ($i-1)*$this->rowperpage;
	          }
              $rtn .= "<a href=\"".$this->files."?".$qs."pg=$i&stg=$stg&offset=$newoffset\" title='Page $i'>$i</a> | "; 
            } 
          } 
          if ($pg<=$maxpage) { 
            if ($pg==$maxpage && $stg<$allstg) { 
              $rtn .= " <a class=\"nextpage\" href=\"".$this->files."?".$qs."pg=".($pg+1)."&stg=".($stg+1)."&offset=".(($pg)*$this->rowperpage)."\">Next &raquo;</a> | "; 
            } elseif ($pg<$maxpage) { 
              $rtn .= " <a class=\"nextpage\" href=\"".$this->files."?".$qs."pg=".($pg+1)."&stg=$stg&offset=" .(($pg)*$this->rowperpage). "\">Next &raquo;</a> | "; 
            } 
          } 
          if ($stg<$allstg) {
	          $rtn .= "<a class=\"nextstage\" href=\"".$this->files."?".$qs."pg=".($maxpage+1)."&stg=".($stg+1)."&offset=".(($maxpage)*$this->rowperpage)."\"> &raquo;&raquo;&raquo;</a> | ";
      		} 
          $rtn = substr($rtn,0,strlen($rtn)-3); 
          $rtn .= "</td></tr></table>"; 
          return $rtn; 
        } 
      } 
    } 
  }  

class paging {
    function paging ($limit) {
      $this->rowperpage = $limit;
      $this->pageperstg = 5;

    }


    function getPaging($jumlah, $pg, $stg) {
        if (!isset ($pg,$stg)){
              $pg = 1;
              $stg = 1;
          }
      $qs = '';
      $arr = explode("&",$_SERVER["QUERY_STRING"]);
      if (is_array($arr)) {
        for ($i=0;$i<count($arr);$i++) {
          if (!is_int(strpos($arr[$i],"pg=")) && !is_int(strpos($arr[$i],"stg=")) && !is_int(strpos($arr[$i],"offset="))&& trim($arr[$i]) != "") {
              $qs .= $arr[$i]."&amp;";
          }
        }
      }
      if ($this->rowperpage<$jumlah) {
        $allpage = ceil($jumlah/$this->rowperpage);
        $allstg  = ceil($allpage/$this->pageperstg);
        $minpage = (($stg-1)*$this->pageperstg)+1;
        $maxpage = $stg*$this->pageperstg;
        if ($maxpage>$allpage) $maxpage = $allpage;
        if ($allpage>1) {
             if (($pg-1) == 1){
                    $newoffset = 0;

                } else {
                   $newoffset = (($pg-2)*$this->rowperpage);
                }
          $rtn  = "<table cellpadding=\"2\" cellspacing=\"0\"><tr style=\"text-align:center\"><td class=\"smallbody\">";
          if ($stg>1) $rtn .= "<a class=\"nextstage\" href=\"".$_SERVER["PHP_SELF"]."?".$qs."pg=".($minpage-1)."&amp;stg=".($stg-1). "&amp;offset=". $newoffset ."\">&laquo;&laquo;&laquo;</a> | ";
          if ($pg>1) {
            if ($pg==$minpage) {
                if (($pg-1) == 1){
                    $newoffset = 0;

                } else {
                   $newoffset = (($pg-2)*$this->rowperpage);
                }
              $rtn .= "<a class=\"nextpage\" href=\"".$_SERVER["PHP_SELF"]."?".$qs."pg=".($pg-1)."&amp;stg=".($stg-1). "&amp;offset=".$newoffset."\">&laquo; Previous</a> | ";
            } else {
                if (($pg-1) == 1){
                    $newoffset = 0;

                } else {
                   $newoffset = (($pg-2)*$this->rowperpage);
                }
              $rtn .= "<a class=\"nextpage\" href=\"".$_SERVER["PHP_SELF"]."?".$qs."pg=".($pg-1)."&amp;stg=$stg&amp;offset=".$newoffset."\">&laquo; Previous</a> | ";
            }
          }
          for ($i=$minpage;$i<=$maxpage;$i++) {

            if ($i==$pg) {
              $rtn .= "<b>$i</b> | ";
            } else {
                if  ($i==1) {
                 $newoffset = 0;
              }else {
                  $newoffset = ($i-1)*$this->rowperpage;
              }
              $rtn .= "<a href=\"".$_SERVER["PHP_SELF"]."?".$qs."pg=$i&amp;stg=$stg&amp;offset=$newoffset\" title='Page $i'>$i</a> | ";
            }
          }
          if ($pg<=$maxpage) {
            if ($pg==$maxpage && $stg<$allstg) {
              $rtn .= " <a class=\"nextpage\" href=\"".$_SERVER["PHP_SELF"]."?".$qs."pg=".($pg+1)."&amp;stg=".($stg+1)."&amp;offset=".(($pg)*$this->rowperpage)."\">Next &raquo;</a> | ";
            } elseif ($pg<$maxpage) {
              $rtn .= " <a class=\"nextpage\" href=\"".$_SERVER["PHP_SELF"]."?".$qs."pg=".($pg+1)."&amp;stg=$stg&amp;offset=" .(($pg)*$this->rowperpage). "\">Next &raquo;</a> | ";
            }
          }
          if ($stg<$allstg) {
              $rtn .= "<a class=\"nextstage\" href=\"".$_SERVER["PHP_SELF"]."?".$qs."pg=".($maxpage+1)."&amp;stg=".($stg+1)."&amp;offset=".(($maxpage)*$this->rowperpage)."\"> &raquo;&raquo;&raquo;</a> | ";
              }
          $rtn = substr($rtn,0,strlen($rtn)-3);
          $rtn .= "</td></tr></table>";
          return $rtn;
        }
      }
    }
    
    
     function getPagingajax($jumlah, $pg, $stg) {
        if (!isset ($pg,$stg)){
              $pg = 1;
              $stg = 1;
          }
          $qs = '';
      $arr = explode("&",$_SERVER["QUERY_STRING"]);
      if (is_array($arr)) {
        for ($i=0;$i<count($arr);$i++) {
          if (!is_int(strpos($arr[$i],"pg=")) && !is_int(strpos($arr[$i],"stg=")) && !is_int(strpos($arr[$i],"offset=")) && !is_int(strpos($arr[$i],"math.rand=")) && trim($arr[$i]) != "") {
              $qs .= $arr[$i]."&";
          }
        }
      }
      if ($this->rowperpage<$jumlah) {
        $allpage = ceil($jumlah/$this->rowperpage);
        $allstg  = ceil($allpage/$this->pageperstg);
        $minpage = (($stg-1)*$this->pageperstg)+1;
        $maxpage = $stg*$this->pageperstg;
        if ($maxpage>$allpage) $maxpage = $allpage;
        if ($allpage>1) {
             if (($pg-1) == 1){
                    $newoffset = 0;

                } else {
                   $newoffset = (($pg-2)*$this->rowperpage);
                }
          $rtn  = array ();
          
          if ($stg>1) {
	          $rtn[] = array('link'=>"".$qs."pg=".($minpage-1)."&stg=".($stg-1). "&offset=". $newoffset,'title'=>'&laquo;&laquo;&laquo;');
      			}
          if ($pg>1) {
            if ($pg==$minpage) {
                if (($pg-1) == 1){
                    $newoffset = 0;

                } else {
                   $newoffset = (($pg-2)*$this->rowperpage);
                }
              $rtn[] = array ('link'=>"".$qs."pg=".($pg-1)."&stg=".($stg-1). "&offset=".$newoffset,'title'=>'&laquo; Previous');
            } else {
                if (($pg-1) == 1){
                    $newoffset = 0;

                } else {
                   $newoffset = (($pg-2)*$this->rowperpage);
                }
              $rtn[] = array('link'=>"".$qs."pg=".($pg-1)."&stg=$stg&offset=".$newoffset,'title'=>'&laquo; Previous');
            }
          }
          for ($i=$minpage;$i<=$maxpage;$i++) {

            if ($i==$pg) {
              $rtn[] = array('link'=>'','title'=>'<b>'.$i.'</b>');
            } else {
                if  ($i==1) {
                 $newoffset = 0;
              }else {
                  $newoffset = ($i-1)*$this->rowperpage;
              }
              $rtn[] = array('link'=>"".$qs."pg=$i&stg=$stg&offset=$newoffset",'title'=>$i);
            }
          }
          if ($pg<=$maxpage) {
            if ($pg==$maxpage && $stg<$allstg) {
              $rtn[] = array('link'=>"".$qs."pg=".($pg+1)."&stg=".($stg+1)."&offset=".(($pg)*$this->rowperpage),'title'=>'Next &raquo;');
            } elseif ($pg<$maxpage) {
              $rtn[] = array('link'=>"".$qs."pg=".($pg+1)."&stg=$stg&offset=" .(($pg)*$this->rowperpage),'title'=>'Next &raquo;');
            }
          }
          if ($stg<$allstg) {
              $rtn[] = array('link'=>"".$qs."pg=".($maxpage+1)."&stg=".($stg+1)."&offset=".(($maxpage)*$this->rowperpage),'title'=>'&raquo;&raquo;&raquo;');
              }
         // $rtn = substr($rtn,0,strlen($rtn)-3);
         
          return $rtn;
        }
      }
    }
    
    
    
  }

function cleanText ($text,$html=true) {
        $text = preg_replace( "'<script[^>]*>.*?</script>'si", '', $text );
        $text = preg_replace( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is', '\2 (\1)', $text );
        $text = preg_replace( '/<!--.+?-->/', '', $text );
        $text = preg_replace( '/{.+?}/', '', $text );
        $text = preg_replace( '/&nbsp;/', ' ', $text );
        $text = preg_replace( '/&amp;/', '&', $text );
        $text = preg_replace( '/&quot;/', '"', $text );
        $text = strip_tags( $text );
        $text = preg_replace("/\r\n\r\n\r\n+/", " ", $text);
        $text = $html ? htmlspecialchars( $text ) : $text;
        return $text;
}

function validate_url($url) {
   return preg_match("/(((ht|f)tps*:\/\/)*)((([a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3}))|(([0-9]{1,3}\.){3}([0-9]{1,3})))((\/|\?)[a-z0-9~#%&'_\+=:\?\.-]*)*)$/", $url);
}

function int_filter ($nama){
//memfilter karakter alpa menjadi kosong
if (is_numeric ($nama)){
return (int)preg_replace ( '/\D/i', '', $nama);
}
else {
    $nama = ltrim($nama, ';');
    $nama = explode (';', $nama);
    return (int)preg_replace ( '/\D/i', '', $nama[0]);
}
}

function aura_login (){
global $UserName,$Expire,$koneksi_db;

$user          = cleantext($_POST['username']);
$password      = md5($_POST['password']);
$query         = $koneksi_db->sql_query ("SELECT * FROM useraura WHERE user='$user' AND password='$password' AND tipe='aktif'");
$total         = $koneksi_db->sql_numrows($query);
$data          = $koneksi_db->sql_fetchrow ($query);


$koneksi_db->sql_freeresult ($query);
if ($total > 0 && $user == $data['user'] && $password == $data['password']){
$update = mysql_query("UPDATE `useraura` SET `lastlogin` = NOW() WHERE `user` = '$user'");
session_is_registered ('UserName') ;
$_SESSION['UserName']= $data['user'];
session_is_registered ('LevelAkses') ;
$_SESSION['LevelAkses']= $data['level'];
session_is_registered ('UserEmail') ;
$_SESSION['UserEmail']= $data['email'];
if($_SESSION['LevelAkses']=="Administrator"){
header ("location:admin.php");
exit;
}else{
header ("location:index.php");
exit;
}

}else {
return '<div class="error" style="width:70%">Wrong Username or Password</div>';
}

}

function cek_login (){
    global $UserName,$Expire;

    if (session_is_registered ('UserName') && isset ($_SESSION['UserName']) && !empty ($_SESSION['UserName'])){
    return true;
    }else {
        return false;
    }
}

function logout (){
    session_unregister ('UserName');
    session_unregister ('LevelAkses');
    session_unregister ('UserEmail');

header ("location:/index.php");
    exit;

}

function limittxt ($nama, $limit){
    if (strlen ($nama) > $limit) {
    $nama = substr($nama, 0, $limit) .'...';
    }else {
        $nama = $nama;
    }
return $nama;
}

function inisialpage($pagenumber){

	$rowsPerPage = $pagenumber;
	$pageNum = 1;
	if(isset($_GET['page']))
	{
		$pageNum = $_GET['page'];
	}
	$offset = ($pageNum - 1) * $rowsPerPage;
	if($offset<0) { $offset=0;}
	return $offset;

}

function showpage($fieldname,$tablename,$links,$pagenumber){

	$rowsPerPage = $pagenumber;
	$pageNum = 1;
	if($_GET['page']!="")
	{
		$pageNum = $_GET['page'];
	}
	$que=mysql_query("SELECT COUNT(".$fieldname.") as numrows FROM ".$tablename);
	$rs=mysql_fetch_array($que);
	$numrows = $rs['numrows'];
	$maxPage = ceil($numrows/$rowsPerPage);
	$self = $_SERVER['PHP_SELF'];
	if ($pageNum > 1)
	{
		$page = $pageNum - 1;
		$prev = " <a href=\"$self?".$links."&page=$page\"><</a> ";

		$first = " <a href=\"$self?".$links."&page=1\"><<</a> ";
	}
	else
	{
		$prev  = ' < ';
		$first = ' << ';
	}
	if ($pageNum < $maxPage)
	{
		$page = $pageNum + 1;
		$next = " <a href=\"$self?".$links."&page=$page\">></a> ";

		$last = " <a href=\"$self?".$links."&page=$maxPage\">>></a> ";
	}
	else
	{
		$next = ' > ';
		$last = ' >> ';
	}
	return $first . $prev . " <strong>$pageNum</strong> : <strong>$maxPage</strong> " . $next . $last;
}

function sensor($str){
$cek = mysql_query ("SELECT `word` FROM `sensor`");
while ($data = mysql_fetch_assoc($cek)){
$badwords[]	= "/".$data['word']."/i";
}
return preg_replace($badwords, "auraCMSsensor", $str);
}

function datetimes($tgl,$Jam=true){
/*Contoh Format : 2007-08-15 01:27:45*/
$tanggal = strtotime($tgl);
$bln_array = array (
			'01'=>'Januari',
			'02'=>'Februari',
			'03'=>'Maret',
			'04'=>'April',
			'05'=>'Mei',
			'06'=>'Juni',
			'07'=>'Juli',
			'08'=>'Agustus',
			'09'=>'September',
			'10'=>'Oktober',
			'11'=>'November',
			'12'=>'Desember'
			);
$hari_arr = Array ('0'=>'Minggu',
				   '1'=>'Senin',
				   '2'=>'Selasa',
					'3'=>'Rabu',
					'4'=>'Kamis',
					'5'=>'Jum`at',
					'6'=>'Sabtu'
				   );
$hari = @$hari_arr[date('w',$tanggal)];
$tggl = date('j',$tanggal);
$bln = @$bln_array[date('m',$tanggal)];
$thn = date('Y',$tanggal);
$jam = $Jam ? date ('H:i:s',$tanggal) : '';
return "$hari, $tggl $bln $thn $jam";			
}






function menu_tabs ($link,$url){



$data = '<DIV id="tabsB"><UL>';
if (is_array ($link)){
	foreach ($link as $key=>$value){
		parse_str(str_replace ('&amp;','&',$value),$output);
		if (@$output['action'] == $url){
		$data .= '<LI id="current"><A href="'.$value.'"><SPAN>'.$key.'</SPAN></A></LI>';
			}else {
				$data .= '<LI><A href="'.$value.'"><SPAN>'.$key.'</SPAN></A></LI>';
				  }
	}
  
}  
$data .= '</UL></DIV>
<br>
';	
return $data;	
}


function input_text ($name,$value,$type='text',$size=33,$opt=''){
	global $_input;
	$value = cleantext(stripslashes($value));

	//$focus =  ' onblur="this.style.color=\'#6A8FB1\'; this.className=\'\'" onfocus="this.style.color=\'#FB6101\'; this.className=\'inputfocus\'"';
return '<input type="'.$type.'" name="'.$name.'" size="'.$size.'" '.@$_input[$name].' value="'.$value.'"'.$opt.' />';	
}

function input_alert($name){
	global $_input;
$_input[$name] = ' class="inputfocus_alert"';	
}

function js_cek ($form,$name){
	
$content = '<script language="javascript" type="text/javascript">
function cek(){
';

/**
if (document.input_siswa.judul.value=="") {
alert("Judul agendanya apa?");
document.input_siswa.judul.focus();
return false
}
**/

if (is_array ($name)){
	
foreach ($name as $k=>$v){
	
$content .= '
if (document.'.$form.'.'.$k.'.value=="") {
alert("'.$v.'");
document.'.$form.'.'.$k.'.focus();
return false
}

';	
	
}	
	
	
	
}


$content .= '
return true
}
</script>';
return $content;
}

function input_textarea ($name,$value,$rows=2,$cols=36,$opt){
	global $_input;
	$_POST = !isset ($_POST) ? array() : $_POST;
	$focus = count($_POST) <= 0 ? ' onblur="this.style.color=\'#6A8FB1\'; this.className=\'\'" onfocus="this.style.color=\'#FB6101\'; this.className=\'inputfocus\'"' : '';
$value = stripslashes($value);	
return '<textarea rows="'.$rows.'" name="'.$name.'" '.@$_input[$name].$focus.' cols="'.$cols.'"'.$opt.'>'.$value.'</textarea>';;	
}

function converttgl ($date){
$bln_array = array ('01'=>'Januari',
			'02'=>'Februari',
			'03'=>'Maret',
			'04'=>'April',
			'05'=>'Mei',
			'06'=>'Juni',
			'07'=>'Juli',
			'08'=>'Agustus',
			'09'=>'September',
			'10'=>'Oktober',
			'11'=>'November',
			'12'=>'Desember'
			);
$date = explode ('-',$date);

return $date[2] . ' ' . $bln_array[$date[1]] . ' ' . $date[0];			
				
}



function referer_encode (){
return base64_encode(basename($_SERVER['PHP_SELF']) .'?'. $_SERVER['QUERY_STRING']);
}

function referer_decode ($url){
return base64_decode($url);	
}

function extension($file)
{
    $pos = strrpos($file,'.');
    if(!$pos)
        return 'Unknown';
    $str = substr($file, $pos, strlen($file));
    return strtolower ($str);
}

function bukafile($filename){
$fp = @fopen($filename, "r");
$sizeof = (@filesize($filename) == 0) ? 1 : filesize($filename);
return @fread($fp, $sizeof);
	fclose($fp);
} 
##------ End Fungsi

##------ Fungsi Tulis file
function tulisfile ($filename , $nilai){
$file = fopen ($filename, "w+");
return fwrite ($file,$nilai);
fclose($file);	
}

function alttxt ($html){
$data = str_replace ('"','&quot;',$html);
//$data = str_replace ("'","\'",$data);
$data = addslashes ($data);
$data = preg_replace ('/([\r\n])[\s]+/', '<br>',wordwrap($data,35,' ',1));
return $data;
}



function is_valid_email($mail) {
	// checks email address for correct pattern
	// simple: 	"/^[-_a-z0-9]+(\.[-_a-z0-9]+)*@[-a-z0-9]+(\.[-a-z0-9]+)*\.[a-z]{2,6}$/i"
	$r = 0;
	if($mail) {
		$p  =	"/^[-_a-z0-9]+(\.[-_a-z0-9]+)*@[-a-z0-9]+(\.[-a-z0-9]+)*\.(";
		// TLD  (01-30-2004)
		$p .=	"com|edu|gov|int|mil|net|org|aero|biz|coop|info|museum|name|pro|arpa";
		// ccTLD (01-30-2004)
		$p .=	"ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|az|ba|bb|bd|";
		$p .=	"be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|";
		$p .=	"cg|ch|ci|ck|cl|cm|cn|co|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|";
		$p .=	"ec|ee|eg|eh|er|es|et|fi|fj|fk|fm|fo|fr|ga|gd|ge|gf|gg|gh|gi|";
		$p .=	"gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|";
		$p .=	"im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|";
		$p .=	"ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mk|ml|";
		$p .=	"mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|";
		$p .=	"nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|";
		$p .=	"py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|";
		$p .=	"sr|st|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|";
		$p .=	"tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|";
		$p .=	"za|zm|zw";
		$p .=	")$/i";

		$r = preg_match($p, $mail) ? 1 : 0;
	}
	return $r;
}
function cek_ip ($check) {
$bytes = explode('.', $check);
		if (count($bytes) == 4 or count($bytes) == 6) {
			$returnValue = true;
			foreach ($bytes as $byte) {
				if (!(is_numeric($byte) && $byte >= 0 && $byte <= 255)) {
					$returnValue = false;
				}
			}
			return $returnValue;
		}
		return false;
}
function getIP(){
$banned = array ('127.0.0.1', '192.168', '10');
$ip_adr = @$_SERVER['HTTP_X_FORWARDED_FOR'];
$bool = false;
foreach ($banned as $key=>$val){
if (ereg("^$val",$ip_adr)){
$bool = true;
break;
}
}
if (empty($ip_adr) or $bool or !cek_ip($ip_adr)){
$ip_adr = @$_SERVER['REMOTE_ADDR'];	
}
return $ip_adr; 	
}

function posted($filename,$menit = 10){
//$file = basename($_SERVER['PHP_SELF']);
$file = $filename;
$IP = getIP();
$waktu = time() + 60 * $menit;
$in = mysql_query ("INSERT INTO `posted_ip` (`file`,`ip`,`time`) VALUES ('$file','$IP','$waktu')");
}
function cek_posted($filename){
$delete = mysql_query ("DELETE FROM `posted_ip` WHERE `time` < '".time()."'");
$cek = mysql_query ("SELECT COUNT(`ip`) AS IP FROM `posted_ip` WHERE `ip` = '".getIP()."' AND `file` = '".$filename."' AND `time` > '".time()."'");
$total = mysql_fetch_assoc($cek);
if ($total['IP'] >= 1){
return true;	
}else {
return false;	
}
}


function utf2html (&$str) {
    
    $ret = "";
    $max = strlen($str);
    $last = 0;  // keeps the index of the last regular character
    for ($i=0; $i<$max; $i++) {
        $c = $str{$i};
        $c1 = ord($c);
        if ($c1>>5 == 6) {  // 110x xxxx, 110 prefix for 2 bytes unicode
            $ret .= substr($str, $last, $i-$last); // append all the regular characters we've passed
            $c1 &= 31; // remove the 3 bit two bytes prefix
            $c2 = ord($str{++$i}); // the next byte
            $c2 &= 63;  // remove the 2 bit trailing byte prefix
            $c2 |= (($c1 & 3) << 6); // last 2 bits of c1 become first 2 of c2
            $c1 >>= 2; // c1 shifts 2 to the right
            $ret .= "&#" . ($c1 * 0x100 + $c2) . ";"; // this is the fastest string concatenation
            $last = $i+1;       
        }
        elseif ($c1>>4 == 14) {  // 1110 xxxx, 110 prefix for 3 bytes unicode
            $ret .= substr($str, $last, $i-$last); // append all the regular characters we've passed
            $c2 = ord($str{++$i}); // the next byte
            $c3 = ord($str{++$i}); // the third byte
            $c1 &= 15; // remove the 4 bit three bytes prefix
            $c2 &= 63;  // remove the 2 bit trailing byte prefix
            $c3 &= 63;  // remove the 2 bit trailing byte prefix
            $c3 |= (($c2 & 3) << 6); // last 2 bits of c2 become first 2 of c3
            $c2 >>=2; //c2 shifts 2 to the right
            $c2 |= (($c1 & 15) << 4); // last 4 bits of c1 become first 4 of c2
            $c1 >>= 4; // c1 shifts 4 to the right
            $ret .= '&#' . (($c1 * 0x10000) + ($c2 * 0x100) + $c3) . ';'; // this is the fastest string concatenation
            $last = $i+1;       
        }
    }
    $str=$ret . substr($str, $last, $i); // append the last batch of regular characters
    return $str;
}

function decodeURIComponent($str){
//return utf2html(rawurldecode($str));
return $str;
}

function wraptext($konten,$panjang=30){
$data_konten = explode (' ',$konten);	
$TMPmsg = array ();
        for ($i=0; $i<count($data_konten); $i++){
                if (strlen($data_konten[$i]) >= $panjang) {
                    $TMPmsg[] = wordwrap($data_konten[$i], $panjang, " <br />", TRUE);
                }else {
                	$TMPmsg[] = $data_konten[$i];
            		}
        }	
return implode (" ",$TMPmsg);	
}

function stripWhitespace($str) {
		$r = preg_replace('/[\n\r\t]+/', '', $str);
		return preg_replace('/\s{2,}/', ' ', $r);
	}
function stripImages($str) {
		$str = preg_replace('/(<a[^>]*>)(<img[^>]+alt=")([^"]*)("[^>]*>)(<\/a>)/i', '$1$3$5<br />', $str);
		$str = preg_replace('/(<img[^>]+alt=")([^"]*)("[^>]*>)/i', '$2<br />', $str);
		$str = preg_replace('/<img[^>]*>/i', '', $str);
		return $str;
	}
function stripScripts($str) {
		return preg_replace('/(<link[^>]+rel="[^"]*stylesheet"[^>]*>|<img[^>]*>|style="[^"]*")|<script[^>]*>.*?<\/script>|<style[^>]*>.*?<\/style>|<!--.*?-->/i', '', $str);
	}

function cleartext($txt) {
        return preg_replace('/[!"\#\$%\'\(\)\?@\[\]\^`\{\}~\*\/]/', '', $txt);
}

function AuraCMSSEO($string) {
	$string = str_replace(' ', '-', $string);
	$string = preg_replace('/[^0-9a-zA-Z-_]/', '', $string); 
	$string = str_replace('-', ' ', $string);
	$string = preg_replace('/^\s+|\s+$/', '', $string);
	$string = preg_replace('/\s+/', ' ', $string);
	$string = str_replace(' ', '-', $string);
	return strtolower(cleartext($string));
}

function create_thumbnail ($source, $destination, $new_width = 100, $new_height = 'auto', $quality = 85)
{
    $im_src = imagecreatefromjpeg($source);
    if (!$im_src) return;
    $im_width = imagesX($im_src);
    $im_height = imagesY($im_src);
    if( !is_int($new_width) && is_int($new_height) )
    {
        // resize the image based on height
        $ratio = $im_height / $new_height;
        $new_width = floor($im_width / $ratio);
    }
    elseif( is_int($new_width) && !is_int($new_height) )
    {
        // resize the image based on the width
        $ratio = $im_width / $new_width;
        $new_height = floor($im_height / $ratio);
    }
    // create blank image
    $im = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($im, $im_src, 0, 0, 0, 0, $new_width, $new_height, $im_width, $im_height);
    imagejpeg($im, $destination, $quality);
    imagedestroy($im);
    imagedestroy($im_src);
}

function rupiah_format($sString) {
	$iNegative = 0;
if(preg_match("/^-/",$sString)) {
		$iNegative	= 1;
		$sString	= preg_replace("|-|","",$sString);
	}

	$sString	= preg_replace("|,|","",$sString);
	$sFull		= split("[.]",$sString);
	$iCount		= count($sFull);
	if($iCount > 1) {
		$sFirst		= $sFull[0];
		$sSecond	= $sFull[1];
		$sNumCents	= strlen($sSecond);

		if($sNumCents == 2) {
		} else if($sNumCents < 2) {
			$sSecond = $sSecond . "0";
		} else if($sNumCents > 2) {
			$sTemp		= substr($sSecond,0,3);
			$Rounded	= round($sTemp,-1);
			$sSecond	= substr($Rounded,0,2);      
}  
	} else {
		$sFirst		= $sFull[0];    
		$sSecond	= "00";
	}
	$iLength = strlen($sFirst);
	if( $iLength <= 3 ) {
		$sString = $sFirst . "." . $sSecond; 
		if($iNegative == 1) {    
			$sString = "-" . $sString;
		}
		return $sString;
	} else {
		$iLoopCount		= intval( ( $iLength / 3 ) );
		$iSectionLength = -3;
		for( $i = 0; $i < $iLoopCount; $i++ ) {
			$aSection[$i] = substr( $sFirst, $iSectionLength, 3 );
			$iSectionLength = $iSectionLength - 3;
		}
		$iStub = ( $iLength % 3 );    
		if( $iStub != 0 ) {
			$aSection[$i] = substr( $sFirst, 0, $iStub );
		}

		$iDone = implode( ".", array_reverse($aSection));
		$iDone = $iDone . "," . $sSecond;

		if($iNegative == 1) {    
			$iDone = "-" . $iDone;
		}
		return  "Rp. ".$iDone;
	}
}


class paging_s { 
    function paging_s ($limit, $aksi='', $query='', $pageperstg=5) { 
      $this->rowperpage = $limit; 
      $this->pageperstg = $pageperstg; 
      // $this->sendiri = $GLOBALS['URLSITE'] . basename ($_SERVER['PHP_SELF'], '.php') . $aksi;
       $this->sendiri = $aksi;
       $this->query = $query;
    } 
    
    
    function getPaging($jumlah, $pg, $stg,$aksi=null, $query=null) { 
	    if (!isset ($pg,$stg)){
	  		$pg = 1;
	  		$stg = 1;
  		}
  		
  		if ($aksi) $this->sendiri = $aksi;
       if ($query) $this->query = $query;
  
     
      if ($this->rowperpage<$jumlah) { 
        $allpage = ceil($jumlah/$this->rowperpage); 
        $allstg  = ceil($allpage/$this->pageperstg); 
        $minpage = (($stg-1)*$this->pageperstg)+1; 
        $maxpage = $stg*$this->pageperstg;
        if ($maxpage>$allpage) $maxpage = $allpage; 
        if ($allpage>1) {
	         if (($pg-1) == 1){
		            $newoffset = 0;
		            
	            } else {
		           $newoffset = (($pg-2)*$this->rowperpage);
	            } 
          $rtn  = "<table cellpadding=2 cellspacing=0><tr align=center valign=middle><td class=\"smallbody\">"; 
          if ($stg>1) $rtn .= "<a class=\"nextstage\" href=\"".$this->sendiri."".($minpage-1)."-".($stg-1). "-". $newoffset ."$this->query\">&laquo;&laquo;&laquo;</a> | "; 
          if ($pg>1) { 
            if ($pg==$minpage) {
	            if (($pg-1) == 1){
		            $newoffset = 0;
		            
	            } else {
		           $newoffset = (($pg-2)*$this->rowperpage);
	            }
              $rtn .= "<a class=\"nextpage\" href=\"".$this->sendiri."".($pg-1)."-".($stg-1). "-".$newoffset."$this->query\">&laquo; Previous</a> | "; 
            } else { 
	            if (($pg-1) == 1){
		            $newoffset = 0;
		            
	            } else {
		           $newoffset = (($pg-2)*$this->rowperpage);
	            }
              $rtn .= "<a class=\"nextpage\" href=\"".$this->sendiri."".($pg-1)."-$stg-".$newoffset."$this->query\">&laquo; Previous</a> | "; 
            } 
          } 
          for ($i=$minpage;$i<=$maxpage;$i++) {
	          
            if ($i==$pg) { 
              $rtn .= "<b>$i</b> | "; 
            } else { 
	            if  ($i==1) {
		         $newoffset = 0;   
	          }else {
		          $newoffset = ($i-1)*$this->rowperpage;
	          }
              $rtn .= "<a href=\"".$this->sendiri."$i-$stg-$newoffset$this->query\" title='Page $i'>$i</a> | "; 
            } 
          } 
          if ($pg<=$maxpage) { 
            if ($pg==$maxpage && $stg<$allstg) { 
              $rtn .= " <a class=\"nextpage\" href=\"".$this->sendiri."".($pg+1)."-".($stg+1)."-".(($pg)*$this->rowperpage)."$this->query\">Next &raquo;</a> | "; 
            } elseif ($pg<$maxpage) { 
              $rtn .= " <a class=\"nextpage\" href=\"".$this->sendiri."".($pg+1)."-$stg-" .(($pg)*$this->rowperpage). "$this->query\">Next &raquo;</a> | "; 
            } 
          } 
          if ($stg<$allstg) {
	          $rtn .= "<a class=\"nextstage\" href=\"".$this->sendiri."".($maxpage+1)."-".($stg+1)."-".(($maxpage)*$this->rowperpage)."$this->query\"> &raquo;&raquo;&raquo;</a> | ";
      		} 
          $rtn = substr($rtn,0,strlen($rtn)-3); 
          $rtn .= "</td></tr></table>"; 
          return $rtn; 
        } 
      } 
    } 
  }

//stoping xss,union and clike injection
if(!function_exists('stripos')) {
	function stripos_clone($haystack, $needle, $offset=0) {
		$return = strpos(strtoupper($haystack), strtoupper($needle), $offset);
		if ($return === false) {
			return false;
		} else {
			return true;
		}
	}
} else {
	// But when this is PHP5, we use the original function
	function stripos_clone($haystack, $needle, $offset=0) {
		$return = stripos($haystack, $needle, $offset=0);
		if ($return === false) {
			return false;
		} else {
			return true;
		}
	}
} 


// Additional security (Union, CLike, XSS)
if(isset($_SERVER['QUERY_STRING']) && (!stripos_clone($_SERVER['QUERY_STRING'], "ad_click"))) {
	$queryString = $_SERVER['QUERY_STRING'];
    if (stripos_clone($queryString,'%20union%20') OR stripos_clone($queryString,'/*') OR stripos_clone($queryString,'*/union/*') OR stripos_clone($queryString,'c2nyaxb0') OR stripos_clone($queryString,'+union+') OR (stripos_clone($queryString,'cmd=') AND !stripos_clone($queryString,'&cmd')) OR (stripos_clone($queryString,'exec') AND !stripos_clone($queryString,'execu')) OR stripos_clone($queryString,'concat')) {
    	die('Illegal Operation');
    }
}


?>