<?php
class dt_voto_lista_cdirectivo extends gu_kena_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_lista,cant_votos  FROM voto_lista_cdirectivo ORDER BY id_lista";
		return toba::db('gu_kena')->consultar($sql);
	}


//obtiene el listado de voto_lista_cdirectivo correspondientes al acta que recibe como parametro 
        function get_listado_votos_dir($id_acta)
	{
            $sql = "SELECT t_l.id_nro_lista, 
                           t_l.nombre,
                           t_v.cant_votos as votos
                    FROM voto_lista_cdirectivo t_v
                    INNER JOIN lista_cdirectivo t_l ON (t_l.id_nro_lista = t_v.id_lista)
                    INNER JOIN acta t_a ON (t_a.id_acta = t_v.id_acta)
                    INNER JOIN sede t_s ON (t_s.id_sede = t_a.id_sede)
                    WHERE t_l.id_ue = t_s.id_ue
                    AND t_a.id_acta = $id_acta ORDER BY t_l.id_nro_lista";
                    
            return toba::db('gu_kena')->consultar($sql);
	}

        //usado por ci_consejeros_directivos
        function get_listas_con_total_votos($id_claustro, $id_nro_ue){
            $sql = "SELECT
                        t_l.id_nro_lista,
                        t_l.nombre,
			sum(t_v.cant_votos) votos
			
		FROM
			voto_lista_cdirectivo as t_v
                        INNER JOIN lista_cdirectivo as t_l ON (t_l.id_nro_lista=t_v.id_lista) 
                        INNER JOIN acta t_a ON (t_a.id_acta = t_v.id_acta)
                        INNER JOIN mesa t_m ON (t_m.id_mesa = t_a.de)
                WHERE t_l.id_claustro = $id_claustro "
                    . "AND t_l.id_ue = $id_nro_ue "
                    . "AND t_l.fecha = (SELECT max(fecha) FROM lista_cdirectivo)"
                    . " AND t_m.estado > 1 "
                    . "GROUP BY t_l.id_nro_lista "
                    . "ORDER BY votos DESC";
		
		return toba::db('gu_kena')->consultar($sql);
        }

}
?>




