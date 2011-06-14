var news = {
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
dhtmlLoadScript : function (url,id){
      var e = document.createElement("script");
	  e.src = url;
	  e.type="text/javascript";
	  document.getElementsByTagName("head")[0].appendChild(e);
},
loadingTextInterval: setInterval(function(){
		if (document.getElementById("ellipsis") && document.getElementById('load').style.display == 'block'){
			var dots = document.getElementById("ellipsis").innerHTML;
			document.getElementById("ellipsis").innerHTML = (dots != "...") ? dots += "." : "";
		}
	}, 500),
indexs_cari:function(frm){
var frms = $(frm);
var cari = frms.search.value;
cari = encodeURIComponent(cari);
news.indexs('action=cariartikel&search='+cari);
return false;	
},
indexs:function(query){
loadartikelmasuk();
boxloading('Loading');
query = query == null ? 'mod/news/ajax/news_admin.php' : 'mod/news/ajax/news_admin.php?'+query;
xmlhttp = news.xmlhttp();
xmlhttp.open("GET", query,true);
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
$('load').style.display = 'none';
var Json = eval ("("+xmlhttp.responseText+")");	
if (typeof Json.list != 'object' || xmlhttp.responseText == ""){
$('respon').innerHTML = 'no Data';
return;	
}
var total = Json.list.length;
var loop = '';
var warna = '';
for(i=0;i<total;i++){
warna = (i % 2 == 0) ? ' class="mod_news_list1"' : ' class="mod_news_list2"';
loop += '<tr'+warna+' id="trID_'+Json.list[i].id+'" style="font-size:10px;"><td valign=top width=2px><input type="checkbox" name="artikel[]" value="'+Json.list[i].id+'" style="border:0px" onclick="news.click_perubahan_warna(this)"></td><td valign=top> <span class="mod_news_list_header">'+Json.list[i].judul+'</span><br />'+Json.list[i].tgl+' Post By : '+Json.list[i].user+'<br /><b>Topik :</b> '+Json.list[i].topik+' <b>Hits :</b> '+Json.list[i].hits+' <a onclick="news.comments(\'o=1&id='+Json.list[i].id+'\')" style="cursor:pointer;" title="View Comments"><b>Comment :</b></a> '+Json.list[i].komentar+'</td><td valign=top align=center><a onclick="news.edit(1,\''+Json.list[i].id+'\',\''+query+'\')" style="cursor:pointer">Edit</a></td></tr>';	
}

var html = '<form name="frmcari" id="frmcari" onsubmit="return news.indexs_cari(\'frmcari\');">Cari : <input type="text" name="search"> <input type="submit" value="cari"></form><br />';
html += '<form name="frm" id="frm"><table width=100% cellspacing=2 cellpadding=4px><tbody id="tbody">';
html += loop;
html += '</tbody></table></form>';
html += '<div class=border><input type="button" value="check All" onclick="checkall(\'frm\',\'artikel[]\')"> <input type="button" value="delete" style="background:red;color:#fff" onclick="news.deleted(\'frm\',\'artikel[]\',\''+query+'\')"></div>';
if (Json.pagging != '') html += Json.pagging;
$('respon').innerHTML = html;

events=function (evt){
evt = (evt) ? evt : event;
if (evt.keyCode == 46){	
news.deleted('frm','artikel[]',query);
return false;
}
if (evt.ctrlKey && evt.keyCode == 65){	
checkall('frm','artikel[]');
return false;
}	
	
};

if (navigator.userAgent.indexOf('Firefox') != -1 || navigator.userAgent.indexOf('Netscape') != -1){
window.onkeydown = events;
}else {
document.body.onkeydown = events;	
}

}

};
xmlhttp.send(null);
},
deleted:function(formName, boxName, referer){
var Querystring	= new Array();
if (referer.match(/\?/g,referer)){	
referer = referer.split('?');
referer = referer[1];
}


for(i = 0; i < document.getElementById(formName).elements.length; i++)
	{
		var formElement = document.getElementById(formName).elements[i];
		if(formElement.type == 'checkbox' && formElement.name == boxName && formElement.disabled == false && formElement.checked == true)
		{	
		Querystring.push('id[]='+formElement.value);
		}
	}
var qs = Querystring.join('&');
if (Querystring.length <= 0){
alert ('No Selected Item(s)');
return false;	
}
if (confirm('Deleted News '+Querystring.length+' Item(s)')){
$('load').style.display = 'block';
$('loadmessage').innerHTML = 'Delete';
xmlhttp = news.xmlhttp();
xmlhttp.open("GET", 'mod/news/ajax/news_admin.php?action=delete_artikel&'+qs,true);
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {	
//alert(xmlhttp.responseText);
$('load').style.display = 'none';
$('loadmessage').innerHTML = 'Loading';
news.indexs(referer);
}

};	
xmlhttp.send(null);	
	
}
			
},
edit_click:function(id,htmleditor,referer){

boxloading('Saving');
xmlhttp = news.xmlhttp();
xmlhttp.open("POST", 'mod/news/ajax/news_admin.php?action=editsaved&id='+id,true);
xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {	
var save = eval("("+xmlhttp.responseText+")");
boxloading('Loading');
if (save.error == true){	
alert(save.errorpesan);	
}else {
//alert ('data Berhasil di Update');
if (referer.match(/getArtikelMasuk/g)){
news.artikelmasuk(referer);
	
}else {
news.indexs(referer);
}
}


}

};
var posted = postForm.getFormValues('frm');
if (typeof tinyMCE == 'object') {
posted += '&konten='+encodeURIComponent(tinyMCE.get('textarea1').getContent());	
}
xmlhttp.send(posted);
	
},
edit:function(htmleditor,id,referer){
if (referer.match(/\?/g,referer)){	
referer = referer.split('?');
referer = referer[1];
}
boxloading('Editing');



xmlhttp = news.xmlhttp();
xmlhttp.open("GET", 'mod/news/ajax/news_admin.php?action=getNews&id='+id,true);
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {	
//alert(xmlhttp.responseText);
boxloading('Loading');
var Json = eval ("("+xmlhttp.responseText+")");
if (typeof Json.konten == 'undefined'){
alert ('Error: No Specifik ID');
return;	
}
Json.konten = Json.konten.replace(/</g, '&lt;');
Json.konten = Json.konten.replace(/>/g, '&gt;');
var listTopik = '<select name="topik" size=1>';
var totaltopik = Json.daftarTopik.length;
var Selected = '';
for(i=0;i<totaltopik;i++){
Selected = Json.topik == Json.daftarTopik[i].id ? ' selected' : '';
listTopik += '<option value="'+Json.daftarTopik[i].id+'"'+Selected+'>'+Json.daftarTopik[i].topik+'</option>';	
}
listTopik += '</select>';
var html = '';
html += '<blockquote><b>Aturan :</b> Dapat menggunakan format HTML/TEXT.<br>Catatan : aturan ini berbeda dengan penulisan artikel.</blockquote>';
html += '<form name="frm" id="frm" enctype ="multipart/form-data"><table>';
html += '<tr><td>Title</td><td>:</td><td><input type="text" name="judul" value="'+Json.judul+'" size=45></td></tr>';
html += '<tr><td>Topik</td><td>:</td><td>'+listTopik+'</td></tr>';
html += '<tr><td valign=top>Isi Berita</td><td valign=top>:</td><td><textarea name="konten" rows="20" cols="60" id="textarea1">'+Json.konten+'</textarea></td></tr>';
html += '<tr><td></td><td></td><td>Tags contoh : satu,dua,tiga dan empat,lima<br /><input type="text" name="tags" value="'+Json.tags+'" size="50" /></td></tr>';
html += '<tr><td>Url Gambar</td><td>:</td><td><input type="text" name="gambar" size=25 value="'+Json.gambar+'"></td></tr>';
html += '<tr><td></td><td></td><td><br /><input type="button" value="save" onclick="news.edit_click(\''+id+'\',\''+htmleditor+'\',\''+referer+'\')"> <input type="button" value="cancel" onclick="news.indexs(\''+referer+'\')"></td></tr>';

html += '</table></form>';
$('respon').innerHTML = html;
news.dhtmlLoadScript('js/tinymce/jscripts/tiny_mce/init.js');

}

};	
xmlhttp.send(null);	
document.body.onkeydown = null;
window.onkeydown = null;
},
addnews_click:function(htmleditor){
if ($('frm').judul.value == '') { alert('Error: Please Enter Title');$('frm').judul.focus();return false; }
if (typeof tinyMCE == 'object') {
if (tinyMCE.get('textarea1').getContent() == '') { alert('Error: Please Enter konten');return false; }	
}else {
if ($('frm').konten.value == '') { alert('Error: Please Enter Title');$('frm').judul.focus();return false; }	
}
boxloading('Saving');

xmlhttp = news.xmlhttp();
xmlhttp.open("post", 'mod/news/ajax/news_admin.php?action=addsaved',true);
xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {	
var save = eval("("+xmlhttp.responseText+")");

if (save.error == true){	
alert(save.errorpesan);	
}else {
//alert ('data Berhasil di Simpan');
$('savemessage').innerHTML = '<div class="sukses">Data Berhasil dimasukkan : <b>'+save.judul+'</b> ('+save.byte+') bytes</div>';
//news.addnews(htmleditor);
$('frm').judul.value = '';
$('frm').tags.value = '';
if (typeof tinyMCE == 'object') {
tinyMCE.get('textarea1').show();
}else {
$('frm').konten.value = '';
}


}
boxloading('Loading');
}

};
var posted = postForm.getFormValues('frm');
if (typeof tinyMCE == 'object') {
posted += '&konten='+encodeURIComponent(tinyMCE.get('textarea1').getContent());	
}
xmlhttp.send(posted);

	
},
addnews:function(htmleditor){

boxloading('Loading Topik');	
xmlhttp = news.xmlhttp();
xmlhttp.open("GET", 'mod/news/ajax/news_admin.php?action=getTopik',true);
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {	
//alert(xmlhttp.responseText);
boxloading('Loading Topik');
var Json = eval ("("+xmlhttp.responseText+")");
var listTopik = '<select name="topik" size=1>';
var totaltopik = Json.daftarTopik.length;
for(i=0;i<totaltopik;i++){
listTopik += '<option value="'+Json.daftarTopik[i].id+'">'+Json.daftarTopik[i].topik+'</option>';	
}
listTopik += '</select>';
var html = '';
html += '<blockquote><b>Aturan :</b>Dapat menggunakan format HTML/TEXT.<br>Catatan : aturan ini berbeda dengan penulisan artikel.</blockquote>';
html += '<div id="savemessage"></div>';
html += '<form name="frm" id="frm" enctype ="multipart/form-data"><table>';
html += '<tr><td>Title</td><td>:</td><td><input type="text" name="judul" size=45></td></tr>';
html += '<tr><td>Topik</td><td>:</td><td>'+listTopik+'</td></tr>';
html += '<tr><td valign=top>Isi Berita</td><td valign=top>:</td><td><textarea name="konten" rows="20" cols="60" id="textarea1"></textarea></td></tr>';
html += '<tr><td></td><td></td><td>Tags contoh : satu,dua,tiga dan empat,lima<br /><input type="text" name="tags" value="" size="50" /></td></tr>';
html += '<tr><td>Url Gambar</td><td>:</td><td><input type="text" name="gambar" size=25></td></tr>';
html += '<tr><td></td><td></td><td><br /><input type="button" value="save" onclick="news.addnews_click(\''+htmleditor+'\')"></td></tr>';

html += '</table></form>';
$('respon').innerHTML = html;
news.dhtmlLoadScript('js/tinymce/jscripts/tiny_mce/init.js');
events=function(evt){
evt = (evt) ? evt : event;
if (evt.ctrlKey && evt.keyCode == 83){	
news.addnews_click(htmleditor);
return false;
}
};
if (navigator.userAgent.indexOf('Firefox') != -1 || navigator.userAgent.indexOf('Netscape') != -1){
window.onkeydown = events;
}else {
document.body.onkeydown = events;	
}



}

};	
xmlhttp.send(null);

},
addtopik_click:function(){
if ($('frm').topik.value == '') { alert('Error: Please Enter Topic');$('frm').topik.focus();return false; }
if ($('frm').desc.value == '') { alert('Error: Please Enter Description');$('frm').desc.focus();return false; }
boxloading('Saving');
xmlhttp = news.xmlhttp();
xmlhttp.open("POST", 'mod/news/ajax/news_admin.php?action=addTopik',true);
xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {	
var save = eval("("+xmlhttp.responseText+")");

if (save.error == true){	
alert(save.errorpesan);	
}else {
//alert ('data Berhasil di Simpan');
$('frm').reset();
}

boxloading('Loading');
}

};
xmlhttp.send(postForm.getFormValues('frm'));	
},
addtopik:function(){
news.dhtmlLoadScript('js/post.js');
var html = '<div class=border>';
html += '<form name="frm" id="frm"><table>';
html += '<tr><td>Topic</td><td>:</td><td><input type="text" name="topik" size=20></td></tr>';
html += '<tr><td valign=top>Description</td><td valign=top>:</td><td><textarea rows="10" cols="40" name="desc"></textarea></td></tr>';
html += '<tr><td></td><td></td><td><br /><input type="button" value="save" onclick="news.addtopik_click()"></td></tr>';

html += '</table></form></div>';	
$('respon').innerHTML = html;
document.body.onkeydown = null;	
window.onkeydown = null;
},
listtopik_delete_confirm_click:function(id){
boxloading('Deleted');
xmlhttp = news.xmlhttp();
xmlhttp.open("POST", 'mod/news/ajax/news_admin.php?action=delTopik&id='+id,true);
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {	
//var save = eval("("+xmlhttp.responseText+")");
boxloading('Loading');
news.listtopik();
}

};
xmlhttp.send(null);	
},
listtopik_delete_confirm_hide:function(){
$('respon1').style.display = 'none';	
},
listtopik_delete_confirm:function(id){
if ($('respon1')){
$('respon1').style.display = 'inline';
$('respon1').innerHTML = '<div style="width:200px;padding-bottom:10px;border:1px solid #efefef"><blockquote>Apakah Anda Yakin Ingin Menghapus Data Ini ?<br>Semua Data Artikel,komentar Akan Terhapus Pada Topik Ini</blockquote><center><input type="button" value="Ya" onclick="news.listtopik_delete_confirm_click(\''+id+'\')"> <input type="button" value="tidak" onclick="news.listtopik_delete_confirm_hide()"></center></div><br />';	
	
}	
},
edittopik_confirm_click:function(id){
boxloading('Saving');
xmlhttp = news.xmlhttp();
xmlhttp.open("POST", 'mod/news/ajax/news_admin.php?action=editTopik&id='+id,true);
xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
boxloading('Loading');
var save = eval("("+xmlhttp.responseText+")");

if (save.error == true){	
alert(save.errorpesan);	
}else {
alert ('data Berhasil di Simpan');
news.listtopik();
}


}

};
xmlhttp.send(postForm.getFormValues('frm_'+id));	
},
edittopik_confirm_cancel:function(id){
var formId = $('frm_'+id);
$('Editable_topik_'+id).innerHTML = formId.topik.value;
$('Editable_ket_'+id).innerHTML = formId.ket.value;	
$('klikId_'+id).style.display = 'inline';

},
edittopik:function(id){
$('a_'+id).onclick = null;
$('Editable_topik_'+id).innerHTML = '<input type="text" name="topik" value="'+$('Editable_topik_'+id).innerHTML+'">';	
$('Editable_ket_'+id).innerHTML = '<input type="text" name="ket" value="'+$('Editable_ket_'+id).innerHTML+'" size="40">';	
$('Editable_ket_'+id).innerHTML += '<br /><input type="button" value="edit" onclick="news.edittopik_confirm_click(\''+id+'\')"> <input type="button" value="cancel" onclick="news.edittopik_confirm_cancel(\''+id+'\')">';
$('klikId_'+id).style.display = 'none';
},
listtopik_index_onclick:function(id){
$('a_'+id).onclick = news.indexs('action=topikDetail&id='+id);
},
listtopik:function(){
news.dhtmlLoadScript('js/post.js');	
boxloading('Loading');
xmlhttp = news.xmlhttp();
xmlhttp.open("POST", 'mod/news/ajax/news_admin.php?action=listTopik',true);
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
$('load').style.display = 'none';		
var Json = eval("("+xmlhttp.responseText+")");

var html = '';
html += '<blockquote><b>Perhatian !!!</b><br>Untuk Mengedit Berita Silahkan Pilih Kategori di Bawah ini dimana Berita yang akan di edit berada dalam kategori tersebut</blockquote>';

if (typeof Json.daftarTopik != 'object'){
html += 'no Data';
return false;	
}
html += '<div id="respon1"></div>';
var total = Json.daftarTopik.length;
html += '<table width=100% cellspacing=0><tr bgcolor=#d1d1d1 style="font-weight:bold;height:20px"><td style="width:10%;text-align:center">No</td><td>Category</td><td></td><td></td></tr>';
var warna = '';
for(i=0;i<total;i++){
warna = warna == '' ? ' bgcolor=#efefef' : '';
html += '<tr'+warna+'><td style="width:10%;text-align:center">'+(i+1)+'</td><td><form id="frm_'+Json.daftarTopik[i].id+'" name="frm_'+Json.daftarTopik[i].id+'"><a id="a_'+Json.daftarTopik[i].id+'" onclick="news.listtopik_index_onclick(\''+Json.daftarTopik[i].id+'\')" style="cursor:pointer"><span id="Editable_topik_'+Json.daftarTopik[i].id+'">'+Json.daftarTopik[i].topik+'</span> ('+Json.daftarTopik[i].totalArtikel+')</a><br /><span id="Editable_ket_'+Json.daftarTopik[i].id+'">'+Json.daftarTopik[i].ket+'</span></form></td><td><a onclick="news.edittopik(\''+Json.daftarTopik[i].id+'\')" style="cursor:pointer" id="klikId_'+Json.daftarTopik[i].id+'">Edit</a></td><td><a onclick="news.listtopik_delete_confirm(\''+Json.daftarTopik[i].id+'\')" style="cursor:pointer;">Delete</a></td></tr>';	
}


html += '</table>';

$('respon').innerHTML = html;



}

};
xmlhttp.send(null);	
document.body.onkeydown = null;	
window.onkeydown = null;
},
comments_mass_delete:function(formName, boxName, referer){
var Querystring	= new Array();
if (referer.match(/\?/g,referer)){	
referer = referer.split('?');
referer = referer[1];
}


for(i = 0; i < document.getElementById(formName).elements.length; i++)
	{
		var formElement = document.getElementById(formName).elements[i];
		if(formElement.type == 'checkbox' && formElement.name == boxName && formElement.disabled == false && formElement.checked == true)
		{	
		Querystring.push('id[]='+formElement.value);
		
		}
	}
var qs = Querystring.join('&');
if (Querystring.length <= 0){
alert ('No Selected Item(s)');
return false;	
}
if (confirm('Deleted Comments '+Querystring.length+' Item(s)')){
boxloading('Delete');
xmlhttp = news.xmlhttp();
xmlhttp.open("GET", 'mod/news/ajax/news_admin.php?action=deleteMComment&'+qs,true);
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {	
//alert(xmlhttp.responseText);
boxloading('Loading');
news.comments(referer);
}

};	
xmlhttp.send(null);	
	
}
			
},
comments_delete:function(id,referer){
if(confirm('Delete This Comment ?')){
if (referer.match(/\?/g,referer)){	
referer = referer.split('?');
referer = referer[1];
}		
//Fat.fade_element('trID_'+id,null,1000,'#FF3333');
boxloading('Delete');
xmlhttp = news.xmlhttp();
xmlhttp.open("GET", 'mod/news/ajax/news_admin.php?action=deleteComment&id='+id,true);
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
boxloading('Loading');
news.comments(referer);	
}
};
xmlhttp.send(null);	
}
},
comments_cari:function(Frm){
var Formname = $(Frm);
var cari = Formname.search.value;
cari = encodeURIComponent(cari);
news.comments('action=searchComment&search='+cari);

return false;
},
click_perubahan_warna:function(obj){
if ($('trID_'+obj.value).className != 'mod_news_selected') {
$('trID_'+obj.value).setAttribute('class1',$('trID_'+obj.value).className);
}
$('trID_'+obj.value).className = obj.checked ? 'mod_news_selected' : $('trID_'+obj.value).getAttribute('class1');	

},
comments:function(query){
boxloading('Loading');
query = query == null ? 'mod/news/ajax/news_admin.php?action=listComment' : 'mod/news/ajax/news_admin.php?action=listComment&'+query;
xmlhttp = news.xmlhttp();
xmlhttp.open("GET", query,true);
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
boxloading('Loading');

var Json = eval ("("+xmlhttp.responseText+")");	
if (typeof Json.list != 'object'){
$('respon').innerHTML = 'no Data';
return;	
}
var total = Json.list.length;
var loop = '';
var warna = '';
for(i=0;i<total;i++){
warna = (i % 2 == 0) ? ' class="mod_news_list1"' : ' class="mod_news_list2"';
loop += '<tr'+warna+' style="font-size:10px;" id="trID_'+Json.list[i].id+'"><td valign=top width=2px><input type="checkbox" name="komentar[]" value="'+Json.list[i].id+'" style="border:0px" onclick="news.click_perubahan_warna(this)"></td><td> <span class="mod_news_list_header">'+Json.list[i].judul+'</span><br />'+Json.list[i].tgl+' <b>Post By:</b> '+Json.list[i].user+'<br />'+Json.list[i].konten+'<br /><b>Email:</b> '+Json.list[i].email+' <b>IP:</b> <a href="http://ws.arin.net/cgi-bin/whois.pl?queryinput='+Json.list[i].ip+'" target="_blank">'+Json.list[i].ip+'</a><br /><b>Artikel:</b> '+Json.list[i].judul_artikel+'</td><td valign=top align=center><a onclick="news.comments_delete(\''+Json.list[i].id+'\',\''+query+'\')" style="cursor:pointer">Delete</a></td></tr>';	
}

var html = '<form name="frmcari" id="frmcari" onsubmit="return news.comments_cari(\'frmcari\');">Cari : <input type="text" name="search"> <input type="submit" value="cari"></form><br />';
html += '<form name="frmdel" id="frmdel"><table width=100% cellspacing=2 cellpadding=4px><tbody id="tbody">';
html += loop;
html += '</tbody></table></form>';
html += '<div class=border><input type="button" value="check All" onclick="checkall(\'frmdel\',\'komentar[]\')"> <input type="button" value="delete" style="background:red;color:#fff" onclick="news.comments_mass_delete(\'frmdel\',\'komentar[]\',\''+query+'\')"></div>';
if (Json.pagging != '') html += Json.pagging;
$('respon').innerHTML = html;
events=function (evt){
evt = (evt) ? evt : event;
if (evt.keyCode == 46){	
news.comments_mass_delete('frmdel','komentar[]',query);
return false;
}
if (evt.ctrlKey && evt.keyCode == 65){	
checkall('frmdel','komentar[]');
return false;
}	
	
};

if (navigator.userAgent.indexOf('Firefox') != -1 || navigator.userAgent.indexOf('Netscape') != -1){
window.onkeydown = events;
}else {
document.body.onkeydown = events;	
}

}

};
xmlhttp.send(null);	
	
},
artikelmasuk_approve:function(id,referer){

xmlhttp = news.xmlhttp();
xmlhttp.open("GET", 'mod/news/ajax/news_admin.php?action=getArtikelMasukApprove&id='+id,true);
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
if (referer.match(/\?/g,referer)){	
referer = referer.split('?');
referer = referer[1];
}
news.artikelmasuk(referer);	
}

};
xmlhttp.send(null);	
},
artikelmasuk:function(query){
query = query == null ? 'mod/news/ajax/news_admin.php?action=getArtikelMasuk' : 'mod/news/ajax/news_admin.php?'+query;
xmlhttp = news.xmlhttp();
xmlhttp.open("GET", query,true);
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
$('load').style.display = 'none';
var Json = eval ("("+xmlhttp.responseText+")");	
if (typeof Json.list != 'object'){
$('respon').innerHTML = 'no Data';
return;	
}
var total = Json.list.length;
var loop = '';
var warna = '';
for(i=0;i<total;i++){
warna = (i % 2 == 0) ? ' class="mod_news_list1"' : ' class="mod_news_list2"';
loop += '<tr'+warna+' id="trID_'+Json.list[i].id+'" style="font-size:10px;"><td valign=top width=2px><input type="checkbox" name="artikel[]" value="'+Json.list[i].id+'" style="border:0px" onclick="news.click_perubahan_warna(this)"></td><td> <span class="mod_news_list_header">'+Json.list[i].judul+'</span><br />'+Json.list[i].tgl+' Post By : '+Json.list[i].user+'<br /><b>Topik :</b> '+Json.list[i].topik+' <b>Hits :</b> '+Json.list[i].hits+' <a onclick="news.comments(\'o=1&id='+Json.list[i].id+'\')" style="cursor:pointer;" title="View Comments"><b>Comment :</b></a> '+Json.list[i].komentar+'</td><td valign=top align=center><a onclick="news.edit(1,\''+Json.list[i].id+'\',\''+query+'\')" style="cursor:pointer">Edit</a></td><td valign=top align=center><a onclick="news.artikelmasuk_approve(\''+Json.list[i].id+'\',\''+query+'\')" style="cursor:pointer">Approve</a></td></tr>';	
}

var html = '<form name="frmcari" id="frmcari" onsubmit="return news.indexs_cari(\'frmcari\');">Cari : <input type="text" name="search"> <input type="submit" value="cari"></form><br />';
html += '<form name="frm" id="frm"><table width=100% cellspacing=2 cellpadding=4px><tbody id="tbody">';
html += loop;
html += '</tbody></table></form>';
html += '<div class=border><input type="button" value="check All" onclick="checkall(\'frm\',\'artikel[]\')"> <input type="button" value="delete" style="background:red;color:#fff" onclick="news.deleted(\'frm\',\'artikel[]\',\''+query+'\')"></div>';
if (Json.pagging != '') html += Json.pagging;
$('respon').innerHTML = html;
events=function (evt){
evt = (evt) ? evt : event;
if (evt.keyCode == 46){	
news.deleted('frm','artikel[]',query);
return false;
}
if (evt.ctrlKey && evt.keyCode == 65){	
checkall('frm','artikel[]');
return false;
}	
	
};
if (navigator.userAgent.indexOf('Firefox') != -1 || navigator.userAgent.indexOf('Netscape') != -1){
window.onkeydown = events;
}else {
document.body.onkeydown = events;	
}


}

};
xmlhttp.send(null);	
	
}

};
$=function(e){
return document.getElementById(e);	
};
//

