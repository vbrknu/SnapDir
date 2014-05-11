function checkUser(user)
{
    if (user.value == '')
    {
        O('info').innerHTML = '';
        return;
    }

    params  = "user=" + user.value;
    request = new ajaxRequest();
    request.open("POST", "checkuser.php", true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.setRequestHeader("Content-length", params.length);
    request.setRequestHeader("Connection", "close");
    
    request.onreadystatechange = function()
    {
        if (this.readyState == 4)
            if (this.status == 200)
                if (this.responseText != null)
                    O('info').innerHTML = this.responseText;
    }
    request.send(params)
}

function checkSelect(selection)
{
    var logCr = document.getElementsByClassName('appname')[0].innerHTML;
    var rgx = /\(([^)]+)\)/;
    var match = logCr.match(rgx);
    var user = match && match[1];

    params =  "selected=" + selection.options[selection.selectedIndex].value +
              "&user=" + encodeURIComponent(user);
    request = new ajaxRequest();
    request.open("POST", "Js/checkCategories.php", true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.setRequestHeader("Content-length", params.length);
    request.setRequestHeader("Connection", "close");

    request.onreadystatechange = function()
    {
        if (this.readyState == 4)
            if (this.status == 200)
                if (this.responseText != null)
                    O('cat').innerHTML = this.responseText;
    }
    request.send(params);
}

function ajaxRequest()
{
    try { var request = new XMLHttpRequest() }
    catch(e1) {
        try { request = new ActiveXObject("Msxml2.XMLHTTP"); }
        catch(e2) {
            try { request = new ActiveXObject("Microsoft.XMLHTTP"); }
            catch(e3) {
                request = false;
    }   }   }
    return request;
}

function O(obj)
{
    if (typeof obj == 'object') return obj;
    else return document.getElementById(obj);
}


function C(name)
{
  var elements = document.getElementsByTagName('*');
  var objects  = [];

  for (var i = 0 ; i < elements.length ; ++i)
    if (elements[i].className == name)
      objects.push(elements[i]);

  return objects;
} 

function loadFile() {
    var input, file, fr;

    if (typeof window.FileReader !== 'function') {
      alert("The file API isn't supported on this browser yet.");
      return;
    }

    input = document.getElementById('fileinput');
    if (!input) {
      alert("Um, couldn't find the fileinput element.");
    }
    else if (!input.files) {
      alert("This browser doesn't seem to support the `files` property of file inputs.");
    }
    else if (!input.files[0]) {
      alert("Please select a file before clicking 'Load'");
    }
    else {
      file = input.files[0];
      fr = new FileReader();
      fr.onload = receivedText;
      fr.readAsText(file);
    }

    function receivedText(e) {
      lines = e.target.result;
      var newArr = JSON.parse(lines);
    }
}

function getVis(user, value) {
    params = "user=" + user + "&value=" + value;
    request = new ajaxRequest();
    request.open("POST", "Js/checkVis.php", true);  
    request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    request.setRequestHeader("Connection", "close");
    request.onload = function() {
      var status = request.status;
     if (this.readyState == 4)
            if (this.status == 200)
                if (this.responseText != null) {
                    document.open();
                    document.write(this.responseText);
                    document.close();
                  }
    }
    request.send(params);
}
