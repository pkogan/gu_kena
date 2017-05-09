<?php
class ci_mesa extends toba_ci
{
        protected $s__actas;
        protected $s__acta_directivo;
        protected $s__acta_superior;
        protected $s__acta_extra;
        
        protected $s__claustro;
        protected $s__id_nro_ue;
        protected $s__id_sede;
        protected $s__id_mesa;
        protected $s__mesa;
        
        //indica a donde debe retornar luego de guardar/verificar los datos
        protected $s__retorno;
        
        protected $s__perfil;
    
        function evt__procesar(){
            //Modificar el estado de la mesa
                $m = $this->dep('datos')->tabla('mesa')->get();
                $p = array_search('junta_electoral', $this->s__perfil);
                if($p !== false){//Ingreso con perfil junta_electoral
                    $m['estado'] = 3;//Cambia el estado de la mesa a Confirmado
                }
                else{
                    $p = array_search('secretaria', $this->s__perfil);
                     if($p !== false){//Ingreso con perfil secretaria
                         $m['estado'] = 4;//Cambia el estado de la mesa a Definitivo
                     }
                     else{
                       $p = array_search('autoridad_mesa', $this->s__perfil);  
                      if($p !== false){//Ingreso con perfil autoridad de mesa
                          $m['estado'] = 1;//Cambia el estado de la mesa a Cargado
                      }
                     }
                }
                $this->dep('datos')->tabla('mesa')->set($m);
//                print_r($m['id_mesa']);
                $this->dep('datos')->tabla('mesa')->sincronizar();
                $this->dep('datos')->tabla('mesa')->resetear();
                
                
            if(isset($this->s__retorno)){//debe retornar a confirmar   
                toba::vinculador()->navegar_a("",$this->s__retorno,true);  
                $this->s__retorno = null; 
                $this->s__id_mesa = null;
            }
        }
        
        //Evento solo disponible para las autoridades de mesa
        function evt__enviar(){
            if($this->dep('datos')->tabla('mesa')->esta_cargada()){
                $m = $this->dep('datos')->tabla('mesa')->get();
         //     print_r($m);
                $m['estado'] = 2;//Cambia el estado de la mesa a Enviado
                $this->dep('datos')->tabla('mesa')->set($m);
        //      print_r($m['id_mesa']);
                $this->dep('datos')->tabla('mesa')->sincronizar();
        //      $this->dep('datos')->tabla('mesa')->resetear();
                
                $m = $this->dep('datos')->tabla('mesa')->get_listado($m['id_mesa']);
                if($m[0]['estado'] == 2){//Obtengo de la BD y verifico que hizo cambios en la BD
                    //Se enviaron correctamente los datos
                    toba::notificacion()->agregar(utf8_decode("Los datos fueron enviados con éxito"),"info");
                }
                else{
                    //Se generó algún error al guardar en la BD
                    toba::notificacion()->agregar(utf8_decode("Error al enviar la información, verifique su conexión a internet"),"info");
                }
            }
            
        }
        
