<?php
class dt_voto_lista_rector extends gu_kena_datos_tabla
{
	function ini()
	{
	}

	/**
	 * Ventana de validacion que se invoca cuando se crea o modifica una fila en memoria. Lanzar una excepcion en caso de error
	 * @param array $fila Datos de la fila
	 * @param mixed $id Id. interno de la fila, si tiene (en el caso modificacion de la fila)
	 */
	function evt__validar_ingreso($fila, $id=null)
	{
	}

}
?>