$(document).ready(function () {
  // Cek apakah nama kategori sudah ada di tabel
  function isCategoryNameDuplicate(name) {
    let isDuplicate = false;
    $("#categoryTable tr").each(function () {
      if ($(this).find("td:first").text().trim().toLowerCase() === name.trim().toLowerCase()) {
        isDuplicate = true;
        return false; // break the loop
      }
    });
    return isDuplicate;
  }

  // Add Category
  $("#addCategory").click(function (e) {
    e.preventDefault();
    const categoryName = $("#kategori").val().trim();
    if (!categoryName) return; // Cegah penambahan kategori kosong

    if (isCategoryNameDuplicate(categoryName)) {
      alert("Nama kategori sudah ada!");
      return;
    }

    $.post("../src/kategori_action.php", { action: "add", name: categoryName }, function (data) {
      if (data === "duplicate") {
        alert("Nama kategori sudah ada!");
      } else {
        $("#categoryTable").append(data);
        $("#kategori").val("");
      }
    });
  });

  // Edit Category
  $(document).on("click", ".editCategory", function (e) {
    e.preventDefault();
    const row = $(this).closest("tr");
    const categoryId = row.data("id");
    const currentName = row.find("td:first").text().trim();
    const newName = prompt("Edit kategori:", currentName).trim();
    if (!newName || newName === currentName) return; // Cegah edit kosong atau nama sama

    if (isCategoryNameDuplicate(newName)) {
      alert("Nama kategori sudah ada!");
      return;
    }

    $.post("../src/kategori_action.php", { action: "edit", id: categoryId, name: newName }, function (response) {
      if (response === "duplicate") {
        alert("Nama kategori sudah ada!");
      } else {
        row.find("td:first").text(newName);
      }
    });
  });

  // Delete Category
  $(document).on("click", ".deleteCategory", function (e) {
    e.preventDefault();
    const row = $(this).closest("tr");
    const categoryId = row.data("id");
    if (confirm("Apakah Anda yakin ingin menghapus kategori ini?")) {
      $.post("../src/kategori_action.php", { action: "delete", id: categoryId }, function () {
        row.remove();
      });
    }
  });
});
