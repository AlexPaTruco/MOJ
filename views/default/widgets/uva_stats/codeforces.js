function CFuserInfo(uname) {
    $.getJSON("http://codeforces.com/api/user.info?handles=" + uname, function(data) {
        $("#CFresult").html(JSON.stringify(data));
    });
}

