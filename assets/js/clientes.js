var tabla;
var id_plan;
var id;

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
  tabla = $("#tableClients").DataTable({
    responsive: true,
    pagingType: "simple_numbers",
    layout: {
      topStart: {
        buttons: [
          {
            extend: "colvis",
            columns: ":not(:first-child):not(:last-child)",
            text: "Filtrar",
            titleAttr: "filter",
            className: "bg-gray mb-2",
          },
        ],
        pageLength: {
          menu: [10, 20, 30],
        },
      },
    },
    order: [[1, "asc"]],
    language: {
      url: "./assets/es-ES.json",
    },
    ajax: {
      url: " ",
      type: "POST",
      dataSrc: "data",
      data: { accion: "getClients" },
    },
    columns: [
      { data: "id", visible: false, searchable: false },
      { data: "id_plan", visible: false, searchable: false },
      { data: "cedula" },
      { data: "nombre" },
      { data: "telefono" },
      { data: "plan" },
      { data: "f_inicial" },
      { data: "f_limite" },
      { data: "dias_restantes" },
      { data: "saldo" },
      { data: "estado" },
      { targets: -1, defaultContent: "" },
    ],
    columnDefs: [
      {
        target: -1,
        searchable: false,
        render: function () {
          return (
            "<button type='button' class='btn btn-primary mb-1 me-1' data-bs-toggle='modal' data-bs-target='#modalGestion' onclick='modalEditar(this)' ><i class='bi bi-pencil-fill'></i></button>" +
            "<button type='button' class='btn btn-danger mb-1 ' onclick='eliminar(this)'><i class='bi bi-x-lg'></i></button>"
          );
        },
      },
      {
        target: 10,
        render: function (data) {
          let estado;
          if (data === "activo") {
            estado = "text-bg-success";
          } else {
            estado = "text-bg-danger";
          }

          return `<span class='badge rounded-pill ${estado}'>${data}</span>`;
        },
      },
      {
        target: [6, 7],
        render: function (data) {
          const [ano, mes, dia] = data.split("-");
          return `${dia}/${mes}/${ano}`;
        },
      },
      {
        target: 8,
        render: function (data) {
          let icon;
          if (data < 0)
            icon =
              '<i class="bi bi-exclamation-triangle-fill text-danger" ></i>';

          return `${data} días ${icon ?? ""}`;
        },
      },
      {
        target: 9,
        render: function (data) {
          return `${parseFloat(data)}$`;
        },
      },

      { responsivePriority: 10, targets: 0 },
      { responsivePriority: 11, targets: 1 },
      { responsivePriority: 1, targets: -1 },
      { responsivePriority: 2, targets: 2 },
      { responsivePriority: 1, targets: 3 },
      { responsivePriority: 7, targets: 4 },
      { responsivePriority: 3, targets: 5 },
      { responsivePriority: 9, targets: 6 },
      { responsivePriority: 6, targets: 7 },
      { responsivePriority: 4, targets: 8 },
      { responsivePriority: 5, targets: 9 },
      { responsivePriority: 6, targets: 10 },
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

  

  $("#monto").keyup(() => {
    actualizarSaldo();
  });

  $("#planes").change(changePlan);

  //validaciones keypress keyup

  $("#cedula").keypress((event) => validateKeyPress(event, /^\d{0,8}$/));
  $("#cedula").keyup(() => validateKeyUp($("#cedula"), /^\d{7,8}$/));

  $("#nombre").keypress((event) =>
    validateKeyPress(event, /^[a-zA-ZáéíóúüÁÉÍÓÚÜñÑ\s]{0,50}$/)
  );
  $("#nombre").keyup(() =>
    validateKeyUp($("#nombre"), /^[a-zA-ZáéíóúüÁÉÍÓÚÜñÑ\s]{1,50}$/)
  );

  $("#telefono").keypress((event) => validateKeyPress(event, /^\d{0,10}$/));
  $("#telefono").keyup(() => validateKeyUp($("#telefono"), /^0(4\d{9})$/));

  $("#planes").change(() => validateKeyUp($("#planes"), /^[a-zA-Z0-9\s]+$/));

  $("#monto").keyup(() => validateKeyUp($("#monto"), /^\d+(\.\d)?$/));

  $("#fecha_inicial").change(() =>
    validateKeyUp(
      $("#fecha_inicial"),
      /^(?:(?:1[6-9]|[2-9]\d)?\d{2})(?:(?:(\/|-|\.)(?:0?[13578]|1[02])\1(?:31))|(?:(\/|-|\.)(?:0?[13-9]|1[0-2])\2(?:29|30)))$|^(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(\/|-|\.)0?2\3(?:29)$|^(?:(?:1[6-9]|[2-9]\d)?\d{2})(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:0?[1-9]|1\d|2[0-8])$/
    )
  );
  $("#fecha_limite").change(() =>
    validateKeyUp(
      $("#fecha_limite"),
      /^(?:(?:1[6-9]|[2-9]\d)?\d{2})(?:(?:(\/|-|\.)(?:0?[13578]|1[02])\1(?:31))|(?:(\/|-|\.)(?:0?[13-9]|1[0-2])\2(?:29|30)))$|^(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(\/|-|\.)0?2\3(?:29)$|^(?:(?:1[6-9]|[2-9]\d)?\d{2})(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:0?[1-9]|1\d|2[0-8])$/
    )
  );

  $("#btn_registrar").click(() => {
    modalRegistrar();
  });

  //fin validaciones

  $("#formManageUser").submit(function (event) {
    event.preventDefault();
    event.stopPropagation();

    allFieldsValidated = true;

    $(".validar").each(function () {
        if (!$(this).hasClass("is-valid")) {

            $(this).addClass("is-invalid");
            allFieldsValidated = false;
            return;
        }
    });

    // Si todos los campos están validados, envía el formulario
    if (allFieldsValidated) {
      const data = new FormData();

      const btn_clicked = event.originalEvent.submitter.id;

      if (btn_clicked === "registrar") {
        data.append("monto", $("#monto").val());
        data.append("fecha_inicial", $("#fecha_inicial").val());
        data.append("fecha_limite", $("#fecha_limite").val());
      }

      if (btn_clicked === "editar") {
        data.append("id", id);
      }

      data.append("accion", btn_clicked);
      data.append("cedula", $("#cedula").val());
      data.append("nombre", $("#nombre").val());
      data.append("telefono", $("#telefono").val());
      data.append("planes", $("#planes").val());

      sendAjax(data);
    } else {
      Toast.fire({
        icon: "error",
        title: "Campos inválidos",
      });
    }
  });

  //funciones

  function disableForm() {
    $("#registrar").addClass("disabled");
    $("#editar").addClass("disabled");

    const loadingSpinner = `<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>`;

    $("#registrar").html(loadingSpinner);
    $("#editar").html(loadingSpinner);

    $("input.validar").each((index, input) => {
      input.disabled = true;
    });
  }
  function enableForm() {
    $("#registrar").removeClass("disabled");
    $("#editar").removeClass("disabled");

    $("#registrar").html("Guardar");
    $("#editar").html("Editar");

    $("input.validar").each((index, input) => {
      input.disabled = false;
    });
  }

  function sendAjax(data) {
    $.ajax({
      async: true,
      url: " ",
      type: "POST",
      contentType: false,
      data: data,
      processData: false,
      cache: false,
      beforeSend: function () {
        disableForm();
      },
      success: function (response) {
        tabla.ajax.reload(null, false);

        Toast.fire({
          icon: "success",
          title: `${response}`,
        });

        $("#modalGestion").modal("hide");
        clearForm();
      },
      error: function ({ responseText }, status, error) {
        Toast.fire({
          icon: "error",
          title: `${responseText}`,
        });
      },
      complete: function () {
        enableForm();
      },
    });
  }
});

function changePlan() {
   
        if (!$("#planes").val()) {
            $("#precio_plan").val("");
            actualizarSaldo();
            return
        }; 
        const data = new FormData();

        data.append("accion", 'valor_plan');
        data.append("planes", $("#planes").val());

        $.ajax({
            async: true,
            url: " ",
            type: "POST",
            contentType: false,
            data: data,
            processData: false,
            cache: false,
            success: function (response) {

                const { valor } = JSON.parse(response);
                
                $("#precio_plan").val(parseFloat(valor) + "$");
                actualizarSaldo();
            },
            error: function ({ responseText }, status, error) {
                Toast.fire({
                    icon: "error",
                    title: `${responseText}`,
                });
            }
        });
        
    
}

function actualizarSaldo() {
  const precio = parseFloat($("#precio_plan").val()) || 0;
  const monto = parseFloat($("#monto").val()) || 0;

    let resultado = "";
    if (!isNaN(precio) && !isNaN(monto)) {
        resultado = monto - precio;
        // Verificar si el resultado tiene decimales
        if (resultado !== parseInt(resultado)) {
            // Si tiene decimales, mostrar el resultado con un decimal
            resultado = resultado.toFixed(1);
        }
    }
    $("#saldo").val(resultado === "" ? "" : `${resultado}$`);
}


function validateKeyPress(event, regex) {
    const keyPressed = event.key;
    const isValidKey = regex.test(keyPressed);

    if (!isValidKey) {
        event.preventDefault();
    }
}

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

function eliminar(fila) {
  Swal.fire({
    title: "¿Estas Seguro?",
    text: "¡No podrás revertir esto!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3B71CA",
    confirmButtonText: "Si, eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
      if (result.isConfirmed) {
        
        const linea = tabla.row(fila).index();
        const id = tabla.cell(linea, 0).data();

        const datos = new FormData();
        datos.append("accion", "eliminar");
        datos.append("id", id);
        
        $.ajax({
          async: true,
          url: " ",
          type: "POST",
          contentType: false,
          data: datos,
          processData: false,
          cache: false,
          success: function (respuesta) {
            Toast.fire({
              icon: "success",
              title: `¡${respuesta}!`,
            });
            tabla.row(linea).remove().draw(false);
          },
          error: function ({ status, responseText }) {
            
            if (status === 500 || status === 400) {
              Swal.fire({
                title: "error",
                text: `${responseText}`,
                icon: "error",
              });
            }

            
          },
        });
    }
  });
}

function modalRegistrar() {

  id = null;
  $("#fecha_inicial").addClass("validar");
  $("#fecha_limite").addClass("validar");
  $("#monto").addClass("validar");

    $("#fila_monto").removeClass("d-none");
    $("#fila_fecha").removeClass("d-none");
  $("#modalGestionLabel").html("Registrar");
  clearForm();
  $("#registrar").show();
  $("#editar").hide();

  $("#cedula").prop("disabled", false);
}

function modalEditar(fila) {
  id = null;

  $("#fecha_inicial").removeClass("validar");
  $("#fecha_limite").removeClass("validar");
  $("#monto").removeClass("validar");

  $("#modalGestionLabel").html("Editar");
  $("#editar").show();
  $("#registrar").hide();

  clearForm();

  // Obtener el ID único de la fila seleccionada
  let idFila = $(fila).closest("tr").data("id");

  // Buscar el índice de la fila correspondiente al ID único
  let indiceFila = tabla.row('[data-id="' + idFila + '"]').index();

  id = tabla.cell(indiceFila, 0).data();
  // Obtener otros valores de la fila si es necesario
  let planes = tabla.cell(indiceFila, 1).data();
  let cedula = tabla.cell(indiceFila, 2).data();
  let nombre = tabla.cell(indiceFila, 3).data();
  let telefono = tabla.cell(indiceFila, 4).data();

  $("#planes").val(planes);
  $("#cedula").val(cedula);
  $("#nombre").val(nombre);
  $("#telefono").val(telefono);
  changePlan();

  $("#fila_monto").addClass("d-none");
  $("#fila_fecha").addClass("d-none");

  $("#cedula").prop("disabled", true);

  validateKeyUp($("#cedula"), /^\d{7,8}$/);
  validateKeyUp($("#nombre"), /^[a-zA-Z\s]{1,50}$/);
  validateKeyUp($("#telefono"), /^0(4\d{9})$/);
  validateKeyUp($("#planes"), /^[a-zA-Z0-9\s]+$/);
}

function clearForm() {
  $(".validar").each((index, input) => {
    input.value = "";
    input.classList.remove("is-valid");
    input.classList.remove("is-invalid");
  });
  $("#precio_plan").val("");
  $("#saldo").val("");
}