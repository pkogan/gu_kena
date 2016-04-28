<?php
class ci_principal extends gu_kena_ci
{
    protected $s__unidad;
	//-----------------------------------------------------------------------------------
	//---- form_unidad -----------------------------------------------------------
	//-----------------------------------------------------------------------------------

    function conf()
	{
            if(!isset($this->s__unidad))
                $this->s__unidad = 1;
            
                        
	}
    
	function evt__form_unidad__modificacion($datos)
	{
            $this->s__unidad = $datos['id_nro_ue'];
            
        }

	

}

?>
