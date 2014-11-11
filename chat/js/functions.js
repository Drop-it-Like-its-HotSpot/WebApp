/**
 * Created by jlkegley on 11/10/2014.
 */

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
    }
    return "";
}


function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function delCookie() {
    var d = new Date();
    d.setTime(d.getTime() + (1));
    var expires = "expires="+d.toUTCString();
    document.cookie = 'ID' + "=" + "NULL" + "; " + expires;
    document.cookie = 'session_id' + "=" + "NULL" + "; " + expires;
    window.location.href = "profile.php";
}


function userLogin(uemail, upassword){
    var email_id = uemail;
    var password = upassword;
    $.ajax({
        url: 'http://54.172.35.180:8080/api/login',
        type: "POST",
        data:{email_id:email_id, password:password},
        dataType: "JSON",
        success:function(html) {
            if (html['success'] == true) {
                alert(JSON.stringify(html));
                setCookie('ID', html['user_id'], 1);
                setCookie('session_id', html['session_id'], 1);
                window.location.href = "profile.php";
            }else{
                alert(JSON.stringify(html));
                alert('Could not log you in, check your username/password');
            }
        }
    });
}