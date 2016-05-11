<?php
class ci_claustros extends ci_validar
{
        //-----------------------------------------------------------------------------------
	//---- pant_estudiantes -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        function conf__pant_estudiantes()
	{
		$this->controlador->s__filtro['claustro']['valor'] = 3;
	}
        
        //-----------------------------------------------------------------------------------
	//---- cuadro_estudiantes -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        function conf__cuadro_estudiantes(toba_ei_cuadro $cuadro)
	{
		$cuadro->set_datos($this->controlador()->dep('datos')->tabla('acta')->get_ultimas_descripciones($this->controlador->s__filtro));
	}

	function evt__cuadro_estudiantes__seleccion($datos)
	{
		//$this->dep('datos')->cargar($datos);
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
		$cuadro->set_datos($this->controlador()->dep('datos')->tabla('acta')->get_ultimas_descripciones($this->controlador->s__filtro));
	}

	function evt__cuadro_graduados__seleccion($datos)
	{
		//$this->dep('datos')->cargar($datos);
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
		$cuadro->set_datos($this->controlador()->dep('datos')->tabla('acta')->get_ultimas_descripciones($this->controlador->s__filtro));
	}

	function evt__cuadro_no_docentes__seleccion($datos)
	{
		//$this->dep('datos')->cargar($datos);
	}
}

?>