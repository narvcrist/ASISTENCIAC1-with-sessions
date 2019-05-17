<?php
class Materia extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('mmateria');
        $this->load->model('mvarios');
    }
    
       function index(){
            $datos['permiso']=$this->lib_usuarios->getAccesoSeccion($this->session->userdata('US_CODIGO'),'ADMS');
			$datos['usuario']=$this->session->userdata('US_USUARIO');
            $this->load->view("materia/js/index_mats_js",$datos);
            $this->load->view("materia/index_mats_v",$datos);
        }
        
        function getdatosItems(){
            echo  $this->mmateria->getdatosItems();
        }
        	
	//funcion para cear una nueva materia
	public function nuevaMateria(){     
            $datos=$this->datos(null,'n');
            $datos['accion'] = 'n';
			$this->load->view("materia/js/materia_js",$datos);
            $this->load->view("materia/materia_v",$datos);            
        }
        
        //funcion para ver la informacion de una materia
        function verMateria($accion=null){
            $numero = $this->input->post('NUMERO');
            if(!empty($numero)){
                $sol = $this->mmateria->dataMateria($numero);
                      $USER=$this->session->userdata('US_CODIGO');
                      if ($accion=='v'|$accion=='e'|$accion=='x'|$accion=='a'){
                            $datos=$this->datos($sol,$accion);
                            $datos['sol']=$sol;
                            $datos['accion'] = $accion;
                            $this->load->view("materia/materia_v",$datos);
                            $this->load->view("materia/js/materia_js",$datos);
                      } else {
                            echo alerta("La acción no es reconocida");
                      }
            }else{
                echo alerta("No se puede mostrar el formulario, debe seleccionar una materia para continuar.");
            }
        }
        
        function datos($sol,$accion){
        if ($accion=='n') {
            //Caso para nueva materia
            $datos['combo_materia']=$this->cmb_materia(null,null," style='width:100px;' id='MAT_NOMBRE'");

        }else{
            //Caso para la edicion de una materia
            $materia=$sol->MAT_NOMBRE;			
            $datos['combo_materia']=$this->cmb_materia($materia,$sol->MAT_NOMBRE," style='width:100px;' id='MAT_NOMBRE'");				
        }
        return($datos);
    }

    //Combo para Nombre Materia
    function  cmb_materia($tipo = null, $attr = null) {
        $output = array();
        $output[null] = "Seleccione Materia";
	    $output['Leyes de Transito'] = "Leyes de Transito";
        $output['Educacion  Vial'] = "Educacion Vial";
        $output['Atencion al Cliente'] = "Atencion al Cliente";
        $output['Psicologia Aplicada'] = "Psicologia Aplicada";
        $output['Mecanica Basica'] = "Mecanica Basica";
        $output['Geografia del Ecuador'] = "Geografia del Ecuador";
        $output['Ingles'] = "Ingles";
        $output['Educacion Ambiental'] = "Educacion Ambiental";
        $output['Computacion'] = "Computacion";
        $output['Teoria de la Conduccion'] = "Teoria de la Conduccion";
        $output['Primeros Auxilios Basicos'] = "Primeros Auxilios Basicos";
        return form_dropdown('materia', $output, $tipo, $attr);
    }
	
	//Administra las funciones de nuevo y editar en una materia
    function admMateria($accion){
        switch($accion){
            case 'n':
                echo $this->mmateria->agrMateria();
                break;
            case 'e':
                echo $this->mmateria->editMateria();
                break;
        }        
    }
    
	//Cambia de estado a pasivo a un materia	
    function anulartoda(){
         $MAT_SECUENCIAL=$this->input->post('NUMERO');
            $SQL="update MATERIA set MAT_ESTADO=1 where MAT_SECUENCIAL=$MAT_SECUENCIAL"; 
            $this->db->query($SQL);
            echo json_encode(array("cod"=>1,"mensaje"=>highlight("Materia ".$MAT_SECUENCIAL." Eliminado, correctamente"))); 
		} 
}
?>