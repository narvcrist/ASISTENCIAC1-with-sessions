<?php
class Mjorxmat extends CI_Model {
   
   //Funcion en la cual muestra cada seleccion que ingresemos
   function getdatosItems(){
        $datos = new stdClass();
        $consulta=$_POST['_search'];
        $numero=  $this->input->post('numero');
        $datos->econdicion ='JORXMAT_ESTADO<>1';
		$user=$this->session->userdata('US_CODIGO');
                
        if ($this->lib_usuarios->getAccesoSeccion($this->session->userdata('US_CODIGO'),'ADMS')!=1){
			$SQLINFOUSER="SELECT US_SECUENCIAL,US_CEDULA,US_NOMBRES,US_APELLIDOS 
							FROM ISTCRE_APLICACIONES.USUARIO 
							WHERE US_CODIGO='$user' AND US_ESTADO=0";
							
			$MATERIA=$this->db->query($SQLINFOUSER)->row()->MAT_SECUENCIAL;
			$datos->econdicion ="MAT_SECUENCIAL='$MATERIA'";
        }
        
               $datos->campoId = "ROWNUM";
			   $datos->camposelect = array("ROWNUM",
											"JORXMAT_SECUENCIAL",
                                            "(select JOR_NOMBRE from JORNADA where JOR_SECUENCIAL=JORXMAT_SEC_JORNADA) JORXMAT_SEC_JORNADA",
                                            "(select MAT_NOMBRE from MATERIA where MAT_SECUENCIAL=JORXMAT_SEC_MATERIA) JORXMAT_SEC_MATERIA",
											"JORXMAT_ESTADO");
			  $datos->campos = array( "ROWNUM",
										"JORXMAT_SECUENCIAL",
										"JORXMAT_SEC_JORNADA",
										"JORXMAT_SEC_MATERIA",
										"JORXMAT_ESTADO");
			  $datos->tabla="JORNADAXMATERIA";
              $datos->debug = false;	
           return $this->jqtabla->finalizarTabla($this->jqtabla->getTabla($datos), $datos);
   }
   
   //Datos que seran enviados para la edicion o visualizacion de cada registro seleccionado
   function dataJorxmat($numero){
       $sql="select
                JORXMAT_SECUENCIAL,
                JORXMAT_SEC_JORNADA,
                JORXMAT_SEC_MATERIA,
                JORXMAT_ESTADO 
          FROM JORNADAXMATERIA WHERE JORXMAT_SECUENCIAL=$numero";
         $sol=$this->db->query($sql)->row();
         if ( count($sol)==0){
                $sql="select
                        JORXMAT_SECUENCIAL,
                        JORXMAT_SEC_JORNADA,
                        JORXMAT_SEC_MATERIA,
                        JORXMAT_ESTADO  
          FROM JORNADAXMATERIA WHERE JORXMAT_SECUENCIAL=$numero";
                         $sol=$this->db->query($sql)->row();
						}
          return $sol;
		}
    	
	//funcion para crear un nuevo reporte o cabecera
    function agrJorxmat(){	
			//VARIABLES DE INGRESO
            $JORXMAT_SEC_JORNADA=$this->input->post('jornada');
            $JORXMAT_SEC_MATERIA=$this->input->post('materia');	
            //validación...
			$sqlREPETICION="select count(*) NUM_JORNADAXMATERIA
                    from jornadaxmateria
                    where jorxmat_sec_jornada='{$JORXMAT_SEC_JORNADA}'
                    and JORXMAT_SEC_MATERIA='{$JORXMAT_SEC_MATERIA}'
                    and jorxmat_estado=0";
             $NUM_JORNADAXMATERIA=$this->db->query($sqlREPETICION)->row()->NUM_JORNADAXMATERIA;
            if($NUM_JORNADAXMATERIA==0){
				$sql="INSERT INTO JORNADAXMATERIA (
							JORXMAT_SEC_JORNADA,
                            JORXMAT_SEC_MATERIA,
                            JORXMAT_ESTADO) VALUES 
							('$JORXMAT_SEC_JORNADA',
                            '$JORXMAT_SEC_MATERIA',
							0)";
            $this->db->query($sql);
            //print_r($sql);
			$JORXMAT_SECUENCIAL=$this->db->query("select max(JORXMAT_SECUENCIAL) SECUENCIAL from JORNADAXMATERIA")->row()->SECUENCIAL;
			echo json_encode(array("cod"=>$JORXMAT_SECUENCIAL,"numero"=>$JORXMAT_SECUENCIAL,"mensaje"=>"Jornada: ".$JORXMAT_SECUENCIAL.", insertado con éxito"));    
    }else {
		echo json_encode(array("cod"=>1,"numero"=>1,"mensaje"=>"!!!...La Materia Ya Se Encuentra ingresada...!!!"));
	}
 }
    
	//funcion para editar un registro selccionado
    function editJorxmat(){
			$JORXMAT_SECUENCIAL=$this->input->post('JORXMAT_SECUENCIAL');
			
			//VARIABLES DE INGRESO
			$JORXMAT_SEC_JORNADA=$this->input->post('jornada');
            $JORXMAT_SEC_MATERIA=$this->input->post('materia');					

			
				$sql="UPDATE JORNADAXMATERIA SET
							JORXMAT_SEC_JORNADA='$JORXMAT_SEC_JORNADA',
							JORXMAT_SEC_MATERIA='$JORXMAT_SEC_MATERIA'
                 WHERE JORXMAT_SECUENCIAL=$JORXMAT_SECUENCIAL";
         $this->db->query($sql);
		 //print_r($sql);
         echo json_encode(array("cod"=>1,"numero"=>$JORXMAT_SECUENCIAL,"mensaje"=>"Joranada: ".$JORXMAT_SECUENCIAL.", editado con éxito"));            
    }

}
?>