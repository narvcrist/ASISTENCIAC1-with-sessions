<script type="text/javascript">
jQuery(document).ready(function(){
    
    var permiso="<?php echo $permiso; ?>";
    
	//Evevnto para llenar el Grid de los datos a presentar
    jQuery("#itemmat").jqGrid({
          url:"materia/getdatosItems",
          datatype: "json",
          colNames:['Num','Sec.','Nombre','Tiempo Duracion','Porcentaje Aprobacion','Carga Horaria','Fecha Ingreso',
					'Responsable','Estado'],
          colModel:[
					{name:'COLUMNAS',index:'COLUMNAS',align:"center",width:30,hidden:false},
                    {name:'MAT_SECUENCIAL',index:'MAT_SECUENCIAL',align:"center",width:60},
					{name:'MAT_NOMBRE',index:'MAT_NOMBRE',align:"center",  width:200},					
					{name:'MAT_TIEMPODURACION',index:'MAT_TIEMPODURACION', width:100,align:"center"},
					{name:'MAT_PORCENTAJE',index:'MAT_PORCENTAJE', width:150,align:"center"},
					{name:'MAT_CARGAHORARIA',index:'MAT_CARGAHORARIA', width:100,align:"center"},
					{name:'MAT_FECHAINGRESO',index:'MAT_FECHAINGRESO', width:100,align:"center"},
					{name:'MAT_RESPONSABLE',index:'MAT_RESPONSABLE', width:100,align:"center"},
					{name:'MAT_ESTADO',index:'MAT_ESTADO',searchable:false, width:40,align:"center", edittype:'select', formatter:'select', editoptions:{value:"0:<span class='ui-icon ui-icon-circle-check ui-icon-extra'>Activo</span>;1:<span class='ui-icon ui-icon-circle-close ui-icon-extra'>Pasivo</span>"}}
                ],
        rowNum:50,
        rowList : [50,100,200,800],
        pager: '#pitemmat',
        sortname: 'MAT_SECUENCIAL',
        viewrecords: true,
        height:350,
        width:1000,
        shrinkToFit:false,
        sortorder: "asc",
        mtype:"POST",
        toolbar: [true,"top"]
    });
    
	//Botones que contendran cada evento o acción
    $("#itemmat").jqGrid('navGrid','#pitemmat',{del:false,add:false,edit:false,refresh:true, search: false},{},{},{},{multipleSearch:true,sopt:['cn','eq']});
    $("#itemmat").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true});
    if(permiso==1){
        $("#t_itemmat").append("<button title='Nueva Materia' id='agr_materia'>Nueva</button>");
        $("#t_itemmat").append("<button title='Editar Materia' id='edit_materia'>Editar</button>");
        $("#t_itemmat").append("<button title='Ver Materia' id='ver_materia'>Ver</button>");
        $("#t_itemmat").append("<button title='Eliminar Materia' id='anular_materia'>Eliminar</button>");  
        $("#t_itemmat").append("<button title='Recargar Listado' id='recargar_lista'>Refresh</button>");
        $("#t_itemmat").append("<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>"); 
    }else{
        $("#t_itemmat").append("<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>");
        $("#t_itemmat").append("<button title='Recargar Listado' id='recargar_lista'>Refresh</button>");
    }    		
        $("#itemmat").setGridParam({datatype: "json",url:"materia/getdatosItems",postData:{numero:''}});
        $("#itemmat").trigger('reloadGrid');

//Evento para ingresar un nuevo registro    
$("#agr_materia").jMostrarNoGrid({
            id:"#t_itemmat",
            idButton:"#agr_materia",
            errorMens:"No se puede mostrar el formulario.",
            url: "materia/nuevaMateria/",
            titulo: "Agregar una Materia",
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
                $("#itemmat").setGridParam({datatype: "json",url:"materia/getdatosItems",postData:{numero:$('#MAT_SECUENCIAL').val()}});
                $("#itemmat").trigger('reloadGrid');
            }
        });

//Evento para editar un registro        
$("#edit_materia").jMostrarNoGrid({
	        id:"#itemmat",
	        idButton:"#edit_materia",
	        errorMens:"",
	        url: "materia/verMateria/e",
	        titulo: "Editar Materia",
              //  alto:900,
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-pencil",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                values:function (){
                    var ids= $("#itemmat").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemmat").getCell(ids,"MAT_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        },
            alCerrar : function() {
                 $("#itemmat").trigger('reloadGrid');
            }
            });

//Evento para ver la informacion de un materia			
$("#ver_materia").jMostrarNoGrid({
	        id:"#itemmat",
	        idButton:"#ver_materia",
	        errorMens:"",
	        url: "materia/verMateria/v",
	        titulo: "Ver Materia",
              //  alto:900,
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-document-b",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                values:function (){
                    var ids= $("#itemmat").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemmat").getCell(ids,"MAT_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        }
            });
            //Actualiza la materia
            $("#itemmat").jRecargar({
                id:"#itemmat",
                showText:true,
                idButton:"#recargar_lista",
                icon:"ui-icon-refresh"
			});            
			
			//Actualiza la materia
            $("#itemmat").jRecargar({
                id:"#itemmat",
                showText:true,
                idButton:"#recargar_lista",
                icon:"ui-icon-refresh"
			});

//Evento para eliminar una materia
 $("#anular_materia").jMostrarNoGrid({
	        id:"#itemmat",
	        idButton:"#anular_materia",
	        errorMens:"",
	        url: "materia/verMateria/x",
	        titulo: "Eliminar Materia",
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-closethick",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                botonSubmit:"Eliminar",
                formAction :function(dialogId){
                    var ids= $("#itemmat").getGridParam("selrow");
                    var numero=$("#itemmat").getCell(ids,"MAT_SECUENCIAL");
                    $.post("materia/anulartoda", {NUMERO:numero},
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
	                            $("#itemmat").trigger("reloadGrid");
	                        }, "json");
                },
                values:function (){
                    var ids= $("#itemmat").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemmat").getCell(ids,"MAT_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        }
            });
	
});
</script>
