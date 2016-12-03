$(document).ready(function() {
    $("#burger").click(function() {
        $("#burgerContent").toggle();
    });

    $("#admHomeSidangButton").click(function() {
        if(!$("#admHomeSidangButton").hasClass("active")) {
            $("#admHomeSidangButton").addClass("active");
            $("#admHomeMhsButton").removeClass("active");
            $("#admHomeSidangContent").show();
            $("#admHomeMhsContent").hide();
        }
    });

    $("#admHomeMhsButton").click(function() {
        if(!$("#admHomeMhsButton").hasClass("active")) {
            $("#admHomeMhsButton").addClass("active");
            $("#admHomeSidangButton").removeClass("active");
            $("#admHomeSidangContent").hide();
            $("#admHomeMhsContent").show();
        }
    });

    $("#modalOpen").click(function() {
        $("#modal").show();
    });

    $("#modalClose").click(function() {
        $("#modal").hide();
    });

    $("#logout").click(function () {
       $("#logout-button").click();
    });

    $('.display').DataTable({
        "paging" : true,
        "ordering": false,
        "info" : false
    });

    $('#jenis_sidang').click(function () {
        $('#sort_js').click();
    });
    $('#mahasiswa').click(function () {
        $('#sort_mhs').click();
    });
    $('#waktu').click(function () {
        $('#sort_waktu').click();
    });

});
