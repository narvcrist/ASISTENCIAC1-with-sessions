<?php
class Mperxjor extends CI_Model {
   
   //Funcion en la cual muestra cada seleccion que ingresemos
   function getdatosItems(){
        $datos = new stdClass();
        $consulta=$_POST['_search'];
        $numero=  $this->input->post('numero');
        $datos->econdicion ='PERXJOR_ESTADO<>1';
		$user=$this->session->userdata('US_CODIGO');
                
		if ($this->lib_usuarios->getAccesoSeccion($this->session->userdata('US_CODIGO'),'ADMS')!=1){
			$SQLINFOUSER="SELECT PER_SECUENCIAL FROM PERSONA WHERE PER_CEDULA=(SELECT US_CEDULA 
							FROM ISTCRE_APLICACIONES.USUARIO 
							WHERE US_CODIGO='$user' AND US_ESTADO=0)";							
			$PERSONA=$this->db->query($SQLINFOUSER)->row()->PER_SECUENCIAL;
			$datos->econdicion ="PERXJOR_SEC_PERSONA='$PERSONA'";
		}
              $datos->campoId = "ROWNUM";
			   $datos->camposelect = array("ROWNUM",
											"PERXJOR_SECUENCIAL",
											"(select JOR_NOMBRE from JORNADA where JOR_SECUENCIAL=PERXJOR_SEC_JORNADA) PERXJOR_SEC_JORNADA",
											"(select CONCAT(CONCAT(PER_APELLIDOS,' '), PER_NOMBRES) from PERSONA where PER_SECUENCIAL=PERXJOR_SEC_PERSONA)PERXJOR_SEC_PERSONA",
											"PERXJOR_ESTADO");
			  $datos->campos = array( "ROWNUM",
										"PERXJOR_SECUENCIAL",
										"PERXJOR_SEC_JORNADA",
										"PERXJOR_SEC_PERSONA",
										"PERXJOR_ESTADO");
			  $datos->tabla="PERSONAXJORNADA";
              $datos->debug = false;	
           return $this->jqtabla->finalizarTabla($this->jqtabla->getTabla($datos), $datos);
   }
   
   //Datos que seran enviados para la edicion o visualizacion de cada registro seleccionado
   function dataPerxjor($numero){
       $sql="select
                PERXJOR_SECUENCIAL,
                PERXJOR_SEC_JORNADA,
                PERXJOR_SEC_PERSONA,
                PERXJOR_ESTADO  
          FROM PERSONAXJORNADA WHERE PERXJOR_SECUENCIAL=$numero";
         $sol=$this->db->query($sql)->row();
         if ( count($sol)==0){
                $sql="select
                        PERXJOR_SECUENCIAL,
                        PERXJOR_SEC_JORNADA,
                        PERXJOR_SEC_PERSONA,
                        PERXJOR_ESTADO  
          FROM PERSONAXJORNADA WHERE PERXJOR_SECUENCIAL=$numero";
                         $sol=$this->db->query($sql)->row();
						}
          return $sol;
		}
    	
	//funcion para crear un nuevo reporte o cabecera
    function agrPerxjor(){	
			//VARIABLES DE INGRESO
            $PERXJOR_SEC_JORNADA=$this->input->post('jornada');
            $PERXJOR_SEC_PERSONA=$this->input->post('persona');	

			//validación...
			$sqlREPETICION="select count(*) NUM_PERSONAXJORNADA 
                from personaxjornada
                where PERXJOR_SEC_PERSONA='{$PERXJOR_SEC_PERSONA}'
                and PERXJOR_SEC_JORNADA='{$PERXJOR_SEC_JORNADA}'
                and perxjor_estado=0";
            $NUM_PERSONAXJORNADA=$this->db->query($sqlREPETICION)->row()->NUM_PERSONAXJORNADA;

        if($NUM_PERSONAXJORNADA==0){
				$sql="INSERT INTO PERSONAXJORNADA(
							PERXJOR_SEC_JORNADA,
                            PERXJOR_SEC_PERSONA,
                            PERXJOR_ESTADO) VALUES 
							('$PERXJOR_SEC_JORNADA',
                            '$PERXJOR_SEC_PERSONA',
							0)";
            $this->db->query($sql);
            //print_r($sql);
			$PERXJOR_SECUENCIAL=$this->db->query("select max(PERXJOR_SECUENCIAL) SECUENCIAL from PERSONAXJORNADA")->row()->SECUENCIAL;
			echo json_encode(array("cod"=>$PERXJOR_SECUENCIAL,"numero"=>$PERXJOR_SECUENCIAL,"mensaje"=>"Jornada: ".$PERXJOR_SECUENCIAL.", insertado con éxito"));    
    }else {
		echo json_encode(array("cod"=>1,"numero"=>1,"mensaje"=>"!!!...La Jornada ya esta asignada...!!!"));
	}
 }
    
	//funcion para editar un registro selccionado
    function editPerxjor(){
			$PERXJOR_SECUENCIAL=$this->input->post('PERXJOR_SECUENCIAL');
			
			//VARIABLES DE INGRESO
			$PERXJOR_SEC_JORNADA=$this->input->post('jornada');
			$PERXJOR_SEC_PERSONA=$this->input->post('persona');	
			
			$sqlREPETICION1="select PERXJOR_SECUENCIAL,PERXJOR_SEC_JORNADA
			from personaxjornada
			where PERXJOR_SECUENCIAL='{$PERXJOR_SECUENCIAL}'
			and perxjor_estado=0";
			$repe1 =$this->db->query($sqlREPETICION1)->row();

			$sqlREPETICION2="select PERXJOR_SECUENCIAL,PERXJOR_SEC_JORNADA
			from personaxjornada
			where PERXJOR_SEC_JORNADA='{$PERXJOR_SEC_JORNADA}'
			and perxjor_estado=0";
			$repe2 =$this->db->query($sqlREPETICION2)->row();

			$sqlREPETICION="select count(*) NUM_REPETICION
			from personaxjornada
			where PERXJOR_SEC_JORNADA='{$PERXJOR_SEC_JORNADA}'
			and perxjor_estado=0";
			$NUM_REPETICION =$this->db->query($sqlREPETICION)->row()->NUM_REPETICION;

			if(($repe1->PERXJOR_SECUENCIAL==$repe2->PERXJOR_SECUENCIAL) or ($NUM_REPETICION==0)){
			
				$sql="UPDATE PERSONAXJORNADA SET
							PERXJOR_SEC_JORNADA='$PERXJOR_SEC_JORNADA',
							PERXJOR_SEC_PERSONA='$PERXJOR_SEC_PERSONA'
                 WHERE PERXJOR_SECUENCIAL=$PERXJOR_SECUENCIAL";
         $this->db->query($sql);
               //print_r($sql);
		 $HOR_SECUENCIAL=$this->db->query("select max(PERXJOR_SECUENCIAL) SECUENCIAL from PERSONAXJORNADA")->row()->SECUENCIAL;
		 echo json_encode(array("cod"=>$HOR_SECUENCIAL,"numero"=>$PERXJOR_SECUENCIAL,"mensaje"=>"Persona por jornada: ".$PERXJOR_SECUENCIAL.", editado con éxito"));    
	}else{     
		 echo json_encode(array("cod"=>1,"numero"=>1,"mensaje"=>"!!!...La Jornada ya esta asignada...!!!"));
        }            
    }

}
?>







         


