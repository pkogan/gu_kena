<?php
class dt_voto_lista_cdirectivo extends gu_kena_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_lista,cant_votos  FROM voto_lista_cdirectivo ORDER BY id_lista";
		return toba::db('gu_kena')->consultar($sql);
	}


//obtiene el listado de voto_lista_cdirectivo correspondientes al acta que recibe como parametro del acta 
        function get_listado_votos_dir($acta)
	{
		
		$sql = "SELECT
                        t_v.id_acta,
                        t_v.id_lista,
			t_l.nombre,
			t_v.cant_votos
			
		FROM
			voto_lista_cdirectivo as t_v, lista_cdirectivo as t_l	
                WHERE t_l.id_nro_lista=t_v.id_lista and t_v.id_acta=".$acta;
		
		return toba::db('gu_kena')->consultar($sql);
	}

        function get_listas_con_total_votos($id_claustro, $id_nro_ue){
            $sql = "SELECT
                        t_l.id_nro_lista,
                        t_l.nombre,
			sum(t_v.cant_votos) votos
			
		FROM
			voto_lista_cdirectivo as t_v, lista_cdirectivo as t_l	
                WHERE t_l.id_nro_lista=t_v.id_lista 
                AND t_l.id_claustro = $id_claustro "
                    . "AND t_l.id_ue = $id_nro_ue "
                    . "GROUP BY t_l.id_nro_lista "
                    . "ORDER BY votos DESC";
		
		return toba::db('gu_kena')->consultar($sql);
        }

}
?>
