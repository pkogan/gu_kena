<?php
class ci_cargar extends toba_ci
{
    protected $s__claustro;
    protected $s__id_nro_ue;
    protected $s__id_sede;
    protected $s__nro_mesa;
    protected $s__id_mesa;
    
    protected $s__actas;
    protected $s__acta_directivo;
    protected $s__acta_superior;
    protected $s__acta_directivo_extra;
    
    protected $s__condicion_superior;
    protected $s__condicion_directivo;
    
    //---- Formulario -------------------------------------------------------------------

	function conf__formulario(toba_ei_formulario $form)
	{
            
	}
        
    //---- AJAX -------------------------------------------------------------------
    
        function ajax__cargar_sedes($id_ue, toba_ajax_respuesta $respuesta){
            $ar = $this->dep('datos')->tabla('sede')->get_descripciones($id_ue);
            $sedes = array();
            foreach($ar as $sede){
                $sedes[$sede['id_sede']] = $sede['nombre'];
            }//$a = array('a'=>'b', 'c'=>'d');
            
            $this->dep('formulario')->ef('id_sede')->set_opciones($sedes);
            $respuesta->set($sedes);
        }
        
        function ajax__cargar_mesas($parametros, toba_ajax_respuesta $respuesta){
            // $parametros[0] = id_sede
            // $parametros[1] = id_claustro
            $ar = $this->dep('datos')->tabla('mesa')->get_descripciones($parametros[0], $parametros[1]);
            $mesas = array();
            foreach($ar as $mesa){
                $mesas[$mesa['id_mesa']] = $mesa['nro_mesa'];
            }
            $this->dep('formulario')->ef('id_mesa')->set_opciones($mesas);
            $respuesta->set($mesas);
        }

	function evt__formulario__cargar($datos)
	{print_r($datos);
		$this->s__claustro = $datos['claustro'];
                $this->s__id_nro_ue = $datos['id_nro_ue'];
                $this->s__id_sede = $datos['id_sede'];
                $this->s__id_mesa = $datos['id_mesa'];
                
                
                $this->s__condicion_superior = $this->dep('datos')->tabla('lista_csuperior')->get_fecha_reciente();
                $this->s__condicion_directivo = $this->dep('datos')->tabla('lista_cdirectivo')->get_fecha_reciente();
            
                $this->set_pantalla('pant_actas');
	}

        //-----------------------------------------------------------------------------------
	//---- form_datos -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_datos(gu_kena_ei_formulario $form)
	{
            $ar['claustro'] = $this->dep('datos')->tabla('claustro')->get_descripcion($this->s__claustro);
            $ue = $this->dep('datos')->tabla('unidad_electoral')->get_descripciones($this->s__id_nro_ue);
            $ar['ue'] = $ue[0]['nombre'];
            $sede = $this->dep('datos')->tabla('sede')->get_descripcion($this->s__id_sede);
            $ar['sede'] = $sede;
            
            $mesa = $this->dep('datos')->tabla('mesa')->get_descripciones(null,null,$this->s__id_mesa);
            //print_r($mesa);
            $ar['nro_mesa'] = $mesa[0]['nro_mesa'];
            $ar['cant_empadronados'] = $mesa[0]['cant_empadronados'];
            
            return $ar;
	}
        
	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__pant_actas(toba_ei_pantalla $pantalla)
	{
            //primer parametro corresponde al id_mesa = de en acta
            $this->s__actas = $this->dep('datos')->tabla('acta')->get_ultimas_descripciones_de($this->s__id_mesa);
           //Si tengo tres actas asociadas a esta mesa ent muestro el form_ml_directivo_extra
           if(sizeof($this->s__actas) == 3)//form extra corresponde al cons. dir. del asentamiento
            $this->dep('form_ml_directivo_extra')->set_titulo('Consejo Directivo de Asentamiento Universitario');
           else //Sino colapsar
            $this->dep('form_ml_directivo_extra')->colapsar();
           print_r($this->s__actas);
           //Separacion de actas
           foreach($this->s__actas as $pos => $un_acta){
                //id_tipo=1 => superior
                //id_tipo=2 => directivo
                if($un_acta['id_tipo'] == 1)//acta superior
                        $this->s__acta_superior = $un_acta;
                else{
                    if(sizeof($this->s__actas) == 2 ){//No hay form extra
                        $this->s__acta_directivo = $un_acta;
                    }
                    else{//hay mas actas
                        if($un_acta['id_tipo'] == 1){//acta directivo
                            if($un_acta['de'] == $un_acta['para'])
                                $this->s__acta_directivo_extra = $un_acta;
                            else
                                $this->s__acta_directivo = $un_acta;
                        }
                    }
                }
                  
            }
//            print_r("directivo->");print_r($this->s__acta_directivo);
//            print_r("\n directivo_extra->");print_r($this->s__acta_directivo_extra);
//            print_r("\n superior->");print_r($this->s__acta_superior);
	}

