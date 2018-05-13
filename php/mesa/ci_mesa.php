<?php
class ci_mesa extends toba_ci
{
        protected $s__actas;
        protected $s__acta_directivo;
        protected $s__acta_superior;
        protected $s__acta_extra;
        protected $s__acta_rector;
        protected $s__acta_decano;
        protected $s__acta_director;

        protected $s__claustro;
        protected $s__id_nro_ue;
        protected $s__id_sede;
        protected $s__id_mesa;
        protected $s__mesa;
        
        //indica a donde debe retornar luego de guardar/verificar los datos
        protected $s__retorno;
        protected $s__retorno_estado;
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
                if($this->s__retorno_estado=='')
                    $dato = true;
                else
                    $dato['f'] = $this->s__retorno_estado;
                toba::vinculador()->navegar_a("",$this->s__retorno,$dato);  
                $this->s__retorno = null; 
                $this->s__id_mesa = null;
                $this->s__retorno_estado = null;
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
                $this->s__retorno_estado = toba::memoria()->get_parametro('f');
                
            
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
               
                //variable utilizado para colapsar o mostrar un formulario segun 
                //si existen elecciones para ese formulario
                $mostrar_form = array(
                    'form_ml_superior'  => false,
                    'form_ml_directivo' => false,
                    'form_ml_extra'     => false,
                    'form_ml_decano'    => false,
                    'form_ml_director'  => false,
                    'form_ml_rector'  => false
                );
               //Separacion de actas    
               foreach($this->s__actas as $pos => $un_acta){
                    //id_tipo=1 => superior
                    //id_tipo=2 => directivo facultad, escuela, etc
                   //id_tipo=3 => directivo asentamiento
                   //id_tipo=4 => rector
                   //id_tipo=5 => decano
                   //id_tipo=6 => director asentamiento
                   switch($un_acta['id_tipo']){
                       case 1: $this->s__acta_superior = $un_acta; 
                           $mostrar_form['form_ml_superior'] = 'Consejeros Superiores'; 
                           break;
                       case 2: $this->s__acta_directivo = $un_acta; 
                           $mostrar_form['form_ml_directivo'] = 'Consejeros Directivos de Facultad o Centro Regional'; 
                           break;
                       case 3: $this->s__acta_extra = $un_acta; 
                           $mostrar_form['form_ml_extra'] = 'Consejo Directivo de Asentamiento Universitario'; 
                           break;
                       case 4: $this->s__acta_rector = $un_acta; 
                           $mostrar_form['form_ml_rector'] = 'Rector'; 
                           break;                        
                       case 5: $this->s__acta_decano = $un_acta; 
                           $mostrar_form['form_ml_decano'] = 'Decano'; 
                           break;
                       case 6: $this->s__acta_director = $un_acta; 
                           $mostrar_form['form_ml_director'] = 'Director de Asentamiento Universitario o Director de Escuela Superior'; 
                           break;
                   }
               }
               
