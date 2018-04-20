<?php
class dt_acto_electoral extends gu_kena_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_fecha, descripcion FROM acto_electoral ORDER BY descripcion";
		return toba::db('gu_kena')->consultar($sql);
	}

}

?>