        function conf(){
            //Obtengo el perfil funcional del usuario logueado, devuelve un array
            $this->s__perfil = toba::manejador_sesiones()->get_perfiles_funcionales();
         //   print_r($this->s__perfil);  
            
            $p = array_search('autoridad_mesa', $this->s__perfil);
            if($p !== false){//Es autoridad de mesa
                //Cargar datos del usuario especifico
                //obtengo el nombre de usuario logueado
                $usr = toba::manejador_sesiones()->get_id_usuario_instancia();
                
                $id_mesa = $this->dep('datos')->tabla('mesa')->get_de_usr($usr);
                if(sizeof($id_mesa)>0){
                    $this->s__id_mesa = $id_mesa[0]['id_mesa'];
                    $datos['id_mesa'] = $this->s__id_mesa;
                    $this->dep('datos')->tabla('mesa')->cargar($datos);
                    $this->s__mesa = $this->dep('datos')->tabla('mesa')->get();
                    
                    if($this->s__mesa['estado'] >= 2){//Ya fue validado por la secretaria
                        $this->controlador()->evento('procesar')->ocultar();
                        $this->controlador()->evento('enviar')->ocultar();
                    }
                }
                else//No se encuentra mesa asociada al usuario logueado
                    toba::notificacion()->agregar("No se encuentra el usuario ingresado","info");
             }
            else{
                $this->s__id_mesa = toba::memoria()->get_parametro('c');//el parametro c tiene el id mesa
            
                $this->s__retorno = toba::memoria()->get_parametro('k');//el parametro k tiene la dir de retorno
            
                $datos['id_mesa'] = $this->s__id_mesa;
                $this->dep('datos')->tabla('mesa')->cargar($datos);
                $this->s__mesa = $this->dep('datos')->tabla('mesa')->get();
                	
                $p = array_search('junta_electoral', $this->s__perfil);
                if($p !== false){//Es junta electoral
                    $this->controlador()->evento('procesar')->set_etiqueta('Confirmar');
                    $this->controlador()->evento('enviar')->ocultar();

                    if($this->s__mesa['estado'] > 3){//Ya fue validado por la secretaria
//                        $this->dep('form_ml_directivo')->set_solo_lectura('votos');
//                        $this->dep('form_ml_superior')->set_solo_lectura('votos');
//                        $this->dep('form_ml_extra')->set_solo_lectura('votos');
                        $this->controlador()->evento('procesar')->ocultar();
                        $this->controlador()->evento('enviar')->ocultar();
                    }
                }
                else{
                    $p = array_search('secretaria', $this->s__perfil);//print_r(isset($p)?'no es false':'es false');
                     if($p !== false){//Es secretaria
                         $this->controlador()->evento('procesar')->set_etiqueta('Validar');
                          $this->controlador()->evento('enviar')->ocultar();

                     }
                     
                }
            }
            
          if(isset($this->s__id_mesa)){//Si el pedido viene de la operacion Confirmar/Cargar//              
                $this->s__claustro = $this->s__mesa['id_claustro'];
                $this->s__id_nro_ue = $this->dep('datos')->tabla('sede')->get_unidad($this->s__mesa['id_sede']);
                $this->s__id_sede = $this->s__mesa['id_sede'];
            }
            
            
        }
        //---- Pantalla -------------------------------------------------------------------

	function conf__pant_edicion(){
           if ($this->dep('datos')->tabla('mesa')->esta_cargada()) {
                        //Obtengo las actas asociadas a esta mesa
                        //primer parametro corresponde al id_mesa = de en acta
                        $this->s__actas = $this->dep('datos')->tabla('acta')->get_ultimas_descripciones_de($this->s__id_mesa);
                       //Si tengo tres actas asociadas a esta mesa ent muestro el form_ml_directivo_extra
                       
                       if(sizeof($this->s__actas) == 3){//form extra corresponde al cons. dir. del asentamiento
                            //$this->dep('form_extra')->set_titulo('Consejo Directivo de Asentamiento Universitario');
                       }else{ //Sino colapsar
                           $this->dep('form_ml_extra')->colapsar();
                           $this->dep('form_ml_extra')->set_titulo('');
                           
                           if(sizeof($this->s__actas) == 1){//form directivo no debe mostrarse
                               $this->dep('form_ml_directivo')->colapsar();
                               $this->dep('form_ml_directivo')->set_titulo('');
                           }
                       }
                       
                       //Separacion de actas    
                       foreach($this->s__actas as $pos => $un_acta){
                            //id_tipo=1 => superior
                            //id_tipo=2 => directivo facultad, escuela, etc
                           //id_tipo=3 => directivo asentamiento
                            if($un_acta['id_tipo'] == 1)//acta superior
                                    $this->s__acta_superior = $un_acta;
                            elseif($un_acta['id_tipo'] == 2){   
                                $this->s__acta_directivo = $un_acta;
                                    
                                }elseif($un_acta['id_tipo'] == 3){
                                     $this->s__acta_extra = $un_acta;
                                     
                                }    
                       } 
            }
        }
        
        
        //---- Formulario -------------------------------------------------------------------

