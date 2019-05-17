<div id="accordion">
        <form id="fjorxmat">
            <div id="cabecera">
				<table width="99%" id="tjorxmat" class="formDialog">    
			
					<tr>
					    <th>
							Jornada
						</th>
                        <td>
							<?php echo $combo_jornada; ?>
						</td>
						<th>
							Materia
						</th>
                        <td>
							<?php echo $combo_materia; ?>
						</td>
					</tr>
                		<?php if($accion=='n'|$accion=='e') : ?>                    
                             <td align="center" colspan="10" class="noclass">
                                <button title="Verifique la información antes de guardar." id="co_grabar" type="submit" ><img src="./imagenes/guardar.png" width="17" height="17"/>Grabar Jornada</button>
                             </td>
                		<?php endif; ?>
				</table>
            </div>
            <input type="hidden"  name="JORXMAT_SECUENCIAL" id="JORXMAT_SECUENCIAL" value="<?php echo !empty($sol->JORXMAT_SECUENCIAL) ? prepCampoMostrar($sol->JORXMAT_SECUENCIAL) : 0 ; ?>"  />
        </form>
</div>
