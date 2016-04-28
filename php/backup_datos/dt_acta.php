<?php
class dt_acta extends gu_kena_datos_tabla
{
	function get_listado()
	{
		$sql = "SELECT
			t_a.id_acta,
			t_a.total_votos_blancos,
			t_a.total_votos_nulos,
			t_a.total_votos_recurridos,
			t_t.descripcion as id_tipo_nombre,
			t_m.id_mesa as para_nombre,
			t_m1.id_mesa as de_nombre
		FROM
			acta as t_a	LEFT OUTER JOIN tipo as t_t ON (t_a.id_tipo = t_t.id_tipo)
			LEFT OUTER JOIN mesa as t_m ON (t_a.para = t_m.id_mesa)
			LEFT OUTER JOIN mesa as t_m1 ON (t_a.de = t_m1.id_mesa)";
		return toba::db('gu_kena')->consultar($sql);
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


	function get_descripciones($de = null, $para = null)
	{
            $where = array();
            if(isset($de) && isset($para)){
                $where = "WHERE de = $de AND para = $para";
            }
            else{
                if(isset($de)){
                    $where = "de=$de";
                }
                if(isset($para)){
                    $where = "para=$para";
                }
            }
            
            $sql = "SELECT id_acta, "
                    . "total_votos_blancos, "
                    . "total_votos_nulos, "
                    . "total_votos_recurridos,"
                    . "id_tipo,"
                    . "t_t.descripcion as tipo"
                    . "de,"
                    . "para "
                    . "FROM acta as t_a "
                    . "INNER JOIN tipo as t_t ON (t_t.id_tipo = t_a.id_tipo)" 
                    . "$where ORDER BY id_acta";
            
            return toba::db('gu_kena')->consultar($sql);
	}
        
}
?>