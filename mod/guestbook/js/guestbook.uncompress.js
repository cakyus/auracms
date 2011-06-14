var headerbukutamu = '<div class="border"><h3><center><a style="cursor:pointer" onclick="tambahbukutamu()">Add Guestbook</a></center></h3></div>';
document.getElementById('headerbukutamu').innerHTML = headerbukutamu;
loadingTextInterval = setInterval(function(){
		if (document.getElementById("ellipsis") && document.getElementById('load').style.display == 'block'){
			var dots = document.getElementById("ellipsis").innerHTML;
			document.getElementById("ellipsis").innerHTML = (dots != "...") ? dots += "." : "";
		}
	}, 500);
substrdata=function(vardata,maxdata){
var txt = vardata.substring(0,10);
if (vardata.length > maxdata) txt += '...';
return txt;
};
bukutamu=function(querystring){
boxloading('Loading');
var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("GET", 'mod/guestbook/guestbook_data.php?'+querystring, true);	
request.onreadystatechange = function(){
if (request.readyState == 4 && request.responseText != "" && request.status == 200){	
var auraCMS = eval("(" + request.responseText + ")");	
if (typeof auraCMS.bukutamuList != 'object'){
boxloading('Loading');
document.getElementById('respon').innerHTML = 'Tidak Ada Data';
return;	
}
var html = '';
html += '<table style="width:100%" cellspacing="1" cellpadding="0"><tr><td width="30%" class=tabel_header><b>Nama dan Lokasi</b></td><td width="70%" class=tabel_header><b>Komentar</b></td></tr>';
var total = auraCMS.bukutamuList.length;

if (total > 0){
for (i=0;i<total;i++){
alamat = auraCMS.bukutamuList[i].alamat.length > 14 ? substrdata(auraCMS.bukutamuList[i].alamat,12) : auraCMS.bukutamuList[i].alamat;
html += '<tr style="border: 1px solid #f2f2f2;padding: 5px;">';
html += '<td width="30%" valign="top" style="border: 1px solid #f2f2f2;padding: 5px;">'+auraCMS.bukutamuList[i].sekarang+'<br>';
html += '<b><a title="'+auraCMS.bukutamuList[i].email+'">'+auraCMS.bukutamuList[i].nama+'</a></b><br>';
html += '<b>'+alamat+'</b><br><a href="'+auraCMS.bukutamuList[i].homepage+'" target=_blank title="'+auraCMS.bukutamuList[i].homepage+'"><img src="images/www.gif" border="0"></a><br> <A href="mailto:'+auraCMS.bukutamuList[i].email+'" title="'+auraCMS.bukutamuList[i].email+'"><img src="images/email.gif" border="0"></A>';
html += '</td><td width="70%" valign="top" style="border: 1px solid #f2f2f2;padding: 5px;">'+auraCMS.bukutamuList[i].komentar+'';
if (auraCMS.bukutamuList[i].jawab != "") html += '<p><table><tr><td valign="top"><b>Admin :</b></td><td><i>'+auraCMS.bukutamuList[i].jawab+'</i></td></tr></table>';
html += '</td></tr>';
}
}

html += '</table>';
if (auraCMS.paging != "") {html += '<div class="border">'+auraCMS.paging+'</div>';}
document.getElementById('respon').innerHTML = html;
document.getElementById('load').style.display = 'none';
}

};
request.send(null);	
};
attVal=function(element, attName) {
 return parseInt(element.getAttribute(attName));
};
limittxt=function (myform){
var myforms = document.getElementById(myform);
myforms.textarea  = document.getElementById('message');	
var maxlimit=attVal(myforms.textarea, 'maxlength');
var limited = maxlimit - myforms.textarea.value.length;
if (limited <= 0)  myforms.textarea.value = myforms.textarea.value.substring(0,maxlimit);
var limited = maxlimit - myforms.textarea.value.length;
document.getElementById('limiter').innerHTML = limited;
};
getFormValues = function (element){
var submitDisabledElements=false;
if(arguments.length > 1&&arguments[1]==true) submitDisabledElements=true;
var prefix="";
if(arguments.length > 2)prefix=arguments[2];
if("string"==typeof(element))element=element;
var aXml=new Array;
if(document.getElementById(element)){
	var formElements=document.getElementById(element).elements;
	for(var i=0;i < formElements.length;++i){
		var child=formElements[i];
		if(!child.name)continue;
		
		if(prefix!=child.name.substring(0,prefix.length))continue;
		if(child.type&&(child.type=='radio'||child.type=='checkbox')&&child.checked==false)continue;
		if((child.type=='checkbox' || child.type=='text')&&child.disabled==true)continue;
		if(child.type=='reset')continue;
		if(prefix!=child.name.substring(0,prefix.length))continue;
		var name=child.name;
		if(name){
			if(1 < aXml.length) aXml.push('&');
		if('select-multiple'==child.type){
			if(name.substr(name.length-2,2)!='[]') name+='[]';
			for(var j=0;j < child.length;++j){
				
					var option=child.options[j];
					if(true==option.selected){
						aXml.push(name);
						aXml.push("=");
						aXml.push(encodeURIComponent(option.value));
						aXml.push("&");
						}
					}
				}else{
					aXml.push(name);
					aXml.push("=");
					aXml.push(encodeURIComponent(child.value));
					}
			}
	}
			}

return aXml.join('');
};
simpan = function (a) {
	a.disabled = true;
	boxloading('Saving');
	var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("POST", "mod/guestbook/guestbook_data.php?action=add&token="+Math.random(), true);
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	request.onreadystatechange = function(){
		if (request.readyState == 4){
			var auraCMSsimpan = eval("(" + request.responseText + ")");
			boxloading('Saving');
			if (typeof(auraCMSsimpan) == 'object'){
				
				if (auraCMSsimpan.error == true){
					
					
					Fat.fade_element("responseFade2",null,1000,'#FF3333');
					document.getElementById('responseFade').innerHTML = '<div class="error" id="responseFade2">'+auraCMSsimpan.pesanError+'</div>';
					document.getElementById('rahasia').innerHTML = '<img src="includes/code_image.php?math.rand='+Math.random()+'" border="1" alt="Security Code" />';
					a.disabled = false;
					
					
					}else {
					Fat.fade_element("responseFade2",null,1000,'#FF3333');	
					document.getElementById('responseFade').innerHTML = '<div class="sukses" id="responseFade2">Thank you, Message has been sent!</div>';	
					
					var myforms = document.getElementById('myform').reset();
					setTimeout("bukutamu('')", 2000);	
					}

				

			}


				

		}
	}
;request.send(getFormValues('myform'));

};
tambahbukutamu=function (){
dhtmlLoadScript("js/fat.js");
var html = '<div id=responseFade></div>';
html +='<blockquote><p><b>Aturan :</b><br>Kode html tidak diijinkan.<br>Catatan : Dalam komentar, jika pindah baris baru gunakan <b>tombol ENTER</b>.</p></blockquote>';
html +='<div class="border">';

html+= '<form id="myform" method="post" action="" name="myform"><table style="width:100%" cellspacing="4" cellpadding="0"><tr><td valign="top">Your Name</td><td valign="top">:</td><td valign="top"><input type="text" name="nama" size="40" /></td></tr><tr><td valign="top">Your Email</td><td valign="top">:</td><td valign="top"><input type="text" name="email" size="40" /></td></tr><tr><td valign="top">Website</td><td valign="top">:</td><td valign="top"><input type="text" name="homepage" size="40" /></td></tr><tr><td valign="top">Lokasi</td><td valign="top">:</td><td valign="top"><input type="text" name="alamat" size="40" /></td></tr><tr><td valign="top">Message</td><td valign="top">:</td><td valign="top"><textarea rows="10" name="message" id="message"  style="width:300px" cols="20" maxlength="500" onkeyup="limittxt(\'myform\')""></textarea></td></tr><tr><td valign="top"></td><td valign="top"></td><td valign="top"><span id="limiter" style="font-weight:bold"></span></td></tr>';

html+= '<tr><td valign="top">Security Code</td><td valign="top">:</td><td valign="top"> <span id="rahasia"><img src="includes/code_image.php?math.rand='+Math.random()+'" border="1" alt="Security Code"></span></td></tr><tr><td valign="top">Type Code</td><td valign="top">:</td><td valign="top"><input type="text" name="gfx_check" size="10"></td></tr>';

html+= '<tr><td valign="top"></td><td valign="top"></td><td valign="top"></td></tr><tr><td valign="top"></td><td valign="top"></td><td valign="top"><input type="button" onclick="limittxt(\'myform\');simpan(this);" name="submit" value="Submit" id="buttonsubmit"> <input type="button" value="Cancel" onclick="bukutamu(\'\')"></td></tr></table></form>';
html +='Cancel : Ctrl + z</div>';		
document.getElementById('respon').innerHTML = html;	
document.getElementById('myform').onkeydown = function(evt){
evt = (evt) ? evt : event;
if (evt.ctrlKey && evt.keyCode == 90){
bukutamu('');
return false;
}

};

boxloading('Loading');
var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("GET", 'mod/guestbook/guestbook_data.php?action=setting', true);	
request.onreadystatechange = function(){
if (request.readyState == 4 && request.responseText != "" && request.status == 200){	
var auraCMS = eval("(" + request.responseText + ")");	
if (typeof auraCMS.bukutamuList != 'object'){
document.getElementById('myform').message.setAttribute('maxlength',500);
}
document.getElementById('myform').message.setAttribute('maxlength',auraCMS.char);
boxloading('Loading');
}

};
request.send(null);
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
dhtmlLoadScript=function(url)
   {
      var e = document.createElement("script");
	  e.src = url;
	  e.type="text/javascript";
	  document.getElementsByTagName("head")[0].appendChild(e);	  
   };