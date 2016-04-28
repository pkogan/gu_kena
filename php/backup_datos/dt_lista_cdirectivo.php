<?php
class dt_lista_cdirectivo extends gu_kena_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_nro_lista, nombre FROM lista_cdirectivo ORDER BY nombre";
		return toba::db('gu_kena')->consultar($sql);
	}


        function get_listas_actuales($id_claustro = null, $id_nro_ue = null){
            $where = "";
            if(isset($id_claustro))
                $where = " id_claustro = $id_claustro ";
            if(isset($id_nro_ue))
                $where = " AND id_ue = $id_nro_ue ";
            $sql = "SELECT id_nro_lista, nombre FROM lista_cdirectivo "
                    . "WHERE fecha = (SELECT max(fecha) FROM lista_cdirectivo ) $where";
                    
            return toba::db('gu_kena')->consultar($sql);
        }
        
        function get_fecha_reciente(){
            $sql = "SELECT max(fecha)as fecha FROM lista_csuperior";
            $ar = toba::db('gu_kena')->consultar($sql);
            return $ar[0];
        }

	function get_listado()
	{
		$sql = "SELECT
			t_lc.id_nro_lista,
			t_ue.nombre as id_ue_nombre,
			t_lc.nombre,
			t_c.descripcion as id_claustro_nombre,
			t_lc.fecha
		FROM
			lista_cdirectivo as t_lc	LEFT OUTER JOIN unidad_electoral as t_ue ON (t_lc.id_ue = t_ue.id_nro_ue)
			LEFT OUTER JOIN claustro as t_c ON (t_lc.id_claustro = t_c.id)
		ORDER BY nombre";
		return toba::db('gu_kena')->consultar($sql);
	}


}
?>