	//-----------------------------------------------------------------------------------
	//---- form_ml_directivo ------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml_directivo(gu_kena_ei_formulario_ml $form_ml)
	{               
            $blancos['id_nro_lista'] = -1;
            $blancos['nombre'] = "VOTOS EN BLANCO";            
            $ar[0] = $blancos;
            
            $nulos['id_nro_lista'] = -2;
            $nulos['nombre'] = "VOTOS NULOS";
            $ar[1] = $nulos;
            
            $recurridos['id_nro_lista'] = -3;
            $recurridos['nombre'] = "VOTOS RECURRIDOS";
            $ar[2] = $recurridos;
            
            if(sizeof($this->s__acta_directivo)){
                $ar[0]['votos'] = $this->s__acta_directivo['total_votos_blancos'];
                $ar[1]['votos'] = $this->s__acta_directivo['total_votos_nulos'];
                $ar[2]['votos'] = $this->s__acta_directivo['total_votos_recurridos'];
            }
            
            $listas = $this->dep('datos')->tabla('lista_cdirectivo')->get_listas_actuales($this->s__claustro,$this->s__id_nro_ue);
            $ar = array_merge($listas, $ar);
            
            $form_ml->set_datos($ar);
	}

	
        function evt__form_ml_directivo__modificacion($datos)
	{            
            if(isset($this->s__acta_directivo)){
                $filtro['id_acta'] = $this->s__acta_directivo['id_acta'];
                $this->dep('datos')->tabla('acta')->cargar($filtro);
                
                //votos blancos tienen id_nro_lista=-1 
                //votos nulos tienen id_nro_lista=-2 
                //votos recurridos tienen id_nro_lista=-3
                foreach($datos as $pos => $dato){
                    switch($dato['id_nro_lista']){
                        case -1://Votos blancos
                            $datos_acta['total_votos_blancos'] = $dato['votos'];
                            break;
                        case -2://Votos nulos
                            $datos_acta['total_votos_nulos'] = $dato['votos'];
                            break;
                        case -3://Votos recurridos
                            $datos_acta['total_votos_recurridos'] = $dato['votos'];
                            break;
                        default://Votos de listas
                            
                            break;
                    }
                }
                $this->dep('datos')->tabla('acta')->set($datos_acta);
                $this->dep('datos')->tabla('acta')->sincronizar();
                $this->dep('datos')->tabla('acta')->resetear();
//                print_r($this->s__acta_directivo['id_acta']);
            }
	}
        
	//-----------------------------------------------------------------------------------
	//---- form_ml_superior -------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml_superior(gu_kena_ei_formulario_ml $form_ml)
	{            
            $blancos['id_nro_lista'] = -1;
            $blancos['nombre'] = "VOTOS EN BLANCO";
            $ar[0] = $blancos;
            
            $blancos['id_nro_lista'] = -2;
            $blancos['nombre'] = "VOTOS NULOS";
            $ar[1] = $blancos;
            
            $blancos['id_nro_lista'] = -3;
            $blancos['nombre'] = "VOTOS RECURRIDOS";
            $ar[2] = $blancos;
            
            if(sizeof($this->s__acta_superior)){
                $ar[0]['votos'] = $this->s__acta_superior['total_votos_blancos'];
                $ar[1]['votos'] = $this->s__acta_superior['total_votos_nulos'];
                $ar[2]['votos'] = $this->s__acta_superior['total_votos_recurridos'];
            }
            
            $listas = $this->dep('datos')->tabla('lista_csuperior')->get_listas_actuales($this->s__claustro);
            $ar = array_merge($listas, $ar);
            $form_ml->set_datos($ar);
            
	}

