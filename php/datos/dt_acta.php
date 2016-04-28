<?php
class dt_acta extends gu_kena_datos_tabla
{
	function get_listado($id_acta = null)
	{
            if(isset($id_acta)){
                $where = "WHERE id_acta = $id_acta";
                
            }
            else
                $where = "";
            
            $sql = "SELECT
                    t_a.id_acta,
                    t_a.total_votos_blancos,
                    t_a.total_votos_nulos,
                    t_a.total_votos_recurridos,
                    t_t.descripcion as id_tipo_nombre,
                    t_a.de,
                    t_a.para
            FROM
                    acta as t_a	LEFT OUTER JOIN tipo as t_t ON (t_a.id_tipo = t_t.id_tipo) 
                    $where ";
            return toba::db('gu_kena')->consultar($sql);
	}

        function get_descripciones($de = null, $para = null)
	{
            $where = array();
            if(isset($de) && isset($para)){
                $where = "WHERE de = $de AND para = $para";
            }
            else{
                if(isset($de)){
                    $where = "WHERE de=$de";
                }
                if(isset($para)){
                    $where = "WHERE para=$para";
                }
            }
            
            $sql = "SELECT id_acta, "
                    . "total_votos_blancos, "
                    . "total_votos_nulos, "
                    . "total_votos_recurridos,"
                    . "t_a.id_tipo,"
                    . "t_t.descripcion as tipo,"
                    . "de,"
                    . "para "
                    . "FROM acta as t_a "
                    . "INNER JOIN tipo as t_t ON (t_t.id_tipo = t_a.id_tipo)" 
                    . " $where ORDER BY id_acta";
            
            return toba::db('gu_kena')->consultar($sql);
	}
        
        function get_ultimas_descripciones_de($de)
	{
            $sql = "SELECT id_acta, "
                    . "total_votos_blancos, "
                    . "total_votos_nulos, "
                    . "total_votos_recurridos,"
                    . "t_a.id_tipo,"
                    . "t_t.descripcion as tipo,"
                    . "t_a.de,"
                    . "t_a.para "
                    . "FROM acta as t_a "
                    . "INNER JOIN tipo as t_t ON (t_t.id_tipo = t_a.id_tipo)"
                    . "INNER JOIN mesa as t_m ON (t_m.id_mesa = t_a.de)" 
                    . "where t_a.de = $de AND t_m.fecha = (SELECT max(fecha) FROM mesa ) ORDER BY id_acta";
            
            return toba::db('gu_kena')->consultar($sql);
	}
        
}
?>