var myBarChart;

$(function () {
  const ctx2 = document.getElementById("myBarChart");

  function fetchDataAndUpdateChart() {
    const data = new FormData();
    data.append("accion", "historypays");
    data.append("anioActual", $("#year_chart").val());

    $.ajax({
      async: true,
      url: " ", 
      type: "POST",
      contentType: false,
      data: data,
      processData: false,
      cache: false,
      success: function (response) {
        const parsedData = JSON.parse(response);
        console.log(parsedData);

        if (myBarChart) {
          myBarChart.data.datasets[0].data = Object.values(parsedData);
          myBarChart.update();
        } else {
          const colors = [
            "#FF6384",
            "#36A2EB",
            "#FFCE56",
            "#4BC0C0",
            "#9966FF",
            "#FF9F40",
            "#E7E9ED",
            "#8E44AD",
            "#3498DB",
            "#1ABC9C",
            "#F39C12",
            "#D35400",
          ];

          myBarChart = new Chart(ctx2, {
            type: "bar",
            data: {
              labels: [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre",
              ],
              datasets: [
                {
                  label: "Ganancia",
                  backgroundColor: colors, 
                  hoverBackgroundColor: colors,
                  borderColor: colors,
                  borderWidth: 2,
                  data: Object.values(parsedData), 
                },
              ],
            },
            options: {
              maintainAspectRatio: false,
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: {
                    callback: function (value) {
                      return "$" + value; // Añadir signo de dólar
                    },
                  },
                },
              },
              tooltips: {
                callbacks: {
                  label: function (tooltipItem, data) {
                    return data.datasets[0].label + ": $" + tooltipItem.yLabel;
                  },
                },
              },
            },
          });
        }
      },
      error: function ({ responseText }, status, error) {
        console.error("Error:", responseText, status, error);
      },
    });
  }

  fetchDataAndUpdateChart();

  $("#year_chart").change(function () {
    fetchDataAndUpdateChart();
  });
});

