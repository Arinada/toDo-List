$(document).ready(function() {
    $('.dropdown-item').click(function () {
        $(".filter-btn").text($(this).text())
    });

    $('#search-btn').click(function () {
        let filterValue = $('.filter-btn').text();
        let searchValue = $('#search-value').val();
        if(filterValue !== 'Filter') {
            const urlParams = new URLSearchParams(window.location.search)
            urlParams.set('filter', filterValue);
            urlParams.set('value', searchValue);
            window.location.search = urlParams;
        }
    });
});