<script type="text/javascript">
jQuery(document).ready(function(){
    
    var permiso="<?php echo $permiso; ?>";
    
	//Evevnto para llegar el Grid de los datos a presentar
    jQuery("#itemhor").jqGrid({
          url:"horario/getdatosItems",
          datatype: "json",
          colNames:['Num','Sec.','Persona','Materia','Fecha Ing.','Hora Inicio','Hora Fin',
                    'Dia','Responsable','Estado'],
          colModel:[
					{name:'COLUMNAS',index:'COLUMNAS',align:"center",width:30,hidden:false},
                    {name:'HOR_SECUENCIAL',index:'HOR_SECUENCIAL',align:"center",width:60},
                    {name:'HOR_SEC_PERSONA',index:'HOR_SEC_PERSONA',align:"center", width:200},
                    {name:'HOR_SEC_MATERIA',index:'HOR_SEC_MATERIA',align:"center", search:false, width:200},
					{name:'HOR_FECHAINGRESO',index:'HOR_FECHAINGRESO',align:"center",  width:80},
					{name:'HOR_HORAINICIO',index:'HOR_HORAINICIO',align:"center",  width:100},
                    {name:'HOR_HORAFIN',index:'HOR_HORAFIN',align:"center",  width:100},					
					{name:'HOR_DIA',index:'HOR_DIA', width:100,align:"center"},
					{name:'HOR_RESPONSABLE',index:'HOR_RESPONSABLE', width:100,align:"center"},
					{name:'HOR_ESTADO',index:'HOR_ESTADO',searchable:false, width:40,align:"center", edittype:'select', formatter:'select', editoptions:{value:"0:<span class='ui-icon ui-icon-circle-check ui-icon-extra'>Activo</span>;1:<span class='ui-icon ui-icon-circle-close ui-icon-extra'>Pasivo</span>"}}
                ],
        rowNum:50,
        rowList : [50,100,200,800], 
        pager: '#pitemhor',
        sortname: '',
        viewrecords: true,
        height:350,
        width:1000,
        shrinkToFit:false,
        sortorder: "asc",
        mtype:"POST",
        toolbar: [true,"top"]
    });
    
	//Botones que contendran cada evento o acción
    $("#itemhor").jqGrid('navGrid','#pitemhor',{del:false,add:false,edit:false,refresh:true, search: false},{},{},{},{multipleSearch:true,sopt:['cn','eq']});
    $("#itemhor").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true});
    if(permiso==1){
        $("#t_itemhor").append("<button title='Nuevo Horario' id='agr_horario'>Nuevo</button>");
        $("#t_itemhor").append("<button title='Editar Horario' id='edit_horario'>Editar</button>");
        $("#t_itemhor").append("<button title='Ver Horario' id='ver_horario'>Ver</button>");
        $("#t_itemhor").append("<button title='Eliminar Horario' id='anular_horario'>Eliminar</button>");  
        $("#t_itemhor").append("<button title='Recargar Listado' id='recargar_lista'>Refresh</button>");
        $("#t_items").append("<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>"); 
    }else{
        $("#t_items").append("<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>");
        $("#t_itemhor").append("<button title='Recargar Listado' id='recargar_lista'>Refresh</button>");
    }		
        $("#itemhor").setGridParam({datatype: "json",url:"horario/getdatosItems",postData:{numero:''}});
        $("#itemhor").trigger('reloadGrid');

//Evento para ingresar un nuevo registro    
$("#agr_horario").jMostrarNoGrid({
            id:"#t_itemhor",
            idButton:"#agr_horario",
            errorMens:"No se puede mostrar el formulario.",
            url: "horario/nuevoHorario/",
            titulo: "Agregar un Horario",
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
                $("#itemhor").setGridParam({datatype: "json",url:"horario/getdatosItems",postData:{numero:$('#HOR_SECUENCIAL').val()}});
                $("#itemhor").trigger('reloadGrid');
            }
        });

//Evento para editar un registro        
$("#edit_horario").jMostrarNoGrid({
	        id:"#itemhor",
	        idButton:"#edit_horario",
	        errorMens:"",
	        url: "horario/verHorario/e",
	        titulo: "Editar Horario",
              //  alto:900,
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-pencil",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                values:function (){
                    var ids= $("#itemhor").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemhor").getCell(ids,"HOR_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        },
            alCerrar : function() {
                 $("#itemhor").trigger('reloadGrid');
            }
            });

//Evento para ver la informacion de un persona			
$("#ver_horario").jMostrarNoGrid({
	        id:"#itemhor",
	        idButton:"#ver_horario",
	        errorMens:"",
	        url: "horario/verHorario/v",
	        titulo: "Ver Horario",
              //  alto:900,
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-document-b",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                values:function (){
                    var ids= $("#itemhor").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemhor").getCell(ids,"HOR_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        }
            });
            //Actualiza la persona
            $("#itemhor").jRecargar({
                id:"#itemhor",
                showText:true,
                idButton:"#recargar_lista",
                icon:"ui-icon-refresh"
			});            
			
			//Actualiza la persona
            $("#itemhor").jRecargar({
                id:"#itemhor",
                showText:true,
                idButton:"#recargar_lista",
                icon:"ui-icon-refresh"
			});

//Evento para eliminar una persona
 $("#anular_horario").jMostrarNoGrid({
	        id:"#itemhor",
	        idButton:"#anular_horario",
	        errorMens:"",
	        url: "horario/verHorario/x",
	        titulo: "Eliminar Horario",
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-closethick",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                botonSubmit:"Eliminar",
                formAction :function(dialogId){
                    var ids= $("#itemhor").getGridParam("selrow");
                    var numero=$("#itemhor").getCell(ids,"HOR_SECUENCIAL");
                    $.post("horario/anulartoda", {NUMERO:numero},
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
	                            $("#itemhor").trigger("reloadGrid");
	                        }, "json");
                },
                values:function (){
                    var ids= $("#itemhor").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemhor").getCell(ids,"HOR_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        }
            });
	
});
</script>
