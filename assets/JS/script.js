$(document).ready(function () {
  // Simpan elemen .ms-auto dalam variabel
  const divMsAuto = $(".ms-auto").clone();

  // Fungsi untuk memeriksa lebar jendela dan memindahkan tombol jika diperlukan
  function checkWindowSize() {
    let windowWidth = $(window).width();

    if (windowWidth < 768) {
      // Tampilan mobile
      if ($(".navbar-nav .mr-3").length === 0) {
        $(".ms-auto").remove();
        $(".navbar-nav").prepend(liRegister);
        $(".navbar-nav").prepend(liLogin);
      }
    } else {
      // Tampilan desktop (kembalikan tampilan)
      if ($(".navbar-nav .mr-3").length > 0) {
        $(".navbar-nav .mr-3").parent().remove(); // Hapus login & register yang ada
        $(".navbar-collapse").append(divMsAuto); // Tambahkan elemen .ms-auto kembali
      }
    }
  }

  // Panggil fungsi ini ketika halaman pertama kali dibuka
  checkWindowSize();

  // Panggil fungsi ini setiap kali ukuran jendela berubah
  $(window).resize(function () {
    checkWindowSize();
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
        <a class="nav-link d-block w-100" href="templates/login.html">Login</a>
      </div>
    </li>
  `;
const liRegister = `
    <li class="nav-item">
      <div class="mr-3 d-flex align-items-center"> 
        <i class="bi bi-clipboard-fill fs-3 me-2"></i>
        <a class="nav-link d-block w-100" href="templates/register.html">Register</a>
      </div>
    </li>
  `;
