<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


function prepararNombreArchivo($str = null){
        return is_string($str)?utf8_encode(regCaracteresEspecialers(trim(fullUpper($str)))):$str;
    }
	
    function prepCampoMostrar($str){
        return is_string($str)?utf8_encode(trim($str)):trim(utf8_encode($str));
    }
   
    function remplazar_puntosXcomas($str){
        return utf8_decode(str_replace(",",".",$str));
    }
    
    function eliminarElementosArray($arreglo = null, $elementos = null){
        foreach ($elementos as $indice){
            unset($arreglo[$indice]);
        }
        return $arreglo;
    } 
    
    function prepCampoAlmacenar($string){
    	return trim(utf8_decode(str_compat($string)));
    }
	
	function str_compat($str = null){
		return strtr($str, array(
    		"'"=>"''"
    	));
	}
    
    function regCaracteresEspecialers($str = null){
        return strtr($str, array(
    			"À"=>"A","È"=>"E","Ì"=>"I","Ò"=>"O","Ù"=>"U","Ñ"=>"N",
    			"Á"=>"A","É"=>"E","Í"=>"I","Ó"=>"O","Ú"=>"U","Â"=>"A",
    			"Ê"=>"E","Î"=>"I","Ô"=>"O","Û"=>"U","Ç"=>"C"," "=>"_",
                        "."=>""
    	));
    }
    
	//Funcion para ayuda de caracteres especiales
	function regCaracteresEspecialers2($str = null){
        return strtr($str, array(
    			"À"=>"A","È"=>"E","Ì"=>"I","Ò"=>"O","Ù"=>"U","Ñ"=>"N",
    			"Á"=>"A","É"=>"E","Í"=>"I","Ó"=>"O","Ú"=>"U","Â"=>"A",
    			"Ê"=>"E","Î"=>"I","Ô"=>"O","Û"=>"U","Ç"=>"C"," "=>" ",
                        "."=>""
    	));
    }
    
    function fullUpper($string){
    	return strtr(strtoupper($string), array(
    			"á"=>"Á", 
    			"é"=>"É",
    			"í"=>"Í",
    			"ó"=>"Ó",
    			"ú"=>"Ú",
    			"ñ"=>"Ñ",
    	));
    }

function highlight($str){
    $str = '<div class="ui-widget">
                <div class="ui-state-highlight ui-corner-all"style="margin: 10px 3px !important; padding: 0 .4em !important;">
                <p style=" margin: 3px !important;">
				
                <span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
				<strong>Aviso:</strong>
                '.$str.'
                </p>
                </div>
            </div>';
    return $str;
}

function alerta($str){
    $str = '<div class="ui-widget">
                <div class="ui-state-error ui-corner-all" style="margin: 10px 3px !important; padding: 0 .4em;">
                <p>
                <span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
                <strong>Alerta:</strong>
                '.$str.'
                </p>
                </div>
            </div>';    
    return $str;
}

function success($str){
	$str = '<div class="ui-widget">
                <div class="ui-state-success ui-corner-all" style="margin: 10px 3px !important; padding: 0 .4em;">
                <p>
               <span class="ui-icon ui-icon-circle-check" style="float: left; margin-right: .3em;"></span>
                <strong>Aviso:</strong>
                '.$str.'
                </p>
                </div>
            </div>';
	return $str;
}

function countchar ($string) {
$result = strlen ($string)  - substr_count($string, ' ');
return $result; 
} 

function unirArreglo($datos, $pega = ', '){
	$CI =& get_instance();
    $ac = array();
        foreach($datos as $ix => $e){
            $ac[] = "$ix=".$CI->db->escape($e);
        }
    return join($pega, $ac);
}

function jsOpcionTabla($datos){
    $ajs = array();
        foreach($datos as $f){
        	$attr = get_object_vars($f);
        	$a = array();
        	foreach($attr as $c){
        	   $a[] = $c;
        	}        	
        	$ajs[] = join(':', $a);
        }
        $cjs = '{value:"' . join(';', $ajs) . '"}';
        return $cjs;
}

function opcionesSelect($datos, $ponerTodos = 0,$vacio = null){
    $ao = array();
    if(is_array($ponerTodos)){
        $claves = array_keys($ponerTodos);
        $valores = array_values($ponerTodos);
        $ao[$claves[0]] = $valores[0];
    }
    foreach($datos as $f){
        $attr = get_object_vars($f);
    	$ac = array();
    	foreach($attr as $c){
    	   $ac[]  = getUTF8(trim($c));
    	}
    	$ao[$ac[0]] = $ac[1];
    }
	
    return $ao;
}

function opcionesChainedSelect($datos, $ponerTodos = 0){
  $respuesta = array();
  foreach(opcionesSelect($datos, $ponerTodos) as $ix => $el){
    array_push($respuesta, array($ix => $el));
  }
  return json_encode($respuesta);
}

function getUTF8($str){
  $esUTF8 = mb_detect_encoding($str, 'UTF-8', true);
  if(!$esUTF8){
    return utf8_encode($str);
  }
  return $str;
}

function selectHTML($datos){
	$ad = opcionesSelect($datos);
	$c = '<select>';
	foreach($ad as $ix => $v)
	{
		$c .= '<option value="'.$ix.'">'.$v.'</option>';
	}
	return $c.'</select>';
}

