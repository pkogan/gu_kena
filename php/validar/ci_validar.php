<?php
class ci_validar extends gu_kena_ci
{
        protected $s__filtro;
	//-----------------------------------------------------------------------------------
	//---- filtro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro(gu_kena_ei_filtro $filtro)
	{
            $filtro->set_datos($this->s__filtro);
	}

	function evt__filtro__filtrar($datos)
	{
            $this->s__filtro = $datos;
            print_r($datos);
	}

	function evt__filtro__cancelar()
	{
            $aux = $this->s__filtro['claustro'];
            $this->s__filtro = null;
            $this->s__filtro['claustro'] = $aux;
	}
       
	//-----------------------------------------------------------------------------------
	//---- cuadro_emp_mesas -------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_emp_mesas(gu_kena_ei_cuadro $cuadro)
	{
            $m = $this->dep('datos')->tabla('mesa')->get_ultimo_listado();
            return $m;
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_actas -----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_actas(gu_kena_ei_cuadro $cuadro)
	{
            $m = $this->dep('datos')->tabla('acta')->get_ultimo_listado();
            return $m;
	}

        //-----------------------------------------------------------------------------------
	//---- cuadro_listas -----------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        function conf__cuadro_listas_sup(gu_kena_ei_cuadro $cuadro)
	{
            $m = $this->dep('datos')->tabla('lista_csuperior')->get_ultimo_listado();
            return $m;
	}
	
        function conf__cuadro_listas_dir(gu_kena_ei_cuadro $cuadro)
	{
            $m = $this->dep('datos')->tabla('lista_cdirectivo')->get_ultimo_listado();
            return $m;
	}
}
?>