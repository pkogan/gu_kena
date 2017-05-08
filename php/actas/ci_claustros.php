<?php
class ci_claustros extends ci_confirmar
{
	
		//-----------------------------------------------------------------------------------
	//---- pant_estudiantes -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
		function conf__pant_estudiantes()
	{
		$this->controlador->s__filtro['claustro']['valor'] = 3;
//                if(isset($this->controlador->s__filtro_guardado)){
//                    $this->controlador->s__filtro=$this->controlador->s__filtro_guardado;
//                }
	}
		
		//-----------------------------------------------------------------------------------
	//---- cuadro_estudiantes -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
		function conf__cuadro_estudiantes(toba_ei_cuadro $cuadro)
	{
			//si esta de vuelta recuperar filtro.
			//print_r($this->controlador->s__filtro);
		$cuadro->set_datos($this->controlador()->dep('datos')->tabla('mesa')->get_ultimas_descripciones($this->controlador->s__filtro));
	}

	function evt__cuadro_estudiantes__seleccion($datos)
	{
			$dato['k'] = 10000045; // guarda el componente actual para cuando retorne
			$dato['c'] = $datos['id_mesa'];
			//$this->controlador->s__filtro_guardado=$this->controlador->s__filtro;
			toba::vinculador()->navegar_a("",10000044,$dato); //navega a el controlador con el id especificado (en este caso operación mesa)
		}

		//-----------------------------------------------------------------------------------
	//---- pant_graduados -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
		function conf__pant_graduados()
	{
		$this->controlador->s__filtro['claustro']['valor'] = 4;
	}
		
		//-----------------------------------------------------------------------------------
	//---- cuadro_estudiantes -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
		function conf__cuadro_graduados(toba_ei_cuadro $cuadro)
	{
		$cuadro->set_datos($this->controlador()->dep('datos')->tabla('mesa')->get_ultimas_descripciones($this->controlador->s__filtro));
	}

	function evt__cuadro_graduados__seleccion($datos)
	{
			$dato['k'] = 10000045;
				$dato['c'] = $datos['id_mesa'];
				toba::vinculador()->navegar_a("",10000044,$dato);
	}
		
		//-----------------------------------------------------------------------------------
	//---- pant_no_docente -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
		function conf__pant_no_docente()
	{
		$this->controlador->s__filtro['claustro']['valor'] = 1;
	}
		
		//-----------------------------------------------------------------------------------
	//---- cuadro_estudiantes -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
		function conf__cuadro_no_docentes(toba_ei_cuadro $cuadro)
	{
		$cuadro->set_datos($this->controlador()->dep('datos')->tabla('mesa')->get_ultimas_descripciones($this->controlador->s__filtro));
	}

	function evt__cuadro_no_docentes__seleccion($datos)
	{
			$dato['k'] = 10000045;
				$dato['c'] = $datos['id_mesa'];
				toba::vinculador()->navegar_a("",10000044,$dato);
	}
}

?>