	function conf__form_datos(toba_ei_formulario $form)
	{
            if ($this->dep('datos')->tabla('mesa')->esta_cargada()) {
		//Obtener los datos necesarios para mostrar en formulario 
                $ar['claustro'] = $this->dep('datos')->tabla('claustro')->get_descripcion($this->s__claustro);
                $ue = $this->dep('datos')->tabla('unidad_electoral')->get_descripciones($this->s__id_nro_ue);
                $ar['unidad_electoral'] = $ue[0]['nombre'];
                $sede = $this->dep('datos')->tabla('sede')->get_descripcion($this->s__id_sede);
                $ar['sede'] = $sede;

                $ar['nro_mesa'] = $this->s__mesa['nro_mesa'];
                $ar['cant_empadronados'] = $this->s__mesa['cant_empadronados'];
                
                return $ar;
            }
        }	

	//-----------------------------------------------------------------------------------
	//---- form_ml_directivo ------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //Formulario dedicado para cargar votos destinadas a la facultad/asentamiento
	function conf__form_ml_directivo(gu_kena_ei_formulario_ml $form_ml)
	{   
            if(isset($this->s__acta_directivo)){
                $ar = array();
                if(isset($this->s__acta_directivo)){
                    $ar[0]['votos'] = $this->s__acta_directivo['total_votos_blancos'];
                    $ar[1]['votos'] = $this->s__acta_directivo['total_votos_nulos'];
                    $ar[2]['votos'] = $this->s__acta_directivo['total_votos_recurridos'];

                    //obtener los votos cargados, asociados a este acta
                    $votos = $this->dep('datos')->tabla('voto_lista_cdirectivo')->get_listado_votos_dir($this->s__acta_directivo['id_acta']);

                }
                if(sizeof($ar) > 0){
                    $ar[0]['id_nro_lista'] = -1;
                    $ar[0]['nombre'] = "VOTOS EN BLANCO";            
    
                    $ar[1]['id_nro_lista'] = -2;
                    $ar[1]['nombre'] = "VOTOS NULOS";
    
                    $ar[2]['id_nro_lista'] = -3;
                    $ar[2]['nombre'] = "VOTOS RECURRIDOS";
    
                }
                if(sizeof($votos) > 0){//existen votos cargados
                    $ar = array_merge($votos, $ar);
                    $form_ml->set_datos($ar);
                }
                else{//no existen votos cargados
                    $listas = $this->dep('datos')->tabla('lista_cdirectivo')->get_listas_a_votar($this->s__acta_directivo['id_acta']);
                    if(sizeof($listas)>0){//Existen listas
                        $ar = array_merge($listas, $ar);
                        $form_ml->set_datos($ar);
                    }
                }
            }
        }

	function evt__form_ml_directivo__modificacion($datos)
	{   
            if(isset($this->s__acta_directivo)){
                $acta['id_acta'] = $this->s__acta_directivo['id_acta'];
                $this->dep('datos')->tabla('acta')->cargar($acta);
                
                //votos blancos tienen id_nro_lista=-1 
                //votos nulos tienen id_nro_lista=-2 
                //votos recurridos tienen id_nro_lista=-3
                foreach($datos as $pos => $dato){
                    switch($dato['id_nro_lista']){
                        case -1://Votos blancos
                            $acta['total_votos_blancos'] = $dato['votos'];
                            break;
                        case -2://Votos nulos
                            $acta['total_votos_nulos'] = $dato['votos'];
                            break;
                        case -3://Votos recurridos
                            $acta['total_votos_recurridos'] = $dato['votos'];
                            break;
                        default://Votos de listas
                            $voto = array();
                            $voto['id_lista'] = $dato['id_nro_lista'];
                            $voto['id_acta'] = $this->s__acta_directivo['id_acta'];
                            
                            $this->dep('datos')->tabla('voto_lista_cdirectivo')->cargar($voto);
                            if($this->dep('datos')->tabla('voto_lista_cdirectivo')->esta_cargada())
                                //obtengo el puntero al registro cargado
                                  $voto = $this->dep('datos')->tabla('voto_lista_cdirectivo')->get();
                            
                            $voto['cant_votos'] = $dato['votos'];
                            
                            $this->dep('datos')->tabla('voto_lista_cdirectivo')->set($voto);                            
                            $this->dep('datos')->tabla('voto_lista_cdirectivo')->sincronizar();
                            $this->dep('datos')->tabla('voto_lista_cdirectivo')->resetear();
                                                           
                            break;
                    }
                }
                $this->dep('datos')->tabla('acta')->set($acta);
                $this->dep('datos')->tabla('acta')->sincronizar();
                //$this->dep('datos')->tabla('acta')->resetear();
//              
            }
	}
        
