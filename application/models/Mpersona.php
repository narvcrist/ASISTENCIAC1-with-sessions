<?php
class Mpersona extends CI_Model {
   
   //Funcion en la cual muestra cada seleccion que ingresemos
   function getdatosItems(){
        $datos = new stdClass();
        $consulta=$_POST['_search'];
        $numero=  $this->input->post('numero');
        $datos->econdicion ='PER_ESTADO<>1';
		$user=$this->session->userdata('US_CODIGO');
		
		if ($this->lib_usuarios->getAccesoSeccion($this->session->userdata('US_CODIGO'),'ADMS')!=1){
			$SQLINFOUSER="SELECT US_SECUENCIAL,US_CEDULA,US_NOMBRES,US_APELLIDOS 
							FROM ISTCRE_APLICACIONES.USUARIO 
							WHERE US_CODIGO='$user' AND US_ESTADO=0";
							
			$USERCEDULA=$this->db->query($SQLINFOUSER)->row()->US_CEDULA;
			$datos->econdicion ="PER_CEDULA='$USERCEDULA'";
		}
                
              $datos->campoId = "ROWNUM";
			   $datos->camposelect = array("ROWNUM",
											"PER_SECUENCIAL",
											"PER_FECHAINGRESO",
											"PER_CEDULA",
											"PER_NOMBRES",
											"PER_APELLIDOS",
											"PER_RESPONSABLE",
											"PER_ESTADO");
			  $datos->campos = array( "ROWNUM",
										"PER_SECUENCIAL",
											"PER_FECHAINGRESO",
											"PER_CEDULA",
											"PER_NOMBRES",
											"PER_APELLIDOS",
											"PER_RESPONSABLE",
											"PER_ESTADO");
			  $datos->tabla="PERSONA";
              $datos->debug = false;	
           return $this->jqtabla->finalizarTabla($this->jqtabla->getTabla($datos), $datos);
   }
   
   //Datos que seran enviados para la edicion o visualizacion de cada registro seleccionado
   function dataPersona($numero){
       $sql="select
				PER_SECUENCIAL,
				PER_FECHAINGRESO,
				PER_CEDULA,
				PER_NOMBRES,
				PER_APELLIDOS,
				PER_RESPONSABLE,
				PER_ESTADO
          FROM PERSONA WHERE PER_SECUENCIAL=$numero";
         $sol=$this->db->query($sql)->row();
         if ( count($sol)==0){
                $sql="select
							PER_SECUENCIAL,
							PER_FECHAINGRESO,
							PER_CEDULA,
							PER_NOMBRES,
							PER_APELLIDOS,
							PER_RESPONSABLE,
							PER_ESTADO
                          FROM PERSONA WHERE PER_SECUENCIAL=$numero";
                         $sol=$this->db->query($sql)->row();
						}
          return $sol;
		}
    	
	//funcion para crear un nuevo reporte o cabecera
    function agrPersona(){
			$sql="select to_char(SYSDATE,'MM/DD/YYYY HH24:MI:SS') FECHA from dual";		
			$conn = $this->db->conn_id;
			$stmt = oci_parse($conn,$sql);
			oci_execute($stmt);
			$nsol=oci_fetch_row($stmt);
			oci_free_statement($stmt);            
            $PER_FECHAINGRESO="TO_DATE('".$nsol[0]."','MM/DD/YYYY HH24:MI:SS')";
			$PER_RESPONSABLE=$this->session->userdata('US_CODIGO');
			
			//VARIABLES DE INGRESO
		
			$PER_CEDULA=prepCampoAlmacenar($this->input->post('PER_CEDULA'));			
			$PER_NOMBRES=prepCampoAlmacenar($this->input->post('PER_NOMBRES'));			
			$PER_APELLIDOS=prepCampoAlmacenar($this->input->post('PER_APELLIDOS'));			
			$PER_SECUENCIAL=$this->db->query("SELECT NVL(max(PER_SECUENCIAL),0)+1 SECUENCIAL from PERSONA")->row()->SECUENCIAL;
			
			//validación...
			$sqlREPETICION="select count(*) NUM_PERSONA
							from PERSONA
							where upper(PER_CEDULA)=upper('{$PER_CEDULA}') 
							and PER_ESTADO=0";
			$NUM_PERSONA=$this->db->query($sqlREPETICION)->row()->NUM_PERSONA;

			if($NUM_PERSONA==0){
			
				$sql="INSERT INTO PERSONA (
							PER_SECUENCIAL,
							PER_FECHAINGRESO,
							PER_CEDULA,
							PER_NOMBRES,
							PER_APELLIDOS,
							PER_RESPONSABLE,
							PER_ESTADO) VALUES 
							($PER_SECUENCIAL,
							$PER_FECHAINGRESO,
							'$PER_CEDULA',
							'$PER_NOMBRES',
							'$PER_APELLIDOS',
							'$PER_RESPONSABLE',
							0)";
			$this->db->query($sql);			
			echo json_encode(array("cod"=>$PER_SECUENCIAL,"numero"=>$PER_SECUENCIAL,"mensaje"=>"Estudiante: ".$PER_SECUENCIAL.", insertado con éxito"));   
		} else {
			echo json_encode(array("cod"=>1,"numero"=>1,"mensaje"=>"!!!...El Estudiante Ingresado Ya Se Encuentra Registrado...!!!"));
		} 
    }
    
	//funcion para editar un registro selccionado
    function editPersona(){
			
		$PER_SECUENCIAL=$this->input->post('PER_SECUENCIAL');	
			
			//VARIABLES DE INGRESO
			$PER_CEDULA=prepCampoAlmacenar($this->input->post('PER_CEDULA'));			
			$PER_NOMBRES=prepCampoAlmacenar($this->input->post('PER_NOMBRES'));			
			$PER_APELLIDOS=prepCampoAlmacenar($this->input->post('PER_APELLIDOS'));
			
			
			
				$sql="UPDATE PERSONA SET
							PER_CEDULA='$PER_CEDULA',
							PER_NOMBRES='$PER_NOMBRES',
							PER_APELLIDOS='$PER_APELLIDOS'
                 WHERE PER_SECUENCIAL=$PER_SECUENCIAL";
         $this->db->query($sql);
		 //print_r($sql);
         echo json_encode(array("cod"=>1,"numero"=>$PER_SECUENCIAL,"mensaje"=>"Estudiante: ".$PER_SECUENCIAL.", editado con éxito"));            
    }

}
?>