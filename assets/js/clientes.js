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

    $("#monto").keyup(() => {
      actualizarDeuda();
    });

    
    $("#membresias").change(function (e) { 
        
        if (!$("#membresias").val()) {
            $("#precio_membresia").val("");
            actualizarDeuda();
            return
        }; 
        const data = new FormData();

        data.append("accion", 'valor_membresia');
        data.append("membresias", $("#membresias").val());

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
                
                $("#precio_membresia").val(parseFloat(valor) + "$");
                actualizarDeuda();
            },
            error: function ({ responseText }, status, error) {
                Toast.fire({
                    icon: "error",
                    title: `${responseText}`,
                });
            }
        });
        
    });

    //validaciones keypress keyup

    $("#cedula").keypress((event) => validateKeyPress(event, /^\d{0,8}$/));
    $("#cedula").keyup(() => validateKeyUp($("#cedula"), /^\d{7,8}$/));

    $("#nombre").keypress((event) =>
        validateKeyPress(event, /^[a-zA-Z\s]{0,50}$/)
    );
    $("#nombre").keyup(() => validateKeyUp($("#nombre"), /^[a-zA-Z\s]{1,50}$/));

    $("#telefono").keypress((event) => validateKeyPress(event, /^\d{0,10}$/));
    $("#telefono").keyup(() => validateKeyUp($("#telefono"), /^0(4\d{9})$/));

    $("#membresias").change(() => validateKeyUp($("#membresias"), /^[a-zA-Z0-9\s]+$/));

    $("#monto").keyup(() => validateKeyUp($("#monto"), /^\d+(\.\d)?$/));

    $("#fecha_inicio").change(() => validateKeyUp($("#fecha_inicio"), /^(?:(?:1[6-9]|[2-9]\d)?\d{2})(?:(?:(\/|-|\.)(?:0?[13578]|1[02])\1(?:31))|(?:(\/|-|\.)(?:0?[13-9]|1[0-2])\2(?:29|30)))$|^(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(\/|-|\.)0?2\3(?:29)$|^(?:(?:1[6-9]|[2-9]\d)?\d{2})(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:0?[1-9]|1\d|2[0-8])$/));
    $("#fecha_limite").change(() => validateKeyUp($("#fecha_limite"), /^(?:(?:1[6-9]|[2-9]\d)?\d{2})(?:(?:(\/|-|\.)(?:0?[13578]|1[02])\1(?:31))|(?:(\/|-|\.)(?:0?[13-9]|1[0-2])\2(?:29|30)))$|^(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(\/|-|\.)0?2\3(?:29)$|^(?:(?:1[6-9]|[2-9]\d)?\d{2})(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:0?[1-9]|1\d|2[0-8])$/));

   
    
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

            data.append("accion", btn_clicked);
            data.append("cedula", $("#cedula").val());
            data.append("nombre", $("#nombre").val());
            data.append("telefono", $("#telefono").val());
            data.append("membresias", $("#membresias").val());
            data.append("monto", $("#monto").val());
            data.append("fecha_inicio", $("#fecha_inicio").val());
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

                // tabla.ajax.reload(null,false);

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

function actualizarDeuda() {
  const precio = parseFloat($("#precio_membresia").val()) || 0;
  const monto = parseFloat($("#monto").val()) || 0;

  const resultado = isNaN(precio) || isNaN(monto) ? "" : precio - monto;

  $("#deuda").val(resultado === "" ? "" : `${resultado}$`);
}


function validateKeyPress(event, regex) {
    const keyPressed = event.key;
    const isValidKey = regex.test(keyPressed);

    if (!isValidKey) {
        event.preventDefault();
    }
}

function validateKeyUp(inputElement, regex) {
    const inputValue = inputElement.val();
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
        const id = $(linea).find("td:eq(0)");

        const datos = new FormData();
        datos.append("accion", "eliminar");
        datos.append("id", id.text());
        
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
    $("#cedula").val($(linea).find("td:eq(1)").text());
    $("#nombre").val($(linea).find("td:eq(2)").text());
    $("#telefono").val($(linea).find("td:eq(3)").text());

    $("#cedula").prop("disabled", true);
    
    validateKeyUp($("#cedula"), /^\d{7,8}$/);
    validateKeyUp($("#nombre"), /^[a-zA-Z\s]{1,50}$/);
    validateKeyUp($("#telefono"), /^0(4\d{9})$/);

}

function clearForm() {
  $("input.validar").each((index, input) => {
    input.value = "";
    input.classList.remove("is-valid");
    input.classList.remove("is-invalid");
  });
}