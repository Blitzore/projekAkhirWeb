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
