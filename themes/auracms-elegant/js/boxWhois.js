window.addEventListener?window.addEventListener('load',so_init,false):window.attachEvent('onload',so_init);var d=document,imgs=new Array(),imgs2=new Array(),zInterval=null,current=0,current2=0,pause=false;function setOpacity(obj)
{if(obj.xOpacity>.99)
{obj.xOpacity=.99;return;}
obj.style.opacity=obj.xOpacity;obj.style.MozOpacity=obj.xOpacity;obj.style.filter='alpha(opacity='+(obj.xOpacity*100)+')';}
function so_init()
{if(!d.getElementById||!d.createElement)return;if(themeUrl=='undefined'||themeUrl===null)var themeUrl;css=d.createElement('link');css.setAttribute('href',themeUrl+'js/slideYM.css');css.setAttribute('rel','stylesheet');css.setAttribute('type','text/css');d.getElementsByTagName('head')[0].appendChild(css);imgs=d.getElementById('rotator');if(imgs!=null){imgs=imgs.getElementsByTagName('div');if(imgs.length>0){for(i=1;i<imgs.length;i++)imgs[i].xOpacity=0;imgs[0].style.display='block';imgs[0].xOpacity=.99;setTimeout(so_xfade,5000);}}
imgs2=d.getElementById('rotator2');if(imgs2!=null){imgs2=imgs2.getElementsByTagName('div');if(imgs2.length>0){for(i=1;i<imgs2.length;i++)imgs2[i].xOpacity=0;imgs2[0].style.display='block';imgs2[0].xOpacity=.99;setTimeout(so_xfade2,5000);}}}
function so_xfade()
{cOpacity=imgs[current].xOpacity;nIndex=imgs[current+4]?current+4:0;nOpacity=imgs[nIndex].xOpacity;cOpacity-=.05;nOpacity+=.05;imgs[nIndex].style.display='block';imgs[current].xOpacity=cOpacity;imgs[nIndex].xOpacity=nOpacity;setOpacity(imgs[current]);setOpacity(imgs[nIndex]);if(cOpacity<=0)
{imgs[current].style.display='none';current=nIndex;setTimeout(so_xfade,5000);}
else
{setTimeout(so_xfade,50);}}
function so_xfade2()
{cOpacity2=imgs2[current2].xOpacity;nIndex2=imgs2[current2+4]?current2+4:0;nOpacity2=imgs2[nIndex2].xOpacity;cOpacity2-=.05;nOpacity2+=.05;imgs2[nIndex2].style.display='block';imgs2[current2].xOpacity=cOpacity2;imgs2[nIndex2].xOpacity=nOpacity2;setOpacity(imgs2[current2]);setOpacity(imgs2[nIndex2]);if(cOpacity2<=0)
{imgs2[current2].style.display='none';current2=nIndex2;setTimeout(so_xfade2,5000);}
else
{setTimeout(so_xfade2,50);}}
function getposOffset(overlay,offsettype){var totaloffset=(offsettype=="left")?overlay.offsetLeft:overlay.offsetTop;var parentEl=overlay.offsetParent;while(parentEl!=null){totaloffset=(offsettype=="left")?totaloffset+parentEl.offsetLeft:totaloffset+parentEl.offsetTop;parentEl=parentEl.offsetParent;}
return totaloffset;}
function overlay(curobj,subobjstr,opt_position,_y){if(document.getElementById){var subobj=document.getElementById(subobjstr)
subobj.style.display=(subobj.style.display!="block")?"block":"none"
var xpos=getposOffset(curobj,"left")+((typeof opt_position!="undefined"&&opt_position.indexOf("right")!=-1)?-(subobj.offsetWidth-curobj.offsetWidth):0)-_y;var ypos=getposOffset(curobj,"top")+((typeof opt_position!="undefined"&&opt_position.indexOf("bottom")!=-1)?curobj.offsetHeight:0)-30;subobj.style.left=xpos+"px"
subobj.style.top=ypos+"px"
return false}else
return true}
function overlayExt(curobj,subobjstr,opt_position,_y){if(document.getElementById){var subobj=document.getElementById(subobjstr)
subobj.style.display=(subobj.style.display!="block")?"block":"none"
var xpos=getposOffset(curobj,"left")+((typeof opt_position!="undefined"&&opt_position.indexOf("right")!=-1)?-(subobj.offsetWidth-curobj.offsetWidth):0)-_y;var ypos=getposOffset(curobj,"top")+((typeof opt_position!="undefined"&&opt_position.indexOf("bottom")!=-1)?curobj.offsetHeight:0)-80;subobj.style.left=xpos+"px"
subobj.style.top=ypos+"px"
return false}else
return true}
function overlayclose(subobj){document.getElementById(subobj).style.display="none"}
function moreExt(obj,moreExt)
{var moreExts=document.getElementById(moreExt).style;if(moreExts.display=='block'){obj.value='More >>';moreExts.display='none';}else{obj.value='<< Hide';moreExts.display='block';}}
function popWhois(path){newWindow=this.open(path,"_blank","width=480px,height=640px,align=top,scrollbars=yes,status=no,resizable=yes");newWindow.window.focus();return false;}
function selcThis(opt){t=opt.name+"3";p=document.getElementById(t);p.value=opt.value;}
function displayOption(domainExt,bool)
{tr=document.getElementById('trOption'+domainExt).style;if(bool){tr.display='block';tr.Height='10px';}else{tr.display='none';tr.Height='0px;';}}
function checkoutOrder(selcId,_url,domainName,domainExt){selc=document.getElementById(selcId);optDomain=selc.value;opt=_url+"index.php?action=order.chooseServiceBoxes&domainName="+domainName+"&domainExt="+domainExt+"&optDomain="+optDomain+"&is_new=1";document.location.href=opt;return false;}
function checkoutDomain(_url,domainName,domainExt){goAction=document.getElementById('action'+domainExt+'3').value;if(goAction=='whois')
{overlayclose('subcontent'+domainExt);popWhois(_url+'index.php?action=order.whoisFromBoxes&Ext='+domainExt);}else
if(goAction=='hosting'){go_to=_url+"index.php?action=order.chooseServiceBoxes&namaDomain="+domainName+"."+domainExt+"&optDomain=hosting&Daftar=Daftar";document.location.href=go_to;}else
if(goAction=='transfer'){optDomain=document.getElementById('optTransDomain'+domainExt+'3').value;go_to=_url+"index.php?action=order.chooseServiceBoxes&namaDomain="+domainName+"&domainExt="+domainExt+"&optTransDomain="+optDomain;document.location.href=go_to;}
return false;}
function domExtCheckBox()
{var c1=document.getElementById('checkDomMain');var c2=document.getElementById('checkDomMore');var b=document.getElementById('outputDiv');var cols=3;var spr=15;var styl='width="'+(100/cols)+'%" style="line-height: nowrap;"';var tc1='<table width="100%" cellspacing="0px" cellpadding="0px" border="0">';var tc2=tc1;var k=0;var tb=tmp=ck='';var rdom=array_chunk(domExt,cols);for(i=0;i<rdom.length;i++)
{tmp='';tmp+='<tr style="text-align: left;">';for(j=0;j<rdom[i].length;j++)
{k+=1;tmp+='<td '+styl+'>';if(rdom[i][j]!=null){ck=(in_array(rdom[i][j],defExt))?' checked="checked"':'';tmp+='<label for="'+rdom[i][j]+'"><input type="checkbox" name="'+rdom[i][j]+'" id="'+rdom[i][j]+'" value="1" onClick="checkboxChange();"'+ck+' /> '+rdom[i][j]+'</label>';}else{tmp+='&nbsp;';}
tb+='<div style="display: none;" id="output_'+rdom[i][j]+'">'+rdom[i][j]+'</div>';}
tmp+='</tr>'
if(k<=spr)tc1+=tmp;else tc2+=tmp;}
tc1+='</table>';tc2+='</table>';b.innerHTML=tb;c1.innerHTML=tc1;c2.innerHTML=tc2;}
function array_chunk(input,size){for(var x,i=0,c=-1,l=input.length,n=[];i<l;i++){(x=i%size)?n[c][x]=input[i]:n[++c]=[input[i]];}
return n;}
function in_array(needle,haystack,strict){var found=false,key,strict=!!strict;for(key in haystack){if((strict&&haystack[key]===needle)||(!strict&&haystack[key]==needle)){found=true;break;}}
return found;}
function submitWhois()
{if(checkDomain(xajax.$('domain').value))
{for(i=0;i<domExt.length;i++){if(xajax.$(domExt[i]).checked){xajax.$('output_'+domExt[i]).innerHTML="<div class=\"boxTop\"></div><div class=\"boxCell\"><h1>www."
+xajax.$('domain').value+"."+domExt[i]+"</h1>"+"<p>&nbsp;</p><img src=\""
+themeUrl+"images/whois/spinner.gif\"><br />Loading</div><div class=\"boxBot\"></div>";xajax.$('output_'+domExt[i]).style.display="block";xajax.$('output_'+domExt[i]).className="boxdomainwaiting";}else{xajax.$('output_'+domExt[i]).style.display="none";}}
xajax_processForm(xajax.getFormValues("whoisForm"));}
return false;}
function checkboxChange()
{for(i=0;i<domExt.length;i++)
{if(xajax.$(domExt[i]).checked){xajax.$('output_'+domExt[i]).style.display="block";xajax.$('output_'+domExt[i]).className="boxdomainwaiting";xajax.$('output_'+domExt[i]).innerHTML="<div class=\"boxTop\"></div><div class=\"boxCell\"><h1>."
+domExt[i]+"</h1></div><div class=\"boxBot\"></div>";}else{xajax.$('output_'+domExt[i]).style.display="none";}}}
function checkDomain(tdomain){var passed=false;if(tdomain==""){alert("silahkan masukkan nama domain untuk memulai service whois");}else if(/\s+/.test(tdomain)||/^-/.test(tdomain)||/-$/.test(tdomain)||/"."/.test(tdomain)||/[^a-zA-Z0-9-]/.test(tdomain)){alert("Nama domain yang Anda masukkan tidak sesuai dengan aturan.");}else{passed=true;}
return passed;}
function setChecked(xyz){document.getElementById(xyz).checked=true;}
function preloadImagesBoxes(){var d=document;if(d.images){if(!d.MM_p)d.MM_p=new Array();var i,j=d.MM_p.length,a=preloadImagesBoxes.arguments;for(i=0;i<a.length;i++)
if(a[i].indexOf("#")!=0){d.MM_p[j]=new Image;d.MM_p[j++].src=a[i];}}}
function tulisalmEmail(){var a=document.getElementById('AlamatEmail');a.innerHTML="Email: <a href='mailto:"+emailInfo+"'>"+emailInfo+"</a>";}