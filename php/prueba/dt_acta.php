<?php
class dt_acta extends toba_datos_tabla
{
	function get_listado()
	{
		$sql = "SELECT
			t_a.id_acta,
			t_a.total_votos_blancos,
			t_a.total_votos_nulos,
			t_a.total_votos_recurridos,
			t_s.nombre as id_sede_nombre,
			t_t.descripcion as id_tipo_nombre
		FROM
			acta as t_a	LEFT OUTER JOIN sede as t_s ON (t_a.de = t_s.id_sede)
			LEFT OUTER JOIN tipo as t_t ON (t_a.id_tipo = t_t.id_tipo)";
		return toba::db('ccomputos')->consultar($sql);
	}
        
        function get_mesas($id_sede = null, $id_claustro = null){
            $where = "";
            
            if(isset($id_sede)){
                $where = " WHERE t_a.de = $id_sede ";
                if(isset($id_claustro))
                    $where = " AND id_claustro = $id_claustro ";
                
            }
            else
                if(isset($id_claustro))
                    $where = " WHERE id_claustro = $id_claustro ";
                
            $sql = "SELECT
			DISTINCT t_a.nro_mesa
		FROM
			acta t_a
                        $where ";
		return toba::db('ccomputos')->consultar($sql);
        }


	function get_descripciones()
	{
		$sql = "SELECT id_acta, id_acta FROM acta ORDER BY id_acta";
		return toba::db('ccomputos')->consultar($sql);
	}
        
        function cant_empadronados($id_nro_ue, $id_claustro, $id_tipo){
            $sql = "SELECT sum(t_a.cant_empadronados) as cant FROM acta t_a "
                    . "INNER JOIN sede t_s ON t_s.id_sede = t_a.para"
                    . " INNER JOIN unidad_electoral t_ue ON t_ue.id_nro_ue = t_s.id_ue "
                    . "WHERE t_ue.id_nro_ue = $id_nro_ue"
                    . " AND id_claustro = $id_claustro "
                    . "AND id_tipo = $id_tipo";
            $ar = toba::db('ccomputos')->consultar($sql);
            return $ar[0]['cant'];
        }

}
?>