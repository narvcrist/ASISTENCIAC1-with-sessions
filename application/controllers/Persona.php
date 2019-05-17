<?php
class Persona extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('mpersona');
        $this->load->model('mvarios');
    }
    
       function index(){
            $datos['permiso']=$this->lib_usuarios->getAccesoSeccion($this->session->userdata('US_CODIGO'),'ADMS');
			$datos['usuario']=$this->session->userdata('US_USUARIO');
            $this->load->view("persona/js/index_pers_js",$datos);
            $this->load->view("persona/index_pers_v",$datos);
        }
        
        function getdatosItems(){
            echo  $this->mpersona->getdatosItems();
        }
        	
	//funcion para cear una nuevo estudiante
	public function nuevaPersona(){     
            $datos=$this->datos(null,'n');
            $datos['accion'] = 'n';
			$this->load->view("persona/js/persona_js",$datos);
            $this->load->view("persona/persona_v",$datos);            
        }
        
        //funcion para ver la informacion de un Estudiante
        function verPersona($accion=null){
            $numero = $this->input->post('NUMERO');
            if(!empty($numero)){
                $sol = $this->mpersona->dataPersona($numero);
                      $USER=$this->session->userdata('US_CODIGO');
                      if ($accion=='v'|$accion=='e'|$accion=='x'|$accion=='a'){
                            $datos=$this->datos($sol,$accion);
                            $datos['sol']=$sol;
                            $datos['accion'] = $accion;
                            $this->load->view("persona/persona_v",$datos);
                            $this->load->view("persona/js/persona_js",$datos);
                      } else {
                            echo alerta("La acción no es reconocida");
                      }
            }else{
                echo alerta("No se puede mostrar el formulario, debe seleccionar un estudiante para continuar.");
            }
        }
              
	//funcion para dar los valores a la cabecera tanto en nuevo, como al momento de editar
	function datos($sol,$accion){
        if ($accion=='n') {
			$datos=null;	
		} else {
			$datos=null;
        }
        return($datos);
     }
	
	//Administra las funciones de nuevo y editar en un Estudiante
    function admPersona($accion){
        switch($accion){
            case 'n':
                echo $this->mpersona->agrPersona();
                break;
            case 'e':
                echo $this->mpersona->editPersona();
                break;
        }        
    }
    
	//Cambia de estado a pasivo a un estudiante	
    function anulartoda(){
         $PER_SECUENCIAL=$this->input->post('NUMERO');
            $SQL="update PERSONA set PER_ESTADO=1 where PER_SECUENCIAL=$PER_SECUENCIAL"; 
            $this->db->query($SQL);
            echo json_encode(array("cod"=>1,"mensaje"=>highlight("Estudiante ".$PER_SECUENCIAL." Eliminado, correctamente"))); 
		}
		
//funcion para imprimir reporte general
  function fmtAsisGeneral(){
			
			//Ingreso de fecha a buscar
            $FECHAASIS=$this->input->post('FECHAASIS');
			$fecha = explode("-", $FECHAASIS);
			$anio=$fecha[0];
			$datos['anio']=$anio;
			$mes=$fecha[1];
			$datos['mesNum']=$mes;
			$datos['mes']=numeroAMes($mes);
			
            
			
			//Inicio de PDF
            $this->load->library('pdf');
            $this->pdf->SetSubject('Asistencia General');			
            $this->pdf->AddPage('P', 'A4');
			            
			//$datos['empleado']=$sol;
            
			$this->pdf->SetFont('times', '', 8);
			//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$this->pdf->SetMargins(20,20,20);
            $datos['titulo']='Asistencia General';
			$datos['subtitulo']="SUBTITULO";
            $datos['subtitulo2']='';
            $datos['subtitulo3']='';
			
				$this->pdf->writeHTML($this->load->view("persona/fmt_asisgeneral",$datos,true), true, false, true, false, '');
				$archivo='tmp/ASISTENCIA_GENERAL_'.$FECHAASIS.'.pdf';
				$this->pdf->Output($archivo, 'F');
				$mensaje = "<iframe src='{$archivo}' width='1020' height='700'></iframe>";
				echo json_encode(array("mensaje" => $mensaje));
				//echo json_encode(array("mensaje" => alerta("!!!...No Existen Registros de Empleado...!!!")));
			
      }
	  
//funcion para imprimir reporte individual
  function fmtAsisIndividual(){
			
			//Ingreso de parametros
			$PERSONA=$this->input->post('numero');
            $FECHAASIS=$this->input->post('FECHAASIS');
			$fecha = explode("-", $FECHAASIS);
			$anio=$fecha[0];
			$datos['anio']=$anio;
			$mes=$fecha[1];
			$datos['mesNum']=$mes;
			$datos['mes']=numeroAMes($mes);
            
			
			//Inicio de PDF
            $this->load->library('pdf');
            $this->pdf->SetSubject('Asistencia Individual');			
            $this->pdf->AddPage('P', 'A4');
			            
			$SQLPERSONA="select PER_SECUENCIAL,PER_NOMBRES,PER_APELLIDOS,PER_CEDULA 
							from PERSONA 
							WHERE PER_ESTADO=0 
							AND PER_SECUENCIAL=$PERSONA";
						$persona=$this->db->query($SQLPERSONA)->row();	
			$datos['estudiante']=$persona;
            
			$this->pdf->SetFont('times', '', 8);
			//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$this->pdf->SetMargins(20,20,20);
            $datos['titulo']='Asistencia Individual';
			$datos['subtitulo']="SUBTITULO";
            $datos['subtitulo2']='';
            $datos['subtitulo3']='';
			
				$this->pdf->writeHTML($this->load->view("persona/fmt_asisindividual",$datos,true), true, false, true, false, '');
				$archivo='tmp/ASISTENCIA_INDIVIDUAL_'.$persona->PER_CEDULA."_".$FECHAASIS.'.pdf';
				$this->pdf->Output($archivo, 'F');
				$mensaje = "<iframe src='{$archivo}' width='1020' height='700'></iframe>";
				echo json_encode(array("mensaje" => $mensaje));
				//echo json_encode(array("mensaje" => alerta("!!!...No Existen Registros de Empleado...!!!")));
			
      }	  
		
}
?>