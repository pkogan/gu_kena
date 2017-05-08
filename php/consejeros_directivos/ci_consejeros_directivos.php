<?php
class ci_consejeros_directivos extends ci_principal
{
    
    //-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf()
	{
            $this->pantalla()->tab("pant_docente")->ocultar();
            
            $this->controlador()->dep('form_unidad')->ef('id_nro_ue')->set_estado($this->controlador->s__unidad);
                        
	}
        
	//-----------------------------------------------------------------------------------
	//---- cuadro_directivo_e -----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_dhondt_e(gu_kena_ei_cuadro $cuadro)
	{
            if($this->controlador->s__unidad == 17 || $this->controlador->s__unidad == 18){
                //Casos especiales cons. dir de asentamiento tiene 3 puestos
                $cargos = 3;                
            }
            else{
                $cargos = 4;
                //Agrega la columna de división por 4 que en asentamiento no lo posee
                $l['clave'] = 4;
                $l['titulo'] = utf8_decode('n°votos/4');
//                $l['estilo'] = 'col-cuadro-resultados';
//                $l['estilo_titulo'] = 'tit-cuadro-resultados';
                $l['permitir_html'] = true;
                $c[5] = $l;
                $this->dep('cuadro_dhondt_e')->agregar_columnas($c);
            }
            
            $listas = $this->controlador()->dep('datos')->tabla('voto_lista_cdirectivo')->get_listas_con_total_votos(3,$this->controlador->s__unidad);
            
            $ar = array();
            foreach($listas as $pos=>$lista){
                //Calcula el cociente para cada cargo
                for($i=1; $i<=$cargos; $i++){
                    //  Cant votos / numero de cargo
                    $x = $listas[$pos]['votos'] / $i;
                    array_push($ar, $x);
                    $listas[$pos][$i] = $x;
                }
            } 
             
            array_multisort($ar,SORT_DESC);
            
            //Resalta los resultados mayores
            for($i=0; $i<$cargos; $i++){//Recorro el arreglo de valores ordenados
                   
                foreach($listas as $pos=>$lista){
                    //Agrego la cant de escaños obtenidos para esta lista
                    // cant de votos obtenidos / menor cociente
                    $c = $lista['votos'] / $ar[$cargos-1];
                    $listas[$pos]['final'] = floor($c);
                    
                    $p = array_search($ar[$i], $lista);
                        if($p != null){//Encontro el valor en esta fila
                            if(strcmp($p, "votos")==0){//Encontro que esta en el campo 'votos' entonces hay que resaltar n°votos/1
                                $valor = "<span style='color:red'>".$listas[$pos][1]."</span>";
                                $listas[$pos][1] = $valor;
                            }
                            else{
                                $valor = "<span style='color:red'>".$listas[$pos][$p]."</span>";
                                $listas[$pos][$p] = $valor;
                            }  
                        }                        
                    }
                }
                
            
            return $listas;
	}

	function evt__cuadro_dhondt_e__seleccion($seleccion)
	{
	}
        
	//-----------------------------------------------------------------------------------
	//---- cuadro_directivo_g -----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_dhondt_g(gu_kena_ei_cuadro $cuadro)
	{
            $listas = $this->controlador()->dep('datos')->tabla('voto_lista_cdirectivo')->get_listas_con_total_votos(4,$this->controlador->s__unidad);
            
            $ar = array();
            foreach($listas as $pos=>$lista){
                //Calcula el cociente para cada cargo
                for($i=1; $i<=1; $i++){
                    //  Cant votos / numero de cargo
                    $x = $listas[$pos]['votos'] / $i;
                    array_push($ar, $x);
                    $listas[$pos][$i] = $x;
                }
            } 
             
            array_multisort($ar,SORT_DESC);
            
            //Resalta los resultados mayores
            for($i=0; $i<1; $i++){//Recorro el arreglo de valores ordenados
                   
                foreach($listas as $pos=>$lista){
                    //Agrego la cant de escaños obtenidos para esta lista
                    // cant de votos obtenidos / menor cociente
                    $c = $lista['votos'] / $ar[0];
                    $listas[$pos]['final'] = floor($c);
                    
                    $p = array_search($ar[$i], $lista);
                        if($p != null){//Encontro el valor en esta fila
                            if(strcmp($p, "votos")==0){//Encontro que esta en el campo 'votos' entonces hay que resaltar n°votos/1
                                $valor = "<span style='color:red'>".$listas[$pos][1]."</span>";
                                $listas[$pos][1] = $valor;
                            }
                            else{
                                $valor = "<span style='color:red'>".$listas[$pos][$p]."</span>";
                                $listas[$pos][$p] = $valor;
                            }  
                        }                        
                    }
                }
                
            
            return $listas;
	}