	//-----------------------------------------------------------------------------------
	//---- form_ml_superior -------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml_superior(gu_kena_ei_formulario_ml $form_ml)
	{         
            if(isset($this->s__acta_superior)){
                $ar = array();
                $votos = array();
                if(isset($this->s__acta_superior)){
                    $ar[0]['votos'] = $this->s__acta_superior['total_votos_blancos'];
                    $ar[1]['votos'] = $this->s__acta_superior['total_votos_nulos'];
                    $ar[2]['votos'] = $this->s__acta_superior['total_votos_recurridos'];

                    //obtener los votos cargados, asociados a este acta
                    $votos = $this->dep('datos')->tabla('voto_lista_csuperior')->get_listado_votos_sup($this->s__acta_superior['id_acta']);

                }
                if(sizeof($ar) > 0){
                    $ar[0]['id_nro_lista'] = -1;
                    $ar[0]['nombre'] = "VOTOS EN BLANCO";            
    //                $ar[0] = $blancos;

                    $ar[1]['id_nro_lista'] = -2;
                    $ar[1]['nombre'] = "VOTOS NULOS";
    //                $ar[1] = $nulos;

                    $ar[2]['id_nro_lista'] = -3;
                    $ar[2]['nombre'] = "VOTOS RECURRIDOS";
    //                $ar[2] = $recurridos;

    //                $ar = array_merge($ar, $arr);
                }
                if(sizeof($votos) > 0){//existen votos cargados
                    $ar = array_merge($votos, $ar);
                    $form_ml->set_datos($ar);
                }
                else{//no existen votos cargados
                    $listas = $this->dep('datos')->tabla('lista_csuperior')->get_listas_actuales($this->s__claustro);
                    if(sizeof($listas)>0){//Existen listas
                        $ar = array_merge($listas, $ar);
                        $form_ml->set_datos($ar);
                    }
                }
            }
        }

	function evt__form_ml_superior__modificacion($datos)
	{
            if(isset($this->s__acta_superior)){
                $acta['id_acta'] = $this->s__acta_superior['id_acta'];
                $this->dep('datos')->tabla('acta')->cargar($acta);
                
                //votos blancos tienen id_nro_lista=-1 
                //votos nulos tienen id_nro_lista=-2 
                //votos recurridos tienen id_nro_lista=-3
                foreach($datos as $pos => $dato){
                    switch($dato['id_nro_lista']){
                        case -1://Votos blancos
                            $acta['total_votos_blancos'] = $dato['votos'];
                            break;
                        case -2://Votos nulos
                            $acta['total_votos_nulos'] = $dato['votos'];
                            break;
                        case -3://Votos recurridos
                            $acta['total_votos_recurridos'] = $dato['votos'];
                            break;
                        default://Votos de listas
                            $voto = array();
                            $voto['id_lista'] = $dato['id_nro_lista'];
                            $voto['id_acta'] = $this->s__acta_superior['id_acta'];
//                            print_r($voto);
                            $this->dep('datos')->tabla('voto_lista_csuperior')->cargar($voto);
                            if($this->dep('datos')->tabla('voto_lista_csuperior')->esta_cargada())
                                //obtengo el puntero al registro cargado
                                  $voto = $this->dep('datos')->tabla('voto_lista_csuperior')->get();
                            
                            $voto['cant_votos'] = $dato['votos'];
                            
                            $this->dep('datos')->tabla('voto_lista_csuperior')->set($voto);                            
                            $this->dep('datos')->tabla('voto_lista_csuperior')->sincronizar();
                            $this->dep('datos')->tabla('voto_lista_csuperior')->resetear();
                                                           
                            break;
                    }
                }
                $this->dep('datos')->tabla('acta')->set($acta);
                $this->dep('datos')->tabla('acta')->sincronizar();
                $this->dep('datos')->tabla('acta')->resetear();
//              
            }
	}

