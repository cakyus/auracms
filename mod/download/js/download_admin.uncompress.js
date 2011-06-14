/*
Modul Downloads Untuk AuraCMS 2.1
Created By	: Ridwan
Homepage	: ridwan.or.id
Modified 	: July 30, 2007 04:40:27 AM 
*/
var download = {
loadingTextInterval: setInterval(function(){
		if (document.getElementById("ellipsis") && document.getElementById('load').style.display == 'block'){
			var dots = document.getElementById("ellipsis").innerHTML;
			document.getElementById("ellipsis").innerHTML = (dots != "...") ? dots += "." : "";
		}
	}, 500),
clickurl:function(url){
location.href = url;	
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
if (confirm ('Deleted Download ' + TempArrayId.length + ' Item(s)')){	
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
//alert(encodeURIComponent(TempArrayId));
xmlhttp.open("GET", 'mod/download/download_admin.php?action=delete_items&id='+TempArrayId);
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
var Json = eval ("("+xmlhttp.responseText+")");
var TMParr = new Array();
if (typeof Json.deleted == 'object'){
for(i=0;i<Json.deleted.length;i++){
TMParr.push (Json.deleted[i]);	
}
alert('Sukses Deleted : '+TMParr);	
}	
download.bukakategori(referer);
}
		
};
xmlhttp.send(null);	
}
}	
},
bukakategori:function(querystring){

document.getElementById('load').style.display = 'block';
querystring = querystring == null ? null : querystring;	
var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("GET", 'mod/download/download_admin.php?'+querystring, true);	
request.onreadystatechange = function(){
	
if (request.readyState == 4){	
var auraCMS = request.responseText != "" ? eval("(" + request.responseText + ")") : null;
if (typeof(auraCMS.List) == 'object'){
var isidownloads = '';
var total = auraCMS.List.length;
var BrowsV = '';

if (total > 0){
var kategorilink = typeof (auraCMS.kategori) == 'object' ? auraCMS.kategori[0] : '';
isidownloads += '<table border=0>';
for (i=0;i<total;i++){
//href="#dl_jump.php?id='+auraCMS.List[i].id+'" navigator.appName
newlink = auraCMS.List[i].newlinks != "" ? auraCMS.List[i].newlinks : '';
BrowsV = '<span style="font-weight:bold;color:blue" class="editText" id="judul_'+i+'" field="judul" iddata="'+auraCMS.List[i].id+'" url="mod/download/download_admin.php?action=editisi">'+auraCMS.List[i].judul+'</span>';
isidownloads += '<tr><td><input type="checkbox" name="deleted[]" value="'+auraCMS.List[i].id+'" style="border:0px;" id="chk_'+auraCMS.List[i].id+'"> '+BrowsV+' '+newlink+'<br \><span iddata="'+auraCMS.List[i].id+'" id="keterangan_'+i+'" class="editText" field="keterangan" url="mod/download/download_admin.php?action=editisi">'+auraCMS.List[i].keterangan+'</span><br \><div style="font-size:9px"><b>Added on</b> : '+auraCMS.List[i].date+' <b>View</b> : <span iddata="'+auraCMS.List[i].id+'" id="hit_'+i+'" class="editText" field="hit" url="mod/download/download_admin.php?action=editisi">'+auraCMS.List[i].hit+'</span> <b>Size</b> : <span iddata="'+auraCMS.List[i].id+'" id="size_'+i+'" class="editText" field="size" url="mod/download/download_admin.php?action=editisi">'+auraCMS.List[i].size+'</span><br \><b>Url</b> : <span iddata="'+auraCMS.List[i].id+'" id="url_'+i+'" class="editText" field="url" url="mod/download/download_admin.php?action=editisi">'+auraCMS.List[i].url+'</span><br /><b>Kategori</b> : <span iddata="'+auraCMS.List[i].id+'" id="kid_'+i+'" class="editSelect" field="kid" kid="'+auraCMS.List[i].kid+'" url="mod/download/download_admin.php?action=editisi">'+auraCMS.List[i].kategori+'</span></div><br \></td></tr>';
}
isidownloads += '</table>';	
	
}else {
alert('tidak Ada Data');	
}

html = '<div class=border>Category: <a style="cursor:pointer;font-weight:bold" onclick="download.indexs()">Home</a> / '+kategorilink+'</div>';
html += '<form name="formDeleted" id="formDeleted" onsubmit="return false;"><div class=border>';
html += isidownloads;
html += '</div></form>';
html += '<div class=border><input type="button" value="cek all" onclick="checkall(\'formDeleted\',\'deleted[]\');"> <input type="button" value="delete" style="background:red;color:#fff" onclick="download.deleted(\'formDeleted\',\'deleted[]\',\''+querystring+'\')"></div>';
if (auraCMS.paging != "") html += '<div class="border">'+auraCMS.paging+'</div>';
document.getElementById('respon').innerHTML = html;	
Edit.load();
document.getElementById('load').style.display = 'none';
}else {
document.getElementById('load').style.display = 'none';
alert ('Maaf Tidak Ada Data...');
document.getElementById('respon').innerHTML = '<div class=border>Tidak Ada Data</div>';	
}
}
};

request.send(null);		
},	
deletecat:function(id){
if (confirm('Delete Categori Ini Dengan Id : '+id)){
var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
request.open("GET", 'mod/download/download_admin.php?action=deletecat&id='+id, true);	
request.onreadystatechange = function(){	
if (request.readyState == 4 && request.status == 200){	
download.indexs('');
}
};	
request.send(null);	
}	
},	
indexs : function(querystring){
document.getElementById('load').style.display = 'block';
querystring = querystring == null ? null : querystring;	
var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
request.open("GET", 'mod/download/download_admin.php?'+querystring, true);	
request.onreadystatechange = function(){
	
if (request.readyState == 4){	
var auraCMS = request.responseText != "" ? eval("(" + request.responseText + ")") : null;
if (auraCMS != null){
var isidownloads = '';
var total = auraCMS.List.length;
if (total > 0){	
isidownloads += '<table border=0><tr>';
for (i=0;i<total;i++){
newlink = auraCMS.List[i].newLink != "" ? auraCMS.List[i].newLink : '';
auraCMS.List[i].keterangan = auraCMS.List[i].keterangan == "" ? 'Edit' : auraCMS.List[i].keterangan;
auraCMS.List[i].kategori = auraCMS.List[i].kategori == "" ? 'Edit' : auraCMS.List[i].kategori;
isidownloads += '<td valign=top width=50%><a style="cursor:pointer" onclick="download.bukakategori(\'action=detail&id='+auraCMS.List[i].kid+'\')"><img src="mod/download/images/dir3.png" align=absmiddle></a> <span style="cursor:pointer;font-weight:bold" id="editkat_'+auraCMS.List[i].kid+'" onclick="InstanEdit.ToText(this)">'+auraCMS.List[i].kategori+'</span> ('+auraCMS.List[i].total+') '+newlink+' <a onclick="download.deletecat(\''+auraCMS.List[i].kid+'\')" style="cursor:pointer;color:red">delete</a><br \><span style="font-size:9px;cursor:pointer;" class="editText" id="editket_'+auraCMS.List[i].kid+'" onclick="InstanEdit.ToText(this)">'+auraCMS.List[i].keterangan+'</span></td>';
if ((i+1) % 2 == 0) isidownloads += '</tr><tr>';
}
isidownloads += '</table>';		
}else {
alert('tidak Ada Data');	
}
var html = '<div class=border>';
html += isidownloads;
html += '</div>';
if (auraCMS.paging != "") html += '<div class="border">'+auraCMS.paging+'</div>';
document.getElementById('respon').innerHTML = html;	
document.getElementById('load').style.display = 'none';
}else {
document.getElementById('load').style.display = 'none';
alert ('Maaf Tidak Ada Data...');
document.getElementById('respon').innerHTML = '<div class=border>Tidak Ada Data</div>';	
}
}
};
request.send(null);		
},
cariclick : function (querystring) {
download.bukakategori(querystring);	
return false;
},
cari : function () {
download.dhtmlLoadScript('js/post.js');
download.dhtmlLoadScript('js/fat.js');
var html = '';
html += '<div class=border><form name="myform" id="myform" onsubmit="return download.cariclick(\'action=cari&search=\'+search.value)"><span>Cari : </span><input type="text" name="search" size=30 id="fieldcari"> <input tabindex="2" type="button" value="cari" onclick="download.cariclick(\'action=cari&search=\'+search.value)"></form></div><div id="responseFade"></div><div id=isidatadownload></div>';

document.getElementById('respon').innerHTML = html;	
},
adddownload_click:function(){
if (document.frm.judul.value == '') {alert ('Error: Please enter Judul'); document.frm.judul.focus();return false;}
if (document.frm.url.value == '') {alert ('Error: Please enter URL'); document.frm.url.focus(); return false;}	
if (document.frm.kid.value == '') {alert ('Error: Please enter Kategori'); document.frm.kid.focus(); return false;}	
if (document.frm.keterangan.value == '') {alert ('Error: Please enter Keterangan'); document.frm.keterangan.focus(); return false;}	

	var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("POST", "mod/download/download_admin.php?action=additems&token="+Math.random(), true);
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	request.onreadystatechange = function(){
		if (request.readyState == 4){
			var auraCMSsimpan = eval("(" + request.responseText + ")");
			if (typeof(auraCMSsimpan) == 'object'){
				if (auraCMSsimpan.sukses == true){
					alert('Sukses Created Kategori');
					document.getElementById('frm').reset();
					
					}else {
					alert (auraCMSsimpan.error);
					}
			}
		}
	};
request.send(postForm.getFormValues('frm'));



},
adddownload:function(){
download.dhtmlLoadScript('js/post.js');	

	var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("POST", "mod/download/download_admin.php?action=listkategori&token="+Math.random(), true);
	request.onreadystatechange = function(){
		if (request.readyState == 4 && request.status == 200){
		document.getElementById('kategoriselect').innerHTML = request.responseText;	
		}
	};
request.send(postForm.getFormValues('frm'));




var html = '';	
html +='<div class=border><form name="frm" id="frm"><table><tr><td>Judul</td><td>:</td><td><input type="text" name="judul"></td></tr><tr><td>Url</td><td>:</td><td><input type="text" name="url" size="50"></td></tr><tr><td>Kategori</td><td>:</td><td><span id="kategoriselect"></span></td></tr><tr><td>Keterangan</td><td>:</td><td><textarea name="keterangan" cols=40></textarea></td></tr><tr><td>Size</td><td>:</td><td><input type="text" name="size" size="6" value="200 Kb"></td></tr><tr><td></td><td></td><td><input type="button" value="tambah" onclick="download.adddownload_click()"></td></tr></form></div>';	
	
document.getElementById('respon').innerHTML = html;		
},
addkategori_click:function(){
if (document.frm.kategori.value == '') {alert ('Error: Please enter Kategori'); document.frm.kategori.focus();return false;}
if (document.frm.keterangan.value == '') {alert ('Error: Please enter Description'); document.frm.keterangan.focus(); return false;}


	var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("POST", "mod/download/download_admin.php?action=addcat&token="+Math.random(), true);
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	request.onreadystatechange = function(){
		if (request.readyState == 4){
			var auraCMSsimpan = eval("(" + request.responseText + ")");
			if (typeof(auraCMSsimpan) == 'object'){
				if (auraCMSsimpan.sukses == true){
					alert('Sukses Created Kategori');
					document.getElementById('frm').reset();
					
					}else {
					alert (auraCMSsimpan.error);
					}
			}
		}
	};
request.send(postForm.getFormValues('frm'));		
},
addkategori:function(){
download.dhtmlLoadScript('js/post.js');	
var html = '';	
html +='<div class=border><form name="frm" id="frm"><table><tr><td>Kategori</td><td>:</td><td><input type="text" name="kategori"></td></tr><tr><td>Keterangan</td><td>:</td><td><textarea name="keterangan" cols=40></textarea></td></tr><tr><td></td><td></td><td><input type="button" value="tambah" onclick="download.addkategori_click()"></td></tr></form></div>';	
	
document.getElementById('respon').innerHTML = html;	
},
dhtmlLoadScript : function (url)
   {
      var e = document.createElement("script");
	  e.src = url;
	  e.type="text/javascript";
	  document.getElementsByTagName("head")[0].appendChild(e);	  
   }	
};
download.dhtmlLoadScript('js/xmlhttp.js');
document.getElementById('headerdownload').innerHTML = '<div class=border style="text-align:left"><a style="cursor:pointer" onclick="download.indexs()" title="Kembali Ke halaman Download Directory">Home</a> | <a onclick="download.adddownload()" style="cursor:pointer;">Add Download</a> | <a onclick="download.addkategori()" style="cursor:pointer;">Add Categori</a> | <a style="cursor:pointer" onclick="download.cari()" title="Cari Download">Search</a></div>';

