<?php
class Asistencia extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('masistencia');
        $this->load->model('mvarios');
    }
    
       function index(){
            $datos['permiso']=$this->lib_usuarios->getAccesoSeccion($this->session->userdata('US_CODIGO'),'ADMS');
			$datos['usuario']=$this->session->userdata('US_USUARIO');
            $this->load->view("asistencia/js/index_asis_js",$datos);
            $this->load->view("asistencia/index_asis_v",$datos);
        }
        
        function getdatosItems(){
            echo  $this->masistencia->getdatosItems();
        }
        	
	//funcion para cear una nueva asistencia
	public function nuevaAsistencia(){     
            $datos=$this->datos(null,'n');
            $datos['accion'] = 'n';
			$this->load->view("asistencia/js/asistencia_js",$datos);
            $this->load->view("asistencia/asistencia_v",$datos);            
        }

            //funcion para dar los valores a la cabecera tanto en nuevo, como al momento de editar
            function datos($sol,$accion){
                if ($accion=='n') {   
                    $datos['combo_persona']=$this->mvarios->cmb_persona(null," style='width:350px;' id='ASIS_SEC_PERSONA'");
                    
                    //$datos=null;
                } else {
                    $ASIS_SEC_PERSONA=$sol->ASIS_SEC_PERSONA;
                    $datos['combo_persona']=$this->mvarios->cmb_persona($ASIS_SEC_PERSONA," style='width:350px;' id='ASIS_SEC_PERSONA'");
                    
					//INGRESO HORA INICIO
					$HORAINGRESO=$sol->ASIS_HORAINGRESO;			
					if($HORAINGRESO!=0){
						$HORAINGRESOARRAY = explode(".", $HORAINGRESO);				
						if(empty($HORAINGRESOARRAY[0])){
							$sol->HORA_INGRESO=0;
							$sol->MINUTO_INGRESO=zero_fill($HORAINGRESOARRAY[1],2);
						}else if(empty($HORAINGRESOARRAY[1])){
							$sol->HORA_INGRESO=zero_fill($HORAINGRESOARRAY[0],2);
							$sol->MINUTO_INGRESO=0;
						}else{
							$sol->HORA_INGRESO=zero_fill($HORAINGRESOARRAY[0],2);
							$sol->MINUTO_INGRESO=zero_fill($HORAINGRESOARRAY[1],2);
						}				
					}else{
						$sol->HORA_INGRESO=0;
						$sol->MINUTO_INGRESO=0;
					}
                    //$datos=null;
                }
               return($datos);
             }
        
        //funcion para ver la informacion de una asistencia
        function verAsistencia($accion=null){
            $numero = $this->input->post('NUMERO');
            if(!empty($numero)){
                $sol = $this->masistencia->dataAsistencia($numero);
                      $USER=$this->session->userdata('US_CODIGO');
                      if ($accion=='v'|$accion=='e'|$accion=='x'|$accion=='a'){
                            $datos=$this->datos($sol,$accion);
                            $datos['sol']=$sol;
                            $datos['accion'] = $accion;
                            $this->load->view("asistencia/asistencia_v",$datos);
                            $this->load->view("asistencia/js/asistencia_js",$datos);
                      } else {
                            echo alerta("La acción no es reconocida");
                      }
            }else{
                echo alerta("No se puede mostrar el formulario, debe seleccionar una sistencia para continuar.");
            }
        } 
    	
	//Administra las funciones de nuevo y editar en una calificacion
    function admAsistencia($accion){
        switch($accion){
            case 'n':
                echo $this->masistencia->agrAsistencia();
                break;
            case 'e':
                echo $this->masistencia->editAsistencia();
                break;
        }        
    }
    
	//Cambia de estado a pasivo a un	
    function anulartoda(){
         $ASIS_SECUENCIAL=$this->input->post('NUMERO');
            $SQL="update ASISTENCIA set ASIS_ESTADO=1 where ASIS_SECUENCIAL=$ASIS_SECUENCIAL"; 
            $this->db->query($SQL);
            echo json_encode(array("cod"=>1,"mensaje"=>highlight("Asistencia".$ASIS_SECUENCIAL." Eliminado, correctamente"))); 
		}
		
