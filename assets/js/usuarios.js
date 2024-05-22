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

$(() => {
    
    let allFieldsValidated = false;


    tabla = $("#tableUsers").DataTable({
      responsive: true,
      pagingType: "simple_numbers",
      order: [[1, "asc"]],
      language: {
        url: "./assets/es-ES.json",
      },
      ajax: {
        url: " ",
        type: "POST",
        dataSrc: "data",
        data: { accion: "getUsers" },
      },
      columns: [
        { data: "cedula" },
        { data: "nombre" },
        { data: "correo" },
        { data: "telefono" },
        { targets: -1, defaultContent: "" },
      ],
      columnDefs: [
        {
          target: -1,
          searchable: false,
          render: function (data, type, row, meta) {

            const btn_edit = "<button type='button' class='btn btn-primary me-1' data-bs-toggle='modal' data-bs-target='#modalGestion' onclick='modalEditar(this)' ><i class='bi bi-pencil-fill'></i></button>";

            const btn_delete =
              "<button type='button' class='btn btn-danger ' onclick='eliminar(this)'><i class='bi bi-trash-fill'></i></button>";
            return (
              "<div class='btn-group' role='group' aria-label='optiones buttons'>" +
              btn_edit +
              btn_delete +
              "</div>"
            );
          },
        },
        { responsivePriority: 1, targets: 1 },
        { responsivePriority: 1, targets: -1 },
        { responsivePriority: 2, targets: 0 },
        { responsivePriority: 3, targets: 2 },
        { responsivePriority: 4, targets: 3 },
      ],
      lengthMenu: [
        [10, 15, 20],
        [10, 15, 20],
      ],
      lengthChange: true,
    });

    //validaciones keypress keyup

    $("#cedula").keypress((event) => validateKeyPress(event, /^\d{0,8}$/));

    $("#nombre").keypress((event) =>
        validateKeyPress(event, /^[a-zA-ZáéíóúüÁÉÍÓÚÜñÑ\s]{0,50}$/)
    );

    $("#contrasena").keypress((event) =>
        validateKeyPress(event, /^[a-zA-Z0-9!@#$%^&*()_+]{0,50}$/)
    );

    $("#contrasena2").keypress((event) =>
        validateKeyPress(event, /^[a-zA-Z0-9!@#$%^&*()_+]{0,50}$/)
    );

    $("#correo").keypress((event) =>
        validateKeyPress(event, /^[a-zA-Z0-9._%+-@]{0,50}$/)
    );

    $("#telefono").keypress((event) => validateKeyPress(event, /^\d{0,10}$/));

    $("#cedula").keyup(() => validateKeyUp($("#cedula"), /^\d{7,8}$/));
    $("#nombre").keyup(() => validateKeyUp($("#nombre"), /^[a-zA-ZáéíóúüÁÉÍÓÚÜñÑ\s]{1,50}$/));
    $("#contrasena").keyup(() =>
        validateKeyUp(
        $("#contrasena"),
        /(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z0-9!@#$%^&*()_+]{8,50}$/
        )
    );

    $("#contrasena2").keyup(() => {
            validateKeyUp($("#contrasena2"), /(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z0-9!@#$%^&*()_+]{8,50}$/)

            if ($("#contrasena").val() !== $("#contrasena2").val()) {
                
                $("#contrasena2").addClass("is-invalid").removeClass("is-valid");
                
            } else {
                $("#contrasena2").addClass("is-valid").removeClass("is-invalid");
                
            }

        }
    );

    $("#correo").keyup(() =>
        validateKeyUp(
        $("#correo"),
        /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
        )
    );
    $("#telefono").keyup(() => validateKeyUp($("#telefono"), /^0(4\d{9})$/));
    
    $("#btn_registrar").click(() => { 
        
        modalRegistrar();
        
    });

    //fin validaciones

    $("#formManageUser").submit(function (event) {
        event.preventDefault();
        event.stopPropagation();

        allFieldsValidated = true; 

        $("input.validar").each(function () {
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

            data.append("accion", btn_clicked);
            data.append("cedula", $("#cedula").val());
            data.append("contrasena", $("#contrasena").val());
            data.append("contrasena2", $("#contrasena2").val());
            data.append("nombre", $("#nombre").val());
            data.append("correo", $("#correo").val());
            data.append("telefono", $("#telefono").val());

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

        $("#registrar").addClass('disabled');
        $("#editar").addClass('disabled');

        const loadingSpinner = `<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>`;

        $("#registrar").html(loadingSpinner);
        $("#editar").html(loadingSpinner);

        $("input.validar").each((index,input)=>{

            input.disabled = true;

        });
    }
    function enableForm() {

        $("#registrar").removeClass('disabled');
        $("#editar").removeClass('disabled');

        $("#registrar").html("Guardar");
        $("#editar").html("Editar");

        $("input.validar").each((index,input)=>{


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
        
        const linea = $(fila).closest("tr");
        const cedula = $(linea).find("td:eq(0)");

        const datos = new FormData();
        datos.append("accion", "eliminar");
        datos.append("cedula", cedula.text());
        
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
  $("#modalGestionLabel").html("Registrar");
  clearForm();
  $("#registrar").show();
  $("#editar").hide();

  $("#cedula").prop("disabled", false);
}

function modalEditar(fila) {
  $("#modalGestionLabel").html("Editar");
  $("#editar").show();
  $("#registrar").hide();
  
    clearForm();

  let linea = $(fila).closest("tr");
  $("#cedula").val($(linea).find("td:eq(0)").text());
  $("#nombre").val($(linea).find("td:eq(1)").text());
  $("#correo").val($(linea).find("td:eq(2)").text());
  $("#telefono").val($(linea).find("td:eq(3)").text());


    $("#cedula").prop("disabled", true);
    
    
    validateKeyUp($("#cedula"), /^\d{7,8}$/);
    validateKeyUp($("#nombre"), /^[a-zA-Z\s]{1,50}$/);
    validateKeyUp( $("#correo"),/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/);
    validateKeyUp($("#telefono"), /^0(4\d{9})$/);

}

function clearForm() {
  $("input.validar").each((index, input) => {
    input.value = "";
    input.classList.remove("is-valid");
    input.classList.remove("is-invalid");
  });
}