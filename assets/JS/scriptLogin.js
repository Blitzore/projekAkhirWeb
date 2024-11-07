$(document).ready(function () {
  $("#loginForm").on("submit", function (e) {
    e.preventDefault(); // Mencegah form mereload halaman

    var username = $("#username").val();
    var password = $("#password").val();

    // Clear any existing alert message
    $(".alert").remove();

    // AJAX request
    $.ajax({
      url: "../src/login.php", // Perbarui ke path yang benar
      type: "POST",
      data: {
        username: username,
        password: password,
      },
      success: function (response) {
        var data = JSON.parse(response);
        console.log(data); // Tambahkan ini untuk debugging
        if (data.status === "success") {
          // Mengarahkan ke main.php
          window.location.href = "main.php";
        } else {
          // Menampilkan pesan error
          $("#loginForm").prepend('<div class="alert alert-danger">' + data.message + "</div>");
        }
      },
      error: function () {
        $("#loginForm").prepend('<div class="alert alert-danger">Terjadi kesalahan pada server</div>');
      },
    });
  });

  // Tampilkan atau sembunyikan password
  $("#showPassword").on("change", function () {
    let passwordField = $("#password");
    if ($(this).is(":checked")) {
      passwordField.attr("type", "text");
    } else {
      passwordField.attr("type", "password");
    }
  });
});