               foreach($mostrar_form as $key => $tit){
                   if($tit == false){//form que se debe colapsar
                       $this->dep($key)->colapsar();
                       $this->dep($key)->set_titulo('');
                   }else//form que se debe mostrar
                        $this->dep($key)->set_titulo($tit);                   
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
                $datos_necesarios['acta'] = $this->s__acta_directivo;
                $datos_necesarios['tabla_listas'] = 'lista_cdirectivo';
                $datos_necesarios['tabla_voto'] = 'voto_lista_cdirectivo';
                
                $respuesta = $this->cargar_formulario($datos_necesarios);
                $form_ml->set_datos($respuesta);
            }
        }

	function evt__form_ml_directivo__modificacion($datos)
	{   
            if(isset($this->s__acta_directivo)){
                $datos_necesarios['acta'] = $this->s__acta_directivo;
                $datos_necesarios['tabla_voto'] = 'voto_lista_cdirectivo';
                $datos_necesarios['datos'] = $datos;
                
                $this->formulario_modificacion($datos_necesarios);                
            }
	}
        
	//-----------------------------------------------------------------------------------
	//---- form_ml_superior -------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml_superior(gu_kena_ei_formulario_ml $form_ml)
	{         
            if(isset($this->s__acta_superior)){
                $datos_necesarios['acta'] = $this->s__acta_superior;
                $datos_necesarios['tabla_listas'] = 'lista_csuperior';
                $datos_necesarios['tabla_voto'] = 'voto_lista_csuperior';
                $respuesta = $this->cargar_formulario($datos_necesarios);
                $form_ml->set_datos($respuesta);                 
            }
        }

	function evt__form_ml_superior__modificacion($datos)
	{
            if(isset($this->s__acta_superior)){
                $datos_necesarios['acta'] = $this->s__acta_superior;
                $datos_necesarios['tabla_voto'] = 'voto_lista_csuperior';
                $datos_necesarios['datos'] = $datos;
                
                $this->formulario_modificacion($datos_necesarios);
                
            }
	}

	//-----------------------------------------------------------------------------------
	//---- formulario para asentamiento -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml_extra(gu_kena_ei_formulario_ml $form_ml)
	{
            if(isset($this->s__acta_extra)){
                $datos_necesarios['acta'] = $this->s__acta_extra;
                $datos_necesarios['tabla_listas'] = 'lista_cdirectivo';
                $datos_necesarios['tabla_voto'] = 'voto_lista_cdirectivo';
                $respuesta = $this->cargar_formulario($datos_necesarios);
                $form_ml->set_datos($respuesta);                
            }
        }
        
        function evt__form_ml_extra__modificacion($datos)
	{
            if(isset($this->s__acta_extra)){
                $datos_necesarios['acta'] = $this->s__acta_extra;
                $datos_necesarios['tabla_voto'] = 'voto_lista_cdirectivo';
                $datos_necesarios['datos'] = $datos;
                
                $this->formulario_modificacion($datos_necesarios);                
            }
        }
        
        
	//-----------------------------------------------------------------------------------
	//---- form_ml_decano ---------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml_decano(form_ml_decano $form_ml)
	{
            if(isset($this->s__acta_decano)){
                $datos_necesarios['acta'] = $this->s__acta_decano;
                $datos_necesarios['tabla_listas'] = 'lista_decano';
                $datos_necesarios['tabla_voto'] = 'voto_lista_decano';
                $respuesta = $this->cargar_formulario($datos_necesarios);
                $form_ml->set_datos($respuesta);
                
            }
	}

	function evt__form_ml_decano__modificacion($datos)
	{
            if(isset($this->s__acta_decano)){
                $datos_necesarios['acta'] = $this->s__acta_decano;
                $datos_necesarios['tabla_voto'] = 'voto_lista_decano';
                $datos_necesarios['datos'] = $datos;
                
                $this->formulario_modificacion($datos_necesarios); 
                
            }
            
	}
        
        //-----------------------------------------------------------------------------------
	//---- form_ml_director ---------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml_director(form_ml_director $form_ml)
	{
            if(isset($this->s__acta_director)){
                $datos_necesarios['acta'] = $this->s__acta_director;
                $datos_necesarios['tabla_listas'] = 'lista_decano';
                $datos_necesarios['tabla_voto'] = 'voto_lista_decano';
                $respuesta = $this->cargar_formulario($datos_necesarios);
                $form_ml->set_datos($respuesta);
                
            }
	}

	function evt__form_ml_director__modificacion($datos)
	{
            if(isset($this->s__acta_director)){
                $datos_necesarios['acta'] = $this->s__acta_director;
                $datos_necesarios['tabla_voto'] = 'voto_lista_decano';
                $datos_necesarios['datos'] = $datos;
                
                $this->formulario_modificacion($datos_necesarios); 
                
            }
            
	}

	//-----------------------------------------------------------------------------------
	//---- form_ml_rector ---------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml_rector(form_ml_rector $form_ml)
	{
            if(isset($this->s__acta_rector)){
                $datos_necesarios['acta'] = $this->s__acta_rector;
                $datos_necesarios['tabla_listas'] = 'lista_rector';
                $datos_necesarios['tabla_voto'] = 'voto_lista_rector';
                $respuesta = $this->cargar_formulario($datos_necesarios);
                $form_ml->set_datos($respuesta);            
            }
            
	}

	function evt__form_ml_rector__modificacion($datos)
	{
            if(isset($this->s__acta_rector)){
                $datos_necesarios['acta'] = $this->s__acta_rector;
                $datos_necesarios['tabla_voto'] = 'voto_lista_rector';
                $datos_necesarios['datos'] = $datos;
                
                $this->formulario_modificacion($datos_necesarios);
                
            }
	}
        
        //-----------------------------------------------------------------------------------
	//---- FUNCION ENCARGADA DE CARGAR/GUARDAR LOS FORMULARIOS ---------------------------------------------------------------
	//-----------------------------------------------------------------------------------

        function cargar_formulario($datos_necesarios){
            if(isset($datos_necesarios['acta'])){
                $ar = array();
                
                $ar[0]['votos'] = $datos_necesarios['acta']['total_votos_blancos'];
                $ar[0]['id_nro_lista'] = -1;
                $ar[0]['nombre'] = "VOTOS EN BLANCO";
                    
                $ar[1]['votos'] = $datos_necesarios['acta']['total_votos_nulos'];
                $ar[1]['id_nro_lista'] = -2;
                $ar[1]['nombre'] = "VOTOS NULOS";
                    
                $ar[2]['votos'] = $datos_necesarios['acta']['total_votos_recurridos'];
                $ar[2]['id_nro_lista'] = -3;
                $ar[2]['nombre'] = "VOTOS RECURRIDOS";

                //obtener los votos cargados, asociados a este acta
                $votos = $this->dep('datos')->tabla($datos_necesarios['tabla_voto'])->get_listado_votos($datos_necesarios['acta']['id_acta']);
                              
                if(sizeof($votos) > 0){//existen votos cargados
                    $ar = array_merge($votos, $ar);
                    
                }
                else{//no existen votos cargados
                    $listas = $this->dep('datos')->tabla($datos_necesarios['tabla_listas'])->get_listas_a_votar($datos_necesarios['acta']['id_acta']);
                    
                    if(sizeof($listas)>0)//Existen listas
                        $ar = array_merge($listas, $ar);                        
                    
                }
                
                return $ar;
            }
        }
        
        function formulario_modificacion($datos_necesarios){
            $acta['id_acta'] = $datos_necesarios['acta']['id_acta'];
            $this->dep('datos')->tabla('acta')->cargar($acta);
            if($this->dep('datos')->tabla('acta')->esta_cargada()){
                $acta = $this->dep('datos')->tabla('acta')->get();
                
                //votos blancos tienen id_nro_lista=-1 
                //votos nulos tienen id_nro_lista=-2 
                //votos recurridos tienen id_nro_lista=-3
                foreach($datos_necesarios['datos'] as $pos => $dato){
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
                            $voto['id_acta'] = $acta['id_acta'];
            
                            $this->dep('datos')->tabla($datos_necesarios['tabla_voto'])->cargar($voto);
                            if($this->dep('datos')->tabla($datos_necesarios['tabla_voto'])->esta_cargada())
                                //obtengo el puntero al registro cargado
                                  $voto = $this->dep('datos')->tabla($datos_necesarios['tabla_voto'])->get();
                            
                            $voto['cant_votos'] = $dato['votos'];

                            $this->dep('datos')->tabla($datos_necesarios['tabla_voto'])->set($voto);                            
                            $this->dep('datos')->tabla($datos_necesarios['tabla_voto'])->sincronizar();
                            $this->dep('datos')->tabla($datos_necesarios['tabla_voto'])->resetear();

                            break;
                    }
                }
                $this->dep('datos')->tabla('acta')->set($acta);
                $this->dep('datos')->tabla('acta')->sincronizar();
                $this->dep('datos')->tabla('acta')->resetear();
            }
//              
        }

}
?>