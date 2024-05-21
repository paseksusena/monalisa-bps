$(document).ready(function () {
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $(".flex .hs-dropdown-menu a").click(function (e) {
        e.preventDefault();
        var year = $(this).data("year");
        $.ajax({
            type: "POST",
            url: "/set-year-session", // ganti dengan URL yang sesuai di sisi server
            data: {
                year: year,
                _token: csrfToken,
            },
            success: function (response) {
                // refresh halaman setelah berhasil
                location.reload();
            },
            error: function (xhr, status, error) {
                // tangani kesalahan jika terjadi
                console.error("Terjadi kesalahan:", error);
            },
        });
    });
});
