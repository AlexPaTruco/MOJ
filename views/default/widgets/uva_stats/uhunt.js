function specificProblemById(pid) {
    $.post("http://uhunt.felix-halim.net/api/p/id/" + pid, function(data) {
        $("#result").html(JSON.stringify(data));
    });
}

function specificProblemByNumber(pnum) {
    $.post("http://uhunt.felix-halim.net/api/p/num/" + pnum, function(data) {
        $("#result").html(JSON.stringify(data));
    });
}

function unameToUid(uname) {
    $.post("http://uhunt.felix-halim.net/api/uname2uid/" + uname, function(data) {
        return JSON.stringify(data);
    });
}

function userLastSubs(id) {
    $.post("http://uhunt.felix-halim.net/api/subs-user-last/" + id + "/10", function(data) {
        $("#result").html(JSON.stringify(data));
    });
}

function userRank(id) {
    $.post("http://uhunt.felix-halim.net/api/ranklist/" + id + "/0/0", function(data) {
        $("#result").html(JSON.stringify(data));
    });
}