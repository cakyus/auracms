var dofade=true;
var centertext=false;
var xoffset=9;
var yoffset=25;
var mousefollow=false;
var hideDelay=300;

function altProps(){
this.w3c=(document.getElementById)?true:false;
this.ns4=(document.layers)?true:false;
this.ie4=(document.all && !this.w3c)?true:false;
this.ie5=(document.all && this.w3c)?true:false;
this.ns6=(this.w3c && navigator.appName.indexOf("Netscape")>=0 )?true:false;
this.w_y=0;
this.w_x=0;
this.navtxt=null;
this.boxheight=0;
this.boxwidth=0;
this.ishover=false;
this.ieop=0;
this.op_id=0;
this.oktomove=false;
this.dy=0;



}


var AT=new altProps();


function toggle_centertext(){
centertext=!centertext;
}

function toggle_mousefollow(){
mousefollow=!mousefollow;
}

function toggle_dofade(){
dofade=!dofade;
if(!dofade)AT.ieop=100;
}


function getwindowdims(){
AT.w_y=(AT.ie5||AT.ie4)?document.body.clientHeight:window.innerHeight;
AT.w_x=(AT.ie5||AT.ie4)?document.body.clientWidth:window.innerWidth;
}

function getboxwidth(){
if(AT.ns4)AT.boxwidth=(AT.navtxt.document.width)? AT.navtxt.document.width : AT.navtxt.clip.width;
else if(AT.ie4)AT.boxwidth=(AT.navtxt.style.pixelWidth)? AT.navtxt.style.pixelWidth : AT.navtxt.offsetWidth;
else AT.boxwidth=(AT.navtxt.style.width)? parseInt(AT.navtxt.style.width) : parseInt(AT.navtxt.offsetWidth);
}

function getboxheight(){
if(AT.ns4)AT.boxheight=(AT.navtxt.document.height)? AT.navtxt.document.height : AT.navtxt.clip.height;
else if(AT.ie4)AT.boxheight=(AT.navtxt.style.pixelHeight)? AT.navtxt.style.pixelHeight : AT.navtxt.offsetHeight;
else AT.boxheight=parseInt(AT.navtxt.offsetHeight);
}

function movenavtxt(x,y){
if(AT.ns4)AT.navtxt.moveTo(x,y);
else{
AT.navtxt.style.left=x+'px';
AT.navtxt.style.top=y+'px';
}}

function getpagescrolly(){
if(AT.ie5||AT.ie4)return document.documentElement.scrollTop;
else return window.pageYOffset;
}

function getpagescrollx(){
if(AT.ie5||AT.ie4)return document.documentElement.scrollLeft;
else return window.pageXOffset;
}

function writeindiv(text){
if(AT.ns4){
AT.navtxt.document.open();
AT.navtxt.document.write(text);
AT.navtxt.document.close();
}
else AT.navtxt.innerHTML=text;
}

function writetxt(text){
if(dofade && (AT.ie4||AT.w3c))clearInterval(AT.op_id);
if(text!=0){
if(!mousefollow)clearTimeout(AT.dy);
AT.oktomove=true;
AT.ishover=true;
if(AT.ns4)text='<div class="navtext">'+((centertext)?'<center>':'')+text+((centertext)?'</center>':'')+'</div>';
if(AT.w3c||AT.ie4)AT.navtxt.style.textAlign=(centertext)?"center":"left";
writeindiv(text);
if(AT.ns4)AT.navtxt.visibility="show";
else{
AT.navtxt.style.visibility="visible";
AT.navtxt.style.display="block";
}
getboxheight();
if((AT.w3c||AT.ie4) && dofade){
if(AT.ie4||AT.ie5)AT.navtxt.style.filter="alpha(opacity=0)";
if(AT.ns6)AT.navtxt.style.MozOpacity=0;
AT.ieop=0;
AT.op_id=setInterval('incropacity()',50);
}}else{
if(mousefollow)hideAlttxt();
else AT.dy=setTimeout('hideAlttxt()',hideDelay);
}}

function hideAlttxt(){
if(AT.ns4)AT.navtxt.visibility="hide";
else{
AT.navtxt.style.display="none";
AT.navtxt.style.visibility="hidden";
}
movenavtxt(-AT.boxwidth-10,0);
writeindiv('');
}

function incropacity(){
if(AT.ieop<=100){
AT.ieop+=15;
if(AT.ie4||AT.ie5)AT.navtxt.style.filter="alpha(opacity="+AT.ieop+")";
if(AT.ns6)AT.navtxt.style.MozOpacity=AT.ieop/100;
}else clearInterval(AT.op_id);
}

function moveobj(evt){
mx=(AT.ie5||AT.ie4)?event.clientX:evt.pageX;
my=(AT.ie5||AT.ie4)?event.clientY:evt.pageY;
if(AT.ishover && AT.oktomove){
margin=(AT.ie4||AT.ie5)?5:25;
if(AT.ns6)if(document.height+27-window.innerHeight<0)margin=15;
if(AT.ns4)if(document.height-window.innerHeight<0)margin=10;
if(AT.ns4||AT.ns6)mx-=getpagescrollx();
if(AT.ns4)my-=getpagescrolly();
xoff=mx+xoffset;
yoff=(my+AT.boxheight+yoffset-((AT.ns6)?getpagescrolly():0)>=AT.w_y)? -5-AT.boxheight-yoffset: yoffset;
movenavtxt( Math.min(AT.w_x-AT.boxwidth-margin , Math.max(2,xoff))+getpagescrollx(), my+yoff+((!AT.ns6)?getpagescrolly():0));
if(!mousefollow)AT.oktomove=false;
}}


function onload(){
  AT.navtxt=(AT.ns4)?document.layers['navtxt']:(AT.ie4)?document.all['navtxt']:(AT.w3c)?document.getElementById('navtxt'):null;
  getboxwidth();
  getboxheight();
  getwindowdims();
  if(AT.ie4||AT.ie5&&dofade)AT.navtxt.style.filter="alpha(opacity=100)";
  AT.navtxt.onmouseover=function(){
  if(!mousefollow)clearTimeout(AT.dy);
  }
  AT.navtxt.onmouseout=function(){
  if(!mousefollow)AT.dy=setTimeout('hideAlttxt()',hideDelay);
  }
  if(AT.ns4)document.captureEvents(Event.MOUSEMOVE);
  document.onmousemove=moveobj;
  window.onresize=getwindowdims;
}
