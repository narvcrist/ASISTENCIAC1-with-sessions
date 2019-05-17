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

 //Fecha Actual
 var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1;
		var yyyy = today.getFullYear();
			if(dd<10){
				dd='0'+dd
			} 
			if(mm<10){
				mm='0'+mm
			}
		var today = dd+'-'+mm+'-'+yyyy; //variable fecha actual
		
 function rangoFecha(input){
    return {
        maxDate: today
		}
    }

    jQuery("#ASIS_HORAINICIO,#ASIS_HORAFIN").datetimepicker({
        dateFormat: 'dd-mm-yy',
        timeFormat: 'hh:mm:ss'
  	//beforeShow: rangoFecha
    });

jQuery(document).ready(function(){
    $("table.formDialog tr th,table.formDialog caption").addClass("ui-widget ui-widget-header");
    $("table.formDialog tr td").addClass("ui-widget ui-widget-content");
    $("table.formDialog tr td.noClass").removeClass("ui-widget ui-widget-content");
    
   var accion='<?php echo $accion;?>';
   var numero='<?php echo !empty($sol->MAT_SECUENCIAL) ? prepCampoMostrar($sol->MAT_SECUENCIAL) : null ; ?>';
   
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

//Manejo de los campos tanto para un nuevo como para editar	
$("#fmateria").validate({
       errorClass: "ui-state-error",
       validClass: "ui-state-highlight",
       wrapper: "span class='ui-extra-validation ui-widget ui-container'",

       submitHandler: function(form){
           $.ajax({
               type: "POST",
               url:  "materia/admMateria/"+accion,
               data: $("#fmateria").serialize(),
               dataType:"json",
               success: function(r){
                       if (r.cod>0) {
                           $("#fmateria").jConfirmacion({
                                        titulo:"Materia: "+r.numero,
                                        mensaje: r.mensaje,
                                        tipoMensaje:"highlight",
                                        ancho: 250,
                                        posicion: "center"
                            });
                           $(":input","#cabecera").attr("disabled", true);
                           if (accion=='n'){
                                $("#MAT_SECUENCIAL").val(r.numero);
								}
                                $("#co_grabar").hide();
                       } else {
                           alert("Error no se ha grabado la información");
                       }
               }
           })// ajax
       },  //submit handler
       rules:{
            materia:{required:true},
             }
     });     
});
</script>
