<style>
  .ui-autocomplete {
    max-height: 100px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  }
  /* IE 6 doesn't support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete {
    height: 100px;
  }
  </style>
<script type="text/javascript">

jQuery(document).ready(function(){
    $("table.formDialog tr th,table.formDialog caption").addClass("ui-widget ui-widget-header");
    $("table.formDialog tr td").addClass("ui-widget ui-widget-content");
    $("table.formDialog tr td.noClass").removeClass("ui-widget ui-widget-content");
    
   var accion='<?php echo $accion;?>';
   var numero='<?php echo !empty($sol->PERXJOR_SECUENCIAL) ? prepCampoMostrar($sol->PERXJOR_SECUENCIAL) : null ; ?>';
   
   //Acciones que se manejan en base a los eventos
   if (accion=='n'){ //nueva
        $('#cabecera :input').attr('disabled', false);
        $('#co_grabar').attr('disabled',false);
    } else {
        if (accion=='e'){ //edicion
            $('#co_grabar').attr('disabled',false);            
        }else{ //ver
          $('#cabecera :input').attr('disabled', true);  
        }
    }
    
	//Botón para guardar
    $("#co_grabar").button({
        icons:{
            primary: ""
        }
    });
	
	//Funcion para validar correo		
	function validarEmail( email ) {
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ( !expr.test(email) ){
		$('#PER_EMAIL').val('');
        alert("!!!...Error: La dirección de correo: " + email + ", es incorrecta...!!!");
	}
		}
		
	$("#PER_EMAIL").change(function() {
        validarEmail($('#PER_EMAIL').val());        
    })
	
    
//Manejo de los campos tanto para un nuevo como para editar	
$("#fperxjor").validate({
       errorClass: "ui-state-error",
       validClass: "ui-state-highlight",
       wrapper: "span class='ui-extra-validation ui-widget ui-container'",

       submitHandler: function(form){
           $.ajax({
               type: "POST",
               url:  "perxjor/admPerxjor/"+accion,
               data: $("#fperxjor").serialize(),
               dataType:"json",
               success: function(r){
                       if (r.cod>0) {
                           $("#fperxjor").jConfirmacion({
                                        titulo:"Perxjor: "+r.numero,
                                        mensaje: r.mensaje,
                                        tipoMensaje:"highlight",
                                        ancho: 250,
                                        posicion: "center"
                            });
                           $(":input","#cabecera").attr("disabled", true);
                           if (accion=='n'){
                                $("#PERXJOR_SECUENCIAL").val(r.numero);
								}
                                $("#co_grabar").hide();
                       } else {
                           alert("Error no se ha grabado la información");
                       }
               }
           })// ajax
       },  //submit handler
       rules:{
            jornada:{required:true},
            persona:{required:true}
             }
     });     
});
</script>
