<?php
class dt_voto_lista_csuperior extends gu_kena_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_acta,id_lista,cant_votos FROM voto_lista_csuperior ";
		return toba::db('gu_kena')->consultar($sql);
	}


//obtiene el listado de voto_lista_csuperior correspondientes al acta que recibe como parametro del acta 
        function get_listado_votos($acta)
	{
		
		$sql = "SELECT
                        t_v.id_acta,
                        t_v.id_lista as id_nro_lista,
			t_l.nombre,
			t_v.cant_votos as votos
			
		FROM
			voto_lista_csuperior as t_v, 
                        lista_csuperior as t_l	
                WHERE t_l.id_nro_lista=t_v.id_lista and t_v.id_acta=".$acta
                        ." ORDER BY t_v.id_lista";
		
		return toba::db('gu_kena')->consultar($sql);
	}

	function get_listado()
	{
		$sql = "SELECT
			t_vlc.id_lista,
			t_a.id_acta as id_acta_nombre,
			t_lc.nombre as id_lista_nombre,
			t_vlc.cant_votos
		FROM
			voto_lista_csuperior as t_vlc	LEFT OUTER JOIN acta as t_a ON (t_vlc.id_acta = t_a.id_acta)
			LEFT OUTER JOIN lista_csuperior as t_lc ON (t_vlc.id_lista = t_lc.id_nro_lista)";
		return toba::db('gu_kena')->consultar($sql);
	}
        
        //usado por ci_consejeros_superior: cantidad de votos que recibe una lista de un claustro y unidad academica
        function cant_votos($id_lista, $id_nro_ue, $id_claustro){
            $sql = "SELECT sum(t_v.cant_votos) votos FROM voto_lista_csuperior t_v "
                    . "INNER JOIN acta t_a ON t_a.id_acta = t_v.id_acta "
                    . "INNER JOIN mesa t_m ON t_m.id_mesa = t_a.de "
                    . "INNER JOIN sede t_s ON t_s.id_sede = t_a.id_sede "
                    . "WHERE t_v.id_lista = $id_lista "
                    . "AND t_m.id_claustro = $id_claustro "
                    . " AND t_m.estado > 1 "
                    . "AND t_s.id_ue = $id_nro_ue "
                    . "ORDER BY votos";
            
            $ar = toba::db('gu_kena')->consultar($sql);
            return $ar[0]['votos'];
        }
        
        function get_listas_con_total_votos($id_claustro){
            $sql = "SELECT
                        t_l.id_nro_lista,
                        t_l.nombre,
			sum(t_v.cant_votos) votos
			
		FROM
			voto_lista_csuperior as t_v, lista_csuperior as t_l	
                WHERE t_l.id_nro_lista=t_v.id_lista 
                AND t_l.id_claustro = $id_claustro "
                    . "AND t_l.fecha = (SELECT max(fecha) FROM lista_csuperior)"
                    . "GROUP BY t_l.id_nro_lista "
                    . "ORDER BY votos DESC";
		
		return toba::db('gu_kena')->consultar($sql);
        }

}
?>
