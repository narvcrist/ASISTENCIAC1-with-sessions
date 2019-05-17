<?php
class Jornada extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('mjornada');
        $this->load->model('mvarios');
    }
    
       function index(){
        $datos['permiso']=$this->lib_usuarios->getAccesoSeccion($this->session->userdata('US_CODIGO'),'ADMS');
			$datos['usuario']=$this->session->userdata('US_USUARIO');
            $this->load->view("jornada/js/index_jors_js",$datos);
            $this->load->view("jornada/index_jors_v",$datos);
        }
        
        function getdatosItems(){
            echo  $this->mjornada->getdatosItems();
        }
        	
	//funcion para cear una nueva jornada
	public function nuevaJornada(){     
            $datos=$this->datos(null,'n');
            $datos['accion'] = 'n';
			$this->load->view("jornada/js/jornada_js",$datos);
            $this->load->view("jornada/jornada_v",$datos);            
        }
        
        //funcion para ver la informacion de una jornada
        function verJornada($accion=null){
            $numero = $this->input->post('NUMERO');
            if(!empty($numero)){
                $sol = $this->mjornada->dataJornada($numero);
                      $USER=$this->session->userdata('US_CODIGO');
                      if ($accion=='v'|$accion=='e'|$accion=='x'|$accion=='a'){
                            $datos=$this->datos($sol,$accion);
                            $datos['sol']=$sol;
                            $datos['accion'] = $accion;
                            $this->load->view("jornada/jornada_v",$datos);
                            $this->load->view("jornada/js/jornada_js",$datos);
                      } else {
                            echo alerta("La acción no es reconocida");
                      }
            }else{
                echo alerta("No se puede mostrar el formulario, debe seleccionar una jornada para continuar.");
            }
        }
		
		function datos($sol,$accion){
        if ($accion=='n') {
            $datos=null;
        }else{			
			//INGRESO HORA INICIO
			$HORAINICIO=$sol->JOR_HORAINICIO;			
			if($HORAINICIO!=0){
				$HORAINICIOARRAY = explode(".", $HORAINICIO);				
				if(empty($HORAINICIOARRAY[0])){
					$sol->HORA_INICIO=0;
					$sol->MINUTO_INICIO=zero_fill($HORAINICIOARRAY[1],2);
				}else if(empty($HORAINICIOARRAY[1])){
					$sol->HORA_INICIO=zero_fill($HORAINICIOARRAY[0],2);
					$sol->MINUTO_INICIO=0;
				}else{
					$sol->HORA_INICIO=zero_fill($HORAINICIOARRAY[0],2);
					$sol->MINUTO_INICIO=zero_fill($HORAINICIOARRAY[1],2);
				}				
			}else{
				$sol->HORA_INICIO=0;
				$sol->MINUTO_INICIO=0;
			}
			
			//INGRESO HORA INICIO
			$HORAFIN=$sol->JOR_HORAFIN;
			if($HORAFIN!=0){
				$HORAFINARRAY = explode(".", $HORAFIN);
				if(empty($HORAFINARRAY[0])){
					$sol->HORA_FIN=0;
					$sol->MINUTO_FIN=zero_fill($HORAFINARRAY[1],2);
				}else if(empty($HORAFINARRAY[1])){
					$sol->HORA_FIN=zero_fill($HORAFINARRAY[0],2);
					$sol->MINUTO_FIN=0;
				}else{
					$sol->HORA_FIN=zero_fill($HORAFINARRAY[0],2);
					$sol->MINUTO_FIN=zero_fill($HORAFINARRAY[1],2);
				}				
			}else{
				$sol->HORA_FIN=0;
				$sol->MINUTO_FIN=0;
			}
            $datos=null;
        }
        return($datos);
    }
   
	//Administra las funciones de nuevo y editar en una jornada
    function admJornada($accion){
        switch($accion){
            case 'n':
                echo $this->mjornada->agrJornada();
                break;
            case 'e':
                echo $this->mjornada->editJornada();
                break;
        }        
    }
    
	//Cambia de estado a pasivo a un jornada	
    function anulartoda(){
         $JOR_SECUENCIAL=$this->input->post('NUMERO');
            $SQL="update JORNADA set JOR_ESTADO=1 where JOR_SECUENCIAL=$JOR_SECUENCIAL"; 
            $this->db->query($SQL);
            echo json_encode(array("cod"=>1,"mensaje"=>highlight("Jornada ".$JOR_SECUENCIAL." Eliminado, correctamente"))); 
		} 
}
?>