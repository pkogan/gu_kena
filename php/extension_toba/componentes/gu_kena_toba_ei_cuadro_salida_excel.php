<?php
//Esta clase fue creada manualmente para quitar tags html en excel, trabaja a la par con gu_kena_ei_cuadro
class gu_kena_toba_ei_cuadro_salida_excel extends toba_ei_cuadro_salida_excel
{
    function generar_layout_fila ($columnas, $datos_cuadro, $id_fila, $formateo, &$estilos)
	{
		$fila = array();
		//---> Creo las CELDAS de una FILA <----
		foreach (array_keys($columnas) as $clave) {
			$valor = "";
			if(isset($columnas[$clave]["clave"])) {
				if(isset($datos_cuadro[$id_fila][$clave])) {
					$valor_real = strip_tags($datos_cuadro[$id_fila][$clave]);
				} else {
					$valor_real = '';
				}
				//Hay que formatear?
				$estilo = array();
				if(isset($columnas[$clave]["formateo"])) {
					$funcion = "formato_" . $columnas[$clave]["formateo"];
					//Formateo el valor
					list($valor, $estilo) = $formateo->$funcion($valor_real);					
					if (is_null($estilo)) {
						$estilo = array();
					}
				} else {
					$valor = $valor_real;
				}				
				$estilos[$clave]['estilo'] = $this->excel_get_estilo($columnas[$clave]['estilo']);
				$estilos[$clave]['estilo'] = array_merge($estilo, $estilos[$clave]['estilo']);
				$estilos[$clave]['ancho'] = 'auto';
				if (isset($columnas[$clave]['grupo']) && $columnas[$clave]['grupo'] != '') {
					$estilos[$clave]['grupo'] = $columnas[$clave]['grupo'];
				}
			}
			$fila[$clave] = $valor;
		}
		return $fila;
	}
}
?>