	function evt__cuadro_dhondt_g__seleccion($seleccion)
	{
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_directivo_nd ----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_dhondt_nd(gu_kena_ei_cuadro $cuadro)
	{
            if($this->controlador->s__unidad == 17 || $this->controlador->s__unidad == 18){
                //Casos especiales cons. dir de asentamiento tiene 2 puestos
                $cargos = 2;                
            }
            else{
                $cargos = 3;
                //Agrega la columna de división por 3 que en asentamiento no lo posee
                $l['clave'] = 3;
                $l['titulo'] = utf8_decode('n°votos/3');
//                $l['estilo'] = 'col-cuadro-resultados';
//                $l['estilo_titulo'] = 'tit-cuadro-resultados';
                $l['permitir_html'] = true;
                $c[0] = $l;
                $this->dep('cuadro_dhondt_nd')->agregar_columnas($c);
            }
             
            $listas = $this->controlador()->dep('datos')->tabla('voto_lista_cdirectivo')->get_listas_con_total_votos(1,$this->controlador->s__unidad);
            
            $ar = array();
            foreach($listas as $pos=>$lista){
                //Calcula el cociente para cada cargo
                for($i=1; $i<=$cargos; $i++){
                    //  Cant votos / numero de cargo
                    $x = $listas[$pos]['votos'] / $i;
                    array_push($ar, $x);
                    $listas[$pos][$i] = $x;
                }
            } 
             
            array_multisort($ar,SORT_DESC);
            
            //Resalta los resultados mayores
            for($i=0; $i<$cargos; $i++){//Recorro el arreglo de valores ordenados
                   
                foreach($listas as $pos=>$lista){
                    //Agrego la cant de escaños obtenidos para esta lista
                    // cant de votos obtenidos / menor cociente
                    $c = $lista['votos'] / $ar[$cargos-1];
                    $listas[$pos]['final'] = floor($c);
                    
                    $p = array_search($ar[$i], $lista, TRUE);
                        if($p != null){//Encontro el valor en esta fila
                            if(strcmp($p, "votos")==0){//Encontro que esta en el campo 'votos' entonces hay que resaltar n°votos/1
                                $valor = "<span style='color:red'>".$listas[$pos][1]."</span>";
                                $listas[$pos][1] = $valor;
                            }
                            else{
                                $valor = "<span style='color:red'>".$listas[$pos][$p]."</span>";
                                $listas[$pos][$p] = $valor;
                            }  
                        }                        
                    }
                }
                
            
            return $listas;
	}

	function evt__cuadro_dhondt_nd__seleccion($seleccion)
	{
	}
        
        //-----------------------------------------------------------------------------------
	//---- cuadro_directivo_d -----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_dhondt_d(gu_kena_ei_cuadro $cuadro)
	{
            if($this->controlador->s__unidad == 17 || $this->controlador->s__unidad == 18){
                //Casos especiales cons. dir de asentamiento tiene 3 puestos
                $cargos = 6;                
            }
            else{
                $cargos = 8;
                //Agrega la columna de división por 7 que en asentamiento no lo posee
                $l['clave'] = 7;
                $l['titulo'] = utf8_decode('n°votos/7');
//                $l['estilo'] = 'col-cuadro-resultados';
//                $l['estilo_titulo'] = 'tit-cuadro-resultados';
                $l['permitir_html'] = true;
                $c[0] = $l;
                $this->dep('cuadro_dhondt_e')->agregar_columnas($c);
                
                //Agrega la columna de división por 8 que en asentamiento no lo posee
                $l['clave'] = 8;
                $l['titulo'] = utf8_decode('n°votos/8');
//                $l['estilo'] = 'col-cuadro-resultados';
//                $l['estilo_titulo'] = 'tit-cuadro-resultados';
                $l['permitir_html'] = true;
                $c[0] = $l;
                $this->dep('cuadro_dhondt_e')->agregar_columnas($c);
            }
               
            $listas = $this->controlador()->dep('datos')->tabla('voto_lista_cdirectivo')->get_listas_con_total_votos(3,$this->controlador->s__unidad);
            
            $ar = array();
            foreach($listas as $pos=>$lista){
                //Calcula el cociente para cada cargo
                for($i=1; $i<=$cargos; $i++){
                    //  Cant votos / numero de cargo
                    $x = $listas[$pos]['votos'] / $i;
                    array_push($ar, $x);
                    $listas[$pos][$i] = $x;
                }
            } 
             
            array_multisort($ar,SORT_DESC);
            
            //Resalta los resultados mayores
            for($i=0; $i<$cargos; $i++){//Recorro el arreglo de valores ordenados
                   
                foreach($listas as $pos=>$lista){
                    //Agrego la cant de escaños obtenidos para esta lista
                    // cant de votos obtenidos / menor cociente
                    $c = $lista['votos'] / $ar[$cargos-1];
                    $listas[$pos]['final'] = floor($c);
                    
                    $p = array_search($ar[$i], $lista);
                        if($p != null){//Encontro el valor en esta fila
                            if(strcmp($p, "votos")==0){//Encontro que esta en el campo 'votos' entonces hay que resaltar n°votos/1
                                $valor = "<span style='color:red'>".$listas[$pos][1]."</span>";
                                $listas[$pos][1] = $valor;
                            }
                            else{
                                $valor = "<span style='color:red'>".$listas[$pos][$p]."</span>";
                                $listas[$pos][$p] = $valor;
                            }  
                        }                        
                    }
                }
                
            
            return $listas;
	}

	function evt__cuadro_dhondt_d__seleccion($seleccion)
	{
	}

        //-----------------------------------------------------------------------------------
	//---- formulario que muestra datos de votos blancos, nulos y recurridos en cada unidad electoral 
	//-----------------------------------------------------------------------------------
	function conf__form_dato_e(gu_kena_ei_formulario $form)
	{ 
//Agrega la cantidad de votos blancos,nulos y recurridos calculado en acta para cada unidad con claustro estudiante y tipo directivo=2/3
            $u_e = $this->controlador->s__unidad;
            
            $nivel = $this->controlador()->dep('datos')->tabla('unidad_electoral')->get_nivel($u_e);
            if($nivel['nivel']==3){ 
                $ar = $this->controlador()->dep('datos')->tabla('acta')->cant_b_n_r($u_e, 3, 3);
            }
            else{
                    $ar = $this->controlador()->dep('datos')->tabla('acta')->cant_b_n_r($u_e, 3, 2);
            }
          return $ar[0];
        }
        
        function conf__form_dato_g(gu_kena_ei_formulario $form)
	{
   //Agrega la cantidad de votos blancos,nulos y recurridos calculado en acta para cada unidad con claustro graduados y tipo directivo=2/3
            $u_e = $this->controlador->s__unidad;
            
            $nivel = $this->controlador()->dep('datos')->tabla('unidad_electoral')->get_nivel($u_e);
            if($nivel['nivel']==3){ 
                $ar = $this->controlador()->dep('datos')->tabla('acta')->cant_b_n_r($u_e, 4, 3);
            }
            else{
                    $ar = $this->controlador()->dep('datos')->tabla('acta')->cant_b_n_r($u_e, 4, 2);
            }
          return $ar[0];
        }
        
        function conf__form_dato_nd(gu_kena_ei_formulario $form)
	{
    //Agrega la cantidad de votos blancos,nulos y recurridos calculado en acta para cada unidad con claustro no docente y tipo directivo=2/3
            $u_e = $this->controlador->s__unidad;
            
            $nivel = $this->controlador()->dep('datos')->tabla('unidad_electoral')->get_nivel($u_e);
            if($nivel['nivel']==3){ 
                $ar = $this->controlador()->dep('datos')->tabla('acta')->cant_b_n_r($u_e, 1, 3);
            }
            else{
                    $ar = $this->controlador()->dep('datos')->tabla('acta')->cant_b_n_r($u_e, 1, 2);
            }
          return $ar[0];
        }
        
        //-----------------------------------------------------------------------------------
	//---- EXPORTACION EXCEL ----------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        function vista_excel(toba_vista_excel $salida){
            $unidad = $this->controlador()->dep('datos')->tabla('unidad_electoral')->get_descripciones($this->controlador->s__unidad);
            $salida->set_nombre_archivo($unidad[0]['sigla']."-EscrutinioDirectivo.xls");
            $excel = $salida->get_excel();
            
            //Estilo de la celda que hara de separacion entre claustros
            $estilo_claustro = array(
 			'font' => array('bold' => true),
 			'fill' => array(
             		'type' => PHPExcel_Style_Fill::FILL_SOLID ,
		            'rotation'   => 0,
		            'startcolor' => array('rgb' => 'F29C46'),
             	),
 		);
            
            //Directivo Estudiantes
            //Obtengo el cursor de escritura en excel
            $cursor = $salida->get_cursor();
            $excel->getActiveSheet()->setCellValueByColumnAndRow($cursor[0], $cursor[1],'Estudiantes');
            $excel->getActiveSheet()->getStyleByColumnAndRow($cursor[0], $cursor[1])->applyFromArray($estilo_claustro);
            $salida->separacion(1);            
            $this->dependencia('form_dato_e')->vista_excel($salida);
            $salida->separacion(1);
            $this->dependencia('cuadro_dhondt_e')->vista_excel($salida);
            
            //Directivo Graduados
            //Obtengo el cursor de escritura en excel
            $salida->separacion(3);
            $cursor = $salida->get_cursor();
            $excel->getActiveSheet()->setCellValueByColumnAndRow($cursor[0], $cursor[1],'Graduados');
            $excel->getActiveSheet()->getStyleByColumnAndRow($cursor[0], $cursor[1])->applyFromArray($estilo_claustro);
            $salida->separacion(1);            
            $this->dependencia('form_dato_g')->vista_excel($salida);
            $salida->separacion(1);
            $this->dependencia('cuadro_dhondt_g')->vista_excel($salida);
            
            //Directivo No Docente
            //Obtengo el cursor de escritura en excel
            $salida->separacion(3);
            $cursor = $salida->get_cursor();            
            $excel->getActiveSheet()->setCellValueByColumnAndRow($cursor[0], $cursor[1],'No Docente');
            $excel->getActiveSheet()->getStyleByColumnAndRow($cursor[0], $cursor[1])->applyFromArray($estilo_claustro);
            $salida->separacion(1);            
            $this->dependencia('form_dato_nd')->vista_excel($salida);
            $salida->separacion(1);
            $this->dependencia('cuadro_dhondt_nd')->vista_excel($salida);

        }
}
?>
