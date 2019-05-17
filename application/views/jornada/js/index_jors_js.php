<script type="text/javascript">
jQuery(document).ready(function(){
    
    var permiso="<?php echo $permiso; ?>";
    
	//Evevnto para llenar el Grid de los datos a presentar
    jQuery("#itemjor").jqGrid({
          url:"jornada/getdatosItems",
          datatype: "json",
          colNames:['Num','Sec.','Nombre','Hora Inicio','Hora Fin','Estado'],
          colModel:[
					{name:'COLUMNAS',index:'COLUMNAS',align:"center",width:30,hidden:false},
                    {name:'JOR_SECUENCIAL',index:'JOR_SECUENCIAL',align:"center",width:60},
					{name:'JOR_NOMBRE',index:'JOR_NOMBRE',align:"center",  width:250},
					{name:'JOR_HORAINICIO',index:'JOR_HORAINICIO', width:150,align:"center"},
					{name:'JOR_HORAFIN',index:'JOR_HORAFIN', width:150,align:"center"},
					{name:'JOR_ESTADO',index:'JOR_ESTADO',searchable:false, width:40,align:"center", edittype:'select', formatter:'select', editoptions:{value:"0:<span class='ui-icon ui-icon-circle-check ui-icon-extra'>Activo</span>;1:<span class='ui-icon ui-icon-circle-close ui-icon-extra'>Pasivo</span>"}}
                ],
        rowNum:50,
        rowList : [50,100,200,800],
        pager: '#pitemjor',
        sortname: 'JOR_SECUENCIAL',
        viewrecords: true,
        height:350,
        width:1000,
        shrinkToFit:false,
        sortorder: "asc",
        mtype:"POST",
        toolbar: [true,"top"]
    });
    
	//Botones que contendran cada evento o acción
    $("#itemjor").jqGrid('navGrid','#pitemjor',{del:false,add:false,edit:false,refresh:true, search: false},{},{},{},{multipleSearch:true,sopt:['cn','eq']});
    $("#itemjor").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true});
    if(permiso==1){
        $("#t_itemjor").append("<button title='Nueva Jornada' id='agr_jornada'>Nueva</button>");
        $("#t_itemjor").append("<button title='Editar Jornada' id='edit_jornada'>Editar</button>");
        $("#t_itemjor").append("<button title='Ver Jornada' id='ver_jornada'>Ver</button>");
        $("#t_itemjor").append("<button title='Eliminar Jornada' id='anular_jornada'>Eliminar</button>");  
        $("#t_itemjor").append("<button title='Recargar Listado' id='recargar_lista'>Refresh</button>");
        $("#t_itemjor").append("<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>"); 
    }else{
        $("#t_itemjor").append("<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>"); 
        $("#t_itemjor").append("<button title='Recargar Listado' id='recargar_lista'>Refresh</button>");
    }            
        $("#itemjor").setGridParam({datatype: "json",url:"jornada/getdatosItems",postData:{numero:''}});
        $("#itemjor").trigger('reloadGrid');

//Evento para ingresar un nuevo registro    
$("#agr_jornada").jMostrarNoGrid({
            id:"#t_itemjor",
            idButton:"#agr_jornada",
            errorMens:"No se puede mostrar el formulario.",
            url: "jornada/nuevaJornada/",
            titulo: "Agregar una Jornada",
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
                $("#itemjor").setGridParam({datatype: "json",url:"jornada/getdatosItems",postData:{numero:$('#JOR_SECUENCIAL').val()}});
                $("#itemjor").trigger('reloadGrid');
            }
        });

//Evento para editar un registro        
$("#edit_jornada").jMostrarNoGrid({
	        id:"#itemjor",
	        idButton:"#edit_jornada",
	        errorMens:"",
	        url: "jornada/verJornada/e",
	        titulo: "Editar Jornada",
              //  alto:900,
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-pencil",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                values:function (){
                    var ids= $("#itemjor").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemjor").getCell(ids,"JOR_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        },
            alCerrar : function() {
                 $("#itemjor").trigger('reloadGrid');
            }
            });

//Evento para ver la informacion de un jornada			
$("#ver_jornada").jMostrarNoGrid({
	        id:"#itemjor",
	        idButton:"#ver_jornada",
	        errorMens:"",
	        url: "jornada/verJornada/v",
	        titulo: "Ver Jornada",
              //  alto:900,
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-document-b",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                values:function (){
                    var ids= $("#itemjor").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemjor").getCell(ids,"JOR_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        }
            });
            //Actualiza la jornada
            $("#itemjor").jRecargar({
                id:"#itemjor",
                showText:true,
                idButton:"#recargar_lista",
                icon:"ui-icon-refresh"
			});            
			
			//Actualiza la jornada
            $("#itemjor").jRecargar({
                id:"#itemjor",
                showText:true,
                idButton:"#recargar_lista",
                icon:"ui-icon-refresh"
			});

//Evento para eliminar una jornada
 $("#anular_jornada").jMostrarNoGrid({
	        id:"#itemjor",
	        idButton:"#anular_jornada",
	        errorMens:"",
	        url: "jornada/verJornada/x",
	        titulo: "Eliminar Jornada",
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-closethick",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                botonSubmit:"Eliminar",
                formAction :function(dialogId){
                    var ids= $("#itemjor").getGridParam("selrow");
                    var numero=$("#itemjor").getCell(ids,"JOR_SECUENCIAL");
                    $.post("jornada/anulartoda", {NUMERO:numero},
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
	                            $("#itemjor").trigger("reloadGrid");
	                        }, "json");
                },
                values:function (){
                    var ids= $("#itemjor").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemjor").getCell(ids,"JOR_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        }
            });
	
});
</script>
