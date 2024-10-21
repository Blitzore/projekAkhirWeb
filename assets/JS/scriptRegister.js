$(document).ready(function () {
  // Checkbox menampilkan password
  $("#showPassword").change(function () {
    if ($(this).is(":checked")) {
      $("#password").attr("type", "text");
    } else {
      $("#password").attr("type", "password");
    }
  });

  // Ajax untuk mengembalikan ke index.html dan pengiriman form
  $("#registerForm").on("submit", function (event) {
    event.preventDefault(); // Mencegah pengiriman formulir secara default

    // Ambil data formulir
    var formData = $(this).serialize();

    // Kirim data menggunakan AJAX
    $.ajax({
      type: "POST",
      url: "../src/register.php", // URL ke file PHP
      data: formData,
      success: function (response) {
        // Tampilkan respons di div
        $("#responseMessage").html(response);

        // Mengatur timer untuk kembali ke index.html setelah 2 detik jika registrasi berhasil
        if (response.includes("Registrasi berhasil!")) {
          setTimeout(function () {
            window.location.href = "../index.html"; // Ganti dengan path yang sesuai
          }, 2000);
        }
      },
      error: function () {
        $("#responseMessage").html('<div class="alert alert-danger">Ada kesalahan saat mengirim data.</div>');
      },
    });
  });
});