        function evt__form_ml_superior__modificacion($datos)
	{
            //votos blancos tienen id_nro_lista=-1 
            //votos nulos tienen id_nro_lista=-2 
            //votos recurridos tienen id_nro_lista=-3
            print_r($datos);
            if(isset($this->s__acta_superior)){
                $filtro['id_acta'] = $this->s__acta_superior['id_acta'];
                $this->dep('datos')->tabla('acta')->cargar($filtro);
                
                //votos blancos tienen id_nro_lista=-1 
                //votos nulos tienen id_nro_lista=-2 
                //votos recurridos tienen id_nro_lista=-3
                foreach($datos as $pos => $dato){
                    switch($dato['id_nro_lista']){
                        case -1://Votos blancos
                            $datos_acta['total_votos_blancos'] = $dato['votos'];
                            break;
                        case -2://Votos nulos
                            $datos_acta['total_votos_nulos'] = $dato['votos'];
                            break;
                        case -3://Votos recurridos
                            $datos_acta['total_votos_recurridos'] = $dato['votos'];
                            break;
                        default://Votos de listas
                            
                            break;
                    }
                }
                $this->dep('datos')->tabla('acta')->set($datos_acta);
                $this->dep('datos')->tabla('acta')->sincronizar();
                $this->dep('datos')->tabla('acta')->resetear();
                print_r($this->s__acta_superior['id_acta']);
            }
	}
        
	//-----------------------------------------------------------------------------------
	//---- form_ml_directivo_extra ------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml_directivo_extra(gu_kena_ei_formulario_ml $form_ml)
	{
            $blancos['id_nro_lista'] = -1;
            $blancos['nombre'] = "VOTOS EN BLANCO";
            $ar[0] = $blancos;
            
            $blancos['id_nro_lista'] = -2;
            $blancos['nombre'] = "VOTOS NULOS";
            $ar[1] = $blancos;
            
            $blancos['id_nro_lista'] = -3;
            $blancos['nombre'] = "VOTOS RECURRIDOS";
            $ar[2] = $blancos;
            
            $listas = $this->dep('datos')->tabla('lista_cdirectivo')->get_listas_actuales($this->s__claustro,$this->s__id_nro_ue);
            $ar = array_merge($listas, $ar);
            
            $form_ml->set_datos($ar);
	}
        
        function evt__form_ml_directivo_extra__modificacion($datos)
	{
            //votos blancos tienen id_nro_lista=-1 
            //votos nulos tienen id_nro_lista=-2 
            //votos recurridos tienen id_nro_lista=-3
            if(isset($this->s__acta_directivo_extra)){
                $filtro['id_acta'] = $this->s__acta_directivo_extra['id_acta'];
                $this->dep('datos')->tabla('acta')->cargar($filtro);
                
                //votos blancos tienen id_nro_lista=-1 
                //votos nulos tienen id_nro_lista=-2 
                //votos recurridos tienen id_nro_lista=-3
                foreach($datos as $pos => $dato){
                    switch($dato['id_nro_lista']){
                        case -1://Votos blancos
                            $datos_acta['total_votos_blancos'] = $dato['votos'];
                            break;
                        case -2://Votos nulos
                            $datos_acta['total_votos_nulos'] = $dato['votos'];
                            break;
                        case -3://Votos recurridos
                            $datos_acta['total_votos_recurridos'] = $dato['votos'];
                            break;
                        default://Votos de listas
                            
                            break;
                    }
                }
                $this->dep('datos')->tabla('acta')->set($datos_acta);
                $this->dep('datos')->tabla('acta')->sincronizar();
                $this->dep('datos')->tabla('acta')->resetear();
                print_r($this->s__acta_directivo_extra['id_acta']);
            }
	}
        
        function evt__procesar(){
            $this->set_pantalla('pant_seleccion');
        }

	

}
?>