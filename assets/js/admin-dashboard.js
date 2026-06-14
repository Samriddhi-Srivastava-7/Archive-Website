const toggleBtn = document.getElementById("toggleSidebar");

const sidebar = document.getElementById("sidebar");

const main = document.getElementById("main");

if (toggleBtn) {

  toggleBtn.addEventListener("click", function () {

    sidebar.classList.toggle("collapsed");

    main.classList.toggle("expanded");

  });

}

/* =========================
   DASHBOARD SEARCH
========================= */

const dashboardSearch = document.getElementById("dashboardSearch");

const dashboardRows = document.querySelectorAll("#dashboardTable tr");

if (dashboardSearch) {

  dashboardSearch.addEventListener("keyup", function () {

    const value = dashboardSearch.value.toLowerCase();

    dashboardRows.forEach(function (row) {

      const text = row.textContent.toLowerCase();

      if (text.includes(value)) {

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