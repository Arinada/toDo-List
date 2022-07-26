$(document).ready(function() {
    $(".dropdown-item").click(function () {
        $(".filter-btn").text($(this).text())
    });

    $("#search-btn").click(function () {
        let filterValue = $(".filter-btn").text();
        let searchValue = $("#search-value").val();
        if(filterValue !== "Filter") {
            const urlParams = new URLSearchParams(window.location.search)
            urlParams.set("page", 1);
            urlParams.set("filter", filterValue);
            urlParams.set("value", searchValue);
            window.location.search = urlParams;
        }
    });
    
    $(".page-item").click(function () {
        let pageNumber = $(this).text();
        const urlParams = new URLSearchParams(window.location.search)
        urlParams.set("page", pageNumber);
        window.location.search = urlParams;
    });
});