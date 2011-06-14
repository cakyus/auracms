var gallery = {
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
borderfiles:function(datafiles){
var out = '<table class=album cellSpacing=1 cellPadding=0 width=80  height=80 border=0><tr><td><table class=album1 cellSpacing=0 cellPadding=0 width=80  height=80 border=0><tr onmouseover="this.style.backgroundColor=\'#efefef\';" onmouseout="this.style.backgroundColor=\'\';"><td align=center>'+datafiles+'</td></tr></table></td></tr></table>';
return out;	
},
loadingTextInterval: setInterval(function(){
		if (document.getElementById("ellipsis") && document.getElementById('load').style.display == 'block'){
			var dots = document.getElementById("ellipsis").innerHTML;
			document.getElementById("ellipsis").innerHTML = (dots != "...") ? dots += "." : "";
		}
	}, 500),
substrdata:function(vardata,maxdata){
var txt = vardata.substring(0,maxdata);
if (vardata.length > maxdata) {txt += '...';}
return txt;
},
deletecat:function(albums,varpesan){
var konfirmasihapus = confirm(varpesan);	

if (konfirmasihapus){
	var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("GET", "mod/gallery/ajax/gallery_admin.php?action=delcat&id="+albums+"&token="+Math.random(), true);
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	request.onreadystatechange = function(){
		if (request.readyState == 4){
			var auraCMSsimpan = eval("(" + request.responseText + ")");
			if (typeof(auraCMSsimpan) == 'object'){
				if (auraCMSsimpan.sukses == true){
					gallery.indexs('');  
					}else {
					alert (auraCMSsimpan.pesanError);
					}
			}
		}
	}
;request.send(null);	
}
return konfirmasihapus;
},
indexs:function(querystring){
document.getElementById('load').style.display = 'block';

var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("GET", 'mod/gallery/ajax/gallery_admin.php?'+querystring, true);
	
	request.onreadystatechange = function(){
	
		if (request.readyState == 4 && request.responseText != ""){
			
				if (request.status == 404){
				alert ('File Tidak Ditemukan');
				return;
				}
			var iwan = eval("(" + request.responseText + ")");		
				
if (typeof(iwan.gallery) == 'object'){			
var total = iwan.gallery.length;
if (document.addEventListener) {
	document.addEventListener("mouseover", PageInfo.onMouseMove, false);
}	

var bd = '';
var intpph = 0;

for (i=0;i<total;i++){
if (iwan.gallery[i].albums != null){
bd += '<td align=center height=150>' + gallery.borderfiles('<a onmouseover="writetxt(\'<b>album</b> : '+iwan.gallery[i].name+'<br \><b>Description</b> : '+iwan.gallery[i].desc+'\'); return true" onmouseout="writetxt(0);" onclick="writetxt(0);gallery.bukagallery(\'action=album&albums='+iwan.gallery[i].albums+'\');" style="cursor:pointer"><img src="images/folder_gallery.gif"></a>') + '<center><small>'+gallery.substrdata(iwan.gallery[i].name,10)+'<br \>Image ('+iwan.gallery[i].images+')<br \><a style="cursor:pointer;color:orange" onclick="gallery.editcat(\''+iwan.gallery[i].albums+'\',\''+iwan.gallery[i].name+'\',\''+iwan.gallery[i].desc+'\');return true">edit</a> <a onclick="return gallery.deletecat(\''+iwan.gallery[i].albums+'\',\'Hapus Album Gallery Dengan ID : '+iwan.gallery[i].albums+'\')" style="color:red;cursor:pointer">Delete</a></small></center></td>';
intpph++;
}
else if (iwan.gallery[i].files != null){
bd += '<td align=center>' + gallery.borderfiles ('<a onclick="detailgallery(\'action=detail&id='+iwan.gallery[i].id+'\')" style="cursor:pointer;"><img src="albums/thumb/'+iwan.gallery[i].name+'"></a>') + '<center><small>'+gallery.substrdata(iwan.gallery[i].files,10)+'<br>Filesize : '+fsize(iwan.gallery[i].size)+'</small></center></td>';
intpph++;
}

if ( intpph % iwan.config.pptabel == 0) bd += '</tr><tr>';		


}
html = '';

html += iwan.sorting.url;
html += '<div class=border><table width="100%"><tbody><tr>';
html += bd;
html += '</tbody></table></div>';
html += '<br><br>';
if (iwan.pagging != "") html += iwan.pagging;
html += '<br><br>';

		document.getElementById('respon').innerHTML = html;
		document.body.onkeydown = null;
		window.onkeydown = null;

}
		document.getElementById('load').style.display = 'none';
	
		}

};
request.send(null);	
document.body.onkeydown = null;
window.onkeydown = null;
	
},
dhtmlLoadScript : function (url)
   {
      var e = document.createElement("script");
	  e.src = url;
	  e.type="text/javascript";
	  document.getElementsByTagName("head")[0].appendChild(e);	  
   },
dhtmlLoadcss : function (url)
   {
      var e = document.createElement("link");
	  e.rel = "stylesheet";
	  e.type="text/css";
	  e.href=url;
	  document.getElementsByTagName("head")[0].appendChild(e);	  
   },
created_click:function(){
if (document.createdd.kategori.value == '') {alert ('Error: Please enter Kategori'); document.createdd.kategori.focus();return false;}
if (document.createdd.desc.value == '') {alert ('Error: Please enter Description'); document.createdd.desc.focus(); return false;}


	var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("POST", "mod/gallery/ajax/gallery_admin.php?action=addcat&token="+Math.random(), true);
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	request.onreadystatechange = function(){
		if (request.readyState == 4){
			var auraCMSsimpan = eval("(" + request.responseText + ")");
			if (typeof(auraCMSsimpan) == 'object'){
				if (auraCMSsimpan.sukses == true){
					document.getElementById('createdgallery').style.display = 'none';	
					gallery.indexs(''); 
					}else {
					alert (auraCMSsimpan.pesanError);
					}
			}
		}
	}
;request.send(postForm.getFormValues('createdd'));	
},
created:function(){
gallery.dhtmlLoadScript('js/post.js');
document.getElementById('createdgallery').style.display = document.getElementById('createdgallery').style.display == 'none' ? 'block' : 'none';
var formdata = '<form name="createdd" id="createdd"><table><tr><td>Kategori</td><td>:</td><td><input type="text" name="kategori"></td></tr><tr><td>Deskripsi</td><td>:</td><td><textarea name="desc" rows=4 cols=30></textarea></td></tr><tr><td></td><td></td><td><input type="button" value="tambah" onclick="gallery.created_click()"> <input type="button" value="Cancel" onclick="gallery.created()"></td></tr></table></form>';
document.getElementById('createdgallery').innerHTML = '<div class=border style="background:#fdfdfd"><span id="errorFormCreated"></span>'+formdata+'</div>';
},
editcat_click:function(id){
if (document.eddt.kategori.value == '') {alert ('Error: Please enter Kategori'); document.eddt.kategori.focus();return false;}
if (document.eddt.desc.value == '') {alert ('Error: Please enter Description'); document.eddt.desc.focus(); return false;}


	var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("POST", "mod/gallery/ajax/gallery_admin.php?action=editcat&id="+id+"&token="+Math.random(), true);
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	request.onreadystatechange = function(){
		if (request.readyState == 4){
			var auraCMSsimpan = eval("("+request.responseText+")");
			if (typeof(auraCMSsimpan) == 'object'){
				if (auraCMSsimpan.sukses == true){
					document.getElementById('editgallery').style.display = 'none';
					gallery.indexs(''); 
					}else {
					alert (auraCMSsimpan.pesanError);
					}
			}
		}
	}
;request.send(postForm.getFormValues('eddt'));		
},
editcat:function(id,kat,desc,evts){
gallery.dhtmlLoadScript('js/post.js');

if (document.addEventListener) {
	document.addEventListener("click", PageInfo.onMouseMove, false);
} else if (document.attachEvent) {
	PageInfo.onMouseMove();
}
if (document.getElementById('editgallery').style.display == 'none'){
document.getElementById('editgallery').style.display = 'inline';
}else {
document.getElementById('editgallery').style.display = 'none';		
}

document.getElementById('editgallery').style.top =  PageInfo.mouseY+'px' ;
document.getElementById('editgallery').style.left = PageInfo.mouseX+'px' ;

var formdata = '<form name="eddt" id="eddt"><table><tr><td>Kategori</td><td>:</td><td><input type="text" name="kategori" value="'+kat+'"></td></tr><tr><td>Deskripsi</td><td>:</td><td><textarea name="desc" rows=4 cols=30>'+desc+'</textarea></td></tr><tr><td></td><td></td><td><input type="button" value="Edit" onclick="gallery.editcat_click(\''+id+'\');"> <input type="button" value="Cancel" onclick="document.getElementById(\'editgallery\').style.display = \'none\'"></td></tr></table></form>';
document.getElementById('editgallery').innerHTML = '<div class=border>'+formdata+'</div>';

},
check:function(obj){
var atribute = obj.getAttribute('id');
var eId = document.getElementById('chk_'+atribute);
eId.checked = eId.checked == true ? false : true;
if (document.all){
if (navigator.appName.match('Opera')){
obj.style.opacity= eId.checked ? 0.4 : '';	
}else {	
obj.style.filter= eId.checked ? 'alpha(opacity=40)' : '';
}
}else {
obj.style.MozOpacity = eId.checked ? 0.4 : 1;
}

},
deleted:function(element,boxName,referer){

var obj = document.getElementById(element);
var TempArrayId = new Array();
	for(i = 0; i < obj.elements.length; i++)
	{
		var formElement = obj.elements[i];
		if(formElement.type == 'checkbox' && formElement.name == boxName && formElement.disabled == false && formElement.checked == true)
		{
		TempArrayId.push(formElement.value);
		}
	}
if (TempArrayId.length <= 0) {alert ('No Selected Item(s)');return;}
else {
if (confirm ('Deleted Gallery ' + TempArrayId.length + ' Item(s)')){	
xmlhttp = false;
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

xmlhttp.open("GET", 'mod/gallery/ajax/gallery_admin.php?action=delete_items&id='+encodeURIComponent(TempArrayId)+'&referer='+referer);
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
var Json = eval ("("+xmlhttp.responseText+")");	
gallery.bukagallery(Json.referer);
}
		
}
;xmlhttp.send(null);	
}
}
},
moved_click:function(TempArrayId,referer){
document.getElementById('editgallery').style.display = 'none';
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

xmlhttp.open("POST", 'mod/gallery/ajax/gallery_admin.php?action=move_items&id='+encodeURIComponent(TempArrayId)+'&referer='+referer);
xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
var Json = eval ("("+xmlhttp.responseText+")");	
gallery.bukagallery(Json.referer);
}
		
}
;xmlhttp.send(postForm.getFormValues('eddts'));	
},
moved:function(element,boxName,referer){
gallery.dhtmlLoadScript('js/post.js');

var obj = document.getElementById(element);
var TempArrayId = new Array();
	for(i = 0; i < obj.elements.length; i++)
	{
		var formElement = obj.elements[i];
		if(formElement.type == 'checkbox' && formElement.name == boxName && formElement.disabled == false && formElement.checked == true)
		{
		TempArrayId.push(formElement.value);
		}
	}
if (TempArrayId.length <= 0) {alert ('No Selected Item(s)');return;}
else {
if (confirm ('Moved Gallery ' + TempArrayId.length + ' Item(s)')){	
if (document.addEventListener) {
	document.addEventListener("click", PageInfo.onMouseMove, false);
} else if (document.attachEvent) {
	PageInfo.onMouseMove();
}



if (document.getElementById('editgallery').style.display == 'none'){
document.getElementById('editgallery').style.display = 'inline';
}else {
document.getElementById('editgallery').style.display = 'none';		
}

document.getElementById('editgallery').style.top =  PageInfo.mouseY+'px' ;
document.getElementById('editgallery').style.left = PageInfo.mouseX+'px' ;
var formdata = '<form name="eddts" id="eddts"><table><tr><td>Album Name</td><td>:</td><td><span id="respon_kategori"></span></td></tr><tr><td></td><td></td><td><input type="button" value="Move" onclick="gallery.moved_click(\''+TempArrayId+'\',\''+referer+'\')"> <input type="button" value="Cancel" onclick="document.getElementById(\'editgallery\').style.display = \'none\'""></td></tr></table></form>';
document.getElementById('editgallery').innerHTML = '<div class=border>'+formdata+'</div>';	


xmlhttp = false;
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
xmlhttp.open("GET", 'mod/gallery/ajax/gallery_admin.php?action=listkategori');
xmlhttp.onreadystatechange = function() {

	if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		var json = eval("("+xmlhttp.responseText+")");
		var fsel = '<select name="kategori" size="1">';
		if (typeof(json.select) == 'object'){
		for(i=0;i<json.select.length;i++){
		fsel += '<option value="'+json.select[i].id+'">'+json.select[i].kategori+' ('+json.select[i].total+')</option>';	
		}	
		}
		
		fsel += '</select>';
		
		document.getElementById('respon_kategori').innerHTML = fsel;
		}
		
}
;xmlhttp.send(null);	




}
}
},
editdesc_click:function(id,referer){
if (document.eddt.desc.value == '') {alert ('Error: Please enter Description'); document.eddt.desc.focus(); return false;}
	var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("POST", "mod/gallery/ajax/gallery_admin.php?action=editdesc&id="+id+"&referer="+referer+"&token="+Math.random(), true);
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	request.onreadystatechange = function(){
		if (request.readyState == 4){
			var auraCMSsimpan = eval("(" + request.responseText + ")");
			if (typeof(auraCMSsimpan) == 'object'){
				if (auraCMSsimpan.sukses == true){
					document.getElementById('editgallery').style.display = 'none';
					alert('sukses Edit Description');
					/*gallery.bukagallery(auraCMSsimpan.referer);*/
					}else {
					alert (auraCMSsimpan.pesanError);
					}
			}
		}
	}
;request.send(postForm.getFormValues('eddt'));	
},
editdesc:function(id,desc,referer){
gallery.dhtmlLoadScript('js/post.js');

if (document.addEventListener) {
	document.addEventListener("click", PageInfo.onMouseMove, false);
} else if (document.attachEvent) {
	PageInfo.onMouseMove();
}

if (document.getElementById('editgallery').style.display == 'none'){
document.getElementById('editgallery').style.display = 'inline';
}else {
document.getElementById('editgallery').style.display = 'none';		
}

document.getElementById('editgallery').style.top =  PageInfo.mouseY+'px' ;
document.getElementById('editgallery').style.left = PageInfo.mouseX+'px' ;

var req = gallery.xmlhttp();
req.open('GET','mod/gallery/ajax/gallery_admin.php?action=getdesc&id='+id);
req.onreadystatechange = function() {
	if (req.readyState == 4 && req.status == 200) {
		desc = req.responseText;
		var formdata = '<form name="eddt" id="eddt"><table><tr><td>Deskripsi</td><td>:</td><td><textarea name="desc" rows=4 cols=30>'+desc+'</textarea></td></tr><tr><td></td><td></td><td><input type="button" value="Edit" onclick="gallery.editdesc_click(\''+id+'\',\''+referer+'\');"> <input type="button" value="Cancel" onclick="document.getElementById(\'editgallery\').style.display = \'none\'"></td></tr></table></form>';
document.getElementById('editgallery').innerHTML = '<div class=border>'+formdata+'</div>';
		}
	};
req.send(null);
},
bukagallery:function(querystring){
document.getElementById('load').style.display = 'block';	
var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("GET", 'mod/gallery/ajax/gallery_admin.php?'+querystring, true);	
request.onreadystatechange = function(){
if (request.readyState == 4 && request.responseText != ""){	
var iwan = eval("(" + request.responseText + ")");	
if (typeof(iwan.gallery) == 'object'){
var total = iwan.gallery.length;
var bd = '';
var intpph = 0;
var offset = parseInt(iwan.offset.integer);
var image;
var urlsorting = '';
var tpath = iwan.config.tpath;		
var tnpath = iwan.config.tnpath;
if (typeof(iwan.urlsorting) == 'object'){
urlsorting = iwan.urlsorting[0];	
}
for (i=0;i<total;i++){
if (iwan.gallery[i].files != null){
image = (offset > 0) ? (offset + i) : i;	
bd += '<td align=center height=150>' + gallery.borderfiles ('<a onmouseover="writetxt(\'<b>Files</b> : '+iwan.gallery[i].files+'<br \><b>Dimension</b> : '+iwan.gallery[i].width+' x '+iwan.gallery[i].height+'<br /><b>Description</b> : '+iwan.gallery[i].desc+'\'); return true" onmouseout="writetxt(0);" style="cursor:pointer;"><img src="'+tnpath+iwan.gallery[i].name+'" onclick="gallery.check(this)" id="'+iwan.gallery[i].id+'" alt="gallery" /></a>') + '<center><small><input type="checkbox" name="img[]" id="chk_'+iwan.gallery[i].id+'" value="'+iwan.gallery[i].id+'" style="display:none"> '+gallery.substrdata(iwan.gallery[i].files,10)+'<br>Filesize : '+fsize(iwan.gallery[i].size)+'<br /><a onclick="gallery.editdesc(\''+iwan.gallery[i].id+'\',\''+iwan.gallery[i].desc+'\',\''+iwan.referer+'\')" style="cursor:pointer;color:orange">Edit</a></small></center></td>';
intpph++;
}

if ( intpph % iwan.config.pptabel == 0) bd += '</tr><tr>';		


}	

var html;

html = '<div class="border" style="text-align:left"><h4>'+iwan.album.name+'</h4></div>';
html += iwan.sorting.url;
html += '<form name="formDeleted" id="formDeleted"><div class=border><table width="100%"><tbody><tr>';
html += bd;
html += '</tbody></table></div>';
if (total > 0) html += '<div class=border><input type="button" value="Select All" onclick="checkall(\'formDeleted\',\'img[]\');"> <input type="button" value="Delete" style="background:red;color:#fff" onclick="gallery.deleted(\'formDeleted\',\'img[]\',\''+iwan.referer+'\')"> <input type="button" value="Move" style="background:orange;color:#fff" onclick="gallery.moved(\'formDeleted\',\'img[]\',\''+iwan.referer+'\')"></div>';
html += '</form>';
html += '<br><br>';
html += iwan.pagging;
html += '<br><br>';
document.getElementById('respon').innerHTML = html;	
events=function(evt){
evt = (evt) ? evt : event;
if (evt.ctrlKey && evt.keyCode == 65){	
checkall('formDeleted','img[]');
return false;
}
if (evt.keyCode == 46){
gallery.deleted('formDeleted','img[]',iwan.referer);
return false;
}

if (evt.keyCode == 77){
gallery.moved('formDeleted','img[]',iwan.referer);
return false;
}

};
window.onkeydown = events;
document.body.onkeydown = events;
}else {
	
html = '<div class="border" style="text-align:left"><h4>'+iwan.album.name+'</h4></div>';
	
	
	
html += '<table width="100%" style="border:1px solid #efefef"><tr><td align="left"><img src="images/warning.gif" border="0"></td><td align="center"><div class="error">Maaf Gallery nya masih Kosong</div></td><td align="right"><img src="images/warning.gif" border="0"></td></tr></table>';
document.getElementById('respon').innerHTML = html;
document.body.onkeydown = null;
window.onkeydown = null;
document.getElementById('load').style.display = 'none';
return;	
}
document.getElementById('load').style.display = 'none';
}

}
	
;request.send(null);
},
upload_click:function(obj){
document.getElementById('uploadframe').style.display = 'inline';
obj.submit();
obj.reset();
},
upload:function(){

var html = '<iframe id="uploadframe" frameborder="0" name="uploadframe" src="mod/gallery/gallery_upload.php?action=upload" style="display:none;border:0px;width:70%"></iframe><blockquote>Silahkan Add Image <br>Album gallery ini support dengan file JPG, JPEG, PNG dan GIF</blockquote>';

html += '<div class="error" id="errorwrite" style="display:none"></div>';	
html += '<form id="uploadform" name="uploadform" action="mod/gallery/gallery_upload.php?action=upload" method="post" enctype="multipart/form-data" target="uploadframe" onsubmit="gallery.upload_click(this); return false">';
html += '<table width="60%">';
html += '<tr><td>Kategori</td><td>:</td><td><span id="respon_kategori">Loading ...</span></td></tr>';
html += '<tr><td>Files</td><td>:</td><td><input type="file" name="namafile[]" size="40"></td></tr>';
html += '<tr><td>Files</td><td>:</td><td><input type="file" name="namafile[]" size="40"></td></tr>';
html += '<tr><td>Files</td><td>:</td><td><input type="file" name="namafile[]" size="40"></td></tr>';
html += '<tr><td>Files</td><td>:</td><td><input type="file" name="namafile[]" size="40"></td></tr>';
html += '<tr><td>Files</td><td>:</td><td><input type="file" name="namafile[]" size="40"></td></tr>';

html += '<tr><td></td><td></td><td><input type="submit" value="upload"></td></tr>';
html += '</table>';
html += '</form>';	
document.getElementById('respon').innerHTML = html;	


var xmlhttp1 = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
xmlhttp1.open("GET", 'mod/gallery/ajax/gallery_admin.php?action=listkategori',true);
xmlhttp1.onreadystatechange = function() {

	if (xmlhttp1.readyState == 4 && xmlhttp1.status == 200) {
		var json = eval("("+xmlhttp1.responseText+")");
		var fsel = '<select name="kategori" size="1">';
		if (typeof(json.select) == 'object'){
		for(i=0;i<json.select.length;i++){
		fsel += '<option value="'+json.select[i].id+'">'+json.select[i].kategori+' ('+json.select[i].total+')</option>';	
		}	
		}
		
		fsel += '</select>';
		
		document.getElementById('respon_kategori').innerHTML = fsel;
		}
		
};
xmlhttp1.send(null);	

var xmlhttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
xmlhttp.open("GET", 'mod/gallery/ajax/gallery_admin.php?action=cekdir');
xmlhttp.onreadystatechange = function() {

	if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		var json = eval("("+xmlhttp.responseText+")");
		if (json != ""){
		document.getElementById('errorwrite').innerHTML = json;
		document.getElementById('errorwrite').style.display = 'block';
		}
		
		
		
		}
		
};
xmlhttp.send(null);	
document.body.onkeydown = null;
window.onkeydown = null;
},
fancyupload:function(){


onchanges=function(obj){
var TxT = obj.options[obj.selectedIndex].text;
if (document.getElementById('selectedKategori')) document.getElementById('selectedKategori').innerHTML = '<a onclick="gallery.fancyupload()" style="cursor:pointer;">'+TxT+'</a>';
};
fancyinitload=function(k){
			 if (k == ""){
				 alert('Silahkan Pilih Kategori nya');
				
				 return;
			 	}
			 	
			
			var input = $('photoupload-filedata-1');

			var uplooad = new FancyUpload(input, {
				swf: 'mod/gallery/fancy/js/Swiff.Uploader.swf',
				queueList: 'photoupload-queue',
				container: $E('h1'),
				//onAllComplete: gallery.fancyupload,	
				url: urluploaded+'?action=fancy&kategory='+k
			}); 
			
			$('photoupload-status').adopt(new Element('a', {
				href: 'javascript:void(null);',
				events: {
					click: uplooad.clearList.bind(uplooad, [false])
				}
			}).setHTML('Clear Completed'));
			
			
			
};

document.getElementById('respon').innerHTML = '<div id="container"><form action="" onsubmit="return false" method="post" id="photoupload" enctype="multipart/form-data"><fieldset><legend>Select Files</legend><div class="note">Photos will be uploaded in a queue, upload them with one click.<br />Selected Options: <b>Filetype Only Images, select multiple files, <i>upload queued</i></b>.</div><div class="label emph"><label for="photoupload-filedata-1">Upload Photos:<span>After selecting the photos start the upload.</span></label><span id="selectedKategori"></span><br /><input type="file" name="Filedata" id="photoupload-filedata-1" style="display:none" /></div></fieldset><fieldset><legend>Upload Queue</legend><div id="photoupload-status">Check the selected files and start uploading.</div><ul class="photoupload-queue" id="photoupload-queue"><li style="display: none" /></ul></fieldset><div class="clear"></div><input type="submit" class="submit" id="profile-submit" value="Start Upload"/></form></div>';
document.body.onkeydown = null;
window.onkeydown = null;

xmlhttp = false;
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
xmlhttp.open("GET", 'mod/gallery/ajax/gallery_admin.php?action=listkategori');
xmlhttp.onreadystatechange = function() {

	if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		var json = eval("("+xmlhttp.responseText+")");
		var fsel = '<select name="kategori" size="1" onchange="fancyinitload(this.options[this.selectedIndex].value);onchanges(this)">';
		fsel += '<option value="">--pilih--</option>';
		if (typeof(json.select) == 'object'){
		for(i=0;i<json.select.length;i++){
		fsel += '<option value="'+json.select[i].id+'">'+json.select[i].kategori+' ('+json.select[i].total+')</option>';	
		}	
		}
		
		fsel += '</select>';
		
		document.getElementById('selectedKategori').innerHTML = fsel;
		}
		
};
xmlhttp.send(null);	

	
}
};
document.getElementById('headergallery').innerHTML = '<div class=border><a onclick="gallery.indexs();" style="cursor:pointer">My Gallery</a> | <a onclick="gallery.created()" style="cursor:pointer">Buat Kategori</a> | <a onclick="gallery.upload()" style="cursor:pointer;">Upload</a> | <a onclick="gallery.fancyupload()" style="cursor:pointer;">Fancy Upload</a></div><div id="createdgallery" style="display:none;position: absolute;left:38%;top:35%;"></div>';	
document.getElementById('responbawah').innerHTML = '<blockquote>Keterangan : <br />Untuk Checkall : Ctrl + a<br />Delete : tombol "delete"<br />Move : tombol "M"<br />Untuk Browser Internet Explore,Mozilla firefox,Netcape,Opera,Safari.</blockquote>';

