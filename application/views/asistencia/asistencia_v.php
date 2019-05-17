<div id="accordion">
        <form id="fasistencia">
            <div id="cabecera">
            <table width="99%" id="tasistencia" class="formDialog">    
					<tr>
						<th>
							Persona
						</th>
                        <td colspan="3">
							<?php echo $combo_persona; ?>
						</td>
					</tr>	
					<tr>
						<th>
						Fecha de Ingreso(*)
						</th>
						
						<td>
							<input type="text" maxlength="100" style="width:150px;" name="ASIS_FECHAINGRESO" id="ASIS_FECHAINGRESO" value="<?php echo !empty($sol->ASIS_FECHAINGRESO) ? prepCampoMostrar($sol->ASIS_FECHAINGRESO) : null ; ?>"  />
						</td>	
					    <th>
							Hora Ingreso
						</th>
						<td> 
						<input type="text" minlength="2" max="24" onkeypress="return numeros(event)" style="width:50px;" name="HORA_INGRESO" id="HORA_INGRESO" value="<?php echo !empty($sol->HORA_INGRESO) ? prepCampoMostrar($sol->HORA_INGRESO) : "00" ;?>" /> :
						<input type="text" minlength="2" max="59" onkeypress="return numeros(event)" style="width:50px;" name="MINUTO_INGRESO" id="MINUTO_INGRESO" value="<?php echo !empty($sol->MINUTO_INGRESO) ? prepCampoMostrar($sol->MINUTO_INGRESO) : "00" ;?>" /> 
						</td>
					</tr>
					<?php if($accion=='n'|$accion=='e') : ?>                    
                                <td align="center" colspan="10" class="noclass">
                                <button title="Verifique la información antes de guardar." id="co_grabar" type="submit" ><img src="./imagenes/guardar.png" width="17" height="17"/>Grabar Asistencia</button>
                             </td>
                    <?php endif; ?>
				</table>
            </div>
            <input type="hidden"  name="ASIS_SECUENCIAL" id="ASIS_SECUENCIAL" value="<?php echo !empty($sol->ASIS_SECUENCIAL) ? prepCampoMostrar($sol->ASIS_SECUENCIAL) : 0 ; ?>"  />
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