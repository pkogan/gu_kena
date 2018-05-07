<?php
class dt_voto_lista_rector extends gu_kena_datos_tabla
{
	//obtiene el listado de voto_lista_rector correspondientes al acta que recibe como parametro 
        function get_listado_votos($id_acta)
	{
            $sql = "SELECT t_l.id_nro_lista, 
                           t_l.nombre,
                           t_v.cant_votos as votos
                    FROM voto_lista_rector t_v
                    INNER JOIN lista_rector t_l ON (t_l.id_nro_lista = t_v.id_lista)
                    INNER JOIN acta t_a ON (t_a.id_acta = t_v.id_acta)
                    WHERE t_a.id_acta = $id_acta 
                    ORDER BY t_l.id_nro_lista";
                    
            return toba::db('gu_kena')->consultar($sql);
	}
        
        //usado por ci_rector: cantidad de votos que recibe una lista de un claustro y unidad academica
        function cant_votos($id_lista, $id_nro_ue, $id_claustro){
            $sql = "SELECT sum(t_v.cant_votos) votos FROM voto_lista_rector t_v "
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
}
?>