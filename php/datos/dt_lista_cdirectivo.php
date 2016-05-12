<?php
class dt_lista_cdirectivo extends gu_kena_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_nro_lista, nombre FROM lista_cdirectivo ORDER BY nombre";
		return toba::db('gu_kena')->consultar($sql);
	}


        function get_listas_a_votar($id_acta){
            $sql = "SELECT t_l.id_nro_lista, 
                           t_l.nombre 
                    FROM acta t_a
                    INNER JOIN mesa t_m ON (t_m.id_mesa = t_a.para)
                    INNER JOIN sede t_s ON (t_s.id_sede = t_m.id_sede)
                    INNER JOIN unidad_electoral t_u ON (t_u.id_nro_ue = t_s.id_ue)
                    INNER JOIN lista_cdirectivo t_l ON (t_l.id_ue = t_u.id_nro_ue)
                    WHERE t_a.id_acta = $id_acta AND t_m.id_claustro = t_l.id_claustro
                    AND t_l.fecha = (SELECT max(fecha) FROM lista_cdirectivo)";
                    
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
