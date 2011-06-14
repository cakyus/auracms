var pages = {
xmlhttp:function(){
var xmlhttp = false;
try {
xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
} catch (e) {
try {
xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
} catch (E) {
xmlhttp = false;
}
}
if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
xmlhttp = new XMLHttpRequest();
}
return xmlhttp;
},
indexs:function(query){
$e('load').style.display = 'block';
xmlhttp = pages.xmlhttp();

query = query == null ? 'js/pages/pages_data.php' : 'js/pages/pages_data.php?'+query;
xmlhttp.open("GET", query);
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
var Json = eval ("("+xmlhttp.responseText+")");	

var isi = '';

total = Json.pages.length;
if (total > 0){
var warna = '';
for(i=0;i<total;i++){
warna = warna == '' ? ' bgcolor=#efefef' : '';	
isi += '<tr'+warna+' id="trID_'+Json.pages[i].id+'"><td><a title="Lihat Halaman Ini" href="index.php?pilih=hal&amp;id='+Json.pages[i].id+'">'+Json.pages[i].judul+'</a></td><td><a onclick="pages.deleted(\''+Json.pages[i].id+'\')" style="cursor:pointer;color:red">Delete</a> - <a title="edit dengan id '+Json.pages[i].id+'" onclick="pages.edited(1,null,\''+Json.pages[i].id+'\')" style="cursor:pointer;color:orange">Edit</a> - <a href="admin.php?pilih=admin_menu&amp;aksi=add&amp;url='+encodeURIComponent('index.php?pilih=hal&id='+Json.pages[i].id)+'">Buat Menu</a> - <a href="admin.php?pilih=admin_menu&amp;aksi=addsub&amp;url='+encodeURIComponent('index.php?pilih=hal&id='+Json.pages[i].id)+'">Buat Submenu</a></td></tr>';	

}	

}


var html = '';
html += '<table width=100%><tbody id="tbody">';
html += isi;
html += '</tbody></table>';
if (Json.pagging != '') html += Json.pagging;
$e('respon').innerHTML = html;
$e('load').style.display = 'none';

}
		
};
xmlhttp.send(null);	

	
},
deleted:function(id){
if (confirm('Deleted Halaman Dengan ID = '+id)){	
var resp = pages.xmlhttp();
resp.open("GET", 'js/pages/pages_data.php?action=delete&id='+id);
resp.onreadystatechange = function() {
if (resp.readyState == 4 && resp.status == 200) {
Fat.fade_element('trID_'+id,null,1000,'#FF3333');
setTimeout('pages.indexs()',1100);
}
};
resp.send(null);

}
},
edited_click:function(obj,htmleditor,varID){
if (htmleditor == 1) updateTextArea('textarea1');
var op = pages.xmlhttp();
op.open("POST", 'js/pages/pages_data.php?action=edit_saved&id='+varID);
op.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
op.onreadystatechange = function() {
if (op.readyState == 4 && op.status == 200) {
var Json = eval("("+op.responseText+")");	
if (Json.error == true){
alert (Json.errorpesan);		
}else {
alert('Data Berhasil Disimpan');	
pages.indexs('');	
}


}
};
op.send(postForm.getFormValues(obj));

return false;	
},
edited:function(htmleditor,request,varID){


var clickedhtml = (htmleditor == 1) ? '<a style="cursor:pointer" onclick="updateTextArea(\'textarea1\');pages.edited(0,postForm.getFormValues(\'frm\'),\''+varID+'\')">TEXTmode</a>' : '<a style="cursor:pointer" onclick="pages.edited(1,postForm.getFormValues(\'frm\'),\''+varID+'\')">HTMLmode</a>';


if (typeof request != 'undefined' && request != null){
var gtform = document.getElementById('frm');
var querystringval = request.split('&');
gtform.judul.value = decodeURIComponent(querystringval[0].split('=')[1]);	
gtform.konten.value = decodeURIComponent(querystringval[1].split('=')[1]);

}

if (typeof varID == 'string' && typeof varID != 'undefined'){

var xmlhttp = pages.xmlhttp();
xmlhttp.open("POST", 'js/pages/pages_data.php?action=edit&id='+varID);
//op.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
var Json = eval ("("+xmlhttp.responseText+")");
Json.konten = Json.konten.replace(/</g, '&lt;');
Json.konten = Json.konten.replace(/>/g, '&gt;');

var html = '';
var txtarea = '<textarea name="konten" rows="20" cols="60"></textarea>';
html += '<b>Edit Halaman Web</b><blockquote><b>Aturan :</b><br>Harus menggunakan format HTML.<br>Catatan : aturan ini berbeda dengan penulisan artikel.</blockquote>';
html += '<form method="post" onsubmit="return false" name="frm" id="frm"><table><tr><td>Judul</td><td valign="top">:</td><td><input type="text" size="50" name="judul" value="'+Json.judul+'"></td></tr><tr><td valign="top">Isi</td><td valign="top">:</td><td><textarea name="konten" rows="20" cols="60" id="textarea1">'+Json.konten+'</textarea>'+clickedhtml+'<br><br></td></tr><tr><td></td><td></td><td><input onclick="pages.edited_click(\'frm\',\''+htmleditor+'\',\''+varID+'\');" type="button" name="submit" value="Send"></td></tr></table></form>';
$e('respon').innerHTML = html;
if (htmleditor == 1) 
{
	
	if (typeof imagesDir == 'undefined') {setTimeout("generate_wysiwyg('textarea1')",1500);}
	if (typeof imagesDir == 'string') {setTimeout("generate_wysiwyg('textarea1')",0);}	
	
}
	
}
};
xmlhttp.send(null);

}







},
created_click:function(obj,htmleditor){
if (htmleditor == 1) updateTextArea('textarea1');
if ($e(obj).judul.value == "") {alert('Error : form Judul Mesti Diisi');$e(obj).judul.focus();return false; }
if ($e(obj).konten.value == "") {alert('Error : form Konten/ISI Mesti Diisi');return false; }

var op = pages.xmlhttp();
op.open("POST", 'js/pages/pages_data.php?action=add');
op.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
op.onreadystatechange = function() {
if (op.readyState == 4 && op.status == 200) {
var Json = eval("("+op.responseText+")");	
if (Json.error == true){
alert (Json.errorpesan);		
}else {
alert('Data Berhasil Dimasukkan');	
$e(obj).reset();
pages.indexs('');

}


}
};
op.send(postForm.getFormValues(obj));

return false;	
},
created:function(htmleditor,request){

var titles = 'Buat Halaman Web Baru';
if (htmleditor == 1) 
{
	
	if (typeof imagesDir == 'undefined') {setTimeout("generate_wysiwyg('textarea1')",3000);}
	if (typeof imagesDir == 'string') {setTimeout("generate_wysiwyg('textarea1')",0);}	
	

}

if (typeof varID != 'undefined'){
titles = 'Edit Halaman Web';	
}



var clickedhtml = (htmleditor == 1) ? '<a style="cursor:pointer" onclick="updateTextArea(\'textarea1\');pages.created(0,postForm.getFormValues(\'frm\'))">TEXTmode</a>' : '<a style="cursor:pointer" onclick="pages.created(1,postForm.getFormValues(\'frm\'))">HTMLmode</a>';

var html = '';
var txtarea = '<textarea name="konten" rows="20" cols="60"></textarea>';
html += '<b>'+titles+'</b><blockquote><b>Aturan :</b><br>Harus menggunakan format HTML.<br>Catatan : aturan ini berbeda dengan penulisan artikel.</blockquote>';
html += '<form method="post" onsubmit="return false" name="frm" id="frm"><table><tr><td>Judul</td><td valign="top">:</td><td><input type="text" size="50" name="judul" value=""></td></tr><tr><td valign="top">Isi</td><td valign="top">:</td><td><textarea name="konten" rows="20" cols="60" id="textarea1"></textarea>'+clickedhtml+'<br><br></td></tr><tr><td></td><td></td><td><input onclick="pages.created_click(\'frm\',\''+htmleditor+'\');" type="button" name="submit" value="Send"></td></tr></table></form>';
$e('respon').innerHTML = html;


if (typeof request != 'undefined' && request != null){
var gtform = document.getElementById('frm');
var querystringval = request.split('&');
gtform.judul.value = decodeURIComponent(querystringval[0].split('=')[1]);	
gtform.konten.value = decodeURIComponent(querystringval[1].split('=')[1]);		
}



},
delrow:function(tbid, trid) {
	var tb= document.getElementById(tbid);
	var tr= document.getElementById(trid);
	tb.removeChild(tr);
  },
loadingTextInterval: setInterval(function(){
		if (document.getElementById("ellipsis") && document.getElementById('load').style.display == 'block'){
			var dots = document.getElementById("ellipsis").innerHTML;
			document.getElementById("ellipsis").innerHTML = (dots != "...") ? dots += "." : "";
		}
	}, 500),
dhtmlLoadScript : function (url)
   {
      var e = document.createElement("script");
	  e.src = url;
	  e.type="text/javascript";
	  document.getElementsByTagName("head")[0].appendChild(e);	  
   }	
	
};
document.getElementById('header_ajax').innerHTML = '<div class=border><a onclick="pages.indexs()" style="cursor:pointer">Home</a> | <a onclick="pages.created(1)" style="cursor:pointer">Buat Halaman Web Baru</a></div>';

$e=function(e){
return document.getElementById(e);	
};

