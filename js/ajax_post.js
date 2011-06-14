function getFormValues (element){
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
}

function openurlf(query,Rmethod,formName,idelement,actionfile){
	if (Rmethod == '') Rmethod = 'POST';
	
	var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	request.open(Rmethod, actionfile, true);
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	request.onreadystatechange = function(){
		if (request.readyState == 4){
			//var iwan = eval("(" + request.responseText + ")");
			//if (iwan.imageFound) {
			//	var imageName = iwan.imageName;
			//	var w = iwan.w;
			//	var h = iwan.h;
				document.getElementById(idelement).innerHTML = request.responseText.replace(/^\s*|\s*$/g,"") ;
				
				 return true ;
				
			//} else {
			//	document.getElementById("ImageEditorImage").innerHTML = '<span style="font-size:12px;color:red;">Image was not found.</span>';
			//}
		}
	};
   if (formName != '')	query = getFormValues (formName);
  
	request.send(query);
	
}
