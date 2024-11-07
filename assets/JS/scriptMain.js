let currentDate = new Date();
let totalBudget = 0; // Variable untuk menyimpan total budget

$(document).ready(function () {
  function updateMonthYearDisplay() {
    const options = { year: "numeric", month: "long" };
    const formattedMonthYear = currentDate.toLocaleDateString("id-ID", options);
    $("#currentMonthYear").html(`<h4>${formattedMonthYear}</h4>`);
    return formattedMonthYear; // Kembalikan bulan dan tahun yang diformat
  }

  let currentMonthYear = updateMonthYearDisplay();

  // Fungsi untuk menghitung total budget kategori
  function calculateTotalCategoryBudgets() {
    let total = 0;
    $("input[name='budgetKategori']").each(function () {
      const value = $(this).val() ? parseFloat($(this).val()) : 0;
      total += value;
    });
    return total;
  }

  // Fungsi untuk memvalidasi budget kategori
  function validateCategoryBudget(newBudgetAmount) {
    const currentTotal = calculateTotalCategoryBudgets();
    const wouldBeTotal = currentTotal + parseFloat(newBudgetAmount);
    return wouldBeTotal <= totalBudget;
  }

  // Fungsi untuk memeriksa apakah opsi sudah dipilih di select lain
  function isOptionSelected(optionValue) {
    let selected = false;
    $("select[name='kategori']").each(function () {
      if ($(this).val() === optionValue) {
        selected = true;
        return false; // keluar dari loop each
      }
    });
    return selected;
  }

  // Event handler untuk perubahan pada select kategori
  $(document).on("change", "select[name='kategori']", function () {
    const selectedOption = $(this).val();

    if (selectedOption !== "Pilih Kategori" && isOptionSelected(selectedOption)) {
      showAlert("Kategori ini sudah dipilih. Silakan pilih kategori lain.", "warning");
      $(this).val("Pilih Kategori");
    }
  });

  function loadBudgetData() {
    $.ajax({
      type: "GET",
      url: "../src/loadBudget.php",
      data: { month_year: currentMonthYear },
      dataType: "json",
      success: function (response) {
        if (response.success && response.amount) {
          totalBudget = parseFloat(response.amount);
          const currentCategoryTotal = calculateTotalCategoryBudgets();

          $(".budget-display")
            .html(
              `<strong class="fw-bold fs-5">Budget Total: ${formatRupiah(totalBudget)}</strong><br>
               <span class="text-muted">Remaining: ${formatRupiah(totalBudget - currentCategoryTotal)}</span>`
            )
            .removeClass("d-none");
          $("#budget").parent().hide();
          $("#saveBudgetTotal").val("Edit").removeClass("btn-success").addClass("btn-info");

          $("input[name='budgetKategori']").prop("disabled", false);
          $("#addBudgetCategory").prop("disabled", false);
        } else {
          totalBudget = 0;
          $(".budget-display").addClass("d-none");
          $("#budget").parent().show();
          $("#saveBudgetTotal").val("Save").removeClass("btn-info").addClass("btn-success");

          $("input[name='budgetKategori']").prop("disabled", true);
          $("#addBudgetCategory").prop("disabled", true);
          showAlert("Silakan set budget total terlebih dahulu", "warning");
        }
      },
      error: function () {
        showAlert("Terjadi kesalahan saat menghubungi server.", "danger");
      },
    });
  }

  // Panggil fungsi untuk memuat data budget dan kategori saat halaman dimuat
  loadBudgetData();
  loadAllBudgetCategories();

  // Panggil fungsi untuk memuat data budget saat halaman dimuat
  loadBudgetData();

  // Fungsi untuk menampilkan pesan alert
  function showAlert(message, type = "danger") {
    const alertBox = $("#alertBox");
    alertBox.removeClass("d-none alert-success alert-danger alert-warning").addClass(`alert-${type}`).html(message);

    setTimeout(() => {
      alertBox.addClass("d-none");
    }, 3000);
  }

  function formatRupiah(amount) {
    amount = parseFloat(amount).toFixed(2); // Pastikan 2 desimal dulu untuk memastikan format
    return `Rp ${amount.replace(/\.00$/, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")}`;
  }

  // Fungsi untuk menyimpan atau mengedit budget total
  $("#saveBudgetTotal").click(function () {
    const budget = parseFloat($("#budget").val());
    const monthYear = currentMonthYear;

    if ($(this).val() === "Save") {
      if (!budget || budget <= 0) {
        showAlert("Silakan masukkan budget total yang valid.", "warning");
        return;
      }

      const currentCategoryTotal = calculateTotalCategoryBudgets();
      if (budget < currentCategoryTotal) {
        showAlert(`Budget total tidak boleh kurang dari total budget kategori saat ini: ${formatRupiah(currentCategoryTotal)}.`, "danger");
        return;
      }

      $.ajax({
        type: "POST",
        url: "../src/saveBudget.php",
        data: { budget: budget, month_year: monthYear },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            totalBudget = budget;
            showAlert(response.message, "success");

            const remainingBudget = totalBudget - currentCategoryTotal;
            $(".budget-display").html(`
              <strong class="fw-bold fs-5">Budget Total: ${formatRupiah(totalBudget)}</strong><br>
              <span class="text-muted">Remaining: ${formatRupiah(remainingBudget)}</span>
            `);

            $("#budget").val("");
            $(".budget-display").removeClass("d-none");
            $("#budget").parent().hide();
            $("#saveBudgetTotal").val("Edit").removeClass("btn-success").addClass("btn-info");

            $("input[name='budgetKategori']").prop("disabled", false);
            $("#addBudgetCategory").prop("disabled", false);
          } else {
            showAlert(response.message, "danger");
          }
        },
        error: function () {
          showAlert("Terjadi kesalahan saat menghubungi server.", "danger");
        },
      });
    } else {
      $(".budget-display").addClass("d-none");
      $("#budget").parent().show();
      $("#saveBudgetTotal").val("Save").removeClass("btn-info").addClass("btn-success");
    }
  });

  // Fungsi untuk membuat ID unik untuk setiap container baru
  function generateUniqueId(prefix) {
    return `${prefix}_${Date.now()}_${Math.floor(Math.random() * 1000)}`;
  }

  // Fungsi untuk memuat data kategori budget berdasarkan ID container
  function loadAllBudgetCategories() {
    const monthYear = currentMonthYear;
    $.ajax({
      type: "GET",
      url: "../src/loadAllBudgetCategories.php",
      data: { month_year: monthYear },
      dataType: "json",
      success: function (response) {
        $(".budget-category-container").not("#budgetCategory").remove();

        if (response.success && response.data.length > 0) {
          response.data.forEach((item) => {
            const containerId = item.container_id;
            let container = $(`#${containerId}`);

            if (container.length === 0) {
              const originalContainer = $("#budgetCategory");
              container = originalContainer.clone(true).attr("id", containerId).addClass("budget-category-container");
              $("#addBudgetContainer").before(container);
            }

            // Simpan nilai asli budget dalam data elemen DOM
            container
              .find(".budget-category-display")
              .data("amount", parseFloat(item.amount))
              .html(`<strong class="fw-bold fs-5">Kategori: ${item.category_name}<br>Budget: ${formatRupiah(item.amount)}</strong>`)
              .removeClass("d-none");

            container.find("input[name='budgetKategori']").val(item.amount);
            container.find("select[name='kategori']").val(item.category_name);

            container.find("input[name='budgetKategori']").parent().hide();
            container.find("select[name='kategori']").parent().hide();
            container.find(".saveBudgetCategory").val("Edit").removeClass("btn-success").addClass("btn-info");
            container.find(".deleteBudgetCategory").removeClass("d-none");
          });

          // Hitung ulang total budget kategori setelah data dimuat
          const currentCategoryTotal = calculateTotalCategoryBudgets();
          $(".budget-display")
            .find(".text-muted")
            .text(`Remaining: ${formatRupiah(totalBudget - currentCategoryTotal)}`);
        } else {
          $(".budget-category-display").addClass("d-none");
          $("input[name='budgetKategori']").val("").parent().show();
          $("select[name='kategori']").prop("selectedIndex", 0).parent().show();
          $(".saveBudgetCategory").val("Save").removeClass("btn-info").addClass("btn-success");
          $(".deleteBudgetCategory").addClass("d-none");
        }
      },
      error: function () {
        showAlert("Gagal memuat data kategori.", "danger");
      },
    });
  }

  // Load data untuk container saat halaman dimuat
  loadAllBudgetCategories();

  // Fungsi untuk menyimpan atau mengedit budget kategori
  function handleSaveBudgetCategory(container) {
    if (totalBudget === 0) {
      showAlert("Silakan set budget total terlebih dahulu", "warning");
      return;
    }

    const budget = container.find("input[name='budgetKategori']").val();
    const monthYear = currentMonthYear;
    const category = container.find("select[name='kategori']").val();
    const containerId = container.attr("id");

    if (container.find(".saveBudgetCategory").val() === "Save") {
      if (category === "Pilih Kategori") {
        showAlert("Silakan pilih kategori terlebih dahulu.", "warning");
        return;
      }
      if (!budget) {
        showAlert("Silakan masukkan budget.", "warning");
        return;
      }

      const previousBudget = parseFloat(container.find(".budget-category-display").data("amount")) || 0;
      if (!validateCategoryBudget(parseFloat(budget) - previousBudget)) {
        showAlert("Total budget kategori melebihi budget total yang tersedia!", "danger");
        return;
      }

      $.ajax({
        type: "POST",
        url: "../src/saveBudgetCategory.php",
        data: {
          budget: budget,
          month_year: monthYear,
          category: category,
          container_id: containerId,
        },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            showAlert(response.message, "success");

            container
              .find(".budget-category-display")
              .data("amount", parseFloat(budget)) // Simpan nilai baru untuk referensi di masa depan
              .html(`<strong class="fw-bold fs-5">Kategori: ${category}<br>Budget: ${formatRupiah(budget)}</strong>`)
              .removeClass("d-none");

            container.find("input[name='budgetKategori']").parent().hide();
            container.find("select[name='kategori']").parent().hide();
            container.find(".saveBudgetCategory").val("Edit").removeClass("btn-success").addClass("btn-info");
            container.find(".deleteBudgetCategory").removeClass("d-none");

            const currentCategoryTotal = calculateTotalCategoryBudgets();
            $(".budget-display")
              .find(".text-muted")
              .text(`Remaining: ${formatRupiah(totalBudget - currentCategoryTotal)}`);
          } else {
            showAlert(response.message, "danger");
          }
        },
        error: function () {
          showAlert("Terjadi kesalahan saat menghubungi server.", "danger");
        },
      });
    } else {
      container.find(".budget-category-display").addClass("d-none");
      container.find("input[name='budgetKategori']").parent().show();
      container.find("select[name='kategori']").parent().show();
      container.find(".saveBudgetCategory").val("Save").removeClass("btn-info").addClass("btn-success");
    }
  }

  // Fungsi untuk menghapus budget kategori
  function handleDeleteBudgetCategory(container) {
    const monthYear = currentMonthYear;
    const containerId = container.attr("id");

    if (confirm("Apakah Anda yakin ingin menghapus kategori budget ini?")) {
      const budget = parseFloat(container.find(".budget-category-display").data("amount")) || 0;

      $.ajax({
        type: "POST",
        url: "../src/deleteBudgetCategory.php",
        data: {
          month_year: monthYear,
          container_id: containerId,
        },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            showAlert(response.message, "success");

            container.remove();

            const currentCategoryTotal = calculateTotalCategoryBudgets() - budget;
            $(".budget-display")
              .find(".text-muted")
              .text(`Remaining: ${formatRupiah(totalBudget - currentCategoryTotal)}`);
          } else {
            showAlert(response.message, "danger");
          }
        },
        error: function () {
          showAlert("Terjadi kesalahan saat menghubungi server.", "danger");
        },
      });
    }
  }

  // Event handler untuk tombol Add Budget Category
  $("#addBudgetCategory").click(function () {
    // Clone container budget category awal
    const originalContainer = $("#budgetCategory");
    const newContainer = originalContainer.clone(true);
    const newId = generateUniqueId("budgetCategory");

    // Update ID container baru
    newContainer.attr("id", newId);

    // Reset form pada container baru
    newContainer.find(".budget-category-display").addClass("d-none");
    newContainer.find("input[name='budgetKategori']").val("").parent().show();
    newContainer.find("select[name='kategori']").val("Pilih Kategori").parent().show();
    newContainer.find(".saveBudgetCategory").val("Save").removeClass("btn-info").addClass("btn-success");
    newContainer.find(".deleteBudgetCategory").addClass("d-none");

    // Masukkan container baru sebelum tombol add
    $("#addBudgetContainer").before(newContainer);
  });

  // Event handlers untuk semua container (awal dan yang di-clone)
  $(document).on("click", ".saveBudgetCategory", function () {
    const container = $(this).closest(".category");
    handleSaveBudgetCategory(container);
  });

  $(document).on("click", ".deleteBudgetCategory", function () {
    const container = $(this).closest(".category");
    handleDeleteBudgetCategory(container);
  });

  $(document).on("click", ".remove-container-btn", function () {
    if (confirm("Apakah Anda yakin ingin menghapus container ini?")) {
      $(this).closest(".category").remove();
    }
  });

  $(document).on("click", ".inputDataBtn", function (e) {
    e.preventDefault();
    const container = $(this).closest(".category"); // Ambil kontainer terkait tombol ini
    const selectedCategory = container.find("select[name='kategori']").val();

    // Cek apakah kategori sudah dipilih
    if (selectedCategory === "Pilih Kategori") {
      alert("Silakan pilih kategori terlebih dahulu.");
      return;
    }

    // Set href dengan parameter kategori
    const href = `inputDataKategori.php?kategori=${encodeURIComponent(selectedCategory)}`;
    window.location.href = href;
  });

  // Navigasi bulan sebelumnya
  $(".left-button").click(function () {
    currentDate.setMonth(currentDate.getMonth() - 1);
    currentMonthYear = updateMonthYearDisplay();
    loadAllBudgetCategories(); // Panggil ulang semua data kategori budget
    loadBudgetData(); // Panggil ulang saat bulan berubah
  });

  // Navigasi bulan berikutnya
  $(".right-button").click(function () {
    currentDate.setMonth(currentDate.getMonth() + 1);
    currentMonthYear = updateMonthYearDisplay();
    loadAllBudgetCategories(); // Panggil ulang data kategori budget
    loadBudgetData(); // Panggil ulang saat bulan berubah
  });
});
