$(document).ready(function () {
  // Simpan elemen .ms-auto dalam variabel
  const divMsAuto = $(".ms-auto").clone();

  $(window).resize(function () {
    var windowWidth = $(window).width();

    if (windowWidth < 768) {
      // Tampilan mobile
      if ($(".navbar-nav .mr-3").length === 0) {
        $(".ms-auto").remove();
        $(".navbar-nav").prepend(liRegister);
        $(".navbar-nav").prepend(liLogin);
      }
    } else {
      // Tampilan desktop (kembalikan tampilan)
      $(".navbar-nav .mr-3").parent().remove();
      $(".navbar-collapse").append(divMsAuto);
    }
  });

  // Membuat nav active
  $(".nav-link").click(function () {
    $(".nav-link").removeClass("active"); // Hapus class active dari semua link
    $(this).addClass("active"); // Tambahkan class active ke link yang diklik
  });

  // Ajax untuk mengirim contact form
  $("#contactForm").on("submit", function (event) {
    event.preventDefault(); // Mencegah pengiriman formulir secara default

    // Ambil data formulir
    var formData = $(this).serialize();

    // Kirim data menggunakan AJAX
    $.ajax({
      type: "POST",
      url: "src/sendEmail.php", // Sesuaikan ini ke path yang benar
      data: formData,
      success: function (response) {
        $("#responseMessage").html('<div class="alert alert-success">' + response + "</div>");
        $("#contactForm")[0].reset(); // Reset formulir setelah berhasil
      },
      error: function () {
        $("#responseMessage").html('<div class="alert alert-danger">Ada kesalahan saat mengirim email.</div>');
      },
    });
  });
});

const liLogin = `
    <li class="nav-item">
      <div class="mr-3 d-flex align-items-center"> 
        <i class="bi bi-person-fill fs-3 me-2"></i>
        <a class="nav-link" href="#">Login</a>
      </div>
    </li>
  `;
const liRegister = `
    <li class="nav-item">
      <div class="mr-3 d-flex align-items-center"> 
        <i class="bi bi-clipboard-fill fs-3 me-2"></i>
        <a class="nav-link" href="#">Register</a>
      </div>
    </li>
  `;
