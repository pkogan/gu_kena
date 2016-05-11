<?php
class dt_mesa extends gu_kena_datos_tabla
{
        function get_descripciones($id_sede = null, $claustro = null, $id_mesa = null)
        {
            if(isset($id_sede) && isset($claustro)){
                $where = " WHERE id_sede = $id_sede AND id_claustro = $claustro";
            }
            else{
                if(isset($id_mesa))
                    $where = "WHERE id_mesa = $id_mesa";
                else
                    $where = "";
            }                   
            $sql = "SELECT id_mesa, nro_mesa, cant_empadronados FROM mesa $where ORDER BY id_mesa";
            return toba::db('gu_kena')->consultar($sql);
        }
        
        function get_empadronados($id_mesa){
            $sql = "SELECT cant_empadronados,nro_mesa FROM mesa "
                    . "WHERE id_mesa = $id_mesa ";
		
            $ar = toba::db('gu_kena')->consultar($sql);
            return $ar[0];
        }
        
        function cant_empadronados($id_nro_ue, $id_claustro){
            $sql = "SELECT sum(t_m.cant_empadronados) as cant FROM mesa t_m "
                    . "INNER JOIN sede t_s ON t_s.id_sede = t_m.id_sede"
                    . " INNER JOIN unidad_electoral t_ue ON t_ue.id_nro_ue = t_s.id_ue "
                    . "WHERE t_ue.id_nro_ue = $id_nro_ue"
                    . "AND t_m.id_claustro = $id_claustro"
                    . "AND t_m.fecha = (SELECT max(fecha) FROM mesa)";
            $ar = toba::db('gu_kena')->consultar($sql);
            return $ar[0]['cant'];
        }
        
	function get_listado($id_mesa = null)
	{
            $where = "";
            if(isset($id_mesa)){
                $where = "WHERE id_mesa = $id_mesa";
            }
		$sql = "SELECT
			t_m.nro_mesa,
			t_m.cant_empadronados,
			t_c.descripcion as id_claustro_nombre,
			t_m.id_mesa,
			t_s.nombre as id_sede_nombre,
			t_m.fecha,
			t_m.estado,
                        t_c.descripcion as claustro,
                        t_s.nombre as sede,
                        t_ue.nombre as unidad_electoral
                        
		FROM
			mesa as t_m	
                        LEFT OUTER JOIN claustro as t_c ON (t_m.id_claustro = t_c.id)
			LEFT OUTER JOIN sede as t_s ON (t_m.id_sede = t_s.id_sede) 
                        LEFT OUTER JOIN unidad_electoral as t_ue ON (t_s.id_ue = t_ue.id_nro_ue) 
                        $where";
		return toba::db('gu_kena')->consultar($sql);
	}

        function get_ultimo_listado($id_mesa = null)
	{
            $where = "";
            if(isset($id_mesa)){
                $where = "WHERE id_mesa = $id_mesa AND t_m.fecha = (SELECT max(fecha) FROM mesa )";
            }
            else
                $where = "WHERE t_m.fecha = (SELECT max(fecha) FROM mesa )";
            
            $sql = "SELECT
			t_m.nro_mesa,
			t_m.cant_empadronados,
			t_m.id_mesa,
			t_m.fecha,
			t_m.estado,
                        t_c.descripcion as claustro,
                        t_s.nombre as sede,
                        t_ue.nombre as unidad_electoral
                        
		FROM
			mesa as t_m	
                        LEFT OUTER JOIN claustro as t_c ON (t_m.id_claustro = t_c.id)
			LEFT OUTER JOIN sede as t_s ON (t_m.id_sede = t_s.id_sede) 
                        LEFT OUTER JOIN unidad_electoral as t_ue ON (t_s.id_ue = t_ue.id_nro_ue) 
                        $where ORDER BY t_s.id_sede";
            return toba::db('gu_kena')->consultar($sql);
	}
        
        function get_cant_cargadas($id_claustro){
            $sql = "SELECT count(id_mesa) as porc FROM mesa "
                    . "WHERE fecha = (SELECT max(fecha) FROM mesa) "
                    . "AND estado >= 1 "
                    . "AND id_claustro = $id_claustro";
            $ar = toba::db('gu_kena')->consultar($sql);
            return $ar[0]['porc'];
        }
        
        function get_cant_confirmadas($id_claustro){
            $sql = "SELECT count(id_mesa) as porc FROM mesa "
                    . "WHERE fecha = (SELECT max(fecha) FROM mesa) "
                    . "AND estado >= 3"
                    . "AND id_claustro = $id_claustro";
            $ar = toba::db('gu_kena')->consultar($sql);
            return $ar[0]['porc'];
        }
        
        function get_cant_definitivas($id_claustro){
            $sql = "SELECT count(id_mesa) as porc FROM mesa "
                    . "WHERE fecha = (SELECT max(fecha) FROM mesa) "
                    . "AND estado = 4"
                    . "AND id_claustro = $id_claustro";
            $ar = toba::db('gu_kena')->consultar($sql);
            return $ar[0]['porc'];
        }
        
        function get_total_mesas($id_claustro){
            $sql = "SELECT count(id_mesa) as total FROM mesa "
                    . "WHERE fecha = (SELECT max(fecha) FROM mesa) "
                    . "AND id_claustro = $id_claustro";
            $ar = toba::db('gu_kena')->consultar($sql);
            return $ar[0]['total'];
        }
        
        function get_de_usr($usuario){
            $sql = "SELECT id_mesa FROM mesa WHERE autoridad LIKE '$usuario'";
            return toba::db('gu_kena')->consultar($sql);
        }
}
?>