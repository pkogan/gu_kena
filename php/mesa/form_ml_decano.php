<?php
class form_ml_decano extends gu_kena_ei_formulario_ml
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
			var total = this.controlador.dep('form_ml_decano').total('votos');
			var emp = this.controlador.dep('form_datos').ef('cant_empadronados').get_estado();
			if(total > emp){
				alert('La cantidad de votos cargados en este formulario supera a la cantidad de empadronados en esta mesa ');
				this.ef('votos').ir_a_fila(fila).set_estado('');
			}
		}
		";
	}



}
?>