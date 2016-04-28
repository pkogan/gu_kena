<?php
class dt_sede extends gu_kena_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_sede, nombre FROM sede ORDER BY nombre";
		return toba::db('gu_kena')->consultar($sql);
	}

        
        function get_descripcion($id_sede){
            $sql = "SELECT nombre FROM sede WHERE id_sede = $id_sede";
            $ar = toba::db('gu_kena')->consultar($sql);
            return $ar[0]['nombre'];
        }

}
?>