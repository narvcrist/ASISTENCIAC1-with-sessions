<script type="text/javascript">
jQuery(document).ready(function(){
    
    var permiso="<?php echo $permiso; ?>";
    
	//Evevnto para llegar el Grid de los datos a presentar
    jQuery("#itemper").jqGrid({
          url:"persona/getdatosItems",
          datatype: "json",
          colNames:['Num','Sec.','Fecha Ing.','Cedula','Nombres','Apellidos','Responsable','Estado'],
          colModel:[
					{name:'COLUMNAS',index:'COLUMNAS',align:"center",width:30,hidden:false},
                    {name:'PER_SECUENCIAL',index:'PER_SECUENCIAL',align:"center",width:50},
					{name:'PER_FECHAINGRESO',index:'PER_FECHAINGRESO',align:"center",  width:80},
					{name:'PER_CEDULA',index:'PER_CEDULA',align:"center",  width:80},					
					{name:'PER_NOMBRES',index:'PER_NOMBRES', width:150,align:"center"},
					{name:'PER_APELLIDOS',index:'PER_APELLIDOS', width:150,align:"center"},
					{name:'PER_RESPONSABLE',index:'PER_RESPONSABLE', width:100,align:"center"},
					{name:'PER_ESTADO',index:'PER_ESTADO',search:false, width:40,align:"center", edittype:'select', formatter:'select', editoptions:{value:"0:<span class='ui-icon ui-icon-circle-check ui-icon-extra'>Activo</span>;1:<span class='ui-icon ui-icon-circle-close ui-icon-extra'>Pasivo</span>"}}
                ],
        rowNum:50,
        rowList : [50,100,200,800],
        pager: '#pitemper',
        sortname: 'PER_APELLIDOS,PER_CEDULA',
        viewrecords: true,
        height:350,
        width:1000,
        shrinkToFit:false,
        sortorder: "asc",
        mtype:"POST",
        toolbar: [true,"top"]
    });
    
	//Botones que contendran cada evento o acción
    $("#itemper").jqGrid('navGrid','#pitemper',{del:false,add:false,edit:false,refresh:true, search: false},{},{},{},{multipleSearch:true,sopt:['cn','eq']});
    $("#itemper").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true});
    if(permiso==1){
        $("#t_itemper").append("<button title='Nueva Persona' id='agr_persona'>Nueva</button>");
        $("#t_itemper").append("<button title='Editar Persona' id='edit_persona'>Editar</button>");
        $("#t_itemper").append("<button title='Ver Persona' id='ver_persona'>Ver</button>");
        $("#t_itemper").append("<button title='Eliminar Persona' id='anular_persona'>Eliminar</button>");
        $("#t_itemper").append("<button title='Recargar Listado' id='recargar_lista'>Refresh</button>");
        $("#t_itemper").append("<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>");
		$("#t_itemper").append("<button title='Reporte General de Asistencia' id='completa_persona'>Asistencia Completa</button>");
		$("#t_itemper").append("<button title='Reporte Individual de Asistencia' id='individual_persona'>Asistencia Individual</button>");
    }else{
        $("#t_itemper").append("<button title='Recargar Listado' id='recargar_lista'>Refresh</button>");
		$("#t_itemper").append("<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>"); 
		$("#t_itemper").append("<button title='Reporte Individual de Asistencia' id='individual_persona'>Asistencia Individual</button>");
        
    }    		
        $("#itemper").setGridParam({datatype: "json",url:"persona/getdatosItems",postData:{numero:''}});
        $("#itemper").trigger('reloadGrid');

//Evento para ingresar un nuevo registro    
$("#agr_persona").jMostrarNoGrid({
            id:"#t_itemper",
            idButton:"#agr_persona",
            errorMens:"No se puede mostrar el formulario.",
            url: "persona/nuevaPersona/",
            titulo: "Agregar una Persona",
            alto:900,
            ancho: 1024,
            posicion: "top",
            showText:true,
            icon:"ui-icon-circle-plus",
            respuestaTipo:"html",
            values:{
                ids:null
            },
            alCerrar : function() {
                $("#itemper").setGridParam({datatype: "json",url:"persona/getdatosItems",postData:{numero:$('#PER_SECUENCIAL').val()}});
                $("#itemper").trigger('reloadGrid');
            }
        });

//Evento para editar un registro        
$("#edit_persona").jMostrarNoGrid({
	        id:"#itemper",
	        idButton:"#edit_persona",
	        errorMens:"",
	        url: "persona/verPersona/e",
	        titulo: "Editar Persona",
              //  alto:900,
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-pencil",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                values:function (){
                    var ids= $("#itemper").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemper").getCell(ids,"PER_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        },
            alCerrar : function() {
                 $("#itemper").trigger('reloadGrid');
            }
            });

//Evento para ver la informacion de un persona			
$("#ver_persona").jMostrarNoGrid({
	        id:"#itemper",
	        idButton:"#ver_persona",
	        errorMens:"",
	        url: "persona/verPersona/v",
	        titulo: "Ver Persona",
              //  alto:900,
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-document-b",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                values:function (){
                    var ids= $("#itemper").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemper").getCell(ids,"PER_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        }
            });
            //Actualiza la persona
            $("#itemper").jRecargar({
                id:"#itemper",
                showText:true,
                idButton:"#recargar_lista",
                icon:"ui-icon-refresh"
			});            
			

