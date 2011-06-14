/*
Modul Weblinks Untuk AuraCMS 2.1
Created By	: Ridwan
Homepage	: ridwan.or.id
Modified 	: July 30, 2007 04:40:27 AM 
*/
document.getElementById('headerlink').innerHTML = '<div class=border style="text-align:right"><a style="cursor:pointer" onclick="weblink.links()" title="Kembali Ke halaman Link Directory">Home</a> | <a style="cursor:pointer" onclick="weblink.tambah()" title="Tambah Link Anda Disini">Add Link</a> | <a style="cursor:pointer" onclick="weblink.cari()" title="Cari Links">Search Link</a></div>';
var weblink = {
loadingTextInterval: setInterval(function(){
		if (document.getElementById("ellipsis") && document.getElementById('load').style.display == 'block'){
			var dots = document.getElementById("ellipsis").innerHTML;
			document.getElementById("ellipsis").innerHTML = (dots != "...") ? dots += "." : "";
		}
	}, 500),
links : function (querystring){
document.getElementById('load').style.display = 'block';
querystring = querystring == null ? null : querystring;	
var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("GET", 'mod/links/links_data.php?'+querystring, true);	
request.onreadystatechange = function(){
	
if (request.readyState == 4){	
var auraCMS = request.responseText != "" ? eval("(" + request.responseText + ")") : null;
if (auraCMS != null){
var isilinks = '';
var total = auraCMS.ListLink.length;
if (total > 0){	
isilinks += '<table border=0><tr>';
for (i=0;i<total;i++){
newlink = auraCMS.ListLink[i].newLink != "" ? auraCMS.ListLink[i].newLink : '';
isilinks += '<td valign=top width=50%><img src="mod/links/images/dir3.png" align=absmiddle> <a style="cursor:pointer" onclick="weblink.bukakategori(\'action=detail&id='+auraCMS.ListLink[i].kid+'\')"><b>'+auraCMS.ListLink[i].kategori+'</b></a> ('+auraCMS.ListLink[i].total+') '+newlink+'<br \><span style="font-size:9px">'+auraCMS.ListLink[i].keterangan+'</span></td>';
if ((i+1) % 2 == 0) isilinks += '</tr><tr>';
}
isilinks += '</table>';		
}else {
alert('tidak Ada Data');	
}



var html = '<div class=border>';
html += isilinks;
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
	
	var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("GET", "mod/links/links_data.php?"+querystring, true);
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	request.onreadystatechange = function(){
		if (request.readyState == 4 && request.responseText != ""){
			var auraCMSsimpan =  eval("(" + request.responseText + ")");
			
			if (typeof(auraCMSsimpan) == 'object'){
				
				if (auraCMSsimpan.finds == true){
				Fat.fade_element("responseFade2",null,1000,'#FF3333');
					document.getElementById('responseFade').innerHTML = '<div class=border id=responseFade2>'+auraCMSsimpan.caption+'</div>';
					
					var html = '';
					var total = auraCMSsimpan.List.length;
if (total > 0){
var html = '<div class=border><table>';

for (i=0;i<total;i++){
newlink = auraCMSsimpan.List[i].newlinks != "" ? auraCMSsimpan.List[i].newlinks : '';
html += '<tr><td><img src="mod/links/images/intlink_1.gif" align=absmiddle> <a style="cursor:pointer;font-weight:bold" href="links_jump.php?id='+auraCMSsimpan.List[i].id+'" target="_blank">'+auraCMSsimpan.List[i].judul+'</a> '+newlink+'<br \>'+auraCMSsimpan.List[i].keterangan+'<br \><span style="font-size:9px"><b>Added on</b> : '+auraCMSsimpan.List[i].date+' <b>View</b> : '+auraCMSsimpan.List[i].hit+'</span><br \><br \></td></tr>';

}
html += '</table></div>';
}else {
alert('tidak Ada Data');	
}
					
					
					
					
					
		if (auraCMSsimpan.paging != "") html += '<div class=border>'+auraCMSsimpan.paging+'</div>';
			document.getElementById('isidataweblink').innerHTML = html;
					
					
					
					
					
				}else {
					Fat.fade_element("responseFade2",null,1000,'#FF3333');
					document.getElementById('responseFade').innerHTML = '<div class=border id=responseFade2>'+auraCMSsimpan.caption+'</div>';
					document.getElementById('isidataweblink').innerHTML = '';
				}

			}


				

		}

	};
request.send(null);	
	
	
	
	
return false;
},
cari : function () {
weblink.dhtmlLoadScript('js/post.js');
weblink.dhtmlLoadScript('js/fat.js');
var html = '';
html += '<div class=border><form name="myform" id="myform" onsubmit="return weblink.cariclick(\'action=cari&search=\'+search.value)"><span>Cari : </span><input type="text" name="search" size=30 id="fieldcari"> <input tabindex="2" type="button" value="cari" onclick="weblink.cariclick(\'action=cari&search=\'+search.value)"></form></div><div id="responseFade"></div><div id=isidataweblink></div>';

document.getElementById('respon').innerHTML = html;	

},
simpan : function (a) {
	a.disabled = true;
	var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("POST", "mod/links/links_data.php?action=add&token="+Math.random(), true);
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	request.onreadystatechange = function(){
		if (request.readyState == 4){
			var auraCMSsimpan = eval("(" + request.responseText + ")");
			
			if (typeof(auraCMSsimpan) == 'object'){
				
				if (auraCMSsimpan.error == true){
					
					
					Fat.fade_element("responseFade2",null,1000,'#FF3333');
					document.getElementById('responseFade').innerHTML = '<div class="error" id="responseFade2">'+auraCMSsimpan.pesanError+'</div>';
					//<img src="includes/code_image.php?math.rand='+Math.random()+'" border="1" alt="Security Code">
					document.getElementById('rahasia').innerHTML = '<img src="includes/code_image.php?math.rand='+Math.random()+'" border="1" alt="Security Code">';
					a.disabled = false;
					
					
					}else {
					Fat.fade_element("responseFade2",null,1000,'#FF3333');	
					document.getElementById('responseFade').innerHTML = '<div class="sukses" id="responseFade2">Thank you, Message has been sent!<br />Waiting To Accept</div>';	
					
					//hapus form
					var myforms = document.getElementById('myform');
					myforms.judul.value = '';	
					myforms.url.value = '';	
					myforms.keterangan.value = '';
					setTimeout("weblink.links()", 2000);	
					}

				

			}


				

		}

	};
request.send(postForm.getFormValues('myform'));	
	
	
	

},	
tambah : function (){
document.getElementById('load').style.display = 'block';
weblink.dhtmlLoadScript('js/post.js');
weblink.dhtmlLoadScript('js/fat.js');
var isilinks = '';



var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
request.open("GET", 'mod/links/links_data.php?action=showkategori', true);	
request.onreadystatechange = function(){
var ready = '';	
if (request.readyState == 4){	
var auraCMS = request.responseText != "" ? eval("(" + request.responseText + ")") : null;
if (auraCMS != null){

var total = auraCMS.List.length;
if (total > 0){
ready += 'Kategori : <br \><select name="kategori" size=1><option value="">--select--</option>';
for (i=0;i<total;i++){
//newlink = auraCMS.ListLink[i].newLink != "" ? auraCMS.ListLink[i].newLink : '';
ready += '<option value="'+auraCMS.List[i].id+'">'+auraCMS.List[i].kategori+'</option>';
}
ready += '</select>';		
}else {
alert('tidassk Ada Data');	
}


}

document.getElementById('load').style.display = 'none';
document.getElementById('idkategori').innerHTML = ready;

}
};


request.send(null);	

var html = '<div id="responseFade"></div><blockquote><p><b>Aturan :</b><br>Kode html tidak diijinkan.<br>Catatan : no flood,no spam</p></blockquote><div class=border>';
html +='';
html += '<form name="myform" id="myform"><table><tr><td>Judul :<br \><input type="text" name="judul" size=30 maxlength="50"></td></tr><tr><td>Url :<br \><input type="text" name="url" size=30 maxlength="200"></td></tr><tr><td><span id="idkategori"></span>';
html += '</td></tr><tr><td>Keterangan :<br \><textarea name="keterangan" row=20 cols=40></textarea></td></tr><tr><td>Kode : <br \><span id="rahasia"><img src="includes/code_image.php?math.rand='+Math.random()+'"></span><br \><input type="text" name="kode" size=10 maxlength="6"></td></tr><tr><td><input type="button" value="add" onclick="weblink.simpan(this)"> <input type="button" value="cancel" onclick="weblink.links()"></td></tr></table></form>';
html += '</div>';
document.getElementById('respon').innerHTML = html;		
},
bukakategori : function (querystring){
document.getElementById('load').style.display = 'block';
querystring = querystring == null ? null : querystring;	
var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("GET", 'mod/links/links_data.php?'+querystring, true);	
request.onreadystatechange = function(){
	
if (request.readyState == 4){	
var auraCMS = request.responseText != "" ? eval("(" + request.responseText + ")") : null;
if (typeof(auraCMS.List) == 'object'){
var isilinks = '';
var total = auraCMS.List.length;
if (total > 0){
var kategorilink = typeof (auraCMS.kategori) == 'object' ? auraCMS.kategori[0] : '';
isilinks += '<table border=0>';
for (i=0;i<total;i++){
newlink = auraCMS.List[i].newlinks != "" ? auraCMS.List[i].newlinks : '';
isilinks += '<tr><td><img src="mod/links/images/intlink_1.gif" align=absmiddle> <a style="cursor:pointer;font-weight:bold" href="links_jump.php?id='+auraCMS.List[i].id+'" target="_blank">'+auraCMS.List[i].judul+'</a> '+newlink+'<br \>'+auraCMS.List[i].keterangan+'<br \><span style="font-size:9px"><b>Added on</b> : '+auraCMS.List[i].date+' <b>View</b> : '+auraCMS.List[i].hit+'</span><br \><br \></td></tr>';
}
isilinks += '</table>';		
}else {
alert('tidak Ada Data');	
}

html = '<div class=border>Category: <a style="cursor:pointer;font-weight:bold" onclick="weblink.links()">Home</a> / '+kategorilink+'</div>';
html += '<div class=border>';
html += isilinks;
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
dhtmlLoadScript : function (url)
   {
      var e = document.createElement("script");
	  e.src = url;
	  e.type="text/javascript";
	  document.getElementsByTagName("head")[0].appendChild(e);	  
   }
};
addrow=function(tbid, st) {
	var tb= document.getElementById(tbid);
	var tr= document.createElement('TR');
	tb.appendChild(tr);
	var td= document.createElement('TD');
	tr.appendChild(td);
	
	var td2= document.createElement('TD');
	tr.appendChild(td2);
	td2.innerHTML= st;
  };