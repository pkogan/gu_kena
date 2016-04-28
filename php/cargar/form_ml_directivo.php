<?php
class form_ml_directivo extends gu_kena_ei_formulario_ml
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
//                  var total = this.controlador.dep('form_ml_directivo').total('votos');
//                  var emp = this.controlador.dep('form_inicial').ef('cant_empadronados').get_estado();
//                  if(total > emp){//La sumatoria de los votantes supera a la cant de empadronados
//                    alert('La cantidad de votantes supera a la cantidad de empadronados en esta mesa');
//                  }
		}
		";
	}

}

?>