function minus2mayus($str)
{
		$str = strtoupper($str);
		return str_replace($minus, $mayus, $str);
}

function obtenerFechaEspanol($fecha){
  $fecha = strtoupper($fecha);
  $origen =  array('JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 
      'SEP', 'OCT', 'NOV', 'DEC' );
  $destino = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio','Julio', 'Agosto', 
      'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
  
  return str_replace($origen, $destino, $fecha);
}

function mes($fecha){
  $mes =  substr($fecha,3,2)-1; 
  $indice=0;
  $indice=$mes;
  $meses = array('ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN','JUL', 'AGO', 
      'SEP', 'OCT', 'NOV', 'DIC');
  
  return $meses[$indice].' '.substr($fecha,6,4);
}

function mesLetras($mes){
  $indice=ltrim($mes, "0");
  $meses = array('JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 
      'SEP', 'OCT', 'NOV', 'DEC' );  
  return $meses[$indice];
}
// ------------------------------------------------------------------------

/**
 * Generates HTML DIV tags
 *
 * @access	public
 * @param	integer
 * @return	string
 */
	function div($action = 0,$class = null, $id = null)
	{
		if(empty($action)){
			$output = "<div ";
			$output .= !empty($class)?"class='".$class."'":null;
			$output .= !empty($class)?"id='".$class."'":null;
			$output .= ">";
		}else{
			$output = "</div>";
		}
		return $output;
	}       
        
function getAccesoSeccion($UXR_UCOD = null,$USD_ALIAS = null){
        $CI =& get_instance();
		$sql="SELECT USP_SECUENCIAL FROM VW_USUARIOSXDIRXPER WHERE USUARIO='$UXR_UCOD' AND ALIAS='$USD_ALIAS'";
        $value = $CI->db->query($sql)->row_array();
        if(!empty($value['USP_SECUENCIAL'])){
            return true;
        }else{
            return false;
        }
    }
        
        function procesarExcel($archivo = null){
           $CI =& get_instance();
           $CI->load->library('PHPExcel');
           //$CI->load->library('PHPExcel/IOFactory');
           $objPHPExcel = new PHPExcel();

           $objReader = PHPExcel_IOFactory::createReader('Excel2007');
           $objPHPExcel = $objReader->load($archivo);
           $objPHPExcel->setActiveSheetIndex(0);
           // Lee registros
           $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
           $filas=array();
           $i = 0;
           foreach($rowIterator as $row){
               $cellIterator = $row->getCellIterator();
               $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
            //   if(1 == $row->getRowIndex ()) continue;//skip first row
               foreach ($cellIterator as $cell) {
                   $filas[$i][$cell->getColumn()]=trim($cell->getCalculatedValue());
               }
               $i++;
           }
       return $filas;
}

function generarEnlace($funcion=null){
         $server=$this->db->query("select PR_URL2 FROM F_PARAMETROS")->row();
         $rutaSinCodificar=$server->PR_URL2;
         $rutaACodificar = base64_encode($funcion);  
         return $rutaSinCodificar."varios/ruta/".$rutaACodificar;
      }
	  
function verificarCorreo($direccion)
{
   $Sintaxis='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
   if(preg_match($Sintaxis,$direccion))
      return true;
   else
     return false;
}

//Funcion para encriptar la clave de acceso
function encriptar($string) {
	$key='ISTCRE_GEST_APP';
	$result = '';
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
   }
   return base64_encode($result);
}

//Funcion para desencriptar la clave de acceso
function desincriptar($string) {
	$key='ISTCRE_GEST_APP';
	$result = '';
   $string = base64_decode($string);
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)-ord($keychar));
      $result.=$char;
   }
   return $result;
}	

//Funcion multiseleccion	
function pharseAmp($string = null){
		return empty($string)?null:utf8_decode(str_replace("&","&' || '",$string));
	}

//Funcion para select en combo
function select($name = 'cmb',$class=null,$data = null,$multiple = null,$number = 0){
		return "<select name='".$name."' id='".substr($name,0,$number)."' ".$multiple."  style='width:200px;' class='ui-widget ui-state-default ".$class."'>".$data."</select>";
	}

//Funcion para establecer opcion	
function option($selected = false, $value = null, $text = null){
		if (!isset($value)){
			return "<option value=''> ---- </option>";
		}
		if(empty($text)){
			$text = $value;
		}
		return "<option value='".prepCampoMostrar($value)."' . ".$selected.">".prepCampoMostrar($text)."</option>";		
	}

//Funcion de numero a meses 	
function numeroAMes($mesIn){
	if($mesIn<=12 and is_numeric($mesIn)){	
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");   
		return $meses[$mesIn-1];
	}else{
		return null;  	
	}  
}

function eliminarDir($carpeta){
		foreach(glob($carpeta . "/*") as $archivos_carpeta)
			{
			echo $archivos_carpeta; 
				if (is_dir($archivos_carpeta)){
					eliminarDir($archivos_carpeta);
				}else{
					unlink($archivos_carpeta);
				}
			} 
		rmdir($carpeta);
		}
		
function zero_fill ($valor, $long = 0)
{
    return str_pad($valor, $long, '0', STR_PAD_LEFT);
}		

?>
