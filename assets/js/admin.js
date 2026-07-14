/*!
==========================================================
AVOLICIUS
ADMIN DASHBOARD
VERSION 2.3
==========================================================
*/

"use strict";

document.addEventListener("DOMContentLoaded", () => {
  initSidebar();

  initRevenueChart();

  initCategoryChart();

  initTooltips();

  initCounter();

  initCardHover();

  initAlert();
});

/* ==========================================================
SIDEBAR
========================================================== */

function initSidebar() {
  const sidebar = document.getElementById("sidebar");

  const toggle = document.getElementById("sidebarToggle");

  if (!sidebar || !toggle) return;

  const state = localStorage.getItem("sidebar");

  if (state === "collapsed") {
    sidebar.classList.add("collapsed");
  }

  toggle.addEventListener("click", () => {
    sidebar.classList.toggle("collapsed");

    localStorage.setItem(
      "sidebar",

      sidebar.classList.contains("collapsed") ? "collapsed" : "expanded",
    );
  });
}

/* ==========================================================
MONTHLY REVENUE
========================================================== */

function initRevenueChart() {
  const dashboard = window.dashboardData || {};

  const canvas = document.getElementById("revenueChart");

  if (!canvas) return;

  if (typeof Chart === "undefined") return;

  if (!dashboard.revenue) return;

  new Chart(canvas, {
    type: "bar",

    data: {
      labels: [
        "Jan",

        "Feb",

        "Mar",

        "Apr",

        "Mei",

        "Jun",

        "Jul",

        "Agu",

        "Sep",

        "Okt",

        "Nov",

        "Des",
      ],

      datasets: [
        {
          label: "Pendapatan",

          data: dashboard.revenue,

          backgroundColor: "#2E7D32",

          borderRadius: 10,

          borderWidth: 0,
        },
      ],
    },

    options: {
      responsive: true,

      maintainAspectRatio: false,

      plugins: {
        legend: {
          display: false,
        },
      },

      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });
}

/* ==========================================================
CATEGORY CHART
========================================================== */

function initCategoryChart() {
  const dashboard = window.dashboardData || {};

  const canvas = document.getElementById("categoryChart");

  if (!canvas) return;

  if (typeof Chart === "undefined") return;

  if (!dashboard.categoryLabels) return;

  new Chart(canvas, {
    type: "doughnut",

    data: {
      labels: dashboard.categoryLabels,

      datasets: [
        {
          data: dashboard.categoryTotals,

          borderWidth: 0,

          backgroundColor: [
            "#2E7D32",

            "#43A047",

            "#66BB6A",

            "#81C784",

            "#A5D6A7",

            "#C8E6C9",

            "#388E3C",

            "#4CAF50",
          ],
        },
      ],
    },

    options: {
      responsive: true,

      maintainAspectRatio: false,

      cutout: "68%",

      plugins: {
        legend: {
          position: "bottom",

          labels: {
            usePointStyle: true,

            padding: 20,
          },
        },
      },
    },
  });
}

/* ==========================================================
BOOTSTRAP TOOLTIP
========================================================== */

function initTooltips() {
  if (typeof bootstrap === "undefined") return;

  document

    .querySelectorAll('[data-bs-toggle="tooltip"]')

    .forEach((element) => {
      new bootstrap.Tooltip(element);
    });
}

/* ==========================================================
COUNTER
========================================================== */

function initCounter() {
  document.querySelectorAll(".dashboard-card h2").forEach((element) => {
    const target = parseInt(element.textContent.replace(/\D/g, ""));

    if (isNaN(target)) return;

    let value = 0;

    const step = Math.ceil(target / 40);

    const timer = setInterval(() => {
      value += step;

      if (value >= target) {
        value = target;

        clearInterval(timer);
      }

      element.textContent = value.toLocaleString("id-ID");
    }, 20);
  });
}

/* ==========================================================
CARD HOVER
========================================================== */

function initCardHover() {
  document.querySelectorAll(".dashboard-card").forEach((card) => {
    card.addEventListener("mouseenter", () => {
      card.style.transform = "translateY(-5px)";
    });

    card.addEventListener("mouseleave", () => {
      card.style.transform = "translateY(0)";
    });
  });
}

/* ==========================================================
AUTO ALERT
========================================================== */

function initAlert() {
  const alert = document.querySelector(".alert");

  if (!alert) return;

  setTimeout(() => {
    alert.classList.add("fade");

    setTimeout(() => {
      alert.remove();
    }, 500);
  }, 4000);
}

// ======================================
// LOGOUT
// ======================================

const logoutButton = document.getElementById("logoutButton");

if (logoutButton) {
  logoutButton.addEventListener("click", function (e) {
    if (!confirm("Apakah Anda yakin ingin logout?")) {
      e.preventDefault();
    }
  });
}
/* ==========================================================
   GLOBAL SEARCH V3
========================================================== */

document.addEventListener("DOMContentLoaded", () => {
  const input = document.getElementById("globalSearch");
  const dropdown = document.getElementById("searchDropdown");
  const loading = document.getElementById("searchLoading");
  const content = document.getElementById("searchContent");
  const empty = document.getElementById("searchEmpty");

  if (!input || !dropdown || !loading || !content || !empty) {
    return;
  }

  let timer = null;
  let selectedIndex = -1;

  /* ==========================================================
   HIGHLIGHT KEYWORD
========================================================== */

  function highlight(text, keyword) {
    if (!keyword) {
      return text;
    }

    const escaped = keyword.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");

    const regex = new RegExp("(" + escaped + ")", "gi");

    return text.replace(
      regex,

      '<span class="search-highlight">$1</span>',
    );
  }

  /**
   * Render Result
   */

  function render(results) {
    content.innerHTML = "";

    if (!results.length) {
      empty.classList.remove("d-none");
      return;
    }

    empty.classList.add("d-none");

    let currentType = "";

    results.forEach((item) => {
      if (currentType !== item.type) {
        currentType = item.type;

        content.insertAdjacentHTML(
          "beforeend",
          `
                <div class="search-group">

                    ${currentType}

                </div>
                `,
        );
      }

      content.insertAdjacentHTML(
        "beforeend",
        `
            <a href="${item.url}" class="search-result-item">

                <div class="search-icon">

                    <i class="bi bi-${item.icon}"></i>

                </div>

                <div class="search-body">

                    <strong>

                        ${highlight(item.title, input.value)}

                    </strong>

                    <small>

                        ${highlight(item.subtitle, input.value)}

                    </small>

                </div>

            </a>
            `,
      );
    });
  }
  function updateSelection() {
    const items = content.querySelectorAll(".search-result-item");

    items.forEach((item, index) => {
      const active = index === selectedIndex;

      item.classList.toggle("active", active);

      if (active) {
        item.scrollIntoView({
          block: "nearest",

          behavior: "smooth",
        });
      }
    });
  }
  /**
   * Search
   */

  input.addEventListener("input", () => {
    clearTimeout(timer);

    const keyword = input.value.trim();

    if (keyword.length < 2) {
      dropdown.classList.add("d-none");

      loading.classList.add("d-none");

      empty.classList.add("d-none");

      content.innerHTML = "";

      return;
    }

    timer = setTimeout(() => {
      dropdown.classList.remove("d-none");

      loading.classList.remove("d-none");

      empty.classList.add("d-none");

      content.innerHTML = "";

      fetch(
        window.APP.baseUrl +
          "admin/search.php?q=" +
          encodeURIComponent(keyword),
        {
          method: "GET",
          headers: {
            "X-Requested-With": "XMLHttpRequest",
          },
        },
      )
        .then((response) => response.json())

        .then((data) => {
          loading.classList.add("d-none");

          render(data);
        })

        .catch((error) => {
          console.error(error);

          loading.classList.add("d-none");

          empty.classList.remove("d-none");
        });
    }, 250);
  });

  /**
   * Focus
   */

  input.addEventListener("focus", () => {
    if (input.value.trim().length >= 2) {
      dropdown.classList.remove("d-none");
    }
  });
  input.addEventListener("keydown", function (e) {
    const items = content.querySelectorAll(".search-result-item");

    if (!items.length) {
      return;
    }

    switch (e.key) {
      case "ArrowDown":
        e.preventDefault();

        selectedIndex++;

        if (selectedIndex >= items.length) {
          selectedIndex = 0;
        }

        updateSelection();

        break;

      case "ArrowUp":
        e.preventDefault();

        selectedIndex--;

        if (selectedIndex < 0) {
          selectedIndex = items.length - 1;
        }

        updateSelection();

        break;

      case "Enter":
        if (selectedIndex >= 0) {
          e.preventDefault();

          items[selectedIndex].click();
        }

        break;

      case "Escape":
        dropdown.classList.add("d-none");

        input.blur();

        break;
    }
  });

  /**
   * Close
   */

  document.addEventListener("click", (e) => {
    if (!e.target.closest(".navbar-search-wrapper")) {
      dropdown.classList.add("d-none");
    }
  });
});
