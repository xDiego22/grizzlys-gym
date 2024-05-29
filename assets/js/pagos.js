var tabla;

const Toast = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 2300,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.onmouseenter = Swal.stopTimer;
    toast.onmouseleave = Swal.resumeTimer;
  },
});

$(function () {
  tabla = $("#tablePagos").DataTable({
    responsive: true,
    pagingType: "simple_numbers",
    order: [0,'desc'],
    language: {
      url: "./assets/es-ES.json",
    },
    ajax: {
      url: " ",
      type: "POST",
      dataSrc: "data",
      data: { accion: "getPays" },
    },
    columns: [
      { data: "id", searchable: false },
      { data: "register_by" },
      { data: "cedula" },
      { data: "nombre" },
      { data: "fecha_pago" },
      { data: "monto" },
    ],
    columnDefs: [
      {
        target: 4,
        render: function (data) {
          const [ano, mes, dia] = data.split("-");
          return `${dia}/${mes}/${ano}`;
        },
      },
      {
        target: 5,
        render: function (data) {
          return `${parseFloat(data)}$`;
        },
      },
        { responsivePriority: 1, targets: 3 },
        { responsivePriority: 2, targets: 4 },
        { responsivePriority: 3, targets: 5 },
        { responsivePriority: 4, targets: 2 },
        { responsivePriority: 5, targets: 0 },
        { responsivePriority: 6, targets: 1 },
    ],
    drawCallback: function (settings, json) {
      // Agregar atributo data-id a cada fila después de que se haya inicializado la tabla
      this.api()
        .rows()
        .every(function () {
          let data = this.data();
          let id = data.id; // Suponiendo que "id" es el ID único de la fila
          $(this.node()).attr("data-id", id);
        });
    },
  });


    $("#cliente").change(() => {
      validateKeyUp($("#cliente"), /^[a-zA-Z0-9\s]+$/);

    });
    $("#cliente").change(() => {
        const data = new FormData();

        data.append("accion", "info_client_pay");
        data.append("id", $("#cliente").val());
        console.log($("#cliente").val());
        $.ajax({
          async: true,
          url: " ",
          type: "POST",
          contentType: false,
          data: data,
          processData: false,
          cache: false,
          success: function (response) {
            let { saldo } = JSON.parse(response);

            saldo = !isNaN(saldo) ? parseFloat(saldo) : null;

            $("#saldoPay").val(saldo + "$");
          },
          error: function ({ responseText }, status, error) {
            Toast.fire({
              icon: "error",
              title: `${responseText}`,
            });
          },
        });
    });

  $("#btn_registrar").click(() => {

    clearFormPay();
  });

  $("#montoPay").keyup(() => {
    actualizarSaldoPay();
    validateKeyUp($("#montoPay"), /^\d+(\.\d)?$/);
  });

  //fin validaciones


  $("#formUserPay").submit(function (e) {
    e.preventDefault();

    allFieldsValidated = true;

    $(".validar").each(function () {
      if (!$(this).hasClass("is-valid")) {
        $(this).addClass("is-invalid");
        allFieldsValidated = false;
        return;
      }
    });

    if (!allFieldsValidated) {
      Toast.fire({
        icon: "error",
        title: "Campos inválidos",
      });
    }

    const data = new FormData();

    data.append("accion", "client_pay");
    data.append("id", $("#cliente").val());
    data.append("monto", $("#montoPay").val());

    $.ajax({
      async: true,
      url: " ",
      type: "POST",
      contentType: false,
      data: data,
      processData: false,
      cache: false,
      beforeSend: function () {
        disableFormPay();
      },
      success: function (response) {
        tabla.ajax.reload(null, false);

        Toast.fire({
          icon: "success",
          title: `${response}`,
        });

        $("#modalPay").modal("hide");
        clearFormPay();
      },
      error: function ({ responseText }, status, error) {
        Toast.fire({
          icon: "error",
          title: `${responseText}`,
        });
      },
      complete: function () {
        enableFormPay();
      },
    });
  });

  //funciones

  function disableFormPay() {
    $("#pagar").addClass("disabled");

    const loadingSpinner = `<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>`;

    $("#pagar").html(loadingSpinner);

    $(".validar").each((index, input) => {
      input.disabled = true;
    });
  }
  function enableFormPay() {
    $("#pagar").removeClass("disabled");

    $("#pagar").html("Pagar$");

    $(".validar").each((index, input) => {
      input.disabled = false;
    });
  }

});

function validateKeyUp(inputElement, regex) {
  const inputValue = inputElement.val().trim();
  const isValidInput = regex.test(inputValue);

  if (isValidInput) {
    inputElement.addClass("is-valid").removeClass("is-invalid");
    return true;
  } else {
    inputElement.addClass("is-invalid").removeClass("is-valid");
    return false;
  }
}

function actualizarSaldoPay() {
  const saldo = parseFloat($("#saldoPay").val()) || 0;
  const monto = parseFloat($("#montoPay").val()) || 0;

  let resultado = NaN;
  if (!isNaN(saldo) && !isNaN(monto)) {
    resultado = saldo + monto;
    // Verificar si el resultado tiene decimales
    if (resultado !== parseInt(resultado)) {
      // Si tiene decimales, mostrar el resultado con un decimal
      resultado = resultado.toFixed(1);
    }
  }

  $("#saldoNewPay").val(resultado === "" ? "" : `${resultado}$`);
}

function clearFormPay() {
  
  $("#saldoNewPay").val("");
  $("#saldoPay").val("");

  $(".validar").each((index, input) => {
    input.value = "";
    input.classList.remove("is-valid");
    input.classList.remove("is-invalid");
  });
}