<?php
class dt_lista_csuperior extends gu_kena_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_nro_lista, nombre FROM lista_csuperior ORDER BY nombre";
		return toba::db('gu_kena')->consultar($sql);
	}
        
        function get_listas_a_votar($id_acta){
            $sql = "SELECT t_l.id_nro_lista, 
                           t_l.nombre 
                    FROM acta t_a
                    INNER JOIN mesa t_m ON (t_m.id_mesa = t_a.de)
                    INNER JOIN sede t_s ON (t_s.id_sede = t_a.id_sede)
                    INNER JOIN lista_csuperior t_l ON (t_l.id_claustro = t_m.id_claustro)
                    WHERE t_a.id_acta = $id_acta
                    AND t_l.fecha = (SELECT max(id_fecha) FROM acto_electoral)"
                    . " order by t_l.id_nro_lista";
                    
            return toba::db('gu_kena')->consultar($sql);
        }

        function get_listas($fecha, $id_claustro){
            $where = "";
            if(isset($id_claustro)){//Se pide de un claustro en especifico
                $where = "AND id_claustro = $id_claustro ";
            }
            
            $sql = "SELECT id_nro_lista, nombre, sigla FROM lista_csuperior "
                    . "WHERE fecha = '$fecha' $where "
                    . "  order by nombre"; //. "ORDER BY id_nro_lista";
            return toba::db('gu_kena')->consultar($sql);
        }

        function get_listas_actuales($id_claustro = null){
            $where = "";
            if(isset($id_claustro)){//Se pide de un claustro en especifico
                $where = "AND id_claustro = $id_claustro ";
            }
            
            $sql = "SELECT id_nro_lista, nombre, sigla FROM lista_csuperior "
                    . "WHERE fecha = (SELECT max(fecha) FROM lista_csuperior ) $where "
                    . "  order by nombre"; //. "ORDER BY id_nro_lista";
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
			t_lc.nombre,
			t_c.descripcion as id_claustro_nombre,
			t_lc.fecha
		FROM
			lista_csuperior as t_lc	LEFT OUTER JOIN claustro as t_c ON (t_lc.id_claustro = t_c.id)
		ORDER BY nombre";
		return toba::db('gu_kena')->consultar($sql);
	}

        //usado en ci_validar
        function get_ultimo_listado()
	{
		$sql = "SELECT
			t_lc.id_nro_lista,
			t_lc.nombre,
			t_c.descripcion as claustro,
			t_lc.fecha
		FROM
			lista_csuperior as t_lc	LEFT OUTER JOIN claustro as t_c ON (t_lc.id_claustro = t_c.id)
                        WHERE t_lc.fecha = (SELECT max(id_fecha) FROM acto_electoral)
		ORDER BY t_c.id, t_lc.nombre";
		$res = toba::db('gu_kena')->consultar($sql);
                return $res;
	}
}
?>
