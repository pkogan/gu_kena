<?php
class dt_mesa extends gu_kena_datos_tabla
{
    function get_descripciones($id_sede = null, $id_claustro = null, $id_mesa = null)
	{
        $where = "";
        if(isset($id_sede) && isset($id_claustro)){
            $where = "WHERE id_sede = $id_sede AND id_claustro = $id_claustro";
        }
        else{
            if(isset($id_sede))
                $where = "WHERE id_sede = $id_sede";
            if(isset($id_claustro))
                $where = "WHERE id_claustro = $id_claustro";
        }
        if(isset($id_mesa)){
            $where = " WHERE id_mesa = $id_mesa";
        }
            $sql = "SELECT id_mesa,nro_mesa,cant_empadronados "
                    . "FROM mesa $where ORDER BY nro_mesa";
	//agregar usuario
            
            $ar = toba::db('gu_kena')->consultar($sql);
            return $ar;
	}
        
        function get_empadronados($id_mesa){
            $sql = "SELECT cant_empadronados,nro_mesa FROM mesa "
                    . "WHERE id_mesa = $id_mesa ";
		
            $ar = toba::db('gu_kena')->consultar($sql);
            return $ar[0];
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

}
?>