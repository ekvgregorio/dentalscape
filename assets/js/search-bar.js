$(document).ready(function () {
    $("#navbar-search-input").on("keyup", function () {
        let value = $(this).val().toLowerCase();

        $("tbody tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});

