<?php
class Perxjor extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('mperxjor');
        $this->load->model('mvarios');
    }
    
       function index(){
        $datos['permiso']=$this->lib_usuarios->getAccesoSeccion($this->session->userdata('US_CODIGO'),'ADMS');
			$datos['usuario']=$this->session->userdata('US_USUARIO');
            $this->load->view("perxjor/js/index_perxjors_js",$datos);
            $this->load->view("perxjor/index_perxjors_v",$datos);
        }
        
        function getdatosItems(){
            echo  $this->mperxjor->getdatosItems();
        }
        	
	//funcion para cear una nueva persona para la jornada
	public function nuevaPerxjor(){     
            $datos=$this->datos(null,'n');
            $datos['accion'] = 'n';
			$this->load->view("perxjor/js/perxjor_js",$datos);
            $this->load->view("perxjor/perxjor_v",$datos);            
        }
        
        //funcion para ver la informacion de una persona para la jornada
        function verPerxjor($accion=null){
            $numero = $this->input->post('NUMERO');
            if(!empty($numero)){
                $sol = $this->mperxjor->dataPerxjor($numero);
                      $USER=$this->session->userdata('US_CODIGO');
                      if ($accion=='v'|$accion=='e'|$accion=='x'|$accion=='a'){
                            $datos=$this->datos($sol,$accion);
                            $datos['sol']=$sol;
                            $datos['accion'] = $accion;
                            $this->load->view("perxjor/perxjor_v",$datos);
                            $this->load->view("perxjor/js/perxjor_js",$datos);
                      } else {
                            echo alerta("La acción no es reconocida");
                      }
            }else{
                echo alerta("No se puede mostrar el formulario, debe seleccionar una persona para continuar.");
            }
        }
              
	//funcion para dar los valores a la cabecera tanto en nuevo, como al momento de editar
	function datos($sol,$accion){
        if ($accion=='n') {
            $datos['combo_jornada']=$this->mvarios->cmb_jornada(null," style='width:350px;' id='PERXJOR_SEC_JORNADA'");
            $datos['combo_persona']=$this->mvarios->cmb_persona(null," style='width:350px;' id='PERXJOR_SEC_PERSONA'");
			//$datos=null;		
		} else {
			$PERXJOR_SEC_JORNADA=$sol->PERXJOR_SEC_JORNADA;			
			$datos['combo_jornada'] = $this->mvarios->cmb_jornada($PERXJOR_SEC_JORNADA,"style='width:350px;' id='PERXJOR_SEC_JORNADA'");
            $PERXJOR_SEC_PERSONA=$sol->PERXJOR_SEC_PERSONA;			
			$datos['combo_persona'] = $this->mvarios->cmb_persona($PERXJOR_SEC_PERSONA,"style='width:350px;' id='PERXJOR_SEC_PERSONA'");
        }
        return($datos);
     }
	 
	//Administra las funciones de nuevo y editar en una persona para la jornada
    function admPerxjor($accion){
        switch($accion){
            case 'n':
                echo $this->mperxjor->agrPerxjor();
                break;
            case 'e':
                echo $this->mperxjor->editPerxjor();
                break;
        }        
    }
    
	//Cambia de estado a pasivo a un persona para la jornada	
    function anulartoda(){
         $PERXJOR_SECUENCIAL=$this->input->post('NUMERO');
            $SQL="update PERSONAXJORNADA set PERXJOR_ESTADO=1 where PERXJOR_SECUENCIAL=$PERXJOR_SECUENCIAL"; 
            $this->db->query($SQL);
            echo json_encode(array("cod"=>1,"mensaje"=>highlight("Jornada ".$PERXJOR_SECUENCIAL." Eliminado, correctamente"))); 
		} 
}
?>