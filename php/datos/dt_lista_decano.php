<?php
class dt_lista_decano extends gu_kena_datos_tabla
{
    
        function get_listas_a_votar($id_acta){
            $sql = "SELECT t_l.id_nro_lista, 
                           t_l.nombre 
                    FROM acta t_a
                    INNER JOIN sede t_s ON (t_s.id_sede = t_a.id_sede)
                    INNER JOIN lista_decano t_l ON (t_l.id_ue = t_s.id_ue)
                    WHERE t_l.fecha = (SELECT max(fecha) FROM lista_decano)
                    AND t_a.id_acta = $id_acta
                    order by t_l.id_nro_lista";
                    
            return toba::db('gu_kena')->consultar($sql);
        }
        
        
}

?>