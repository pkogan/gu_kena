<?php
class ci_consejeros_superior extends toba_ci
{
    protected $s__votos_e;
    protected $s__votos_g;
    protected $s__votos_nd;
    protected $s__votos_d;
    
    //---- Cuadro -----------------------------------------------------------------------

	function conf__cuadro_superior_e(toba_ei_cuadro $cuadro)
	{
            $this->dep('cuadro_superior_e')->colapsar();//No se muestra el cuadro en un principio
            
            $unidades = $this->dep('datos')->tabla('unidad_electoral')->get_descripciones();
            
            //Cargar la cantidad de empadronados para el claustro estudiantes=3
            // en cada unidad
            $ar = $this->cargar_cant_empadronados($unidades, 3);
            
            //Ultima fila carga los votos ponderados de cada lista
            $pos = sizeof($ar);
            $ar[$pos]['nombre'] = 'VOTOS PONDERADOS';
                        
            //Obtener las listas del claustro estudiantes=3
            $listas = $this->dep('datos')->tabla('lista_csuperior')->get_listas_actuales(3); 
            
            //Agregar las etiquetas de todas las listas
            $i = 1;
            foreach($listas as $lista){
                $l['clave'] = $lista['id_nro_lista'];
                $l['titulo'] = $lista['nombre'];
                $l['estilo'] = 'col-cuadro-resultados';
                $l['estilo_titulo'] = 'tit-cuadro-resultados';
                //$l['permitir_html'] = true;
                
                $grupo[$i] = $lista['id_nro_lista'];
                
                $columnas[$i] = $l;
                $this->dep('cuadro_superior_e')->agregar_columnas($columnas);
                
                //Cargar la cantidad de votos para cada lista de claustro estudiantes=3 
                //en cada unidad
                $ar = $this->cargar_cant_votos($lista['id_nro_lista'], $ar, 3);
                
                //Cargar los votos ponderados para cada lista agregado como última fila
                //para claustro estudiantes=3
                $ar[$pos][$lista['id_nro_lista']] = 0;
                $ar = $this->cargar_votos_ponderados($lista['id_nro_lista'], $ar, 3);
                
                $i++;
            }
            $this->dep('cuadro_superior_e')->set_grupo_columnas('Listas',$grupo);
              
            $this->s__votos_e = $ar;//Guardar los votos para el calculo dhondt
            
            return $ar;
        }
        
        //Metodo responsable de cargar la segunda columna con la cantidad de empadronados
        // en cada unidad electoral
        function cargar_cant_empadronados($unidades, $id_claustro){
            for($i=0; $i<sizeof($unidades); $i++){//Recorro las unidades
                //Agrega la cantidad de empadronados calculado en acta para cada unidad con claustro estudiante y tipo 'superior'
                $unidades[$i]['cant_empadronados'] = $this->dep('datos')->tabla('acta')->cant_empadronados($unidades[$i]['id_nro_ue'], $id_claustro, 1);
                
            }
            return $unidades;
        }
        
        function cargar_cant_votos($id_lista, $unidades, $id_claustro){
            for($i=0; $i<sizeof($unidades)-1; $i++){//Recorro las unidades
                //Agrega la cantidad de empadronados calculado en acta para cada unidad con claustro estudiante y tipo 'superior'
                $unidades[$i][$id_lista] = $this->dep('datos')->tabla('voto_lista_csuperior')->cant_votos($id_lista, $unidades[$i]['id_nro_ue'], $id_claustro);
                
            }
            return $unidades;
        }
        
        function cargar_votos_ponderados($id_lista, $unidades, $id_claustro){
            $pos = sizeof($unidades)-1;
            //Recorro las unidades exluyendo la última fila que tiene los votos ponderados
            for($i=0; $i<$pos; $i++){
                if(isset($unidades[$i][$id_lista]) && isset($unidades[$i]['cant_empadronados'])){
                    //Suma el cociente entre cant de votos de la 
                    //lista en la UEn / cant empadronados del claustro en la UEn
                    $cociente = $unidades[$i][$id_lista]/$unidades[$i]['cant_empadronados'];
                    
                    $unidades[$pos][$id_lista] += $cociente;
                }
            }
            
            return $unidades;
        }

	function evt__cuadro_superior_e__seleccion($datos)
	{
		$this->dep('datos')->cargar($datos);
	}
        
