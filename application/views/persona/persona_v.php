<div id="accordion">
   <form id="fpersona">
            <div id="cabecera">
            <table width="99%" id="tpersona" class="formDialog">    
			
					<tr>
						<th>
							Apellidos
						</th>
                        	<td>
                            <input type="text" maxlength="99" style="width:200px;" name="PER_APELLIDOS" id="PER_APELLIDOS" value="<?php echo !empty($sol->PER_APELLIDOS) ? prepCampoMostrar($sol->PER_APELLIDOS) : null ; ?>"  />
							</td>
					   
						<th>
							Nombres
						</th>
							<td>
                            <input type="text" maxlength="99" style="width:200px;" name="PER_NOMBRES" id="PER_NOMBRES" value="<?php echo !empty($sol->PER_NOMBRES) ? prepCampoMostrar($sol->PER_NOMBRES) : null ; ?>"  />
							</td>
					    <th>
							Cedula
						</th>
                       		<td>
                            <input type="text" maxlength="15" style="width:100px;" name="PER_CEDULA" id="PER_CEDULA" value="<?php echo !empty($sol->PER_CEDULA) ? prepCampoMostrar($sol->PER_CEDULA) : null ; ?>"  />
							</td>
					</tr>	
					<tr>
					<?php if($accion=='n'|$accion=='e') : ?>                    
                            
							<td align="center" colspan="6" class="noclass">
							   <button title="Verifique la información antes de guardar." id="co_grabar" type="submit" ><img src="./imagenes/guardar.png" width="17" height="17"/>Grabar Estudiante</button>
							</td>

							<?php endif; ?>

					</tr>

					
                </table>
            </div>
            <input type="hidden"  name="PER_SECUENCIAL" id="PER_SECUENCIAL" value="<?php echo !empty($sol->PER_SECUENCIAL) ? prepCampoMostrar($sol->PER_SECUENCIAL) : 0 ; ?>"  />
        </form>
</div>
