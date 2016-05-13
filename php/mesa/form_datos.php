<?php
class form_datos extends gu_kena_ei_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Validacion general ----------------------------------
		
		{$this->objeto_js}.evt__modificacion = function()
		{
					var cant_dir = this.controlador.dep('form_ml_directivo').total('votos');
                                        var cant_extra = this.controlador.dep('form_ml_extra').total('votos');
                                        var cant_sup = this.controlador.dep('form_ml_superior').total('votos');

                                        var contenido_extra = this.controlador.dep('form_ml_extra').filas();
                                        var contenido_dir = this.controlador.dep('form_ml_directivo').filas();
                                           
                                        if(contenido_dir.length > 0 && cant_dir != cant_sup){
                                            alert('La cantidad total de votantes deben coincidir en cada categoria');
                                            return false;
                                        }
                                        else{
                                            if(contenido_extra.length > 0 && (cant_extra != cant_dir || cant_extra != cant_sup)){
                                                alert('La cantidad total de votantes deben coincidir en cada categoria');
                                                return false;
                                            }
                                        }
		}
		";
	}

}
?>