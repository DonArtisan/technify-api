import {createPopper} from "@popperjs/core";
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

/* Make dynamic date appear */
(function () {
  if (document.getElementById("get-current-year")) {
    document.getElementById("get-current-year").innerHTML = new Date()
      .getFullYear()
      .toString();
  }
})();

document.querySelectorAll("[data-navbar]").forEach((element) => {
  element.addEventListener("click", () => toggleNavbar(element.dataset.target));
});

/* Sidebar - Side navigation menu on mobile/responsive mode */
function toggleNavbar(collapseID) {
  document.getElementById(collapseID).classList.toggle("hidden");
  document.getElementById(collapseID).classList.toggle("bg-white");
  document.getElementById(collapseID).classList.toggle("m-2");
  document.getElementById(collapseID).classList.toggle("py-3");
  document.getElementById(collapseID).classList.toggle("px-6");
}

document.querySelectorAll("[data-dropdown]").forEach((element) => {
  element.addEventListener("click", (event) =>
    openDropdown(event, element.dataset.target)
  );
});

/* Function for dropdowns */
function openDropdown(event, dropdownID) {
  let element = event.target;
  while (element.nodeName !== "A") {
    element = element.parentNode;
  }

  createPopper(element, document.getElementById(dropdownID), {
    placement: "bottom-start",
  });

  document.getElementById(dropdownID).classList.toggle("hidden");
  document.getElementById(dropdownID).classList.toggle("block");
}


function initCharts() {
    let ctx = document.getElementById("line-chart").getContext("2d");
    let config = {
        type: 'line',
        data: {
            labels: [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July"
            ],
            datasets: [
                {
                    label: new Date().getFullYear(),
                    backgroundColor: "#4c51bf",
                    borderColor: "#4c51bf",
                    data: [65, 78, 66, 44, 56, 67, 75],
                },
                {
                    label: new Date().getFullYear() - 1,
                    backgroundColor: "#fff",
                    borderColor: "#fff",
                    data: [40, 68, 86, 74, 56, 60, 87]
                }
            ]
        },
        options: {
            plugins: {
                maintainAspectRatio: false,
                responsive: true,
                title: {
                    display: false,
                    text: "Sales Charts",
                    color: "white"
                },
                legend: {
                    labels: {
                        color: "white"
                    },
                    align: "end",
                    position: "bottom"
                },
                tooltips: {
                    mode: "index",
                    intersect: false
                },
            },
            hover: {
                mode: "nearest",
                intersect: true
            },
            scales: {
                x:
                    {
                        ticks: {
                            color: "rgba(255,255,255,.7)"
                        },
                        display: true,
                        title: {
                            display: false,
                            text: "Month",
                            color: "white"
                        },
                        grid: {
                            display: false,
                            borderDash: [2],
                            borderDashOffset: [2],
                            color: "rgba(33, 37, 41, 0.3)",
                            zeroLineColor: "rgba(0, 0, 0, 0)",
                            zeroLineBorderDash: [2],
                            zeroLineBorderDashOffset: [2]
                        }
                    }
                ,
                y:
                    {
                        ticks: {
                            color: "rgba(255,255,255,.7)"
                        },
                        display: true,
                        title: {
                            display: false,
                            text: "Value",
                            color: "white"
                        },
                        grid: {
                            borderDash: [3],
                            borderDashOffset: [3],
                            drawBorder: false,
                            color: "rgba(255, 255, 255, 0.15)",
                            zeroLineColor: "rgba(33, 37, 41, 0)",
                            zeroLineBorderDash: [2],
                            zeroLineBorderDashOffset: [2]
                        }
                    }

            }
        }
    }

    const chart = new Chart(ctx, config);

    config = {
        type: "bar",
        data: {
            labels: [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July"
            ],
            datasets: [
                {
                    label: new Date().getFullYear(),
                    backgroundColor: "#ed64a6",
                    borderColor: "#ed64a6",
                    data: [30, 78, 56, 34, 100, 45, 13],
                    fill: false,
                    barThickness: 8
                },
                {
                    label: new Date().getFullYear() - 1,
                    fill: false,
                    backgroundColor: "#4c51bf",
                    borderColor: "#4c51bf",
                    data: [27, 68, 86, 74, 10, 4, 87],
                    barThickness: 8
                }
            ]
        },
        options: {
            plugins: {
                maintainAspectRatio: false,
                responsive: true,
                title: {
                    display: false,
                    text: "Orders Chart"
                },
                tooltips: {
                    mode: "index",
                    intersect: false
                },
                legend: {
                    labels: {
                        color: "rgba(0,0,0,.4)"
                    },
                    align: "end",
                    position: "bottom"
                },
            },
            hover: {
                mode: "nearest",
                intersect: true
            },
            scales: {
                x:
                    {
                        display: false,
                        title: {
                            display: true,
                            text: "Month"
                        },
                        grid: {
                            borderDash: [2],
                            borderDashOffset: [2],
                            color: "rgba(33, 37, 41, 0.3)",
                            zeroLineColor: "rgba(33, 37, 41, 0.3)",
                            zeroLineBorderDash: [2],
                            zeroLineBorderDashOffset: [2]
                        }
                    }
                ,
                y:
                    {
                        offset: true,
                        display: true,
                        title: {
                            display: false,
                            text: "Value"
                        },
                        grid: {
                            borderDash: [2],
                            drawBorder: false,
                            borderDashOffset: [2],
                            color: "rgba(33, 37, 41, 0.2)",
                            zeroLineColor: "rgba(33, 37, 41, 0.15)",
                            zeroLineBorderDash: [2],
                            zeroLineBorderDashOffset: [2]
                        }
                    }

            }
        }
    };
    ctx = document.getElementById("bar-chart").getContext("2d");
    const myBar = new Chart(ctx, config);
    window.myBar = myBar;
}

initCharts();
