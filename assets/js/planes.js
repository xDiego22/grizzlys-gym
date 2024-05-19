var tabla;
var id_plan;

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

     tabla = $("#tablePlans").DataTable({
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
         data: { accion: "getPlans" },
       },
       columns: [
         { data: "id" },
         { data: "nombre" },
         { data: "valor" },
         { data: "descripcion" },
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
           target: 2,
           render: function (data) {
             return `${parseFloat(data)}$`;
           },
         },

         { responsivePriority: 1, targets: 1 },
         { responsivePriority: 1, targets: -1 },
         { responsivePriority: 1, targets: 2 },
         { responsivePriority: 2, targets: 3 },
         { responsivePriority: 3, targets: 0 },
       ],
       lengthMenu: [
         [10, 15, 20],
         [10, 15, 20],
       ],
     });
    //validaciones keypress keyup


    $("#nombre").keypress((event) =>
      validateKeyPress(event, /^[\w\sáéíóúüñÑÁÉÍÓÚÜ0-9]{0,50}$/)
    );
    $("#nombre").keyup(() => validateKeyUp($("#nombre"), /^[\w\sáéíóúüñÑÁÉÍÓÚÜ0-9]{1,50}$/));
    
    $("#precio").keypress((event) => validateKeyPress(event, /^[0-9.,]{0,10}$/));
    
    $("#precio").keyup(() => {

        validateKeyUp($("#precio"), /^\d+(\.\d)?$/);

        $("#precio").val() > 300 || $("#precio").val() === ""
          ? $("#precio").addClass("is-invalid").removeClass("is-valid")
          : $("#precio").addClass("is-valid").removeClass("is-invalid");
    });
    
    $("#descripcion").keypress((event) =>
        validateKeyPress(event, /^[\w\sáéíóúüñÑÁÉÍÓÚÜ0-9.,\-#%+]{0,100}$/)
    );

    $("#descripcion").keyup(() => validateKeyUp($("#descripcion"), /^[\w\sáéíóúüñÑÁÉÍÓÚÜ0-9.,\-#%+]{0,100}$/));


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

            if (btn_clicked === "editar") data.append("id", id_plan);

            data.append("accion", btn_clicked);
            data.append("nombre", $("#nombre").val());
            data.append("precio", $("#precio").val());
            data.append("descripcion", $("#descripcion").val());

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

function disableForm() {
  $("#registrar").addClass("disabled");
  $("#editar").addClass("disabled");

  const loadingSpinner = `<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>`;

  $("#registrar").html(loadingSpinner);
  $("#editar").html(loadingSpinner);

  $(".validar").each((index, input) => {
    input.disabled = true;
  });
}
function enableForm() {
  $("#registrar").removeClass("disabled");
  $("#editar").removeClass("disabled");

  $("#registrar").html("Guardar");
  $("#editar").html("Editar");

  $(".validar").each((index, input) => {
    input.disabled = false;
  });
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

}

function modalEditar(fila) {
    $("#modalGestionLabel").html("Editar");
    $("#editar").show();
    $("#registrar").hide();
  
    clearForm();

    let linea = $(fila).closest("tr");

    id_plan = $(linea).find("td:eq(0)").text();
    $("#nombre").val( $(linea).find("td:eq(1)").text() );
    $("#precio").val( $(linea).find("td:eq(2)").text().replace( "$" , "" ) );
    $("#descripcion").val( $(linea).find("td:eq(3)").text() );

    
    validateKeyUp($("#nombre"), /^[\w\sáéíóúüñÑÁÉÍÓÚÜ0-9]{1,50}$/);
    validateKeyUp($("#precio"), /^\d+(\.\d)?$/);
    validateKeyUp($("#descripcion"), /^[\w\sáéíóúüñÑÁÉÍÓÚÜ0-9.,\-#%+]{0,100}$/);

}

function clearForm() {
  $(".validar").each((index, input) => {
    input.value = "";
    input.classList.remove("is-valid");
    input.classList.remove("is-invalid");
  });
}