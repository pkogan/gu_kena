<?php
class form_unidad extends gu_kena_ei_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__id_nro_ue__procesar = function(es_inicial)
		{
                    if(!es_inicial)
                        this.controlador.dep('form_unidad').set_evento(new evento_ei('modificacion', true, '' ));
                   
                    return false;
		}";
	}

}

?>
