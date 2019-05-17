<?php
class Mjornada extends CI_Model {
   
   //Funcion en la cual muestra cada seleccion que ingresemos
   function getdatosItems(){
        $datos = new stdClass();
        $consulta=$_POST['_search'];
        $numero=  $this->input->post('numero');
        $datos->econdicion ='JOR_ESTADO<>1';
		$user=$this->session->userdata('US_CODIGO');
                
        if ($this->lib_usuarios->getAccesoSeccion($this->session->userdata('US_CODIGO'),'ADMS')!=1){
			$SQLINFOUSER="SELECT US_SECUENCIAL,US_CEDULA,US_NOMBRES,US_APELLIDOS 
							FROM ISTCRE_APLICACIONES.USUARIO 
							WHERE US_CODIGO='$user' AND US_ESTADO=0";
							
			$JORNADA=$this->db->query($SQLINFOUSER)->row()->JOR_SECUENCIAL;
			$datos->econdicion ="JOR_SECUENCIAL='$JORNADA'";
		}      
              $datos->campoId = "ROWNUM";
			   $datos->camposelect = array("ROWNUM",
											"JOR_SECUENCIAL",
											"JOR_NOMBRE",
											"(REPLACE(TO_CHAR(JOR_HORAINICIO,'00.90'),'.',':')) JOR_HORAINICIO",
											"(REPLACE(TO_CHAR(JOR_HORAFIN,'00.90'),'.',':')) JOR_HORAFIN",
											"JOR_ESTADO");
			  $datos->campos = array( "ROWNUM",
											"JOR_SECUENCIAL",
											"JOR_NOMBRE",
											"JOR_HORAINICIO",
											"JOR_HORAFIN",
											"JOR_ESTADO");
			  $datos->tabla="JORNADA";
              $datos->debug = false;	
           return $this->jqtabla->finalizarTabla($this->jqtabla->getTabla($datos), $datos);
   }
   
   //Datos que seran enviados para la edicion o visualizacion de cada registro seleccionado
   function dataJornada($numero){
	   $sql="select 
	   JOR_SECUENCIAL,
	   JOR_NOMBRE,
	   JOR_HORAINICIO,
	   JOR_HORAFIN,
	   JOR_ESTADO
			FROM JORNADA WHERE JOR_SECUENCIAL=$numero";           
         $sol=$this->db->query($sql)->row();
         if ( count($sol)==0){
                $sql="select 
				JOR_SECUENCIAL,
				JOR_NOMBRE,
				JOR_HORAINICIO,
				JOR_HORAFIN,
				JOR_ESTADO
					FROM JORNADA WHERE JOR_SECUENCIAL=$numero";
                         $sol=$this->db->query($sql)->row();
						}
          return $sol;
		}
    	
	//funcion para crear un nuevo reporte o cabecera
    function agrJornada(){
			

			
			//VARIABLES DE INGRESO
			
			$JOR_NOMBRE=$this->input->post('JOR_NOMBRE');

			//INGRESO HORAINICIAL	
			$HORA_INICIO=$this->input->post('HORA_INICIO');
			$MINUTO_INICIO=$this->input->post('MINUTO_INICIO');
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
			$JOR_HORAINICIO=$HORA_INICIO.".".$MINUTO_INICIO;
			
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
			$JOR_HORAFIN=$HORA_FIN.".".$MINUTO_FIN;
			
			
        	$sqlJORNADAVALIDA="select count(*) NUM_JORNADA from JORNADA WHERE JOR_NOMBRE='{$JOR_NOMBRE }' and JOR_ESTADO=0";
			$NUM_JORNADA =$this->db->query($sqlJORNADAVALIDA)->row()->NUM_JORNADA ;

				//validación...
			$sqlREPETICION="select count(*) NUM_JORNADA 
			from jornada
			where upper(jor_nombre)=upper('{$JOR_NOMBRE}') 
			and jor_estado=0";
		$NUM_JORNADA=$this->db->query($sqlREPETICION)->row()->NUM_JORNADA;

		if($NUM_JORNADA==0){

				$sql="INSERT INTO JORNADA (
							JOR_NOMBRE,
							JOR_HORAINICIO,
							JOR_HORAFIN,
							JOR_ESTADO
							)VALUES (
							'$JOR_NOMBRE',
							$JOR_HORAINICIO,
							$JOR_HORAFIN,
							0)";
            $this->db->query($sql);
            //print_r($sql);
			$JOR_SECUENCIAL=$this->db->query("select max(JOR_SECUENCIAL) SECUENCIAL from JORNADA")->row()->SECUENCIAL;
			echo json_encode(array("cod"=>$JOR_SECUENCIAL,"numero"=>$JOR_SECUENCIAL,"mensaje"=>"Jornada: ".$JOR_SECUENCIAL.", insertado con éxito"));    
	}else {
		echo json_encode(array("cod"=>1,"numero"=>1,"mensaje"=>"!!!...La jornada ingresada ya existe...!!!"));
	}
 }
    
	//funcion para editar un registro selccionado
    function editJornada(){
			$JOR_SECUENCIAL=$this->input->post('JOR_SECUENCIAL');
			
			//VARIABLES DE INGRESO
			$JOR_NOMBRE=$this->input->post('JOR_NOMBRE');
			
			//INGRESO HORAINICIAL	
			$HORA_INICIO=$this->input->post('HORA_INICIO');
			$MINUTO_INICIO=$this->input->post('MINUTO_INICIO');
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
			$JOR_HORAINICIO=$HORA_INICIO.".".$MINUTO_INICIO;
			
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
			$JOR_HORAFIN=$HORA_FIN.".".$MINUTO_FIN;


				$sql="UPDATE JORNADA SET
							JOR_NOMBRE='$JOR_NOMBRE',
							JOR_HORAINICIO=$JOR_HORAINICIO,
							JOR_HORAFIN=$JOR_HORAFIN
                		WHERE JOR_SECUENCIAL=$JOR_SECUENCIAL";
         $this->db->query($sql);
		 //print_r($sql);
         echo json_encode(array("cod"=>1,"numero"=>$JOR_SECUENCIAL,"mensaje"=>"Jornada: ".$JOR_SECUENCIAL.", editado con éxito"));            
    }

}
?>