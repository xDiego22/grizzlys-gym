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

    let allFieldsValidatedEdit = false;
    let allFieldsValidatedPassword = false;

    $("#showPassword").click(() => {
        if ($("#showPassword").is(":checked")) {
            $("#contrasena_actual").attr("type", "text");
            $("#contrasena").attr("type", "text");
            $("#contrasena2").attr("type", "text");
        } else {
            $("#contrasena_actual").attr("type", "password");
            $("#contrasena").attr("type", "password");
            $("#contrasena2").attr("type", "password");
        }
    });


    $("#btn_editar").click(function (e) { 
        $("#nombre").val(($("#nombre_info").text()).trim());
        $("#correo").val(($("#correo_info").text()).trim());
        $("#telefono").val(($("#telefono_info").text()).trim());

        validateKeyUp($("#nombre"), /^[a-zA-Z\s]{1,50}$/);
        validateKeyUp(
          $("#correo"),
          /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
        );
        validateKeyUp($("#telefono"), /^0(4\d{9})$/);
    });

    $("#nombre").keypress((event) =>
      validateKeyPress(event, /^[a-zA-ZáéíóúüÁÉÍÓÚÜñÑ\s]{0,50}$/)
    );

    $("#contrasena_actual").keypress((event) =>
      validateKeyPress(event, /^[a-zA-Z0-9!@#$%^&*()_+]{0,50}$/)
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
    

    $("#nombre").keyup(() =>
      validateKeyUp($("#nombre"), /^[a-zA-ZáéíóúüÁÉÍÓÚÜñÑ\s]{1,50}$/)
    );

    $("#contrasena_actual").keyup(() =>
      validateKeyUp(
        $("#contrasena_actual"),
        /(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z0-9!@#$%^&*()_+]{8,50}$/
      )
    );
    $("#contrasena").keyup(() =>
      validateKeyUp(
        $("#contrasena"),
        /(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z0-9!@#$%^&*()_+]{8,50}$/
      )
    );

    $("#contrasena2").keyup(() => {
      validateKeyUp(
        $("#contrasena2"),
        /(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z0-9!@#$%^&*()_+]{8,50}$/
      );

      if ($("#contrasena").val() !== $("#contrasena2").val()) {
        $("#contrasena2").addClass("is-invalid").removeClass("is-valid");
      } else {
        $("#contrasena2").addClass("is-valid").removeClass("is-invalid");
      }
    });

    $("#correo").keyup(() =>
      validateKeyUp(
        $("#correo"),
        /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
      )
    );
    $("#telefono").keyup(() => validateKeyUp($("#telefono"), /^0(4\d{9})$/));


    $("#formEditUser").submit(function (event) {
        event.preventDefault();
        event.stopPropagation();

        allFieldsValidatedEdit = true; 

        $("validar_editar").each(function () {
            if (!$(this).hasClass("is-valid")) {
                
                $(this).addClass("is-invalid");
                allFieldsValidatedEdit = false;
                return;
            }
        });

        // Si todos los campos están validados, envía el formulario
        if (allFieldsValidatedEdit) {

            const data = new FormData();

            data.append("accion", "editar");
            data.append("nombre", $("#nombre").val());
            data.append("correo", $("#correo").val());
            data.append("telefono", $("#telefono").val());

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

                    const { message, nombre, correo, telefono } = JSON.parse(response);

                    $("#nombre_info").text(nombre);
                    $("#correo_info").text(correo);
                    $("#telefono_info").text(telefono);

                    Toast.fire({
                        icon: "success",
                        title: `${message}`,
                    });
                    
                    $("#modalEditar").modal('hide');

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

            
        } else {
            Toast.fire({
               icon: "error",
               title: "Campos inválidos",
            });
        }
    });

    $("#formChangePassword").submit(function (event) {
      event.preventDefault();
      event.stopPropagation();

      allFieldsValidatedPassword = true;

      $("validar_password").each(function () {
        if (!$(this).hasClass("is-valid")) {
          $(this).addClass("is-invalid");
          allFieldsValidatedPassword = false;
          return;
        }
      });

      if (allFieldsValidatedPassword) {
        const data = new FormData();

        data.append("accion", "cambiarContrasena");
        data.append("contrasena_actual", $("#contrasena_actual").val());
        data.append("contrasena", $("#contrasena").val());
        data.append("contrasena2", $("#contrasena2").val());

        $.ajax({
          async: true,
          url: " ",
          type: "POST",
          contentType: false,
          data: data,
          processData: false,
          cache: false,
          beforeSend: function () {
            disableFormPassword();
          },
          success: function (response) {

            Toast.fire({
              icon: "success",
              title: `${response}`,
            });

              clearForm();
            $("#modalContrasena").modal("hide");
          },
          error: function ({ responseText }, status, error) {
            Toast.fire({
              icon: "error",
              title: `${responseText}`,
            });
          },
          complete: function () {
            enableFormPassword();
          },
        });
      } else {
        Toast.fire({
          icon: "error",
          title: "Campos inválidos",
        });
      }
    });

    function disableForm() {

        $("#editar").addClass('disabled');

        const loadingSpinner = `<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>`;

        $("#editar").html(loadingSpinner);

        $("validar_editar").each((index,input)=>{

            input.disabled = true;

        });
    }
    function enableForm() {

        $("#editar").removeClass('disabled');

        $("#editar").html("Editar");

        $("validar_editar").each((index,input)=>{


            input.disabled = false;

        });
    }
    function disableFormPassword() {

        $("#cambiar").addClass('disabled');

        const loadingSpinner = `<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>`;

        $("#cambiar").html(loadingSpinner);

        $(".validar_password").each((index,input)=>{

            input.disabled = true;

        });
    }
    function enableFormPassword() {

        $("#cambiar").removeClass('disabled');

        $("#cambiar").html("Cambiar");

        $(".validar_password").each((index,input)=>{


            input.disabled = false;

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
function clearForm() {
    
    $(".validar_password").each((index, input) => {
      input.value = "";
      input.classList.remove("is-valid");
      input.classList.remove("is-invalid");
    });
    $("#contrasena_actual").val("");
    $("#contrasena").val("");
    $("#contrasena2").val("");
}