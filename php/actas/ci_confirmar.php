<?php
class ci_confirmar extends toba_ci
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
            
	}

	function evt__filtro__cancelar()
	{
            $aux = $this->s__filtro['claustro'];
            $this->s__filtro = null;
            $this->s__filtro['claustro'] = $aux;
	}
        
}
?>