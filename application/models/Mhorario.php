<?php
class Mhorario extends CI_Model {
   
   //Funcion en la cual muestra cada seleccion que ingresemos
   function getdatosItems(){
        $datos = new stdClass();
        $consulta=$_POST['_search'];
        $numero= $this->input->post('numero');
        $datos->econdicion ='HOR_ESTADO<>1';
		$user=$this->session->userdata('US_CODIGO');
                
        if ($this->lib_usuarios->getAccesoSeccion($this->session->userdata('US_CODIGO'),'ADMS')!=1){
			$SQLINFOUSER="SELECT US_SECUENCIAL,US_CEDULA,US_NOMBRES,US_APELLIDOS 
							FROM ISTCRE_APLICACIONES.USUARIO 
							WHERE US_CODIGO='$user' AND US_ESTADO=0";
							
			$HORARIO=$this->db->query($SQLINFOUSER)->row()->HOR_SECUENCIAL;
			$datos->econdicion ="HOR_SECUENCIAL='$HORARIO'";
        }
            
              $datos->campoId = "ROWNUM";
			   $datos->camposelect = array("ROWNUM",
											"HOR_SECUENCIAL",
                                            "(SELECT CONCAT(CONCAT(PER_APELLIDOS,' '), PER_NOMBRES) FROM PERSONA WHERE PER_SECUENCIAL = HOR_SEC_PERSONA)
                                            HOR_SEC_PERSONA",
											"(select MAT_NOMBRE FROM materia WHERE MAT_SECUENCIAL=HOR_SEC_MATERIA) HOR_SEC_MATERIA",
											"HOR_FECHAINGRESO",
											"(REPLACE(TO_CHAR(HOR_HORAINICIO,'00.90'),'.',':')) HOR_HORAINICIO",
											"(REPLACE(TO_CHAR(HOR_HORAFIN,'00.90'),'.',':')) HOR_HORAFIN",
											"HOR_DIA",
											"HOR_RESPONSABLE",
											"HOR_ESTADO");
			  $datos->campos = array( "ROWNUM",
                                            "HOR_SECUENCIAL",
                                            "HOR_SEC_PERSONA",
                                            "HOR_SEC_MATERIA",
                                            "HOR_FECHAINGRESO",
                                            "HOR_HORAINICIO",
                                            "HOR_HORAFIN",
                                            "HOR_DIA",
                                            "HOR_RESPONSABLE",
                                            "HOR_ESTADO");
			  $datos->tabla="HORARIO";
              $datos->debug = false;	
           return $this->jqtabla->finalizarTabla($this->jqtabla->getTabla($datos), $datos);
   }
   //Datos que seran enviados para la edicion o visualizacion de cada registro seleccionado
   function dataHorario($numero){
       $sql="select
                HOR_SECUENCIAL,
                HOR_SEC_PERSONA,
                HOR_SEC_MATERIA,
                HOR_FECHAINGRESO,
                HOR_HORAINICIO,
                HOR_HORAFIN,
                HOR_DIA,
                HOR_RESPONSABLE,
                HOR_ESTADO
          FROM HORARIO WHERE HOR_SECUENCIAL=$numero";
         $sol=$this->db->query($sql)->row();
         if ( count($sol)==0){
                $sql="select
                            HOR_SECUENCIAL,
                            HOR_SEC_PERSONA,
                            HOR_SEC_MATERIA,
                            HOR_FECHAINGRESO,
                            HOR_HORAINICIO,
                            HOR_HORAFIN,
                            HOR_DIA,
                            HOR_RESPONSABLE,
                            HOR_ESTADO
                          FROM HORA WHERE HOR_SECUENCIAL=$numero";
                         $sol=$this->db->query($sql)->row();
						}
          return $sol;
		}	
	//funcion para crear un nuevo reporte o cabecera
    function agrHorario(){
			$sql="select to_char(SYSDATE,'MM/DD/YYYY HH24:MI:SS') FECHA from dual";		
			$conn = $this->db->conn_id;
			$stmt = oci_parse($conn,$sql);
			oci_execute($stmt);
			$nsol=oci_fetch_row($stmt);
			oci_free_statement($stmt);            
            $HOR_FECHAINGRESO="TO_DATE('".$nsol[0]."','MM/DD/YYYY HH24:MI:SS')";
            $HOR_RESPONSABLE= $this->session->userdata('US_CODIGO');
		
			//VARIABLES DE INGRESO
			$HOR_SEC_PERSONA=$this->input->post('persona');
            $HOR_SEC_MATERIA=prepCampoAlmacenar($this->input->post('materia'));
            $HORA_INICIO=prepCampoAlmacenar($this->input->post('HORA_INICIO'));
            $MINUTO_INICIO=prepCampoAlmacenar($this->input->post('MINUTO_INICIO'));
            if($HORA_INICIO!=0){
				$HORA_INICIO=$HORA_INICIO;
			}else{
				$HORA_INICIO=0;
			}
			
			if($MINUTO_INICIO!=0){
				$MINUTO_INICIO=$MINUTO_INICIO;
			}else{
				$MINUTO_INICIO=0;
			}	
			$HOR_HORAINICIO=$HORA_INICIO.".".$MINUTO_INICIO;
			
			//INGRESO HORAFINAL
			$HORA_FIN=$this->input->post('HORA_FIN');
			$MINUTO_FIN=$this->input->post('MINUTO_FIN');
			if($HORA_FIN!=0){
				$HORA_FIN=$HORA_FIN;
			}else{
				$HORA_FIN=0;
			}
			
			if($MINUTO_FIN!=0){
				$MINUTO_FIN=$MINUTO_FIN;
			}else{
				$MINUTO_FIN=0;
			}	
			$HOR_HORAFIN=$HORA_FIN.".".$MINUTO_FIN;
			
			$HOR_DIA=prepCampoAlmacenar($this->input->post('dia'));
        	$sqlHORARIOVALIDA="select count(*) NUM_HORARIO from HORARIO WHERE HOR_SEC_PERSONA='{$HOR_SEC_PERSONA }' and HOR_ESTADO=0";
			$NUM_HORARIO =$this->db->query($sqlHORARIOVALIDA)->row()->NUM_HORARIO ;

            //validación...
			$sqlREPETICION="select count(*) NUM_HORARIO
                from HORARIO
                where upper(HOR_SEC_PERSONA)=upper('{$HOR_SEC_PERSONA}') 
                and upper(HOR_SEC_MATERIA)=upper('{$HOR_SEC_MATERIA}') 
                and upper(HOR_DIA)=upper('{$HOR_DIA}') 
                and HOR_ESTADO=0";
            $NUM_HORARIO=$this->db->query($sqlREPETICION)->row()->NUM_HORARIO;

            if($NUM_HORARIO ==0){

                $sql="INSERT INTO HORARIO (
                    HOR_SEC_PERSONA,
                    HOR_SEC_MATERIA,
                    HOR_FECHAINGRESO,
                    HOR_HORAINICIO,
                    HOR_HORAFIN,
                    HOR_DIA,
                    HOR_RESPONSABLE,
                    HOR_ESTADO) VALUES (
                    $HOR_SEC_PERSONA,
                    $HOR_SEC_MATERIA,
                    $HOR_FECHAINGRESO,
                    $HOR_HORAINICIO,
                    $HOR_HORAFIN,
                    '$HOR_DIA',
                    '$HOR_RESPONSABLE', 
                    0)";
				$this->db->query($sql);
				//print_r($sql);
				$HOR_SECUENCIAL=$this->db->query("select max(HOR_SECUENCIAL) SECUENCIAL from HORARIO")->row()->SECUENCIAL;
				echo json_encode(array("cod"=>$HOR_SECUENCIAL,"numero"=>$HOR_SECUENCIAL,"mensaje"=>"Horario: ".$HOR_SECUENCIAL.", insertado con éxito"));    
		}else{
		echo json_encode(array("cod"=>1,"numero"=>1,"mensaje"=>"!!!...El Registro Ya Existe...!!!"));
			}
			    
    }
	//funcion para editar un registro selccionado
    function editHorario(){
			$HOR_SECUENCIAL=$this->input->post('HOR_SECUENCIAL');
			
            //VARIABLES DE INGRESO
            
			$HOR_SEC_PERSONA=$this->input->post('persona');
            $HOR_SEC_MATERIA=$this->input->post('materia');	
            $HORA_INICIO=prepCampoAlmacenar($this->input->post('HORA_INICIO'));
            $MINUTO_INICIO=prepCampoAlmacenar($this->input->post('MINUTO_INICIO'));
            if($HORA_INICIO!=0){
				$HORA_INICIO=$HORA_INICIO;
			}else{
				$HORA_INICIO=0;
			}
			
			if($MINUTO_INICIO!=0){
				$MINUTO_INICIO=$MINUTO_INICIO;
			}else{
				$MINUTO_INICIO=0;
			}	
			$HOR_HORAINICIO=$HORA_INICIO.".".$MINUTO_INICIO;
			
			//INGRESO HORAFINAL
			$HORA_FIN=$this->input->post('HORA_FIN');
			$MINUTO_FIN=$this->input->post('MINUTO_FIN');
			if($HORA_FIN!=0){
				$HORA_FIN=$HORA_FIN;
			}else{
				$HORA_FIN=0;
			}
			
			if($MINUTO_FIN!=0){
				$MINUTO_FIN=$MINUTO_FIN;
			}else{
				$MINUTO_FIN=0;
			}	
			$HOR_HORAFIN=$HORA_FIN.".".$MINUTO_FIN;
			
			$HOR_DIA=prepCampoAlmacenar($this->input->post('dia'));
        	$sqlHORARIOVALIDA="select count(*) NUM_HORARIO from HORARIO WHERE HOR_SEC_PERSONA='{$HOR_SEC_PERSONA }' and HOR_ESTADO=0";
			$NUM_HORARIO =$this->db->query($sqlHORARIOVALIDA)->row()->NUM_HORARIO ;
            
	
				$sql="UPDATE HORARIO SET
							HOR_SEC_PERSONA=$HOR_SEC_PERSONA,
                            HOR_SEC_MATERIA=$HOR_SEC_MATERIA,
							HOR_HORAINICIO=$HOR_HORAINICIO,
                            HOR_HORAFIN=$HOR_HORAFIN,
							HOR_DIA='$HOR_DIA'
                     WHERE HOR_SECUENCIAL=$HOR_SECUENCIAL";
                $this->db->query($sql);
               //print_r($sql);
               
        $HOR_SECUENCIAL=$this->db->query("select max(HOR_SECUENCIAL) SECUENCIAL from HORARIO")->row()->SECUENCIAL;
		 echo json_encode(array("cod"=>$HOR_SECUENCIAL,"numero"=>$HOR_SECUENCIAL,"mensaje"=>"Horario: ".$HOR_SECUENCIAL.", editado con éxito"));    
    } 
}
?>