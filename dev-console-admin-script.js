var fontSize = 12;
var filePath = document.getElementsByClassName("filename");
var filename = document.getElementsByClassName("filename")[0].innerHTML.split("/").pop();

var mainEditor = ace.edit("mainEditor");
mainEditor.setTheme("ace/theme/monokai");
mainEditor.setOptions({
    maxLines: Infinity
});
mainEditor.getSession().setMode("ace/mode/css");
mainEditor.setFontSize(fontSize);

//CTRL/CMD + S for save
document.addEventListener("keydown", function(e) {
  if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
    e.preventDefault();
    saveData();
  }
}, false);

function textSmaller() {
	fontSize -=1;
	mainEditor.setFontSize(fontSize);
	return false;
}
function textLarger() {
	fontSize +=1;
	mainEditor.setFontSize(fontSize);
	return false;
}

function newFile() {
	var newFilename = prompt("Please enter a name for your new file!\nDon't forget filename extension. e.g. .html, .php or .css");
	mainEditor.setValue("");
	changeMode(newFilename);
	filePath[0].innerHTML = "plugin/"+newFilename;
	filePath[1].innerHTML = "plugin/"+newFilename;
	
	
	alert("Not implemented!");
	
	return false;
}

function renameFile() {
	prompt("Please enter a new name for \""+filename+"\"", filename);
	
	
	alert("Not implemented!");
	
	return false;
}

function deleteFile() {
	var filename = document.getElementsByClassName("filename")[0].innerHTML.split("/").pop();
	if(confirm("Are you sure you want to delete \""+filename+"\"")) {
		//DELETE IT!
	} else {
		//DONT DELETE IT!
	}
	alert("Not implemented!");
}

function fullscreen(elementID) {
	var elem = document.getElementById(elementID);
	if (elem.requestFullscreen) {
	  elem.requestFullscreen();
	} else if (elem.msRequestFullscreen) {
	  elem.msRequestFullscreen();
	} else if (elem.mozRequestFullScreen) {
	  elem.mozRequestFullScreen();
	} else if (elem.webkitRequestFullscreen) {
	  elem.webkitRequestFullscreen();
	}
	return false;
}

function saveData() {
	var xmlhttp;
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			var now = new Date();
			console.log("[" + now.getHours() + ":" + now.getMinutes() + "] JK development console is saved.");
			var saveNotice = document.getElementById("save-notice");
			var saveNoticeClasses = saveNotice.className;
			saveNotice.className = "anim opacityFull";
			setTimeout(function() { saveNotice.className = "anim opacityZero";}, 1500);
			
			document.getElementById("serverResponse").innerHTML=xmlhttp.responseText;
			
		}
	}
	  
	xmlhttp.open("POST",document.URL + '&noheader=true',true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	//var dataToSend = "cssData="+cssEditor.getValue()+"&jsData="+jsEditor.getValue()+"";
	var dataToSend = "path="+filePath[0].innerHTML+"&data="+mainEditor.getValue()+"";
	xmlhttp.send(dataToSend);
	//console.log(dataToSend);
}

function readDirectory(directory) {
	var xmlhttp;
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			
			document.getElementById("directory").innerHTML=xmlhttp.responseText;
			
		}
	  }
	xmlhttp.open("POST",document.URL + '&noheader=true',true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	var dataToSend = "directory="+directory;
	xmlhttp.send(dataToSend);
	//console.log("sending directory = " +dataToSend);
}

function readFile(filename) {
	var xmlhttp;
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			
			//document.getElementById("mainEditor").innerHTML=xmlhttp.responseText;
			var response = xmlhttp.responseText.split("###", 2);
			filePath[0].innerHTML=response[0];
			filePath[1].innerHTML=response[0];
			mainEditor.setValue(response[1]);
			mainEditor.navigateFileStart();
			mainEditor.focus();
			changeMode(filename);
			
		}
	  }
	xmlhttp.open("POST",document.URL + '&noheader=true',true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	var dataToSend = "readFile="+filename;
	xmlhttp.send(dataToSend);
	//console.log(dataToSend);
}

function changeMode(filename) {
	var fileEnding = filename.split(".").pop();
    switch(fileEnding.toLowerCase()) {
    case 'txt':
        mainEditor.getSession().setMode("ace/mode/plain_text");
        break;
	case 'html': case 'htm': case 'xhtml':
        mainEditor.getSession().setMode("ace/mode/html");
        break;
    case 'svg':
        mainEditor.getSession().setMode("ace/mode/svg");
        break;
	case 'xml':
        mainEditor.getSession().setMode("ace/mode/xml");
        break;
	case 'php': case 'php3':
        mainEditor.getSession().setMode("ace/mode/php");
        break;
	case 'js':
        mainEditor.getSession().setMode("ace/mode/javascript");
        break;
	case 'css':
        mainEditor.getSession().setMode("ace/mode/css");
        break;
    default:
        mainEditor.getSession().setMode("ace/mode/plain_text");
    }
}