//Cargar Asistencia
public function cargarAsistencia(){
		$this->load->view("asistencia/carga_asistencia_v",$_POST);
		$this->load->view("asistencia/js/carga_asistencia_js",$_POST);
	}
	
//Función para subir los transmittal mediante el documento seleccionado	
 public function subirAsistencia(){
			//Nombre del archivo
			$fechaActual=date("Y-m-d");
			$nombreArchivo="asistencia_".$fechaActual;			
			$archivo="tmp/".$nombreArchivo.".txt";
			
			//Elimina Archivo si lo encuentra
			if(file_exists($archivo)){ 
				unlink($archivo);
			}
			//USUARIO IMPORTA
            $ASIS_RESPONSABLE=$this->session->userdata('US_CODIGO');
			
			$config['upload_path'] = "tmp";
			$config['file_name'] = $nombreArchivo;
			$config['allowed_types'] = 'txt|TXT';
			$config['max_size'] = '0';
			$this->load->library('upload', $config);
			if(!$this->upload->do_upload('userfile')){
				echo '<div class="error">'.$this->upload->display_errors().'</div>';
				$data['file_name'] = "Error";
			}else{
				$data = $this->upload->data();
			}
				$file = fopen($archivo, "r") or exit("Unable to open file!");
					//Output a line of the file until the end is reached
					while(!feof($file)){
						$lineaimp= fgets($file);
						$lineaArray = explode(",", $lineaimp);
						$SEC_PERSONA=trim($lineaArray[0]);
						$FECHA_INGRESO=trim($lineaArray[1]);
						
						$HORAINGRESOIMP=trim($lineaArray[2]);
						//INGRESO HORA INICIO
						$HORAINGRESO=$HORAINGRESOIMP;			
						if($HORAINGRESO!=0){
							$HORAINGRESOARRAY = explode(":", $HORAINGRESO);				
							if(empty($HORAINGRESOARRAY[0])){
								$HORA_INGRESO=0;
								$MINUTO_INGRESO=zero_fill($HORAINGRESOARRAY[1],2);
							}else if(empty($HORAINGRESOARRAY[1])){
								$HORA_INGRESO=zero_fill($HORAINGRESOARRAY[0],2);
								$MINUTO_INGRESO=0;
							}else{
								$HORA_INGRESO=zero_fill($HORAINGRESOARRAY[0],2);
								$MINUTO_INGRESO=zero_fill($HORAINGRESOARRAY[1],2);
							}				
						}else{
							$HORA_INGRESO=0;
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
									WHERE PER.PER_SECUENCIAL=$SEC_PERSONA
										AND PER.PER_ESTADO=0
										AND $ASIS_HORAINGRESO BETWEEN JOR.JOR_HORAINICIO AND JOR.JOR_HORAFIN";
						$NUM_JOR=$this->db->query($SQLJORNADA)->row()->NUM_JOR;
						if($NUM_JOR==1){
								
								//Verifica si el registro ya esta ingresado
								$SQLREG="SELECT COUNT(*) NUM_ASIS FROM ASISTENCIA 
											where ASIS_SEC_PERSONA=$SEC_PERSONA
												AND ASIS_FECHAINGRESO=TO_DATE('$FECHA_INGRESO','DD/MM/YYYY')
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
												$SEC_PERSONA,$ASIS_HORAINGRESO,TO_DATE('".$FECHA_INGRESO."','DD/MM/YYYY'),'$ASIS_RESPONSABLE',0)";
												$this->db->query($sqlImporta);
												//print_r($sqlImporta);
								}							
						}						
					}
					fclose($file);				
				echo json_encode($data);
		}		
}
?>