//Evento para eliminar una persona
 $("#anular_persona").jMostrarNoGrid({
	        id:"#itemper",
	        idButton:"#anular_persona",
	        errorMens:"",
	        url: "persona/verPersona/x",
	        titulo: "Eliminar Persona",
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-closethick",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                botonSubmit:"Eliminar",
                formAction :function(dialogId){
                    var ids= $("#itemper").getGridParam("selrow");
                    var numero=$("#itemper").getCell(ids,"PER_SECUENCIAL");
                    $.post("persona/anulartoda", {NUMERO:numero},
	                        function(data){
	                            $(dialogId).html(data.mensaje);
	                            $(dialogId).dialog({
	                            buttons: {
	                                "Cerrar": function(){
	                                    $(this).dialog("destroy");
	                                    $(dialogId).remove();
	                                    }
	                                }
	                            });
	                            $("#itemper").trigger("reloadGrid");
	                        }, "json");
                },
                values:function (){
                    var ids= $("#itemper").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemper").getCell(ids,"PER_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        }
            });
			
//Generar PDF Asistencia General		
$("#completa_persona").button({
            icons:{
                primary: "ui-icon-script"
            }
        }).click(function(evento){
                var today = new Date();
				var dd = today.getDate();
				var mm = today.getMonth()+1; //January is 0!
				var yyyy = today.getFullYear();
				if(dd<10){
					dd='0'+dd;
				}
				if(mm<10){
					mm='0'+mm;
				}
				var today = yyyy+'-'+mm;
				
				var fechaAsis = prompt("Ingrese Fecha de Asistencia(yyyy-mm): ", today);
				var countFecha = fechaAsis.length;
				var expr=/^\d{4}[\/\-](0?[1-9]|1[012])$/;
				if(fechaAsis===""){
						alert("!!...Debe Ingresar la Fecha de Asistencia(yyyy-mm)...!!");
					}else if(countFecha!=7){
						alert("!!..Ingrese La Fecha En Formato => yyyy-mm");
					}else if(!expr.test(fechaAsis)){
						alert("!!..Ingrese La Fecha En Formato => yyyy-mm");
					}else{
					$.post("persona/fmtAsisGeneral", {FECHAASIS:fechaAsis},
								function(data){
										 var dialogId= "dialog_"+$().jRand(10,100);
										$("#d_persona").append("<div id='"+dialogId+"'></div>");
										$("#"+dialogId).html(data.mensaje);
										$("#"+dialogId).dialog(
										{
												modal:true,
												position: "top",
												width:1040,
												height:780,
												closeOnEscape: false,
												beforeclose : function() {
													 $('#'+dialogId).dialog("destroy");
													 $('#'+dialogId).remove();
												}
										}
											);
									}
									, "json");
				}
        });
		
//Generar PDF Asistencia Individual		
$("#individual_persona").button({
            icons:{
                primary: "ui-icon-person"
            }
        }).click(function(evento){
           var ids=$("#itemper").getGridParam("selrow");
            if(ids == null) {
                alert ("Seleccione un Estudiante para Imprimir Asistenacia...");
            }else {
                var today = new Date();
				var dd = today.getDate();
				var mm = today.getMonth()+1; //January is 0!
				var yyyy = today.getFullYear();
				if(dd<10){
					dd='0'+dd;
				}
				if(mm<10){
					mm='0'+mm;
				}
				var today = yyyy+'-'+mm;
				
				var secEmpleado=$("#itemper").getCell(ids,'PER_SECUENCIAL')
				var fechaAsis = prompt("Ingrese Fecha de Asistencia(yyyy-mm): ", today);
				var countFecha = fechaAsis.length;
				var expr=/^\d{4}[\/\-](0?[1-9]|1[012])$/;
				if(fechaAsis===""){
						alert("!!...Debe Ingresar la Fecha de Asistencia(yyyy-mm)...!!");
					}else if(countFecha!=7){
						alert("!!..Ingrese La Fecha En Formato => yyyy-mm");
					}else if(!expr.test(fechaAsis)){
						alert("!!..Ingrese La Fecha En Formato => yyyy-mm");
					}else{
					$.post("persona/fmtAsisIndividual", {numero:secEmpleado,FECHAASIS:fechaAsis},
								function(data){
										 var dialogId= "dialog_"+$().jRand(10,100);
										$("#d_persona").append("<div id='"+dialogId+"'></div>");
										$("#"+dialogId).html(data.mensaje);
										$("#"+dialogId).dialog(
										{
												modal:true,
												position: "top",
												width:1040,
												height:780,
												closeOnEscape: false,
												beforeclose : function() {
													 $('#'+dialogId).dialog("destroy");
													 $('#'+dialogId).remove();
												}
										}
											);
									}
									, "json");
				}				
            }
        });		
	
});
</script>
