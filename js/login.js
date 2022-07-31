$(document).ready(function() {
    $("#log-in").click(function () {
        window.location.replace('/log-in');
    });

    $("#log-out").click(function () {
        window.location.replace("/log-out");
    });

    $("#confirm-log-in").click(function () {
        window.location.replace('/');
    });
});