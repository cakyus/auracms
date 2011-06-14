function showdesc(id){
	document.getElementById('desc_'+id).style.display = '';
}
function hidedesc(id){
	document.getElementById('desc_'+id).style.display = 'none';
}
function switc(id, id2){
	if(document.getElementById(id).style.display == 'none') {
		document.getElementById(id).style.display = '';
		document.getElementById(id2).style.display = 'none';
	}else if(document.getElementById(id).style.display == '') {
		document.getElementById(id).style.display = '';
		document.getElementById(id2).style.display = 'none';
	}
	else if(document.getElementById(id2).style.display == 'none') {
		document.getElementById(id2).style.display = '';
		document.getElementById(id).style.display = 'none';}
}