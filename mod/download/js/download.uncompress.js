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
bukakategori:function(querystring){
document.getElementById('load').style.display = 'block';
querystring = querystring == null ? null : querystring;	
var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("GET", 'mod/download/download_data.php?'+querystring, true);	
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
BrowsV = '<a onclick="download.clickurl(\'dl_jump.php?id='+auraCMS.List[i].id+'\');" style="cursor:pointer;font-weight:bold">'+auraCMS.List[i].judul+'</a>';
isidownloads += '<tr><td><img src="mod/download/images/intlink_1.gif" align=absmiddle> '+BrowsV+' '+newlink+'<br \>'+auraCMS.List[i].keterangan+'<br \><span style="font-size:9px"><b>Added on</b> : '+auraCMS.List[i].date+' <b>View</b> : '+auraCMS.List[i].hit+' <b>Size</b> : '+auraCMS.List[i].size+'</span><br />'+auraCMS.List[i].rate+'<br \></td></tr>';
}
isidownloads += '</table>';	

}else {
alert('tidak Ada Data');	
}

html = '<div class="border">Category: <a style="cursor:pointer;font-weight:bold" onclick="download.indexs()">Home</a> / '+kategorilink+'</div>';
html += '<div class=border>';
html += isidownloads;
html += '</div>';
if (auraCMS.paging != "") html += '<div class="border">'+auraCMS.paging+'</div>';
document.getElementById('respon').innerHTML = html;	


/* =============================================================== */
var ratingAction = {
		'a.rater' : function(element){
			element.onclick = function(){

			var parameterString = this.href.replace(/.*\?(.*)/, "$1"); // onclick="sndReq('j=1&q=2&t=127.0.0.1&c=5');
			var parameterTokens = parameterString.split("&"); // onclick="sndReq('j=1,q=2,t=127.0.0.1,c=5');
			var parameterList = new Array();

			for (j = 0; j < parameterTokens.length; j++) {
				var parameterName = parameterTokens[j].replace(/(.*)=.*/, "$1"); // j
				var parameterValue = parameterTokens[j].replace(/.*=(.*)/, "$1"); // 1
				parameterList[parameterName] = parameterValue;
			}
			var theratingID = parameterList['q'];
			var theVote = parameterList['j'];
			var theuserIP = parameterList['t'];
			var theunits = parameterList['c'];
			
			//alert('sndReq('+theVote+','+theratingID+','+theuserIP+','+theunits+')'); return false;
			sndReq(theVote,theratingID,theuserIP,theunits);return false;		
			}
		}
		
	};
	
Behaviour.register(ratingAction);
Behaviour.apply();

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
	
indexs : function(querystring){
document.getElementById('load').style.display = 'block';
querystring = querystring == null ? null : querystring;	
var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("GET", 'mod/download/download_data.php?'+querystring, true);	
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
isidownloads += '<td valign=top width=50%><img src="mod/download/images/dir3.png" align=absmiddle> <a style="cursor:pointer" onclick="download.bukakategori(\'action=detail&id='+auraCMS.List[i].kid+'\')"><b>'+auraCMS.List[i].kategori+'</b></a> ('+auraCMS.List[i].total+') '+newlink+'<br \><span style="font-size:9px">'+auraCMS.List[i].keterangan+'</span></td>';
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
	
	var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open("GET", "mod/download/download_data.php?"+querystring, true);
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
BrowsV = '<a onclick="download.clickurl(\'dl_jump.php?id='+auraCMSsimpan.List[i].id+'\');" style="cursor:pointer;font-weight:bold">'+auraCMSsimpan.List[i].judul+'</a>';
html += '<tr><td><img src="mod/download/images/intlink_1.gif" align=absmiddle> '+BrowsV+' '+newlink+'<br \>'+auraCMSsimpan.List[i].keterangan+'<br \><span style="font-size:9px"><b>Added on</b> : '+auraCMSsimpan.List[i].date+' <b>View</b> : '+auraCMSsimpan.List[i].hit+' <b>Size</b> : '+auraCMSsimpan.List[i].size+'</span><br />'+auraCMSsimpan.List[i].rate+'<br \></td></tr>';

}
html += '</table></div>';
}else {
alert('tidak Ada Data');	
}
	
		if (auraCMSsimpan.paging != "") html += '<div class=border>'+auraCMSsimpan.paging+'</div>';
			document.getElementById('isidatadownload').innerHTML = html;
			/* =============================================================== */
var ratingAction = {
		'a.rater' : function(element){
			element.onclick = function(){

			var parameterString = this.href.replace(/.*\?(.*)/, "$1"); // onclick="sndReq('j=1&q=2&t=127.0.0.1&c=5');
			var parameterTokens = parameterString.split("&"); // onclick="sndReq('j=1,q=2,t=127.0.0.1,c=5');
			var parameterList = new Array();

			for (j = 0; j < parameterTokens.length; j++) {
				var parameterName = parameterTokens[j].replace(/(.*)=.*/, "$1"); // j
				var parameterValue = parameterTokens[j].replace(/.*=(.*)/, "$1"); // 1
				parameterList[parameterName] = parameterValue;
			}
			var theratingID = parameterList['q'];
			var theVote = parameterList['j'];
			var theuserIP = parameterList['t'];
			var theunits = parameterList['c'];
			
			//alert('sndReq('+theVote+','+theratingID+','+theuserIP+','+theunits+')'); return false;
			sndReq(theVote,theratingID,theuserIP,theunits);return false;		
			}
		}
		
	};
	
Behaviour.register(ratingAction);
Behaviour.apply();
			
			
					
				}else {
					Fat.fade_element("responseFade2",null,1000,'#FF3333');
					document.getElementById('responseFade').innerHTML = '<div class=border id=responseFade2>'+auraCMSsimpan.caption+'</div>';
					document.getElementById('isidatadownload').innerHTML = '';
				}

			}

		}

	};
request.send(null);		
return false;
},
cari : function () {
download.dhtmlLoadScript('js/post.js');
download.dhtmlLoadScript('js/fat.js');
var html = '';
html += '<div class=border><form name="myform" id="myform" onsubmit="return download.cariclick(\'action=cari&search=\'+search.value)"><span>Cari : </span><input type="text" name="search" size=30 id="fieldcari"> <input tabindex="2" type="button" value="cari" onclick="download.cariclick(\'action=cari&search=\'+search.value)"></form></div><div id="responseFade"></div><div id=isidatadownload></div>';

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
document.getElementById('headerdownload').innerHTML = '<div class=border style="text-align:right"><a style="cursor:pointer" onclick="download.indexs()" title="Kembali Ke halaman Download Directory">Home</a> | <a style="cursor:pointer" onclick="download.bukakategori(\'action=new\')" title="New Download">New Download</a> | <a style="cursor:pointer" onclick="download.bukakategori(\'action=top\')" title="Top Download">Top Download</a> | <a style="cursor:pointer" onclick="download.cari()" title="Cari Download">Search</a></div>';