var InstanEdit = {
changed:false,
fieldEnter:function(element,evt,id){
evt = (evt) ? evt : window.event;
if(evt.keyCode == 13 && element.value != "" && !element.value.match(/^ /)){
newElemt = document.getElementById(id);
newElemt.innerHTML = element.value;
var getKid = id.split('_');
var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
request.open("POST", 'mod/download/download_admin.php?action='+getKid[0]+'&id='+getKid[1]);
request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
request.onreadystatechange = function(){
if (request.readyState == 4 && request.status == 200){	
var iwanGanteng = eval ("("+request.responseText+")");
if (iwanGanteng.error) alert(iwanGanteng.errorpesan);	
newElemt.innerHTML = iwanGanteng.keterangan;
}

};
request.send('desc='+encodeURIComponent(element.value));

InstanEdit.changed = false;

}
return false;
},
fieldBlur:function(el,idspn){
if (el.value != "" && !el.value.match(/^ /)){
newElemt = document.getElementById(idspn);
var getKid = idspn.split('_');
var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
request.open("POST", 'mod/download/download_admin.php?action='+getKid[0]+'&id='+getKid[1]);
request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
request.onreadystatechange = function(){
if (request.readyState == 4 && request.status == 200){	
var iwanGanteng = eval ("("+request.responseText+")");
if (iwanGanteng.error) alert(iwanGanteng.errorpesan);	
newElemt.innerHTML = iwanGanteng.keterangan;
}

};
request.send('desc='+encodeURIComponent(el.value));




InstanEdit.changed = false;
return false;
}
},	
ToText:function(el){
if (!InstanEdit.changed){
width = InstanEdit.widthEl(el.id) + 30;
height = InstanEdit.heightEl(el.id) + 2;
if (el.innerHTML.length < 50){
el.innerHTML = '<input type="text" style="width:'+width+'px;" value="'+el.innerHTML+'" id="'+el.id+'_field" onkeypress="InstanEdit.fieldEnter(this,event,\''+el.id+'\')" onblur="return InstanEdit.fieldBlur(this,\''+el.id+'\')">';
}else {
el.innerHTML = '<textarea type="text" cols="40" rows="3" id="'+el.id+'_field" onblur="return InstanEdit.fieldBlur(this,\''+el.id+'\')">'+el.innerHTML+'</textarea>';	
}
InstanEdit.changed = true;
}
el.firstChild.focus();
},
widthEl:function(span){

	if (document.layers){
	  w=document.layers[span].clip.width;
	} else if (document.all && !document.getElementById){
	  w=document.all[span].offsetWidth;
	} else if(document.getElementById){
	  w=document.getElementById(span).offsetWidth;
	}
return w;
},
heightEl:function(span){

	if (document.layers){
	  h=document.layers[span].clip.height;
	} else if (document.all && !document.getElementById){
	  h=document.all[span].offsetHeight;
	} else if(document.getElementById){
	  h=document.getElementById(span).offsetHeight;
	}
return h;
},
load:function(){
if (!document.getElementsByTagName){ return; }
var spans = document.getElementsByTagName("span");	
// loop through all span tags
	for (var i=0; i<spans.length; i++){
		var spn = spans[i];
        	if (((' '+spn.className+' ').indexOf("editText") != -1) && (spn.id)) {
			spn.onclick = function () { InstanEdit.ToText(this); };
			spn.style.cursor = "pointer";
			spn.title = "Edit Data Ini";
			
       		}

	}
}	
	
};


