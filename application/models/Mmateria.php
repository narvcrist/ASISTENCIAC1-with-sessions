<?php
class Mmateria extends CI_Model {
   
   //Funcion en la cual muestra cada seleccion que ingresemos
   function getdatosItems(){
        $datos = new stdClass();
        $consulta=$_POST['_search'];
        $numero=  $this->input->post('numero');
        $datos->econdicion ='MAT_ESTADO<>1';
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
											"MAT_SECUENCIAL",
											"MAT_NOMBRE",
											"(MAT_TIEMPODURACION || ' Dias') MAT_TIEMPODURACION",
											"(MAT_PORCENTAJE  || ' %') MAT_PORCENTAJE",
											"(MAT_CARGAHORARIA || ' Horas') MAT_CARGAHORARIA",
											"MAT_FECHAINGRESO",
											"MAT_RESPONSABLE",
											"MAT_ESTADO");
			  $datos->campos = array( "ROWNUM",
			  						  "MAT_SECUENCIAL",
			  						  "MAT_NOMBRE",
			  						  "MAT_TIEMPODURACION",
			  						  "MAT_PORCENTAJE",
			  						  "MAT_CARGAHORARIA",
			  						  "MAT_FECHAINGRESO",
			  						  "MAT_RESPONSABLE",
			  						  "MAT_ESTADO");
			  $datos->tabla="MATERIA";
              $datos->debug = false;	
           return $this->jqtabla->finalizarTabla($this->jqtabla->getTabla($datos), $datos);
   }
   
   //Datos que seran enviados para la edicion o visualizacion de cada registro seleccionado
   function dataMateria($numero){
	   $sql="select 
	        MAT_SECUENCIAL,
			MAT_NOMBRE,
			MAT_TIEMPODURACION,
			MAT_PORCENTAJE,
			MAT_CARGAHORARIA,
			MAT_FECHAINGRESO,
			MAT_RESPONSABLE,
			MAT_ESTADO
			FROM MATERIA WHERE MAT_SECUENCIAL=$numero";
           
         $sol=$this->db->query($sql)->row();
         if ( count($sol)==0){
                $sql="select 
				MAT_SECUENCIAL,
				MAT_NOMBRE,
				MAT_TIEMPODURACION,
				MAT_PORCENTAJE,
				MAT_CARGAHORARIA,
				MAT_FECHAINGRESO,
				MAT_RESPONSABLE,
				MAT_ESTADO
				FROM MATERIA WHERE MAT_SECUENCIAL=$numero";
                    $sol=$this->db->query($sql)->row();
						}
          return $sol;
		}
    	
	//funcion para crear un nuevo reporte o cabecera
    function agrMateria(){
			$sql="select to_char(SYSDATE,'MM/DD/YYYY') FECHA from dual";		
			$conn = $this->db->conn_id;
			$stmt = oci_parse($conn,$sql);
			oci_execute($stmt);
			$nsol=oci_fetch_row($stmt);
			oci_free_statement($stmt);            
			$MAT_FECHAINGRESO="TO_DATE('".$nsol[0]."','MM/DD/YYYY')";
			$MAT_RESPONSABLE=$this->session->userdata('US_CODIGO');
			
			//VARIABLES DE INGRESO
			$MAT_NOMBRE=$this->input->post('materia');
			$MAT_TIEMPODURACION=$this->input->post('MAT_TIEMPODURACION');					
			$MAT_PORCENTAJE=$this->input->post('MAT_PORCENTAJE');
			$MAT_CARGAHORARIA=$this->input->post('MAT_CARGAHORARIA');
			
				//Validacion
				$sqlREPETICION="select count(*) NUM_MATERIA 
				from materia 
				where mat_nombre='{$MAT_NOMBRE}'
				and mat_estado=0";
			$NUM_MATERIA=$this->db->query($sqlREPETICION)->row()->NUM_MATERIA;
		
		if($NUM_MATERIA==0){	
				$sql="INSERT INTO MATERIA (
							MAT_NOMBRE,
							MAT_TIEMPODURACION,
							MAT_PORCENTAJE,
							MAT_CARGAHORARIA,
							MAT_FECHAINGRESO,
							MAT_RESPONSABLE,
							MAT_ESTADO
							)VALUES 
							(
							'$MAT_NOMBRE',
							$MAT_TIEMPODURACION,
							$MAT_PORCENTAJE,
							$MAT_CARGAHORARIA,
							$MAT_FECHAINGRESO,
							'$MAT_RESPONSABLE',
							0)";
            $this->db->query($sql);
            //print_r($sql);
			$MAT_SECUENCIAL=$this->db->query("select max(MAT_SECUENCIAL) SECUENCIAL from MATERIA")->row()->SECUENCIAL;
			echo json_encode(array("cod"=>$MAT_SECUENCIAL,"numero"=>$MAT_SECUENCIAL,"mensaje"=>"Materia: ".$MAT_SECUENCIAL.", insertado con éxito"));    
    } else {
		echo json_encode(array("cod"=>1,"numero"=>1,"mensaje"=>"!!!...La Materia Ya Se Encuentra Ingresada...!!!"));

	}
	}
	//funcion para editar un registro selccionado
    function editMateria(){
			$MAT_SECUENCIAL=$this->input->post('MAT_SECUENCIAL');
			
			//VARIABLES DE INGRESO
			$MAT_NOMBRE=$this->input->post('materia');
			$MAT_TIEMPODURACION=$this->input->post('MAT_TIEMPODURACION');
			$MAT_PORCENTAJE=$this->input->post('MAT_PORCENTAJE');
			$MAT_CARGAHORARIA=$this->input->post('MAT_NUM_CARGAHORARIA');
			$sql="UPDATE MATERIA SET
			    			MAT_NOMBRE='$MAT_NOMBRE',
							MAT_TIEMPODURACION=$MAT_TIEMPODURACION,
							MAT_PORCENTAJE=$MAT_PORCENTAJE,
							MAT_CARGAHORARIA=$MAT_CARGAHORARIA,
							WHERE MAT_SECUENCIAL=$MAT_SECUENCIAL";
         $this->db->query($sql);
		 //print_r($sql);
         echo json_encode(array("cod"=>1,"numero"=>$MAT_SECUENCIAL,"mensaje"=>"Materia: ".$MAT_SECUENCIAL.", editado con éxito"));            
    }
}
?>
