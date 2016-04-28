<?php
class dt_tipo extends gu_kena_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_tipo, descripcion FROM tipo ORDER BY descripcion";
		return toba::db('gu_kena')->consultar($sql);
	}



























}
?>