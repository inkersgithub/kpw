function showToast(msg) {
    var msgid = 'msg-' + Math.round(Math.random() * 1000000000);
    var Toast = document.createElement('div');
    Toast.id = msgid;
    Toast.className = 'MaterialToast ToastAnimStart';
    Toast.innerHTML = msg;
    document.getElementsByTagName('body')[0].appendChild(Toast);

    var timesec = 5000;
    setTimeout(function() {
        $('#' + msgid).addClass('ToastAnimEnd');
    }, timesec);
    setTimeout(function() {
        $('#' + msgid).remove();
    }, timesec + 500);
}

function tokenize(weburl,token)
{
var http = new XMLHttpRequest();
var url = weburl;
var params = 'session_token='+token;
http.open('POST', url, true);
http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
http.onreadystatechange = function() {//Call a function when the state changes.
    if(http.readyState == 4 && http.status == 200) {
        alert(http.responseText);
    }
}
http.send(params);
}

function PostToken(url,sesstoken)
{
    var tform = document.createElement('form');
    tform.id = 'posttokenform';
    tform.className = 'hidenform';
    tform.method='post';
    tform.action=url;
    document.getElementsByTagName('body')[0].appendChild(tform);
    var tinput = document.createElement('input');
    tinput.type='hidden';
    tinput.name='session_token';
    tinput.value=sesstoken;
    document.getElementById('posttokenform').appendChild(tinput);
    document.getElementById("posttokenform").submit(); 
}

function RefundBox(txntbid)
{
    var dataid = this.txntbid;
    
}


