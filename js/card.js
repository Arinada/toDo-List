$(document).ready(function() {
    $("#add-card-btn").click(function () {
        $('.modal').modal('show');
    });

    $(".close").click(function (){
        $('.modal').modal('hide');
    });
});