<div id="accordion">
        <form id="fperxjor">
            <div id="cabecera">
            <table width="99%" id="tperxjor" class="formDialog">    
			
					<tr>
					    <th>
							Jornada
						</th>
                        <td>
						<?php echo $combo_jornada; ?>
						</td>
					   
						<th>
							Persona
						</th>
                        <td>
						<?php echo $combo_persona; ?>
						</td>
					</tr>
                    	<?php if($accion=='n'|$accion=='e') : ?>                    
                            
                             <td align="center" colspan="6" class="noclass">
                                <button title="Verifique la información antes de guardar." id="co_grabar" type="submit" ><img src="./imagenes/guardar.png" width="17" height="17"/>Grabar Jornada</button>
                             </td>
                    	<?php endif; ?>
			 </table>
            </div>
            <input type="hidden"  name="PERXJOR_SECUENCIAL" id="PERXJOR_SECUENCIAL" value="<?php echo !empty($sol->PERXJOR_SECUENCIAL) ? prepCampoMostrar($sol->PERXJOR_SECUENCIAL) : 0 ; ?>"  />
        </form>
</div>