var Edit = {
changed:false,
Enter:function(element,evt,id){
evt = (evt) ? evt : window.event;
if(evt.keyCode == 13 && element.value != "" && !element.value.match(/^ /)){
newElemt = document.getElementById(id);
var url = newElemt.getAttribute('url')+'&id='+newElemt.getAttribute('iddata');
var field = newElemt.getAttribute('field');
var contentedit = 'field='+field+'&textedit='+encodeURIComponent(element.value);
var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
request.open("POST", url);
request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
request.onreadystatechange = function(){
if (request.readyState == 4 && request.status == 200){	
var iwanGanteng = eval ("("+request.responseText+")");
if (iwanGanteng.error) alert(iwanGanteng.errorpesan);	
newElemt.innerHTML = iwanGanteng.edited;
}

};
request.send(contentedit);
//newElemt.innerHTML = element.value;

Edit.changed = false;

}
return false;
},
Blur:function(el,idspn){
if (el.value != "" && !el.value.match(/^ /)){
newElemt = document.getElementById(idspn);
var url = newElemt.getAttribute('url')+'&id='+newElemt.getAttribute('iddata');
var field = newElemt.getAttribute('field');
var contentedit = 'field='+field+'&textedit='+encodeURIComponent(el.value);

var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
request.open("POST", url);
request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
request.onreadystatechange = function(){
if (request.readyState == 4 && request.status == 200){	
var iwanGanteng = eval ("("+request.responseText+")");
if (iwanGanteng.error) alert(iwanGanteng.errorpesan);	
newElemt.innerHTML = iwanGanteng.edited;
}

};
request.send(contentedit);
newElemt.innerHTML = el.value;
Edit.changed = false;
return false;
}
},	
ToText:function(el){
if (!Edit.changed){
width = Edit.widthEl(el.id) + 30;
height = Edit.heightEl(el.id) + 2;
el.innerHTML = el.innerHTML.replace(/<([A-Z][A-Z0-9]*)[^>]*>(.*?)<\/\1>/gi,'$2');
if (el.innerHTML.length < 50){
el.innerHTML = '<input type="text" style="width:'+width+'px;" value="'+el.innerHTML+'" id="'+el.id+'_field" onkeypress="Edit.Enter(this,event,\''+el.id+'\')" onblur="return Edit.Blur(this,\''+el.id+'\')">';
}else {
el.innerHTML = '<textarea type="text" cols="40" rows="3" id="'+el.id+'_field" onblur="return Edit.Blur(this,\''+el.id+'\')">'+el.innerHTML+'</textarea>';	
}
Edit.changed = true;
el.firstChild.focus();

}
},
ToSelectMouseOut:function(el,idspn){
newElemt = document.getElementById(idspn);
elNew = document.getElementById(el);
//newElemt.innerHTML = el.value;
var kidsel = elNew.options[elNew.selectedIndex].value;
var optioss = elNew.options[elNew.selectedIndex].text;
newElemt.setAttribute('kid',kidsel);


var url = newElemt.getAttribute('url')+'&id='+newElemt.getAttribute('iddata');
var field = newElemt.getAttribute('field');
var contentedit = 'field='+field+'&textedit='+kidsel;

var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
request.open("POST", url);
request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
request.onreadystatechange = function(){
if (request.readyState == 4 && request.status == 200){	
var iwanGanteng = eval ("("+request.responseText+")");
if (iwanGanteng.error) alert(iwanGanteng.errorpesan);	
//newElemt.innerHTML = iwanGanteng.edited;
}

};
request.send(contentedit);


newElemt.innerHTML = optioss;

Edit.changed = false;
},
ToSelect:function(el){
if (!Edit.changed){
var kid = el.getAttribute('kid');
var id = el.getAttribute('iddata');
var field =el.getAttribute('field');

var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
request.open("POST", 'mod/download/download_admin.php?action=loadscript&kid='+kid);
request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
request.onreadystatechange = function(){
if (request.readyState == 4 && request.status == 200){	
var iwanGanteng = eval (""+request.responseText+"");
el.innerHTML = '<select name="'+kid+'" id="'+el.id+'_field" onblur="return Edit.ToSelectMouseOut(\''+el.id+'_field\',\''+el.id+'\')">'+out+'</select> <input type="button" value="close" onclick="Edit.ToSelectMouseOut(\''+el.id+'_field\',\''+el.id+'\')">';

}

};
request.send(null);


Edit.changed = true;
}
	
},
widthEl:function(span){

	if (document.layers){
	  w=document.layers[span].clip.width;
	} else if (document.all && !document.getElementById){
	  w=document.all[span].offsetWidth;
	} else if(document.getElementById){
	  w=document.getElementById(span).offsetWidth;
	}
return w;
},
heightEl:function(span){

	if (document.layers){
	  h=document.layers[span].clip.height;
	} else if (document.all && !document.getElementById){
	  h=document.all[span].offsetHeight;
	} else if(document.getElementById){
	  h=document.getElementById(span).offsetHeight;
	}
return h;
},
mouseover:function(span){
span.style.background = "#d1d1d1";
//span.style.border = "1px solid #d1d1d1";
},
mouseout:function(span){
span.style.border = "0px";
span.style.background = "";
},
load:function(){
if (!document.getElementsByTagName){ return; }
var spans = document.getElementsByTagName("span");	
// loop through all span tags
	for (var i=0; i<spans.length; i++){
		var spn = spans[i];
        	if (((' '+spn.className+' ').indexOf("editText") != -1) && (spn.id)) {
			spn.onclick = function () { Edit.ToText(this); };
			spn.style.cursor = "pointer";
			spn.title = "Edit Data Ini";
			spn.onmouseover = function () { Edit.mouseover(this); };
			spn.onmouseout = function () { Edit.mouseout(this); };
       		}
       		
       		if (((' '+spn.className+' ').indexOf("editSelect") != -1) && (spn.id)) {
			spn.onclick = function () { Edit.ToSelect(this); };
			spn.style.cursor = "pointer";
			spn.title = "Edit Data Ini";
       		}
       		
       
       		

	}
}	
	
};
var all_checked = true;
function checkall(formName, boxName) {
	
	for(i = 0; i < document.getElementById(formName).elements.length; i++)
	{
		var formElement = document.getElementById(formName).elements[i];
		if(formElement.type == 'checkbox' && formElement.name == boxName && formElement.disabled == false)
		{
			formElement.checked = all_checked;
		}
	}	
all_checked = all_checked ? false : true;
};