	//-----------------------------------------------------------------------------------
	//---- formulario para asentamiento -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml_extra(gu_kena_ei_formulario_ml $form_ml)
	{
            //print_r($this->s__acta_extra);
            if(isset($this->s__acta_extra)){
                $ar = array();
                if(isset($this->s__acta_extra)){
                    $ar[0]['votos'] = $this->s__acta_extra['total_votos_blancos'];
                    $ar[1]['votos'] = $this->s__acta_extra['total_votos_nulos'];
                    $ar[2]['votos'] = $this->s__acta_extra['total_votos_recurridos'];

                    //obtener los votos cargados, asociados a este acta
                    $votos = $this->dep('datos')->tabla('voto_lista_cdirectivo')->get_listado_votos_dir($this->s__acta_extra['id_acta']);

                }
                if(sizeof($ar) > 0){
                    $ar[0]['id_nro_lista'] = -1;
                    $ar[0]['nombre'] = "VOTOS EN BLANCO";            
    //                $ar[0] = $blancos;

                    $ar[1]['id_nro_lista'] = -2;
                    $ar[1]['nombre'] = "VOTOS NULOS";
    //                $ar[1] = $nulos;

                    $ar[2]['id_nro_lista'] = -3;
                    $ar[2]['nombre'] = "VOTOS RECURRIDOS";
    //                $ar[2] = $recurridos;

    //                $ar = array_merge($ar, $arr);
                }
                if(sizeof($votos) > 0){//existen votos cargados
                    $ar = array_merge($votos, $ar);
                    $form_ml->set_datos($ar);
                }
                else{//no existen votos cargados
                    $listas = $this->dep('datos')->tabla('lista_cdirectivo')->get_listas_a_votar($this->s__acta_extra['id_acta']);
                    if(sizeof($listas)>0){//Existen listas
                        $ar = array_merge($listas, $ar);
                        $form_ml->set_datos($ar);
                    }
                }
            }
        }
        
        function evt__form_ml_extra__modificacion($datos)
	{
            if(isset($this->s__acta_extra)){
                $acta['id_acta'] = $this->s__acta_extra['id_acta'];
                $this->dep('datos')->tabla('acta')->cargar($acta);
                
                //votos blancos tienen id_nro_lista=-1 
                //votos nulos tienen id_nro_lista=-2 
                //votos recurridos tienen id_nro_lista=-3
                foreach($datos as $pos => $dato){
                    switch($dato['id_nro_lista']){
                        case -1://Votos blancos
                            $acta['total_votos_blancos'] = $dato['votos'];
                            break;
                        case -2://Votos nulos
                            $acta['total_votos_nulos'] = $dato['votos'];
                            break;
                        case -3://Votos recurridos
                            $acta['total_votos_recurridos'] = $dato['votos'];
                            break;
                        default://Votos de listas
                            $voto = array();
                            $voto['id_lista'] = $dato['id_nro_lista'];
                            $voto['id_acta'] = $this->s__acta_extra['id_acta'];
//                            print_r($voto);
                            $this->dep('datos')->tabla('voto_lista_cdirectivo')->cargar($voto);
                            if($this->dep('datos')->tabla('voto_lista_cdirectivo')->esta_cargada())
                                //obtengo el puntero al registro cargado
                                  $voto = $this->dep('datos')->tabla('voto_lista_cdirectivo')->get();
                            
                            $voto['cant_votos'] = $dato['votos'];
                            
                            $this->dep('datos')->tabla('voto_lista_cdirectivo')->set($voto);                            
                            $this->dep('datos')->tabla('voto_lista_cdirectivo')->sincronizar();
                            $this->dep('datos')->tabla('voto_lista_cdirectivo')->resetear();
                                                           
                            break;
                    }
                }
                $this->dep('datos')->tabla('acta')->set($acta);
                $this->dep('datos')->tabla('acta')->sincronizar();
                $this->dep('datos')->tabla('acta')->resetear();
//              
            }
        }
        
        
}
?>