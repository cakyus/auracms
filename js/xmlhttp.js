var xmlhttp = false;
var ajax = {
ErrorRequest : new Array (
203,
204,
205,
206,
400,
401,
403,
404,
406,
407,
408,
409,
410,
415,
500,
503,
505
),
Content_type : new Array(
'application/andrew-inset',
'application/mac-binhex40',
'application/mac-compactpro',
'application/mathml+xml',
'application/msword',
'application/octet-stream',
'application/oda',
'application/ogg',
'application/pdf',
'application/postscript',
'application/rdf+xml',
'application/smil',
'application/srgs',
'application/srgs+xml',
'application/vnd.mif',
'application/vnd.mozilla.xul+xml',
'application/vnd.ms-excel',
'application/vnd.ms-powerpoint',
'application/vnd.wap.wbxml',
'application/vnd.wap.wmlc',
'application/vnd.wap.wmlscriptc',
'application/voicexml+xml',
'application/x-bcpio',
'application/x-cdlink',
'application/x-chess-pgn',
'application/x-cpio',
'application/x-csh',
'application/x-director',
'application/x-dvi',
'application/x-futuresplash',
'application/x-gtar',
'application/x-hdf',
'application/x-javascript',
'application/x-koan',
'application/x-latex',
'application/x-netcdf',
'application/x-sh',
'application/x-shar',
'application/x-shockwave-flash',
'application/x-stuffit',
'application/x-sv4cpio',
'application/x-sv4crc',
'application/x-tar',
'application/x-tcl',
'application/x-tex',
'application/x-texinfo',
'application/x-troff',
'application/x-troff-man',
'application/x-troff-me',
'application/x-troff-ms',
'application/x-ustar',
'application/x-wais-source',
'application/xhtml+xml',
'application/xslt+xml',
'application/xml',
'application/xml-dtd',
'application/zip',
'audio/basic',
'audio/midi',
'audio/mpeg',
'audio/x-aiff',
'audio/x-mpegurl',
'audio/x-pn-realaudio',
'application/vnd.rn-realmedia',
'audio/x-wav',
'chemical/x-pdb',
'chemical/x-xyz',
//'image/bmp',
//'image/cgm',
//'image/gif',
//'image/ief',
//'image/jpeg',
//'image/png',
//'image/svg+xml',
//'image/tiff',
//'image/vnd.djvu',
//'image/vnd.wap.wbmp',
//'image/x-cmu-raster',
//'image/x-icon',
//'image/x-portable-anymap',
//'image/x-portable-bitmap',
//'image/x-portable-graymap',
//'image/x-portable-pixmap',
//'image/x-rgb',
//'image/x-xbitmap',
//'image/x-xpixmap',
//'image/x-xwindowdump',
'model/iges',
'model/mesh',
'model/vrml',
'text/calendar',
//'text/css',
//'text/html',
//'text/plain',
//'text/richtext',
//'text/rtf',
//'text/sgml',
//'text/tab-separated-values',
//'text/vnd.wap.wml',
//'text/vnd.wap.wmlscript',
//'text/x-setext',
//'video/mpeg',
//'video/quicktime',
//'video/vnd.mpegurl',
//'video/x-msvideo',
//'video/x-sgi-movie',
'x-conference/x-cooltalk',
'application/x-gzip'
),
connect:function (){
xmlhttp = false;
try {
xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
//alert ("You are using Microsoft Internet Explorer. 1");
} catch (e) {
try {
xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
//alert ("You are using Microsoft Internet Explorer 2");
} catch (E) {
xmlhttp = false;
}
}

if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
try {
xmlhttp = new XMLHttpRequest();
//alert ("You are not using Microsoft Internet Explorer");
}catch (e) {
xmlhttp = false;	
}
}

return xmlhttp;
},
sendError:function(url){
xmlhttp = false;
xmlhttp = ajax.connect();	
xmlhttp.open("GET", url);
xmlhttp.onreadystatechange = function() {

	if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		alert ('Unable To Located This URL, Gagal Membuka URL');
		//location.href = url;
		return;
		}
		
};
xmlhttp.send(null);	
},
request:function(Url,Method){
Method = (Method == null) ? 'GET' : Method;

xmlhttp = false;
xmlhttp = ajax.connect();
xmlhttp.open(Method, Url);

return xmlhttp;
},

downloaded:function(serverPage,ErrorPage){
xmlhttp = false;
xmlhttp = ajax.connect();	

try{
xmlhttp.open("GET", serverPage);
}catch (e){
//xmlhttp.abort();
alert(xmlhttp.status);
xmlhttp = false;	
alert('abort: , '+e);

	
}

if (typeof xmlhttp == 'object'){


xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && ajax.ErrorRequest.toString().match(xmlhttp.status)){
alert(xmlhttp.status +' '+xmlhttp.statusText);
//xmlhttp.abort();
ajax.sendError(ErrorPage);
return;
}

if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
var contenttype = xmlhttp.getResponseHeader("Content-Type");
contenttype_split = contenttype.split(';');
var tostring = ajax.Content_type.toString();
if (tostring.toLowerCase().match(contenttype_split[0].toLowerCase())){
//alert(xmlhttp.getResponseHeader('Content-type'));	
location.href = serverPage;
   
return;
}else {
if (confirm('Buka Window Baru')) window.open(serverPage);
}

}

if (xmlhttp.readyState == 4 && xmlhttp.status == 12007){
alert('Aborted : Cannot Find DNS');	
}



};
xmlhttp.send(null);
}

}


}