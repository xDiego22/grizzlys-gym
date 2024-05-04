$(function () {

    let allFieldsValidated = false;

    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
    });

    $("#cedula").keypress((event) => validateKeyPress(event, /^\d{0,8}$/));

    $("#cedula").keyup(() => validateKeyUp($("#cedula"), /^\d{7,8}$/));

    $("#contrasena").keypress((event) => validateKeyPress(event, /^[a-zA-Z0-9!@#$%^&*()_+]{0,50}$/));
    
    $("#contrasena").keyup(() => validateKeyUp( $("#contrasena"),/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z0-9!@#$%^&*()_+]{8,50}$/)
    );

    $("#showPassword").click( () => { 
        
        ($("#showPassword").is(":checked")) ? $("#contrasena").attr('type', 'text') : $("#contrasena").attr('type', 'password');
        
    });

    $("#formLogin").submit(function (event) { 
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

        if (allFieldsValidated) {
            const data = new FormData();

            data.append("accion", "login");
            data.append("cedula", $("#cedula").val());
            data.append("contrasena", $("#contrasena").val());

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

        
        $("#entrar").addClass('disabled');
        
        const loadingSpinner = `<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>`;

        $("#entrar").html(loadingSpinner);


        $("input.validar").each((index,input)=>{

            input.disabled = true;

        });
    }
    function enableForm() {

        $("#entrar").removeClass('disabled');

        $("#entrar").html('Entrar');

        $("input.validar").each((index,input)=>{

            input.disabled = false;

        });
    }

    function clearForm() {

        $("input.validar").each((index,input)=>{
            
            input.value = '';

        });
    }

    function sendAjax(data) {
   
        $.ajax({
            async: true,
            url: "",
            type: "POST",
            contentType: false,
            data: data,
            processData: false,
            cache: false,
            beforeSend: function() {
                disableForm();
            },
            success: function (response) {
                if (response === "ok") {

                    clearForm();
                    Toast.fire({
                        icon: "success",
                        title: "Inicio exitoso",
                    });
                    setTimeout(() => location = "?pagina=home", 2000);
                } else if (response === "error") {
                    Toast.fire({
                        icon: "error",
                        title: "error de datalogin",
                    });
                }
            },
            error: function ({ responseText , status}) {
                Toast.fire({
                    icon: "error",
                    title: responseText,
                });
            },
            complete: function () {
                enableForm();
            },
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

function clearForm() {
  $("#cedula").val("");
  $("#contrasena").val("");
}

