<script type="text/javascript">
jQuery(document).ready(function(){
    
    var permiso="<?php echo $permiso; ?>";
    
	//Evento para llenar el Grid de los datos a presentar
    jQuery("#itemjorxmat").jqGrid({
          url:"jorxmat/getdatosItems",
          datatype: "json",
          colNames:['Num','Sec.','Jornada','Materia','Estado'],
          colModel:[
					{name:'COLUMNAS',index:'COLUMNAS',align:"center",width:30,hidden:false},
                    {name:'JORXMAT_SECUENCIAL',index:'JORXMAT_SECUENCIAL',align:"center",width:60},
                    {name:'JORXMAT_SEC_JORNADA',index:'JORXMAT_SEC_JORNADA',align:"center", width:150},
					{name:'JORXMAT_SEC_MATERIA',index:'JORXMAT_SEC_MATERIA',align:"center",  width:200},
					{name:'JORXMAT_ESTADO',index:'JORXMAT_ESTADO',searchable:false, width:40,align:"center", edittype:'select', formatter:'select', editoptions:{value:"0:<span class='ui-icon ui-icon-circle-check ui-icon-extra'>Activo</span>;1:<span class='ui-icon ui-icon-circle-close ui-icon-extra'>Pasivo</span>"}}
                ],
        rowNum:50,
        rowList : [50,100,200,800],
        pager: '#pitemjorxmat',
        sortname: 'JORXMAT_SECUENCIAL',
        viewrecords: true,
        height:350,
        width:1000,
        shrinkToFit:false,
        sortorder: "asc",
        mtype:"POST",
        toolbar: [true,"top"]
    });
    
	//Botones que contendran cada evento o acción
    $("#itemjorxmat").jqGrid('navGrid','#pitemjorxmat',{del:false,add:false,edit:false,refresh:true, search: false},{},{},{},{multipleSearch:true,sopt:['cn','eq']});
    $("#itemjorxmat").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true});
    if(permiso==1){
        $("#t_itemjorxmat").append("<button title='Nueva' id='agr_jorxmat'>Nueva</button>");
        $("#t_itemjorxmat").append("<button title='Editar' id='edit_jorxmat'>Editar</button>");
        $("#t_itemjorxmat").append("<button title='Ver' id='ver_jorxmat'>Ver</button>");
        $("#t_itemjorxmat").append("<button title='Eliminar' id='anular_jorxmat'>Eliminar</button>");  
        $("#t_itemjorxmat").append("<button title='Recargar Listado' id='recargar_jorxmat'>Refresh</button>");
        $("#t_itemjorxmat").append("<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>"); 
    }else{ 
        $("#t_itemjorxmat").append("<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>");
        $("#t_itemjorxmat").append("<button title='Recargar Listado' id='recargar_jorxmat'>Refresh</button>");
    }    	
        $("#itemjorxmat").setGridParam({datatype: "json",url:"jorxmat/getdatosItems",postData:{numero:''}});
        $("#itemjorxmat").trigger('reloadGrid');

//Evento para ingresar un nuevo registro    
$("#agr_jorxmat").jMostrarNoGrid({
            id:"#t_itemjorxmat",
            idButton:"#agr_jorxmat",
            errorMens:"No se puede mostrar el formulario.",
            url: "jorxmat/nuevaJorxmat/",
            titulo: "Agregar Jornada por Materia",
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
                $("#itemjorxmat").setGridParam({datatype: "json",url:"jorxmat/getdatosItems",postData:{numero:$('#JORXMAT_SECUENCIAL').val()}});
                $("#itemjorxmat").trigger('reloadGrid');
            }
        });

//Evento para editar un registro        
$("#edit_jorxmat").jMostrarNoGrid({
	        id:"#itemjorxmat",
	        idButton:"#edit_jorxmat",
	        errorMens:"",
	        url: "jorxmat/verJorxmat/e",
	        titulo: "Editar Jornada por Materia",
              //  alto:900,
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-pencil",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                values:function (){
                    var ids= $("#itemjorxmat").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemjorxmat").getCell(ids,"JORXMAT_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        },
            alCerrar : function() {
                 $("#itemjorxmat").trigger('reloadGrid');
            }
            });

//Evento para ver la informacion de un persona para la licencia			
$("#ver_jorxmat").jMostrarNoGrid({
	        id:"#itemjorxmat",
	        idButton:"#ver_jorxmat",
	        errorMens:"",
	        url: "jorxmat/verjorxmat/v",
	        titulo: "Ver Jornada por Materia",
              //  alto:900,
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-document-b",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                values:function (){
                    var ids= $("#itemjorxmat").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemjorxmat").getCell(ids,"JORXMAT_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        }
            });
            //Actualiza la persona para la licencia
            $("#itemjorxmat").jRecargar({
                id:"#itemjorxmat",
                showText:true,
                idButton:"#recargar_jorxmat",
                icon:"ui-icon-refresh"
			});            
			
			//Actualiza la persona para la licencia
            $("#itemjorxmat").jRecargar({
                id:"#itemjorxmat",
                showText:true,
                idButton:"#recargar_jorxmat",
                icon:"ui-icon-refresh"
			});

//Evento para eliminar una persona para la licencia
 $("#anular_jorxmat").jMostrarNoGrid({
	        id:"#itemjorxmat",
	        idButton:"#anular_jorxmat",
	        errorMens:"",
	        url: "jorxmat/verjorxmat/x",
	        titulo: "Eliminar Jornada por Materia",
	        ancho:1024,
	        posicion: "top",
	        showText:true,
	        icon:"ui-icon-closethick",
	        respuestaTipo:"html",
	        valuesIsFunction: true,
                botonSubmit:"Eliminar",
                formAction :function(dialogId){
                    var ids= $("#itemjorxmat").getGridParam("selrow");
                    var numero=$("#itemjorxmat").getCell(ids,"JORXMAT_SECUENCIAL");
                    $.post("jorxmat/anulartoda", {NUMERO:numero},
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
	                            $("#itemjorxmat").trigger("reloadGrid");
	                        }, "json");
                },
                values:function (){
                    var ids= $("#itemjorxmat").getGridParam("selrow");
	            if ($().jEmpty(ids)){
	                return {ids:null};
	            }else{
                        var numero=$("#itemjorxmat").getCell(ids,"JORXMAT_SECUENCIAL");
	                return {NUMERO:numero};
	            };
	        }
            });
	
});
</script>
