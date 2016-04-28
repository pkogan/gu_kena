<?php
class form_ml_superior extends gu_kena_ei_formulario_ml
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__votos__procesar = function(es_inicial, fila)
		{
                    
		}
		";
	}      
       

}

?>
