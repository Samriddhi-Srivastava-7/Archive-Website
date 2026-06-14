 const searchInput = document.getElementById("searchInput");

const tableRows = document.querySelectorAll("#certificateTable tr");

/* =========================
   SEARCH FILTER
========================= */

if (searchInput) {

  searchInput.addEventListener("keyup", function () {

    const searchValue = searchInput.value.toLowerCase();

    tableRows.forEach(function (row) {

      const rowText = row.textContent.toLowerCase();

      if (rowText.includes(searchValue)) {

        row.style.display = "";

      } else {

        row.style.display = "none";

      }

    });

  });

}

/* =========================
   PUBLISH CONFIRMATION
========================= */

document.querySelectorAll(".publish").forEach(function (btn) {

  btn.addEventListener("click", function (e) {

    const confirmPublish = confirm(
      "Are you sure you want to publish this certificate?"
    );

    if (!confirmPublish) {

      e.preventDefault();

    }

  });

});

/* =========================
   REVOKE CONFIRMATION
========================= */

document.querySelectorAll(".revoke").forEach(function (btn) {

  btn.addEventListener("click", function (e) {

    const confirmRevoke = confirm(
      "Are you sure you want to revoke this certificate?"
    );

    if (!confirmRevoke) {

      e.preventDefault();

    }

  });

});