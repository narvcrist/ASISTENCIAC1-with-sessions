    <?php
class Masistencia extends CI_Model {
   
   //Funcion en la cual muestra cada seleccion que ingresemos
   function getdatosItems(){
        $datos = new stdClass();
        $consulta=$_POST['_search'];
        $numero=  $this->input->post('numero');
        $datos->econdicion ='ASIS_ESTADO<>1';
		$user=$this->session->userdata('US_CODIGO');
                
         if ($this->lib_usuarios->getAccesoSeccion($this->session->userdata('US_CODIGO'),'ADMS')!=1){
			$SQLINFOUSER="SELECT PER_SECUENCIAL FROM PERSONA WHERE PER_CEDULA=(SELECT US_CEDULA 
							FROM ISTCRE_APLICACIONES.USUARIO 
							WHERE US_CODIGO='$user' AND US_ESTADO=0)";							
			$PERSONA=$this->db->query($SQLINFOUSER)->row()->PER_SECUENCIAL;
			$datos->econdicion ="ASIS_SEC_PERSONA='$PERSONA'";
		}
		
              $datos->campoId = "ROWNUM";
			   $datos->camposelect = array("ROWNUM",
                                            "ASIS_SECUENCIAL",
                                            "(SELECT (PER_APELLIDOS || ' ' || PER_NOMBRES)
                                            FROM PERSONA 
                                            WHERE PER_SECUENCIAL = ASIS_SEC_PERSONA)
                                            ASIS_SEC_PERSONA",
                                            "(REPLACE(TO_CHAR(ASIS_HORAINGRESO,'00.90'),'.',':')) ASIS_HORAINGRESO",
                                            "ASIS_FECHAINGRESO",
											"(SELECT (JOR.JOR_NOMBRE || '('||
											(REPLACE(TO_CHAR(JOR_HORAINICIO,'00.90'),'.',':')) || ' -' ||
											(REPLACE(TO_CHAR(JOR_HORAFIN,'00.90'),'.',':')) || ' )') JOR_NOMBRE
											  FROM PERSONA PER
												   JOIN PERSONAXJORNADA PERXJOR
													  ON PERXJOR.PERXJOR_SEC_PERSONA = PER.PER_SECUENCIAL
												   JOIN JORNADA JOR ON JOR.JOR_SECUENCIAL = PERXJOR.PERXJOR_SEC_JORNADA
											 WHERE     PER.PER_SECUENCIAL = ASIS_SEC_PERSONA AND PER.PER_ESTADO = 0
												   AND ASIS_HORAINGRESO BETWEEN JOR.JOR_HORAINICIO AND JOR.JOR_HORAFIN) ASIS_NOMJOR",
											"ASIS_RESPONSABLE",
											"ASIS_ESTADO");
			  $datos->campos = array( "ROWNUM",
                                      "ASIS_SECUENCIAL",
                                      "ASIS_SEC_PERSONA",
                                      "ASIS_HORAINGRESO",
                                      "ASIS_FECHAINGRESO",
									  "ASIS_NOMJOR",
                                      "ASIS_RESPONSABLE",
                                      "ASIS_ESTADO");
			  $datos->tabla="ASISTENCIA";
              $datos->debug = false;	
           return $this->jqtabla->finalizarTabla($this->jqtabla->getTabla($datos), $datos);
   }
   
   //Datos que seran enviados para la edicion o visualizacion de cada registro seleccionado
   function dataAsistencia($numero){
	   $sql="select
       ASIS_SECUENCIAL,
       ASIS_SEC_PERSONA,
       ASIS_HORAINGRESO,
       ASIS_FECHAINGRESO,
       ASIS_RESPONSABLE,
       ASIS_ESTADO
          FROM ASISTENCIA WHERE ASIS_SECUENCIAL=$numero";
         $sol=$this->db->query($sql)->row();
         if ( count($sol)==0){
                $sql="select
                ASIS_SECUENCIAL,
                ASIS_SEC_PERSONA,
                ASIS_HORAINGRESO,
                ASIS_FECHAINGRESO,
                ASIS_RESPONSABLE,
                ASIS_ESTADO
                      FROM ASISTENCIA WHERE ASIS_SECUENCIAL=$numero";
                         $sol=$this->db->query($sql)->row();
						}
	      return $sol;
		}

	//funcion para crear un nuevo reporte o cabecera
    function agrAsistencia(){
			$sql="select to_char(SYSDATE,'DD/MM/YYYY HH24:MI:SS') FECHA from dual";		
			$conn = $this->db->conn_id;
			$stmt = oci_parse($conn,$sql);
			oci_execute($stmt);
			$nsol=oci_fetch_row($stmt);
			oci_free_statement($stmt);            
            $ASIS_RESPONSABLE=$this->session->userdata('US_CODIGO');
			$ASIS_FECHAINGRESO="TO_DATE('".$nsol[0]."','DD/MM/YYYY HH24:MI:SS')";
			
			//VARIABLES DE INGRESO
            $ASIS_SEC_PERSONA=$this->input->post('persona');
			$ASIS_FECHAINGRESO=prepCampoAlmacenar($this->input->post('ASIS_FECHAINGRESO'));
			
			$HORA_INGRESO=$this->input->post('HORA_INGRESO');
			$MINUTO_INGRESO=$this->input->post('MINUTO_INGRESO');
			if($HORA_INGRESO!=0){
				$HORA_INGRESO=$HORA_INGRESO;
			}else{
				$HORA_INGRESO=0;
			}
			
			if($MINUTO_INGRESO!=0){
				$MINUTO_INGRESO=$MINUTO_INGRESO;
			}else{
				$MINUTO_INGRESO=0;
			}	
			$ASIS_HORAINGRESO=$HORA_INGRESO.".".$MINUTO_INGRESO;            
			
			//Verifica si esta en un horario
			$SQLJORNADA="SELECT COUNT(*) NUM_JOR 
						FROM PERSONA PER 
						JOIN PERSONAXJORNADA PERXJOR
							ON PERXJOR.PERXJOR_SEC_PERSONA=PER.PER_SECUENCIAL
						JOIN JORNADA JOR
							ON JOR.JOR_SECUENCIAL=PERXJOR.PERXJOR_SEC_JORNADA
						WHERE PER.PER_SECUENCIAL=$ASIS_SEC_PERSONA
							AND PER.PER_ESTADO=0
							AND $ASIS_HORAINGRESO BETWEEN JOR.JOR_HORAINICIO AND JOR.JOR_HORAFIN";
			$NUM_JOR=$this->db->query($SQLJORNADA)->row()->NUM_JOR;
			if($NUM_JOR==1){
					
					//Verifica si el registro ya esta ingresado
					$SQLREG="SELECT COUNT(*) NUM_ASIS FROM ASISTENCIA 
								where ASIS_SEC_PERSONA=$ASIS_SEC_PERSONA
									AND ASIS_FECHAINGRESO=TO_DATE('$ASIS_FECHAINGRESO','DD/MM/YYYY')
									AND ASIS_HORAINGRESO BETWEEN 
										(SELECT JOR_HORAINICIO
											FROM JORNADA 
											WHERE $ASIS_HORAINGRESO 
											BETWEEN JOR_HORAINICIO AND JOR_HORAFIN) 
										AND (SELECT JOR_HORAFIN
												FROM JORNADA 
												WHERE $ASIS_HORAINGRESO 
												BETWEEN JOR_HORAINICIO AND JOR_HORAFIN)";
					$NUM_REG=$this->db->query($SQLREG)->row()->NUM_ASIS;
					if($NUM_REG==0){
						$sqlImporta="INSERT INTO ASISTENCIA 
									(ASIS_SEC_PERSONA,ASIS_HORAINGRESO,ASIS_FECHAINGRESO,ASIS_RESPONSABLE,ASIS_ESTADO) 
										VALUES(
									$ASIS_SEC_PERSONA,$ASIS_HORAINGRESO,TO_DATE('".$ASIS_FECHAINGRESO."','DD/MM/YYYY'),'$ASIS_RESPONSABLE',0)";
									$this->db->query($sqlImporta);
							$ASIS_SECUENCIAL=$this->db->query("select max(ASIS_SECUENCIAL) SECUENCIAL from ASISTENCIA")->row()->SECUENCIAL;
							echo json_encode(array("cod"=>$ASIS_SECUENCIAL,"numero"=>$ASIS_SECUENCIAL,"mensaje"=>"Asistencia: ".$ASIS_SECUENCIAL.", insertada con éxito"));
					}else{
						echo json_encode(array("cod"=>1,"numero"=>1,"mensaje"=>"!!!...La Asistencia Ya Existe...!!!"));
					}
			}else{
				echo json_encode(array("cod"=>1,"numero"=>1,"mensaje"=>"!!!...No se Encuentra en el Horario...!!!"));
			}
}
    
	//funcion para editar un registro selccionado
    function editAsistencia(){
			$ASIS_SECUENCIAL=$this->input->post('ASIS_SECUENCIAL');
			
			//VARIABLES DE INGRESO
			$ASIS_SEC_PERSONA=$this->input->post('persona');
            
			$HORA_INGRESO=$this->input->post('HORA_INGRESO');
			$MINUTO_INGRESO=$this->input->post('MINUTO_INGRESO');
			if($HORA_INGRESO!=0){
				$HORA_INGRESO=$HORA_INGRESO;
			}else{
				$HORA_INGRESO=0;
			}
			
			if($MINUTO_INGRESO!=0){
				$MINUTO_INGRESO=$MINUTO_INGRESO;
			}else{
				$MINUTO_INGRESO=0;
			}	
			$ASIS_HORAINGRESO=$HORA_INGRESO.".".$MINUTO_INGRESO;

				$sql="UPDATE ASISTENCIA SET
							ASIS_SEC_PERSONA=$ASIS_SEC_PERSONA,
							ASIS_HORAINGRESO='$ASIS_HORAINGRESO'
							WHERE ASIS_SECUENCIAL=$ASIS_SECUENCIAL";
		$this->db->query($sql);
               //print_r($sql);
		 $ASIS_SECUENCIAL=$this->db->query("select max(ASIS_SECUENCIAL) SECUENCIAL from ASISTENCIA")->row()->SECUENCIAL;
		 echo json_encode(array("cod"=>$ASIS_SECUENCIAL,"numero"=>$ASIS_SECUENCIAL,"mensaje"=>"Asistencia: ".$ASIS_SECUENCIAL.", editado con éxito"));    
	
   }    
}
?>