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

if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}

function select_value ($name, $selected, $value = array (),$opt='',$alert='',$pilihan='-pilih-') {
	

$admin ="<select name='$name' size='1' $opt $alert>"; 
$admin .="<option value=''>$pilihan</option>";
if (is_array ($value)){
foreach ($value as $k=>$v) {
		
					if (strtolower($k) == strtolower($selected)){
						$admin .="<option value=\"".$k."\" selected>$v</option>";
						}else {
							$admin .="<option value=\"".$k."\">$v</option>";
							}

}

}  
   
$admin .="</select>";     	
	
return $admin;	
}

function input_form ($alert, $nama, $value, $size=28, $type='text',$option=''){
if (!empty($value)) {$values = 'value="'.$value.'"';}else {$values='';}
$txt = "<input $alert onblur=\"$nama.style.color='#6A8FB1'; this.className='inputblur'\" onfocus=\"$nama.style.color='#FB6101'; this.className='inputfocus'\" type='$type' name='$nama' size='$size' $values $option>";
	
return $txt;	
}




?>