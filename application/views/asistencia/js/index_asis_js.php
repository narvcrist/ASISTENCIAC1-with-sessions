<script type="text/javascript">
jQuery(document).ready(function(){
    
    var permiso="<?php echo $permiso; ?>";
    
	//Evento para llenar el Grid de los datos a presentar
    jQuery("#itemasis").jqGrid({
          url:"asistencia/getdatosItems",
          datatype: "json",
          colNames:['Num','Sec.','Persona','Hora Ingreso','Fecha Ingreso','Evento','Responsable','Estado'],
          colModel:[
					{name:'COLUMNAS',index:'COLUMNAS',align:"center",width:30,hidden:false},
                    {name:'ASIS_SECUENCIAL',index:'ASIS_SECUENCIAL',align:"center",width:60},
                    {name:'ASIS_SEC_PERSONA',index:'ASIS_SEC_PERSONA',align:"center",  width:200},
					{name:'ASIS_HORAINGRESO',index:'ASIS_HORAINGRESO', width:100,align:"center"},
					{name:'ASIS_FECHAINGRESO',index:'ASIS_FECHAINGRESO', width:100,align:"center"},
                    {name:'ASIS_NOMJOR',index:'ASIS_NOMJOR', width:300,align:"center"},
                    {name:'ASIS_RESPONSABLE',index:'ASIS_RESPONSABLE', width:100,align:"center"},
					{name:'ASIS_ESTADO',index:'ASIS_ESTADO',searchable:false, width:40,align:"center", edittype:'select', formatter:'select', editoptions:{value:"0:<span class='ui-icon ui-icon-circle-check ui-icon-extra'>Activo</span>;1:<span class='ui-icon ui-icon-circle-close ui-icon-extra'>Pasivo</span>"}}
					],
                rowNum:50,
        rowList : [50,100,200,800],
        pager: '#pitemasis',
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
    $("#itemasis").jqGrid('navGrid','#pitemasis',{del:false,add:false,edit:false,refresh:true, search: false},{},{},{},{multipleSearch:true,sopt:['cn','eq']});
    $("#itemasis").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true});
    
	if(permiso==1){
		$("#t_itemasis").append("<button title='Nueva Asistencia' id='agr_asistencia'>Nueva</button>");
		$("#t_itemasis").append("<button title='Editar Asistencia' id='edit_asistencia'>Editar</button>");
		$("#t_itemasis").append("<button title='Ver Asistencia' id='ver_asistencia'>Ver</button>");
		$("#t_itemasis").append("<button title='Eliminar Asistencia' id='anular_asistencia'>Eliminar</button>");
		$("#t_itemasis").append("<button title='Recargar Listado' id='recargar_lista'>Refresh</button>");
		$("#t_itemasis").append("<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>"); 
		$("#t_itemasis").append("<button title='Importar Asistencia Biométrico' id='importar_asistencia'>Subir</button>");
    }else{
		$("#t_itemasis").append("<button title='Recargar Listado' id='recargar_lista'>Refresh</button>");
		$("#t_itemasis").append("<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>"); 
	}		
        $("#itemasis").setGridParam({datatype: "json",url:"asistencia/getdatosItems",postData:{numero:''}});
        $("#itemasis").trigger('reloadGrid');

//Evento para ingresar un nuevo registro    
$("#agr_asistencia").jMostrarNoGrid({           
            id:"#t_itemasis",
            idButton:"#agr_asistencia",
            errorMens:"No se puede mostrar el formulario.",
            url: "asistencia/nuevaAsistencia/",
            titulo: "Agregar Asistencia",
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
                $("#itemasis").setGridParam(
                    {datatype: "json",url:"asistencia/getdatosItems",postData:{numero:$('#ASIS_SECUENCIAL').val()}
                });
                $("#itemasis").trigger('reloadGrid');
            }
});

//Evento para editar un registro        
$("#edit_asistencia").jMostrarNoGrid({
	        id:"#itemasis",
	        idButton:"#edit_asistencia",
	        errorMens:"",
	        url: "asistencia/verAsistencia/e",
	        titulo: "Editar Asistencia",
              //  alto:900,
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-pencil",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                values:function (){
                    var ids= $("#itemasis").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemasis").getCell(ids,"ASIS_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        },
            alCerrar : function() {
                 $("#itemasis").trigger('reloadGrid');
            }
            });

//Evento para ver la informacion de la asistencia			
$("#ver_asistencia").jMostrarNoGrid({
	        id:"#itemasis",
	        idButton:"#ver_asistencia",
	        errorMens:"",
	        url: "asistencia/verAsistencia/v",
	        titulo: "Ver Asistencia",
              //  alto:900,
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-document-b",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                values:function (){
                    var ids= $("#itemasis").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemasis").getCell(ids,"ASIS_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        }
            });
            //Actualiza la asistencia
            $("#itemasis").jRecargar({
                id:"#itemasis",
                showText:true,
                idButton:"#recargar_lista",
                icon:"ui-icon-refresh"
			}); 

             	//Actualiza asistencia
            $("#itemasis").jRecargar({
                id:"#itemasis",
                showText:true,
                idButton:"#recargar_lista",
                icon:"ui-icon-refresh"
			});                
			
//Evento para eliminar asistencia
 $("#anular_asistencia").jMostrarNoGrid({
	        id:"#itemasis",
	        idButton:"#anular_asistencia",
	        errorMens:"",
	        url: "asistencia/verAsistencia/x",
	        titulo: "Eliminar Asistencia",
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-closethick",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                botonSubmit:"Eliminar",
                formAction :function(dialogId){
                    var ids= $("#itemasis").getGridParam("selrow");
                    var numero=$("#itemasis").getCell(ids,"ASIS_SECUENCIAL");
                    $.post("asistencia/anulartoda", {NUMERO:numero},
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
	                            $("#itemasis").trigger("reloadGrid");
	                        }, "json");
                },
                values:function (){
                    var ids= $("#itemasis").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemasis").getCell(ids,"ASIS_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        }
            });

//Cargar Archivo            
$("#importar_asistencia").jMostrarNoGrid({
    id:"#itemasis",
    idButton:"#importar_asistencia",
    errorMens:"No puede cargar un archivo, debe seleccionar un documento.",
    url: "asistencia/cargarAsistencia",
    titulo: "Importación de Asistencia",
    ancho:400,
    posicion: "center",
    showText:true,
    icon:"ui-icon-clock",
    respuestaTipo:"html",
    valuesIsFunction: false,
	values:function (){}
});			
			
});
</script>