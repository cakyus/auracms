function createRequestObject() {   
    var ro;   
    var browser = navigator.appName;   
    if(browser == "Microsoft Internet Explorer"){   
        ro = new ActiveXObject("Microsoft.XMLHTTP");   
    }else{   
        ro = new XMLHttpRequest();   
    }   
    return ro;   
}   
  
var http = createRequestObject();   
var input;   
     
function utf8(wide) {   
    var c, s;   
    var enc = "";   
    var i = 0;   
    while (i < wide.length) {   
      c= wide.charCodeAt(i++);   
      // handle UTF-16 surrogates   
      if (c>=0xDC00 && c<0xE000) continue;   
      if (c>=0xD800 && c<0xDC00) {   
        if (i>=wide.length) continue;   
        s= wide.charCodeAt(i++);   
        if (s<0xDC00 || c>=0xDE00) continue;   
        c= ((c-0xD800)<<10)+(s-0xDC00)+0x10000;   
      }   
      // output value   
      if (c<0x80) enc += String.fromCharCode(c);   
      else if (c<0x800) enc += String.fromCharCode(0xC0+(c>>6),0x80+(c&0x3F));   
      else if (c<0x10000) enc += String.fromCharCode(0xE0+(c>>12),0x80+(c>>6&0x3F),0x80+(c&0x3F));   
      else enc += String.fromCharCode(0xF0+(c>>18),0x80+(c>>12&0x3F),0x80+(c>>6&0x3F),0x80+(c&0x3F));   
    }   
    return enc;   
  }   
  
function toHex(n) {   
    var hexchars = "0123456789ABCDEF";   
    return hexchars.charAt(n>>4)+hexchars.charAt(n & 0xF);   
  }   
function encodeURIComponent(s) {   
    var okURIchars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_-";   
    var s = utf8(s);   
    var c;   
    var enc = "";   
    for (var i= 0; i<s.length; i++) {   
      if (okURIchars.indexOf(s.charAt(i)) == -1)   
        enc += "%" + toHex(s.charCodeAt(i));   
      else  
        enc += s.charAt(i);   
    }   
    return enc;   
  }   
  
function sndGetReq() {   
    http.open('post', 'ajax.php');   
    http.onreadystatechange = handleResponse;   
    http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');   
    http.send('action=get');   
       
}   
  
function sndInputReq() {   
    http.open('post', 'ajax.php');   
    http.onreadystatechange = handleResponse;   
    http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');   
    http.send('action=load&action=set&uzenet=' + encodeURIComponent(document.Input_Form.chat_input.value));   
    document.Input_Form.chat_input.value = "";   
}   
  
function sndLoadReq() {   
    input = prompt('Milyen néven akarsz bejelentkezni?');   
    http.open('post', 'ajax.php');   
    http.onreadystatechange = handleResponse;   
    http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');   
    http.send('action=load&name=' + encodeURIComponent(input));   
}   
  
function sndCheckReq() {   
    http.open('post', 'ajax_check.php');   
    http.onreadystatechange = handleResponse;   
    http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');   
    http.send(null);   
}   
  
function handleResponse() {   
    if(http.readyState == 4){   
        var response = http.responseText;   
        var update = new Array();   
  
        if(response.indexOf('*******' != -1)) {   
            update = response.split('*******');   
            var element = document.getElementById(update[0]);   
            if (element == 'chat_status') {   
                element.innerHTML = update[1];   
            }   
            else {   
                element.innerHTML += update[1];   
            }   
        }   
    }   
}  
