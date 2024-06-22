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

    tabla = $("#tableMemberships").DataTable({
      responsive: true,
      pagingType: "simple_numbers",
      
      order: [[2, "asc"]],
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
        { data: "cedula" },
        { data: "nombre" },
        { data: "f_inicial" },
        { data: "f_limite" },
        { data: "dias_restantes" },
        { data: "estado" },
        { data: null, defaultContent: "" },
      ],
      columnDefs: [
        {
          target: -1,
          searchable: false,
          render: function () {

            const btn_edit =
                  "<button type='button' class='btn btn-primary me-1' data-bs-toggle='modal' data-bs-target='#modalGestion' onclick='modalEditar(this)' ><i class='bi bi-pencil-fill'></i></button>";
              
            return (
              "<div class='btn-group' role='group' aria-label='optiones buttons'>" +
              
              btn_edit +
              
              "</div>"
            );
          },
        },
        {
          target: 6,
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
          target: [3, 4],
          render: function (data) {
            const [ano, mes, dia] = data.split("-");
            return `${dia}/${mes}/${ano}`;
          },
        },
        {
          target: 5,
          render: function (data) {
            let icon;
            if (data < 0)
              icon =
                '<i class="bi bi-exclamation-triangle-fill text-danger" ></i>';

            return `${data} días ${icon ?? ""}`;
          },
        },
        

        { responsivePriority: 1, targets: 2 },
        { responsivePriority: 2, targets: 3 },
        { responsivePriority: 2, targets: 4 },
        { responsivePriority: 3, targets: 5 },
        { responsivePriority: 4, targets: 6 },
        { responsivePriority: 5, targets: 0 },
        { responsivePriority: 6, targets: 7 },
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

    $("#btn_registrar").click(function () { 
  
      $("#cliente").attr("disabled", false);
      
    });


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

    //fin validaciones

    $("#formManageMemberships").submit(function (event) {
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


            data.append("accion", 'editar');
            data.append("id", id);
            data.append("fecha_inicial", $("#fecha_inicial").val());
            data.append("fecha_limite", $("#fecha_limite").val());

            sendAjax(data);

            
        } else {
            Toast.fire({
               icon: "error",
               title: "Campos inválidos",
            });
        }
    });

    //funciones
    
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

                tabla.ajax.reload(null,false);

                Toast.fire({
                    icon: "success",
                    title: `${response}`,
                });
                
                $("#modalGestion").modal('hide');
                clearForm();

            },
            error: function ({ responseText }, status, error) {
                Toast.fire({
                    icon: "error",
                    title: `${responseText}`,
                });
            },
            complete: function(){
                enableForm();
            } 
        });
    }
});

function modalEditar(fila) {
  id = null;
  $("#cliente").attr("disabled", true);

  clearForm();

  // Obtener el ID único de la fila seleccionada
  let idFila = $(fila).closest("tr").data("id");

  // Buscar el índice de la fila correspondiente al ID único
  let indiceFila = tabla.row('[data-id="' + idFila + '"]').index();

  id = tabla.cell(indiceFila, 0).data();
  let fecha_inicial = tabla.cell(indiceFila, 3).data();
  let fecha_limite = tabla.cell(indiceFila, 4).data();

  $("#cliente").val(id);
  $("#fecha_inicial").val(fecha_inicial);
  $("#fecha_limite").val(fecha_limite);
  validateKeyUp($("#cliente"), /^[a-zA-Z0-9\s]+$/);
    
    
  validateKeyUp($("#fecha_inicial"),
    /^(?:(?:1[6-9]|[2-9]\d)?\d{2})(?:(?:(\/|-|\.)(?:0?[13578]|1[02])\1(?:31))|(?:(\/|-|\.)(?:0?[13-9]|1[0-2])\2(?:29|30)))$|^(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(\/|-|\.)0?2\3(?:29)$|^(?:(?:1[6-9]|[2-9]\d)?\d{2})(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:0?[1-9]|1\d|2[0-8])$/
  );
  validateKeyUp($("#fecha_limite"),
    /^(?:(?:1[6-9]|[2-9]\d)?\d{2})(?:(?:(\/|-|\.)(?:0?[13578]|1[02])\1(?:31))|(?:(\/|-|\.)(?:0?[13-9]|1[0-2])\2(?:29|30)))$|^(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(\/|-|\.)0?2\3(?:29)$|^(?:(?:1[6-9]|[2-9]\d)?\d{2})(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:0?[1-9]|1\d|2[0-8])$/
  );
}

function clearForm() {
  $(".validar").each((index, input) => {
    input.value = "";
    input.classList.remove("is-valid");
    input.classList.remove("is-invalid");
  });
}

function reajustarFecha(fecha) {
    const [ano, mes, dia] = fecha.split("/");
     return `${ano}-${mes}-${dia}`;
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

function disableForm() {
  $("#editar").addClass("disabled");

  const loadingSpinner = `<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>`;

  $("#editar").html(loadingSpinner);

  $("input.validar").each((index, input) => {
    input.disabled = true;
  });
}
function enableForm() {
  $("#editar").removeClass("disabled");

  $("#editar").html("Editar");

  $("input.validar").each((index, input) => {
    input.disabled = false;
  });
    
}