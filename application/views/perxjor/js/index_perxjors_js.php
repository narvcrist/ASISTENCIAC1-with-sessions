<script type="text/javascript">
jQuery(document).ready(function(){
    
    var permiso="<?php echo $permiso; ?>";
    
	//Evento para llenar el Grid de los datos a presentar
    jQuery("#itemperxjor").jqGrid({
          url:"perxjor/getdatosItems",
          datatype: "json",
          colNames:['Num','Sec.','Jornada','Persona','Estado'],
          colModel:[
					{name:'COLUMNAS',index:'COLUMNAS',align:"center",width:30,hidden:false},
                    {name:'PERXJOR_SECUENCIAL',index:'PERXJOR_SECUENCIAL',align:"center",width:60},
                    {name:'PERXJOR_SEC_jornada',index:'PERXJOR_SEC_jornada',align:"center", width:150},
					{name:'PERXJOR_SEC_PERSONA',index:'PERXJOR_SEC_PERSONA',align:"center",  width:200},
					{name:'PERXJOR_ESTADO',index:'PERXJOR_ESTADO',searchable:false, width:40,align:"center", edittype:'select', formatter:'select', editoptions:{value:"0:<span class='ui-icon ui-icon-circle-check ui-icon-extra'>Activo</span>;1:<span class='ui-icon ui-icon-circle-close ui-icon-extra'>Pasivo</span>"}}
                ],
        rowNum:50,
        rowList : [50,100,200,800],
        pager: '#pitemperxjor',
        sortname: 'PERXJOR_SECUENCIAL',
        viewrecords: true,
        height:350,
        width:1000,
        shrinkToFit:false,
        sortorder: "asc",
        mtype:"POST",
        toolbar: [true,"top"]
    });
    
	//Botones que contendran cada evento o acción
    $("#itemperxjor").jqGrid('navGrid','#pitemperxjor',{del:false,add:false,edit:false,refresh:true, search: false},{},{},{},{multipleSearch:true,sopt:['cn','eq']});
    $("#itemperxjor").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true});
    if(permiso==1){
        $("#t_itemperxjor").append("<button title='Nueva' id='agr_perxjor'>Nueva</button>");
        $("#t_itemperxjor").append("<button title='Editar' id='edit_perxjor'>Editar</button>");
        $("#t_itemperxjor").append("<button title='Ver' id='ver_perxjor'>Ver</button>");
        $("#t_itemperxjor").append("<button title='Eliminar' id='anular_perxjor'>Eliminar</button>");  
        $("#t_itemperxjor").append("<button title='Recargar Listado' id='recargar_lista'>Refresh</button>");
        $("#t_itemperxjor").append("<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>"); 
    }else{
        $("#t_itemperxjor").append("<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>"); 
        $("#t_itemperxjor").append("<button title='Recargar Listado' id='recargar_lista'>Refresh</button>");
    }		
        $("#itemperxjor").setGridParam({datatype: "json",url:"perxjor/getdatosItems",postData:{numero:''}});
        $("#itemperxjor").trigger('reloadGrid');

//Evento para ingresar un nuevo registro    
$("#agr_perxjor").jMostrarNoGrid({
            id:"#t_itemperxjor",
            idButton:"#agr_perxjor",
            errorMens:"No se puede mostrar el formulario.",
            url: "perxjor/nuevaPerxjor/",
            titulo: "Agregar Estudiante por Jornada",
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
                $("#itemperxjor").setGridParam({datatype: "json",url:"perxjor/getdatosItems",postData:{numero:$('#PERXJOR_SECUENCIAL').val()}});
                $("#itemperxjor").trigger('reloadGrid');
            }
        });

//Evento para editar un registro        
$("#edit_perxjor").jMostrarNoGrid({
	        id:"#itemperxjor",
	        idButton:"#edit_perxjor",
	        errorMens:"",
	        url: "perxjor/verPerxjor/e",
	        titulo: "Editar Estudiante por Jornada",
              //  alto:900,
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-pencil",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                values:function (){
                    var ids= $("#itemperxjor").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemperxjor").getCell(ids,"PERXJOR_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        },
            alCerrar : function() {
                 $("#itemperxjor").trigger('reloadGrid');
            }
            });

//Evento para ver la informacion de un persona para la jornada			
$("#ver_perxjor").jMostrarNoGrid({
	        id:"#itemperxjor",
	        idButton:"#ver_perxjor",
	        errorMens:"",
	        url: "perxjor/verPerxjor/v",
	        titulo: "Ver Estudiante por Jornada",
              //  alto:900,
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-document-b",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                values:function (){
                    var ids= $("#itemperxjor").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemperxjor").getCell(ids,"PERXJOR_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        }
            });
            //Actualiza la persona para la jornada
            $("#itemperxjor").jRecargar({
                id:"#itemperxjor",
                showText:true,
                idButton:"#recargar_lista",
                icon:"ui-icon-refresh"
			});            
			
			//Actualiza la persona para la jornada
            $("#itemperxjor").jRecargar({
                id:"#itemperxjor",
                showText:true,
                idButton:"#recargar_lista",
                icon:"ui-icon-refresh"
			});

//Evento para eliminar una persona para la jornada
 $("#anular_perxjor").jMostrarNoGrid({
	        id:"#itemperxjor",
	        idButton:"#anular_perxjor",
	        errorMens:"",
	        url: "perxjor/verPerxjor/x",
	        titulo: "Eliminar Estudiante por Jornada",
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-closethick",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                botonSubmit:"Eliminar",
                formAction :function(dialogId){
                    var ids= $("#itemperxjor").getGridParam("selrow");
                    var numero=$("#itemperxjor").getCell(ids,"PERXJOR_SECUENCIAL");
                    $.post("perxjor/anulartoda", {NUMERO:numero},
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
	                            $("#itemperxjor").trigger("reloadGrid");
	                        }, "json");
                },
                values:function (){
                    var ids= $("#itemperxjor").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemperxjor").getCell(ids,"PERXJOR_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        }
            });
	
});
</script>