//$('header_ajax').innerHTML = '<div class=border><a onclick="news.indexs()" style="cursor:pointer">Home</a> | <a onclick="news.addnews(1)" style="cursor:pointer">Add News</a> | <a onclick="news.addtopik()" style="cursor:pointer">Add Topic</a> | <a onclick="news.listtopik()" style="cursor:pointer">List Topic</a> | <a onclick="news.comments()" style="cursor:pointer">Comment</a> | <a onclick="news.artikelmasuk()" style="cursor:pointer" id="artikelmasuklink">Artikel Masuk</a></div>';

//$('responbawah').innerHTML = '<blockquote>Keterangan : <br />Untuk Checkall : Ctrl + a<br />Delete : tombol delete<br />Untuk Browser Internet Explore,Opera,Safari,Mozilla firefox<br /></blockquote>';

var all_checked = true;
checkall=function(formName, boxName) {
	
	for(i = 0; i < document.getElementById(formName).elements.length; i++)
	{
		var formElement = document.getElementById(formName).elements[i];
		if(formElement.type == 'checkbox' && formElement.name == boxName && formElement.disabled == false)
		{
			formElement.checked = all_checked;
			news.click_perubahan_warna(formElement);
			
		}
	}	
all_checked = all_checked ? false : true;
};
loadartikelmasuk=function(){
var artikelmasuklink = $('artikelmasuklink').innerHTML;
var xmlhttp = news.xmlhttp();
xmlhttp.open("GET", 'mod/news/ajax/news_admin.php?action=HitungArtikelMasuk',true);

xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
$('artikelmasuklink').innerHTML = 'Artikel Masuk (' +xmlhttp.responseText+ ')';	
if (xmlhttp.responseText == ""){
clearInterval(loadingTextInterval);
$('ellipsis1').innerHTML = '';
xmlhttp.abort();
alert('Error: gagal Membuka Koneksi');
}

}
};

xmlhttp.send(null);

};
boxloading=function(pesan){

var posisi_top = 0;
if (navigator.appName == "Microsoft Internet Explorer")
	{
		posisi_top = parseInt(document.documentElement.scrollTop + (screen.height/3));
	}
	else
	{
		posisi_top = parseInt(window.pageYOffset + (screen.height/3));
	}
var lebar = pesan.length * 6 + 40;
document.getElementById('load').style.width = lebar + 'px';
document.getElementById('load').style.top = posisi_top + 'px';
document.getElementById('load').style.display = document.getElementById('load').style.display == 'none' ? 'block' : 'none';	
document.getElementById('loadmessage').innerHTML = pesan;
};