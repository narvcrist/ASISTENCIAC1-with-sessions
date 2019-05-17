<?php
class Jorxmat extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('mjorxmat');
        $this->load->model('mvarios');
    }
    
       function index(){
            $datos['permiso']=$this->lib_usuarios->getAccesoSeccion($this->session->userdata('US_CODIGO'),'ADMS');
			$datos['usuario']=$this->session->userdata('US_USUARIO');
            $this->load->view("jorxmat/js/index_jorxmats_js",$datos);
            $this->load->view("jorxmat/index_jorxmats_v",$datos);
        }
        
        function getdatosItems(){
            echo  $this->mjorxmat->getdatosItems();
        }
        	
	//funcion para cear una nueva persona para la licencia
	public function nuevaJorxmat(){     
            $datos=$this->datos(null,'n');
            $datos['accion'] = 'n';
			$this->load->view("jorxmat/js/jorxmat_js",$datos);
            $this->load->view("jorxmat/jorxmat_v",$datos);            
        }
        
        //funcion para ver la informacion de una persona para la licencia
        function verJorxmat($accion=null){
            $numero = $this->input->post('NUMERO');
            if(!empty($numero)){
                $sol = $this->mjorxmat->dataJorxmat($numero);
                      $USER=$this->session->userdata('US_CODIGO');
                      if ($accion=='v'|$accion=='e'|$accion=='x'|$accion=='a'){
                            $datos=$this->datos($sol,$accion);
                            $datos['sol']=$sol;
                            $datos['accion'] = $accion;
                            $this->load->view("jorxmat/jorxmat_v",$datos);
                            $this->load->view("jorxmat/js/jorxmat_js",$datos);
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
            $datos['combo_jornada']=$this->mvarios->cmb_jornada(null," style='width:300px;' id='JORXMAT_SEC_JORNADA'");
			$datos['combo_materia']=$this->mvarios->cmb_materia(null," style='width:300px;' id='JORXMAT_SEC_MATERIA'");
			//$datos=null;		
		} else {
			$JORXMAT_SEC_JORNADA=$sol->JORXMAT_SEC_JORNADA;			
			$datos['combo_jornada'] = $this->mvarios->cmb_jornada($JORXMAT_SEC_JORNADA,"style='width:300px;' id='JORXMAT_SEC_JORNADA'");
            $JORXMAT_SEC_MATERIA=$sol->JORXMAT_SEC_MATERIA;			
			$datos['combo_materia'] = $this->mvarios->cmb_materia($JORXMAT_SEC_MATERIA,"style='width:300px;' id='JORXMAT_SEC_MATERIA'");

        }
        return($datos);
     }
	 
	//Administra las fonciones de nuevo y editar en una persona para la licencia
    function admJorxmat($accion){
        switch($accion){
            case 'n':
                echo $this->mjorxmat->agrJorxmat();
                break;
            case 'e':
                echo $this->mjorxmat->editJorxmat();
                break;
        }        
    }
    
	//Cambia de estado a pasivo a un persona para la licencia	
    function anulartoda(){
         $JORXMAT_SECUENCIAL=$this->input->post('NUMERO');
            $SQL="update JORNADAXMATERIA set JORXMAT_ESTADO=1 where JORXMAT_SECUENCIAL=$JORXMAT_SECUENCIAL"; 
            $this->db->query($SQL);
            echo json_encode(array("cod"=>1,"mensaje"=>highlight("Jornada ".$JORXMAT_SECUENCIAL." Eliminado, correctamente"))); 
		} 
}
?>