        //-----------------------------------------------------------------------------------
	//---- cuadro_dhondt_e --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_dhondt_e(gu_kena_ei_cuadro $cuadro)
	{
            //En $s__votos_e tengo todos los datos de los votos ponderados
            
            //Obtener las listas del claustro estudiantes=3
            $listas = $this->dep('datos')->tabla('lista_csuperior')->get_listas_actuales(3); 
            
            foreach($listas as $pos=>$lista){
                $listas[$pos]['votos'] = $this->s__votos_e[sizeof($this->s__votos_e)-1][$listas[$pos]['id_nro_lista']] *10000; 
            }
            
            return $listas;
	}

        
	//-----------------------------------------------------------------------------------
	//---- cuadro_superior_g ------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_superior_g(gu_kena_ei_cuadro $cuadro)
	{
            $this->dep('cuadro_superior_g')->colapsar();//No se muestra el cuadro en un principio
            
            $unidades = $this->dep('datos')->tabla('unidad_electoral')->get_descripciones();
            
            //Cargar la cantidad de empadronados para el claustro graduados=4
            // en cada unidad
            $ar = $this->cargar_cant_empadronados($unidades, 4);
            
            //Ultima fila carga los votos ponderados de cada lista
            $pos = sizeof($ar);
            $ar[$pos]['nombre'] = 'VOTOS PONDERADOS';
                        
            //Obtener las listas del claustro graduados=4
            $listas = $this->dep('datos')->tabla('lista_csuperior')->get_listas_actuales(4); 
            
            //Agregar las etiquetas de todas las listas
            $i = 1;
            foreach($listas as $lista){
                $l['clave'] = $lista['id_nro_lista'];
                $l['titulo'] = $lista['nombre'];
                $l['estilo'] = 'col-cuadro-resultados';
                $l['estilo_titulo'] = 'tit-cuadro-resultados';
                //$l['permitir_html'] = true;
                
                $grupo[$i] = $lista['id_nro_lista'];
                
                $columnas[$i] = $l;
                $this->dep('cuadro_superior_e')->agregar_columnas($columnas);
                
                //Cargar la cantidad de votos para cada lista de claustro graduados=4 
                //en cada unidad
                $ar = $this->cargar_cant_votos($lista['id_nro_lista'], $ar, 4);
                
                //Cargar los votos ponderados para cada lista agregado como última fila
                //para claustro graduados=4
                $ar[$pos][$lista['id_nro_lista']] = 0;
                $ar = $this->cargar_votos_ponderados($lista['id_nro_lista'], $ar, 4);
                
                $i++;
            }
            
            if(isset($grupo))
                $this->dep('cuadro_superior_e')->set_grupo_columnas('Listas',$grupo);
              
            $this->s__votos_g = $ar;//Guardar los votos para el calculo dhondt
            
            return $ar;
        }

	function evt__cuadro_superior_g__seleccion($seleccion)
	{
	}
        
        //-----------------------------------------------------------------------------------
	//---- cuadro_dhondt_g --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_dhondt_g(gu_kena_ei_cuadro $cuadro)
	{
            //En $s__votos_g tengo todos los datos de los votos ponderados
            
            //Obtener las listas del claustro graduados=4
            $listas = $this->dep('datos')->tabla('lista_csuperior')->get_listas_actuales(4); 
            
            foreach($listas as $pos=>$lista){
                $listas[$pos]['votos'] = $this->s__votos_g[sizeof($this->s__votos_g)-1][$listas[$pos]['id_nro_lista']]; 
            }
            
            return $listas;
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_superior_nd -----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_superior_nd(gu_kena_ei_cuadro $cuadro)
	{
            $cuadro->set_datos($this->dep('datos')->tabla('unidad_electoral')->get_descripciones());
	}

	function evt__cuadro_superior_nd__seleccion($seleccion)
	{
	}
	
	//-----------------------------------------------------------------------------------
	//---- cuadro_dhondt_nd -------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_dhondt_nd(gu_kena_ei_cuadro $cuadro)
	{
	}
        
        //-----------------------------------------------------------------------------------
	//---- cuadro_superior_d ------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_superior_d(gu_kena_ei_cuadro $cuadro)
	{
            $cuadro->set_datos($this->dep('datos')->tabla('unidad_electoral')->get_descripciones());
	}

	function evt__cuadro_superior_d__seleccion($seleccion)
	{
	}

        //-----------------------------------------------------------------------------------
	//---- cuadro_dhondt_d --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_dhondt_d(gu_kena_ei_cuadro $cuadro)
	{
	}

        //-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf()
	{
            $this->pantalla()->tab('pant_docente')->ocultar();
	}


}
?>
