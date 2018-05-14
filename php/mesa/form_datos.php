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
		//Validacion de totales iguales
		{$this->objeto_js}.evt__modificacion = function()
		{
                    var cant_rec = this.controlador.dep('form_ml_rector').total('votos');
                    var cant_dec = this.controlador.dep('form_ml_decano').total('votos');
                    var cant_director = this.controlador.dep('form_ml_director').total('votos');
                    var cant_dir = this.controlador.dep('form_ml_directivo').total('votos');
                    var cant_extra = this.controlador.dep('form_ml_extra').total('votos');
                    var cant_sup = this.controlador.dep('form_ml_superior').total('votos');

                    var arr = new Array();
                    if(this.controlador.dep('form_ml_rector').filas().length > 0 )
                        arr.push(cant_rec);
                    if(this.controlador.dep('form_ml_decano').filas().length > 0 )
                        arr.push(cant_dec);
                    if(this.controlador.dep('form_ml_director').filas().length > 0 )
                        arr.push(cant_director);
                    if(this.controlador.dep('form_ml_directivo').filas().length > 0 )
                        arr.push(cant_dir);
                    if(this.controlador.dep('form_ml_extra').filas().length > 0 )
                        arr.push(cant_extra);
                    if(this.controlador.dep('form_ml_superior').filas().length > 0 )
                        arr.push(cant_sup);
                    
                    var x = arr[0];
                    for(var i=0; i<arr.length; i++){
                        if(x != arr[i]){
                            alert('La cantidad total de votantes deben coincidir en cada categoria');
                            return false;
                        }
                        x = arr[i];
                    }
                    
		}
		";
	}

}
?>