var komentar = {
url:'mod/news/ajax/komentar_data.php',
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
loadingTextInterval: setInterval(function(){
		if (document.getElementById("ellipsis") && document.getElementById('load').style.display == 'block'){
			var dots = document.getElementById("ellipsis").innerHTML;
			document.getElementById("ellipsis").innerHTML = (dots != "...") ? dots += "." : "";
		}
	}, 500),
buka:function(obj){
$(obj).style.display = ($(obj).style.display == 'none') ? 'inline' : 'none';	
},
indexs:function(query){
boxloading('Loading');
query = (typeof query == 'number') ? komentar.url+'?id='+query : komentar.url+'?'+query;
var xmlhttp = komentar.xmlhttp();
xmlhttp.open("GET", query,true);
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

var Json = eval ("("+xmlhttp.responseText+")");
if (typeof Json.list != 'object'){
boxloading('Loading');
$('respon').innerHTML = '<div class=border>Tidak Ada Komentar Pada Artikel Ini</div>';
komentar.formkomentar(ID);
return false;	
}
var out = '';
var total = Json.list.length;
for(i=0;i<total;i++){
out += '<div style="background:#f1f1f1;padding:5px;"><span title="Detail" style="font-weight:bold;cursor:pointer;" onclick="komentar.buka(\'ip_'+i+'\')">'+Json.list[i].countdown+'. '+Json.list[i].judul+'</span><br /><small id="ip_'+i+'" style="display:none;color:orange">Ip : '+Json.list[i].ip+'<br /></small><small>'+Json.list[i].tgl+' - Oleh : <a>'+Json.list[i].user+'</a></small></div><div style="border:1px solid #efefef;padding:5px;font-size:10px;">'+Json.list[i].konten+'</div><br />';	
}

var html = '';
html += out;
if (Json.pagging != '') html += Json.pagging;
$('respon').innerHTML = html;
boxloading('Loading');
komentar.formkomentar(ID);

}

};
xmlhttp.send(null);
},
formkomentar_click:function(id,obj){

var Frm = $('frm');
var User = Frm.user.value;
var Email = Frm.email.value;
var Judul = Frm.judul.value;
var Konten = Frm.konten.value;
var Codex = Frm.codex.value;
if(User == ""){ alert('Error : Please Enter Your Name'); Frm.user.focus();return false;}
if(Email == ""){ alert('Error : Please Enter Your Email'); Frm.email.focus();return false;}
if(Judul == ""){ alert('Error : Please Enter Comment Title'); Frm.judul.focus();return false;}
if(Konten == ""){ alert('Error : Please Enter Comment'); Frm.konten.focus();return false;}
if(Codex == ""){ alert('Error : Please Enter Security Code'); Frm.codex.focus();return false;}
obj.disabled = true;
boxloading('Sending');
var xmlhttp = komentar.xmlhttp();
xmlhttp.open("POST", komentar.url+'?action=add&id='+id,true);
xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
boxloading('Loading');
var Json = eval ("("+xmlhttp.responseText+")");
if (Json.error == true){
$('formMessage').innerHTML = '<div class="error">'+Json.errorpesan+'</div>';
obj.disabled = false;
$('gambarcode').innerHTML = '<img src="/includes/code_image.php?rand='+Math.random()+'" border=0>';
}else {
$('formMessage').innerHTML = '<div class="sukses">Thank you, Message has been sent!</div>';
$('frm').reset();

setTimeout("komentar.indexs("+id+")",1000);	
}


}

};
xmlhttp.send('user='+encodeURIComponent(User)+'&email='+encodeURIComponent(Email)+'&judul='+encodeURIComponent(Judul)+'&konten='+encodeURIComponent(Konten)+'&codex='+encodeURIComponent(Codex));


},
formkomentar:function(id){
	
$('responbawah').innerHTML = '<br /><div id="formMessage"></div><div class=border><form name="frm" id="frm"><table><tr><td>Name</td><td>:</td><td><input type="text" name="user"></td></tr><tr><td>Email</td><td>:</td><td><input type="text" name="email"></td></tr><tr><td>Comment Title</td><td>:</td><td><input type="text" name="judul"></td></tr><tr><td>Comment</td><td>:</td><td><textarea name="konten" rows=4 cols=40></textarea></td></tr><tr><td>Security Code</td><td>:</td><td><span id="gambarcode"><img src="/includes/code_image.php?rand='+Math.random()+'" border=0></span></td></tr><tr><td>Type Code</td><td>:</td><td><input type="text" name="codex" size=7></td></tr><tr><td></td><td></td><td><input type="button" value="Send" onclick="komentar.formkomentar_click('+id+',this)" id="tomboltekanvalueid"></td></tr></table></form></div>';	
	
}
	
	
	
	
	
};
$=function(e){return document.getElementById(e);};
$('header_ajax').innerHTML = '<h4 class="bg">Komentar Pengunjung</h4>';

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