<?php
class ci_consejeros_directivos extends ci_principal
{
    
    //-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf()
	{
            $this->pantalla()->tab("pant_docente")->ocultar();
                        
	}
        
        
	//-----------------------------------------------------------------------------------
	//---- cuadro_directivo_d -----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_directivo_d(gu_kena_ei_cuadro $cuadro)
	{
            $cuadro->set_datos($this->dep('datos')->tabla('lista_cdirectivo')->get_listado());
	}

	function evt__cuadro_directivo_d__seleccion($seleccion)
	{
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_directivo_e -----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_directivo_e(gu_kena_ei_cuadro $cuadro)
	{
            //Obtener la unidad electoral seleccionada, si es null entonces tiene 
            //seleccionado 'Adm. Central', id_nro_ue = 1
            $this->controlador()->dep('form_unidad')->ef('id_nro_ue')->set_estado($this->controlador->s__unidad);
            $listas = $this->dep('datos')->tabla('voto_lista_cdirectivo')->get_listas_con_total_votos(3,$this->controlador->s__unidad);
             
            $ar = array();
            foreach($listas as $pos=>$lista){
                //Calcula el cociente para cada cargo
                for($i=1; $i<=4; $i++){
                    //Cant votos / numero de cargo
                    $x = $listas[$pos]['votos'] / $i;
                    array_push($ar, $x);
                    $listas[$pos][$i] = $x;
                }
            } 
             
            array_multisort($ar,SORT_DESC);
            
            //Resalta los resultados mayores
            for($i=0; $i<4; $i++){//Recorro el arreglo de valores ordenados
                   
                foreach($listas as $pos=>$lista){
                    //Agrego la cant de escaños obtenidos para esta lista
                    // cant de votos obtenidos / menor cociente
                    $c = $lista['votos'] / $ar[3];
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

	function evt__cuadro_directivo_e__seleccion($seleccion)
	{
	}
        
	//-----------------------------------------------------------------------------------
	//---- cuadro_directivo_g -----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_directivo_g(gu_kena_ei_cuadro $cuadro)
	{
            $cuadro->set_datos($this->dep('datos')->tabla('lista_cdirectivo')->get_listado());
	}

	function evt__cuadro_directivo_g__seleccion($seleccion)
	{
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_directivo_nd ----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_directivo_nd(gu_kena_ei_cuadro $cuadro)
	{
            $cuadro->set_datos($this->dep('datos')->tabla('lista_cdirectivo')->get_listado());
	}

	function evt__cuadro_directivo_nd__seleccion($seleccion)
	{
	}
        
}
?>
