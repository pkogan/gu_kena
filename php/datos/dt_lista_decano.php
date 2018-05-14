<?php
class dt_lista_decano extends gu_kena_datos_tabla
{
    
        function get_listas_a_votar($id_acta){
            $sql = "SELECT t_l.id_nro_lista, 
                           t_l.nombre 
                    FROM acta t_a
                    INNER JOIN sede t_s ON (t_s.id_sede = t_a.id_sede)
                    INNER JOIN lista_decano t_l ON (t_l.id_ue = t_s.id_ue)
                    WHERE t_l.fecha = (SELECT max(id_fecha) FROM acto_electoral)
                    AND t_a.id_acta = $id_acta
                    order by t_l.id_nro_lista";
                    
            return toba::db('gu_kena')->consultar($sql);
        }
        
       function get_listas($fecha, $id_claustro = null){
            //Todos los claustros
            $sql = "SELECT id_nro_lista, nombre, sigla FROM lista_decano "
                    . "WHERE fecha = '$fecha' "
                    . "  order by nombre"; 
            return toba::db('gu_kena')->consultar($sql);
        } 
        
        //usado en ci_validar
        function get_ultimo_listado()
	{
		$sql = "SELECT
			t_lc.id_nro_lista,
                        t_lc.sigla sigla_lista,
			t_lc.nombre descripcion,
                        t_ue.nombre unidad_electoral,
                        t_ue.sigla sigla_ue,
			t_lc.fecha
		FROM
			lista_decano as t_lc
                        inner join unidad_electoral t_ue on t_ue.id_nro_ue = t_lc.id_ue
                        WHERE t_lc.fecha = (SELECT max(id_fecha) FROM acto_electoral)
		ORDER BY t_ue.sigla, t_lc.nombre";
		$res = toba::db('gu_kena')->consultar($sql);
                return $res;
	}
}

?>