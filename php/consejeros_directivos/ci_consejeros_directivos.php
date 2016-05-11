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
                //Casos especiales cons. dir de asentamiento tiene 3 puestos
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
                $this->dep('cuadro_dhondt_e')->agregar_columnas($c);
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

        
}
?>
