<?php
class form_ml_rector extends gu_kena_ei_formulario_ml
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Procesamiento de EFs --------------------------------
		
		/**
		 * Metodo que se invoca al cambiar el valor del ef en el cliente
		 * Se dispara inicialmente al graficar la pantalla, enviando en true el primer parametro
		 */
		{$this->objeto_js}.evt__votos__procesar = function(es_inicial, fila)
		{
                    var total = this.controlador.dep('form_ml_rector').total('votos');
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