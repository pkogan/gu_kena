<?php
class dt_claustro extends gu_kena_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, descripcion FROM claustro ORDER BY descripcion";
		return toba::db('gu_kena')->consultar($sql);
	}









        function get_descripcion($id_claustro){
            $sql = "SELECT descripcion FROM claustro WHERE id = $id_claustro";
            $ar = toba::db('gu_kena')->consultar($sql);
            return $ar[0]['descripcion'];
        }
}
?>