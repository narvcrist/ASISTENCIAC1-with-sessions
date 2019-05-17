<?php $this->load->view('general/cabecera');
?>
<body>
	<div id="wrapper" class="active" style="border: 0px solid #CCC; margin: 0em 0em 0em 20em; width:70%; height:auto;">
        <!-- CONTENIDO DE LA PAGINA -->
        <div id="page-content-wrapper">
			<!-- CONTENIDOS DE LA PAGINA, ELEMENTOS -->
			<div class="page-content inset">
				<!--DESCRIPCION DEL SISTEMA-->
				<div class="row">
					<div class="col-md-12">
					  <div class="row" style="background:#e4e4e4; padding:10px; border:0px solid #ccc; margin: 0em 0em 0em -19em; width:999px">
						<div class="col-lg-12 text-center">							
							<table>
								<tr>
									<td>
										<a  name="istcre" href="https://cruzrojainstituto.edu.ec" title="Instituto Superior Tecnológico Cruz Roja Ecuatoriana" target="_blank" >
										<img src="imagenes/istcre.png" alt="ISTCRE" class="img-responsive" width="450" height="300" />
										</a>
									</td>
									<td>
										<h3 style="color:#333;"><b>ASISTENCIA C1</b></h3>
										<table>
											<tr>
												<th>
													<b>USUARIO: </b>&nbsp;<?php echo utf8_encode($this->session->userdata('US_CODIGO'));?> 
												</th>
											</tr>	
											<tr>	
												<th>
													<b>TIPO DE USUARIO: </b>&nbsp;<?php echo utf8_encode($this->session->userdata('US_NOMBRE_PERFIL'));?>
												</th>
											</tr>	
											<tr>	
												<th>
													<b>FECHA ACTUAL: </b>&nbsp;<?php echo @date("Y-m-d");?> &nbsp;<span id="time"><?php echo @date("H:i:s");?></span>
												</th>
											</tr>
										</table>
									</td>
								</tr>						
							</table>
						</div>
					  </div>
				  </div>
				</div>					
				<!--MENU DE NAVEGACION-->	
				<div id="c_menu_opciones" class="grid_10" style="border: 1px solid #CCC; margin: 0em 0em 0em -18.5em;  width:115%; height:auto;">
					<ul id="menu_opciones">
						<?php if ($this->lib_usuarios->getAccesoSeccion($this->session->userdata('US_CODIGO'),'ADM')==1){?>
						
							<li><a href="#">Administración</a> 
								<ul>
									<li> <a id="e_estudiante" title="Administración de Estudiantes" name="persona/index"  class="cabecera-links" href="#">Estudiantes</a></li>
									<li> <a id="e_jornada" title="Administración de Jornadas" name="jornada/index"  class="cabecera-links" href="#">Jornada</a></li>
								  <li> <a id="e_asistencia" title="Administración de Asistencias" name="asistencia/index"  class="cabecera-links" href="#">Asistencia</a></li>
									<li> <a id="e_perxjor" title="Administración de Jornada Por Estudiante" name="perxjor/index"  class="cabecera-links" href="#">Estudiante Por Jornada </a></li>
								</ul>
							</li>
							<li><a title="Administración Parametros de Estudios" href="#">Administración Estudios</a> 
								<ul>
									<li> <a id="e_materia" title="Administración de Materias" name="materia/index"  class="cabecera-links" href="#">Materias</a></li>
									<li> <a id="e_cronograma" title="Administración de Jornada Por Materia" name="jorxmat/index"  class="cabecera-links" href="#">Jornada Por Materias</a></li>
									<li> <a id="e_tipoCalificacion" title="Administración de Tipo de Horarios" name="horario/index"  class="cabecera-links" href="#">Horarios</a></li>
								</ul>
							</li>			
						<?php } ?>
						<li> <a id="e_salir" title="Cierre de Sesión" class="titular" href="general/inicio/salir"><span class="titular"><font color="#D32525"><b>Salir</b></font></span></a></li>
					</ul>
				</div>
				<!--ESPACIO CONTENEDOR-->
			    <div class="row" style="border: 0px solid #CCC; margin: 2em 0em 0em -18.5em; width:110%; height:auto;">
					<span id="contenido_general" style="padding: 20px;">						
						<h2 style="border: 0px solid #CCC; margin: 0em 0em 0em 6em; width:auto; height:auto; text-shadow: 1px 2px #999;">
							<img src="imagenes/istcre.png" alt="ISTCRE" class="img-responsive" width="625" height="300" />
							<font color="#000000"><b>Bienvenidos al Sistema Asistencia C1 ISTCRE</b></font>
						</h2>
					</span>
			    </div>
					
			</div>
        </div>
    </div>
<div id="dialog-form" title="..."></div>

<script>
$(function(){

$("#menu_opciones").wijmenu({
      trigger: ".wijmo-wijmenu-item",
      triggerEvent: "click"
  });

  $(".cabecera-links").each(function(){
    $('#'+$(this).attr("id")).click(function(){
        $("#contenido_general").load($(this).attr("name"),{});
    });
  });


var interval_verificar_sesion = setInterval(
    function(){
      $.ajax({
	type: 'GET',
	url: "<?php echo site_url("general/verificar_sesion")?>",
	success: function(r){
	  if(r.estaActivo == 1){
	  }else{
	    alert(r.mensaje);
	    document.location = "<?php echo site_url('general/inicio/salir')?>";
	  }
	},
	beforeSend: function(jqXHR, settings){
	  $(".container_12:first").unmask();
	},
	error: function(jqXHR, textStatus, errorThrown){
	  var pregunta = confirm("Se perdió el contacto con el servidor. \n Desea esperar?...");
	  if(!pregunta){
	    document.location = "<?php echo site_url('general/inicio/salir');?>";
	  }
	},
	dataType: "json"
      });
    },
    4500000
    );
});

setInterval(function(){
	var tiempo = new Date();
	var hora = tiempo.getHours();
	var minuto = tiempo.getMinutes();
	var segundo = tiempo.getSeconds();
	if(segundo.toString().length == 1 ){
		segundo='0'+segundo;
	}
	$('#time').html(hora+':'+minuto+':'+segundo);
  }, 1000 );

 var ISTCRE = {
  intervalo: null,
  crearWijdialog: function(elemento, opciones){
    this.intervalo = self.setInterval(function(){
		      ISTCRE.verificarCrearWijdialog(elemento, opciones)
		    }, 130);
  },
  verificarCrearWijdialog: function(elm, opciones){
    var elemento = $(elm);
    if(elemento.size() > 0){ 
      opciones.close = function(e, ui){
	  $("*", $(this)).remove();
	  $(this).dialog("destroy").remove();	  
	  //$(this).remove();
      };
      opciones.zIndex = 1;
      opciones.captionButtons = {refresh:{visible: false}, pin:{visible: false}, maximize:{visible: false}};
      elemento.wijdialog(opciones);      
      window.clearInterval(this.intervalo);
    }
  }
}
</script>
</body>
</html>