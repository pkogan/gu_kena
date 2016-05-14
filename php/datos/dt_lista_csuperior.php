<?php
class dt_lista_csuperior extends gu_kena_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_nro_lista, nombre FROM lista_csuperior ORDER BY nombre";
		return toba::db('gu_kena')->consultar($sql);
	}


        function get_listas_actuales($id_claustro = null){
            $where = "";
            if(isset($id_claustro)){//Se pide de un claustro en especifico
                $where = "AND id_claustro = $id_claustro ";
            }
            
            $sql = "SELECT id_nro_lista, nombre FROM lista_csuperior "
                    . "WHERE fecha = (SELECT max(fecha) FROM lista_csuperior ) $where "
                    . "ORDER BY id_nro_lista";
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
                        WHERE t_lc.fecha = (SELECT max(fecha) FROM lista_csuperior)
		ORDER BY t_c.id";
		return toba::db('gu_kena')->consultar($sql);
	}
}
?>
