<?php
class Horario extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('mhorario');
        $this->load->model('mvarios');
    }
    
       function index(){
        $datos['permiso']=$this->lib_usuarios->getAccesoSeccion($this->session->userdata('US_CODIGO'),'ADMS');
			$datos['usuario']=$this->session->userdata('US_USUARIO');
            $this->load->view("horario/js/index_hor_js",$datos);
            $this->load->view("horario/index_hor_v",$datos);
        }
        
        function getdatosItems(){
           echo  $this->mhorario->getdatosItems();
        }
        	
	//funcion para cear un nuevo horario
	public function nuevoHorario(){     
            $datos=$this->datos(null,'n');
            $datos['accion'] = 'n';
			$this->load->view("horario/js/horario_js",$datos);
            $this->load->view("horario/horario_v",$datos);            
        }
        
        //funcion para ver la informacion de un horario
        function verHorario($accion=null){
            $numero = $this->input->post('NUMERO');
            if(!empty($numero)){
                $sol = $this->mhorario->dataHorario($numero);
                      $USER=$this->session->userdata('US_CODIGO');
                      if ($accion=='v'|$accion=='e'|$accion=='x'|$accion=='a'){
                            $datos=$this->datos($sol,$accion);
                            $datos['sol']=$sol;
                            $datos['accion'] = $accion;
                            $this->load->view("horario/horario_v",$datos);
                            $this->load->view("horario/js/horario_js",$datos);
                      } else {
                            echo alerta("La acción no es reconocida");
                      }
            }else{
                echo alerta("No se puede mostrar el formulario, debe seleccionar una materia para continuar.");
            }
        }
              
	//funcion para dar los valores a la cabecera tanto en nuevo, como al momento de editar
	function datos($sol,$accion){
        if ($accion=='n') {
            $datos['combo_persona']=$this->mvarios->cmb_persona(null," style='width:800px;' id='HOR_SEC_PERSONA'");
            $datos['combo_materia']=$this->mvarios->cmb_materia(null," style='width:800px;' id='HOR_SEC_MATERIA'");
            $datos['combo_dia']=$this->cmb_dia(null," style='width:100px;' id='HOR_DIA'");
            //$datos=null;
		} else {
            $HOR_SEC_PERSONA=$sol->HOR_SEC_PERSONA;
            $datos['combo_persona']=$this->mvarios->cmb_persona($HOR_SEC_PERSONA," style='width:800px;' id='HOR_SEC_PERSONA'");  
            $HOR_SEC_MATERIA=$sol->HOR_SEC_MATERIA;
            $datos['combo_materia']=$this->mvarios->cmb_materia($HOR_SEC_MATERIA,"style='width:800px;' id='HOR_SEC_MATERIA'");
            
            //INGRESO HORA INICIO
			$HORAINICIO=$sol->HOR_HORAINICIO;			
			if($HORAINICIO!=0){
				$HORAINICIOARRAY = explode(".", $HORAINICIO);
				if(empty($HORAINICIOARRAY[0])){
					$sol->HORA_INICIO=0;
					$sol->MINUTO_INICIO=$HORAINICIOARRAY[1];
				}else if(empty($HORAINICIOARRAY[1])){
					$sol->HORA_INICIO=$HORAINICIOARRAY[0];
					$sol->MINUTO_INICIO=0;
				}else{
					$sol->HORA_INICIO=$HORAINICIOARRAY[0];
                    $sol->MINUTO_INICIO=$HORAINICIOARRAY[1];
                    
                    $dia=$sol->HOR_DIA;
                    $datos['combo_dia']=$this->cmb_dia($dia,$sol->HOR_DIA," style='width:100px;' id='HOR_DIA'");
				}				
			}else{
				$sol->HORA_INICIO=0;
				$sol->MINUTO_INICIO=0;
			}
			
			//INGRESO HORA INICIO
			$HORAFIN=$sol->HOR_HORAFIN;
			if($HORAFIN!=0){
				$HORAFINARRAY = explode(".", $HORAFIN);
				if(empty($HORAFINARRAY[0])){
					$sol->HORA_FIN=0;
					$sol->MINUTO_FIN=$HORAFINARRAY[1];
				}else if(empty($HORAFINARRAY[1])){
					$sol->HORA_FIN=$HORAFINARRAY[0];
					$sol->MINUTO_FIN=0;
				}else{
					$sol->HORA_FIN=$HORAFINARRAY[0];
                    $sol->MINUTO_FIN=$HORAFINARRAY[1];
                    $dia=$sol->HOR_DIA;
                    $datos['combo_dia']=$this->cmb_dia($dia,$sol->HOR_DIA," style='width:100px;' id='HOR_DIA'");
                    
				}				
			}else{
				$sol->HORA_FIN=0;
                $sol->MINUTO_FIN=0;
                
			}
            //$datos=null;
        }
        return($datos);
    }
	 
	 //Combo para dias de la semana 
    function  cmb_dia($tipo = null, $attr = null) {
        $output = array();
        $output[null] = "Día....";
        $output['Lunes'] = "Lunes";
        $output['Martes'] = "Martes";
        $output['Miércoles'] = "Miércoles";
        $output['Jueves'] = "Jueves";
        $output['Viernes'] = "Viernes";
        $output['Sábado'] = "Sábado";
        $output['Domingo'] = "Domingo";
        
        return form_dropdown('dia', $output, $tipo, $attr);
    }	
	 
	//Administra las funciones de nuevo y editar horario
    function admHorario($accion){
        switch($accion){
            case 'n':
                echo $this->mhorario->agrHorario();
                break;
            case 'e':
                echo $this->mhorario->editHorario();
                break;
        }        
    }
	//Cambia de estado a pasivo un horario
    function anulartoda(){
         $HOR_SECUENCIAL=$this->input->post('NUMERO');
            $SQL="update HORARIO set HOR_ESTADO=1 where HOR_SECUENCIAL=$HOR_SECUENCIAL"; 
            $this->db->query($SQL);
            echo json_encode(array("cod"=>1,"mensaje"=>highlight("Horario ".$HOR_SECUENCIAL." Eliminado, correctamente"))); 
		} 
}
?>