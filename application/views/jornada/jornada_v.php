<div id="accordion">  
        <form id="fjornada">
            <div id="cabecera">
            <table width="99%" id="tjornada" class="formDialog">    
			
					<tr>
					    <th>
							Jornada
						</th>
                        <td colspan="3">
                            <input type="text" maxlength="99" style="width:400px;" name="JOR_NOMBRE" id="JOR_NOMBRE" value="<?php echo !empty($sol->JOR_NOMBRE) ? prepCampoMostrar($sol->JOR_NOMBRE) : null ; ?>"  />
                        </td>
					</tr>	
					<tr>	
                    	<th>
							Hora Inicio
						</th>
						<td>
						<input type="text" minlength="2" max="24" onkeypress="return numeros(event)" style="width:50px;" name="HORA_INICIO" id="HORA_INICIO" value="<?php echo !empty($sol->HORA_INICIO) ? prepCampoMostrar($sol->HORA_INICIO) : "00" ;?>" /> :
						<input type="text" minlength="2" max="59" onkeypress="return numeros(event)" style="width:50px;" name="MINUTO_INICIO" id="MINUTO_INICIO" value="<?php echo !empty($sol->MINUTO_INICIO) ? prepCampoMostrar($sol->MINUTO_INICIO) : "00" ;?>" /> 
						</td>
						<th>
							Hora fin
                        </th>
                        <td>
						<input type="text" minlength="2" max="24" onkeypress="return numeros(event)" style="width:50px;" name="HORA_FIN" id="HORA_FIN" value="<?php echo !empty($sol->HORA_FIN) ? prepCampoMostrar($sol->HORA_FIN) : "00" ;?>" /> :
						<input type="text" minlength="2" max="59" onkeypress="return numeros(event)" style="width:50px;" name="MINUTO_FIN" id="MINUTO_FIN" value="<?php echo !empty($sol->MINUTO_FIN) ? prepCampoMostrar($sol->MINUTO_FIN) : "00" ;?>" />  
                        </td>
						
                    </tr>
						<?php if($accion=='n'|$accion=='e') : ?>                    
                             <td align="center" colspan="6" class="noclass">
                                <button title="Verifique la información antes de guardar." id="co_grabar" type="submit" ><img src="./imagenes/guardar.png" width="17" height="17"/>Grabar Jornada</button>
                             </td>
                    
						<?php endif; ?>
						
                </table>
            </div>
            <input type="hidden"  name="JOR_SECUENCIAL" id="JOR_SECUENCIAL" value="<?php echo !empty($sol->JOR_SECUENCIAL) ? prepCampoMostrar($sol->JOR_SECUENCIAL) : 0 ; ?>"  />
        </form>
</div>
<script>
function numeros(e){
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = "0123456789";
    especiales = [8,37,39,46];
 
    tecla_especial = false
    for(var i in especiales){
 if(key == especiales[i]){
     tecla_especial = true;
     break;
        } 
    }
 
    if(letras.indexOf(tecla)==-1 && !tecla_especial)
        return false;
}
</script>