var PageInfo = {
	getResolutionWidth  : function() { return self.screen.width; },
	getResolutionHeight : function() { return self.screen.height; },
	getColorDepth       : function() { return self.screen.colorDepth; },

	getScrollLeft       : function() { var scrollLeft = 0; if (document.documentElement && document.documentElement.scrollLeft && document.documentElement.scrollLeft != 0) { scrollLeft = document.documentElement.scrollLeft; } if (document.body && document.body.scrollLeft && document.body.scrollLeft != 0) { scrollLeft = document.body.scrollLeft; } if (window.pageXOffset && window.pageXOffset != 0) { scrollLeft = window.pageXOffset; } return scrollLeft; },
	getScrollTop        : function() { var scrollTop = 0; if (document.documentElement && document.documentElement.scrollTop && document.documentElement.scrollTop != 0) { scrollTop = document.documentElement.scrollTop; } if (document.body && document.body.scrollTop && document.body.scrollTop != 0) { scrollTop = document.body.scrollTop; } if (window.pageYOffset && window.pageYOffset != 0) { scrollTop = window.pageYOffset; } return scrollTop; },

	getDocumentWidth    : function() { var documentWidth = 0; var w1 = document.body.scrollWidth; var w2 = document.body.offsetWidth; if (w1 > w2) { documentWidth = document.body.scrollWidth; } else { documentWidth = document.body.offsetWidth; } return documentWidth; },
	getDocumentHeight   : function() { var documentHeight = 0; var h1 = document.body.scrollHeight; var h2 = document.body.offsetHeight; if (h1 > h2) { documentHeight = document.body.scrollHeight; } else { documentHeight = document.body.offsetHeight; } return documentHeight; },
	getVisibleWidth     : function() { var visibleWidth = 0; if (self.innerWidth) { visibleWidth = self.innerWidth; } else if (document.documentElement && document.documentElement.clientWidth) { visibleWidth = document.documentElement.clientWidth; } else if (document.body) { visibleWidth = document.body.clientWidth; } return visibleWidth; },
	getVisibleHeight    : function() { var visibleHeight = 0; if (self.innerHeight) { visibleHeight = self.innerHeight; } else if (document.documentElement && document.documentElement.clientHeight) { visibleHeight = document.documentElement.clientHeight; } else if (document.body) { visibleHeight = document.body.clientHeight; } return visibleHeight; },

	getElementLeft      : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; var left = element.offsetLeft; var oParent = element.offsetParent; while (oParent != null) { left += oParent.offsetLeft; oParent = oParent.offsetParent; } return left; },
	getElementTop       : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; var top = element.offsetTop; var oParent = element.offsetParent; while (oParent != null) { top += oParent.offsetTop; oParent = oParent.offsetParent; } return top; },
	getElementWidth     : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; return element.offsetWidth; },
	getElementHeight    : function(element) { var element = (typeof element == "string") ? document.getElementById(element) : element; return element.offsetHeight; },

	getMouseX           : function() { return PageInfo.mouseX; },
	getMouseY           : function() { return PageInfo.mouseY; },


	// HELPER CODE FOR TRACKING MOUSE POSITION
	mouseX: 0,
	mouseY: 0,
	onMouseMove: function(e) { e = (!e) ? window.event : e; PageInfo.mouseX = e.clientX + PageInfo.getScrollLeft(); PageInfo.mouseY = e.clientY + PageInfo.getScrollTop(); }
};
function fsize(zahl) {
	if(zahl < 1000) {
		zahl = $zahl+"";
	} else {
		if(zahl < 1048576) {
			zahl = Math.round(parseFloat(zahl/1024))+"&nbsp;Kb";
		} else {
			zahl = Math.round(parseFloat(zahl/1048576))+"&nbsp;Mb";
		}
	}
	return zahl;
}

var all_checked = true;
checkall=function(formName, boxName) {
	
	for(i = 0; i < document.getElementById(formName).elements.length; i++)
	{
		var formElement = document.getElementById(formName).elements[i];
		if(formElement.type == 'checkbox' && formElement.name == boxName && formElement.disabled == false)
		{
			formElement.checked = all_checked;
			var obj = document.getElementById(formElement.getAttribute('value'));
			if (document.all){	
			if (navigator.appName.match('Opera')){
					obj.style.opacity= formElement.checked ? 0.4 : '';	
					}else {	
					obj.style.filter= formElement.checked ? 'alpha(opacity=40)' : '';
						}
			}else {
				obj.style.MozOpacity= formElement.checked ? 0.4 : 1;	
			}
			
			
		}
	}	
all_checked = all_checked ? false : true;
};
