borderfiles=function(datafiles){
var out = '<table class=album cellSpacing=1 cellPadding=0 width=80  height=80 border=0><tr><td><table class=album1 cellSpacing=0 cellPadding=0 width=80  height=80 border=0><tr onmouseover="this.style.backgroundColor=\'#efefef\';" onmouseout="this.style.backgroundColor=\'\';"><td align=center>'+datafiles+'</td></tr></table></td></tr></table>';	
return out;	
};

var replacedata = {quotes:function(text){
var do1 = text.replace (/'/,"\\\'");
do1 = do1.replace(/"/,'\\\"'); 
return do1;
}};

substrdata=function(vardata,maxdata){
var txt = vardata.substring(0,10);
if (vardata.length > maxdata) txt += '...';
return txt;
};

loadingTextInterval = setInterval(function(){
		if (document.getElementById("ellipsis") && document.getElementById('load').style.display == 'block'){
			var dots = document.getElementById("ellipsis").innerHTML;
			document.getElementById("ellipsis").innerHTML = (dots != "...") ? dots += "." : "";
		}
	}, 500);

detailgallery=function(querystring){
document.getElementById('load').style.display = 'block';
var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("GET", 'mod/gallery/ajax/gallery_data.php?'+querystring, true);	
request.onreadystatechange = function(){
if (request.readyState == 4 && request.responseText != ""){	
var iwan = eval("(" + request.responseText + ")");	
if (typeof(iwan.gallery) == 'object'){
	
var html = '';
html += '<div class="border" style="text-align:left"><h4>'+iwan.boxheader[0]+'</h4></div>';
var total = iwan.total[0];
var now = parseInt(iwan.now[0]);
var w = iwan.gallery[now].w;
var h = iwan.gallery[now].h;
var wd = iwan.gallery[now].width;
var hg = iwan.gallery[now].height;
var view = iwan.gallery[now].view;
var desc = iwan.gallery[now].desc;
var tpath = iwan.config.tpath;		
var tnpath = iwan.config.tnpath;

var img = '<img src="'+tpath+iwan.gallery[now].name+'" width="'+w+'" height="'+h+'">';


var prev = now - 1;
var next = now + 1;
var imgprev = '';
var imgnext = '';
var albums = iwan.albums[0];
var captionnext = '';
var captionprev = '';
var urlsorting = typeof(iwan.urlsorting) == 'object' ? iwan.urlsorting[0] : '';
if (typeof(iwan.gallery[prev]) == 'object'){
var idprev = iwan.gallery[prev].id;	
imgprev = borderfiles('<a style="cursor:pointer" onclick="detailgallery(\'action=detail&id='+idprev+'&image='+(parseInt(iwan.image[0])-1)+'&albums='+albums+'&'+urlsorting+'\');"><img src="'+tnpath+iwan.gallery[prev].name+'"></a>');
captionprev = '&laquo; Prev';

}
if (typeof(iwan.gallery[next]) == 'object'){
var idnext = iwan.gallery[next].id;
imgnext = borderfiles('<a style="cursor:pointer" onclick="detailgallery(\'action=detail&id='+idnext+'&image='+(parseInt(iwan.image[0])+1)+'&albums='+albums+'&'+urlsorting+'\');"><img src="'+tnpath+iwan.gallery[next].name+'"></a>');
captionnext = 'Next &raquo;';
}

html += '<div class="border"><center><table style="border:1px solid #d1d1d1" align=center><tr><td align=center>'+img+'</td></tr><tr><td align=center><span style="font-size:9px;">Image '+(parseInt(iwan.image[0])+1)+' of '+total+'<br>Dimension : '+wd+' x '+hg+'<br> View : '+view+'<br><div style="width:300px">'+desc+'</div></span></td></tr></table><table align=center><tr><td>'+imgprev+'</td><td>'+imgnext+'</td></tr><tr><td align=center>'+captionprev+'</td><td align=center>'+captionnext+'</td></tr></table></center></div>';


html += '<div class=border>Keterangan : <br><br>Untuk Next : Tekan Keyboard (Right Arrow) &raquo;<br>Untuk Prev : Tekan Keyboard (Left Arrow) &laquo;<br>Untuk Kembali Tekan Keyboard : Esc</div>';
events=function(evt){
evt = (evt) ? evt : event;
//37 39
if (evt.keyCode == 39 && typeof(iwan.gallery[next]) == 'object'){	
detailgallery('action=detail&id='+idnext+'&image='+(parseInt(iwan.image[0])+1)+'&albums='+albums+'&'+urlsorting+'');
return false;
}
if (evt.keyCode == 37 && typeof(iwan.gallery[prev]) == 'object'){	
detailgallery('action=detail&id='+idprev+'&image='+(parseInt(iwan.image[0])-1)+'&albums='+albums+'&'+urlsorting+'');
return false;
}
if (evt.keyCode == 27){	
bukagallery('action=album&albums='+albums);
return false;
}


};
document.body.onkeydown = events;
window.onkeydown = events;	
	
document.getElementById('respon').innerHTML = html;

}
document.getElementById('load').style.display = 'none';
}


};
request.send(null);
};

bukagallery=function(querystring){
document.body.onkeydown = null;
window.onkeydown = null;
document.getElementById('load').style.display = 'block';	
var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("GET", 'mod/gallery/ajax/gallery_data.php?'+querystring, true);	
request.onreadystatechange = function(){
if (request.readyState == 4 && request.responseText != ""){	
var iwan = eval("(" + request.responseText + ")");	
if (typeof(iwan.gallery) == 'object'){
var total = iwan.gallery.length;
var bd = '';
var intpph = 0;
var tpath = iwan.config.tpath;		
var tnpath = iwan.config.tnpath;
var offset = parseInt(iwan.offset.integer);
var image;
var urlsorting = '';
if (typeof(iwan.urlsorting) == 'object'){
urlsorting = iwan.urlsorting[0];	
}
for (i=0;i<total;i++){
if (iwan.gallery[i].files != null){
image = (offset > 0) ? (offset + i) : i;	
bd += '<td align=center height=150>' + borderfiles ('<a onmouseover="writetxt(\'<b>Files</b> : '+iwan.gallery[i].files+'<br \><b>Dimension</b> : '+iwan.gallery[i].width+' x '+iwan.gallery[i].height+'<br \><b>Description</b> : '+iwan.gallery[i].desc+'\'); return true" onmouseout="writetxt(0);" onclick="writetxt(0);detailgallery(\'action=detail&id='+iwan.gallery[i].id+'&image='+image+'&albums='+iwan.album.albumID+'&'+urlsorting+'\')" style="cursor:pointer;"><img src="'+tnpath+iwan.gallery[i].name+'"></a>') + '<center><small>'+substrdata(iwan.gallery[i].files,10)+'<br>Filesize : '+fsize(iwan.gallery[i].size)+'</small></center></td>';
intpph++;
}

if ( intpph % iwan.config.pptabel == 0) bd += '</tr><tr>';		


}	

var html;

html = '<div class="border" style="text-align:left"><h4>'+iwan.album.name+'</h4></div>';
html += iwan.sorting.url;
html += '<div class=border><table width="100%"><tbody><tr>';
html += bd;
html += '</tbody></table></div>';
html += '<br><br>';
html += iwan.pagging;
html += '<br><br>';
document.getElementById('respon').innerHTML = html;	
}else {
	
html = '<div class="border" style="text-align:left"><h4>'+iwan.album.name+'</h4></div>';
	
	
	
html += '<table width="100%" style="border:1px solid #efefef"><tr><td align="left"><img src="images/warning.gif" border="0"></td><td align="center"><div class="error">Maaf Gallery nya masih Kosong</div></td><td align="right"><img src="images/warning.gif" border="0"></td></tr></table>';
document.getElementById('respon').innerHTML = html;
document.getElementById('load').style.display = 'none';
return;	
}
document.getElementById('load').style.display = 'none';
}

};
	
request.send(null);
};

bukadata=function (querystring){
document.getElementById('load').style.display = 'block';

var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("GET", 'mod/gallery/ajax/gallery_data.php?'+querystring, true);
	
	request.onreadystatechange = function(){
	
		if (request.readyState == 4 && request.responseText != ""){
			
				if (request.status == 404){
				alert ('File Tidak Ditemukan');
				return;
				}
			var iwan = eval("(" + request.responseText + ")");		
				
if (typeof(iwan.gallery) == 'object'){			
var total = iwan.gallery.length;
var tpath = iwan.config.tpath;		
var tnpath = iwan.config.tnpath;		

var bd = '';
var intpph = 0;
for (i=0;i<total;i++){
if (iwan.gallery[i].albums != null){
bd += '<td align=center height=150>' + borderfiles ('<a onmouseover="writetxt(\'<b>album</b> : '+replacedata.quotes(iwan.gallery[i].name)+'<br \><b>Description</b> : '+iwan.gallery[i].desc+'\'); return true" onmouseout="writetxt(0);" onclick="writetxt(0);bukagallery(\'action=album&albums='+iwan.gallery[i].albums+'\');" style="cursor:pointer"><img src="images/folder_gallery.gif"></a>') + '<center><small>'+substrdata(iwan.gallery[i].name,10)+'<br \>Image ('+iwan.gallery[i].images+')</small></center></td>';
intpph++;
}
else if (iwan.gallery[i].files != null){
bd += '<td align=center>' + borderfiles ('<a onclick="detailgallery(\'action=detail&id='+iwan.gallery[i].id+'\')" style="cursor:pointer;"><img src="'+tnpath+iwan.gallery[i].name+'"></a>') + '<center><small>'+substrdata(iwan.gallery[i].files,10)+'<br>Filesize : '+fsize(iwan.gallery[i].size)+'</small></center></td>';
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

}
		document.getElementById('load').style.display = 'none';
	
		}

};
request.send(null);
};
fsize=function (zahl) {
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
};
