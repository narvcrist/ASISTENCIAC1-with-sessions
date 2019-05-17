<div id="accordion">
        <form id="fmateria">
            <div id="cabecera">
            <table width="99%" id="tmateria" class="formDialog">    
			
					<tr>
					   <th>
							Materia
						</th>
                        <td>
						<?php echo $combo_materia; ?>
						</td>
						<th>
							Tiempo Duracion
                        </th>
                        <td>
							<input type="number" min="0" max="999" style="width:100px;" name="MAT_TIEMPODURACION" id="MAT_TIEMPODURACION" value="<?php echo !empty($sol->MAT_TIEMPODURACION) ? prepCampoMostrar($sol->MAT_TIEMPODURACION) : 0 ;?>" /> dias
                        </td>
						<th>
							Porcentaje Aprobacion
                        </th>
                        <td>
							<input type="text" style="width:100px;" name="MAT_PORCENTAJE" id="MAT_PORCENTAJE" value="<?php echo !empty($sol->MAT_PORCENTAJE) ? prepCampoMostrar($sol->MAT_PORCENTAJE) : null ;?>" /> 
                        </td>
						<th>
							Carga Horaria
                        </th>
                        <td>
							<input type="text" style="width:100px;" name="MAT_CARGAHORARIA" id="MAT_CARGAHORARIA" value="<?php echo !empty($sol->MAT_CARGAHORARIA) ? prepCampoMostrar($sol->MAT_CARGAHORARIA) : null ;?>" /> 
                        </td>
					</tr>
						<?php if($accion=='n'|$accion=='e') : ?>                    
                             <td align="center" colspan="10" class="noclass">
                                <button title="Verifique la información antes de guardar." id="co_grabar" type="submit" ><img src="./imagenes/guardar.png" width="17" height="17"/>Grabar Materia</button>
                             </td>
                    	<?php endif; ?>
					</table>
            </div>
            <input type="hidden"  name="MAT_SECUENCIAL" id="MAT_SECUENCIAL" value="<?php echo !empty($sol->MAT_SECUENCIAL) ? prepCampoMostrar($sol->MAT_SECUENCIAL) : 0 ; ?>"  />
        </form>
</div>
