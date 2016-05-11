<?php
class dt_estado extends gu_kena_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_estado, descripcion FROM estado ORDER BY descripcion";
		return toba::db('gu_kena')->consultar($sql);
	}

}
?>