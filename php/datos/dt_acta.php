<?php
class dt_acta extends gu_kena_datos_tabla
{
	function get_listado($id_acta = null) //VER SI SE USA MODIFICAR "PARA"(si no se lo usa el "t_a.para", se puede borrar)!!!
	{
            if(isset($id_acta)){
                $where = "WHERE id_acta = $id_acta";
                
            }
            else
                $where = "";
            
            $sql = "SELECT
                    t_a.id_acta,
                    t_a.total_votos_blancos,
                    t_a.total_votos_nulos,
                    t_a.total_votos_recurridos,
                    t_t.descripcion as id_tipo_nombre,
                    t_a.de  "
                    //t_a.para
            ."FROM
                    acta as t_a	LEFT OUTER JOIN tipo as t_t ON (t_a.id_tipo = t_t.id_tipo) 
                    $where ";
            return toba::db('gu_kena')->consultar($sql);
	}
        
        function get_ultimo_listado($id_acta = null) 
	{
            if(isset($id_acta)){
                $where = "AND id_acta = $id_acta";
                
            }
            else
                $where = "";
            
            $sql = "SELECT
                    t_a.id_acta,
                    t_a.total_votos_blancos,
                    t_a.total_votos_nulos,
                    t_a.total_votos_recurridos,
                    t_t.descripcion as tipo,
                    t_a.de, 
                    t_sde.nombre as sede_de, "
                    ." t_a.id_sede as sede_para "
            ."FROM
                    acta as t_a	
                    LEFT OUTER JOIN tipo as t_t ON (t_a.id_tipo = t_t.id_tipo) 
                    INNER JOIN mesa t_de ON (t_de.id_mesa = t_a.de)
                    INNER JOIN sede t_sde ON (t_de.id_sede = t_sde.id_sede)
                    INNER JOIN sede t_spara ON (t_spara.id_sede = t_a.id_sede)
                    WHERE t_de.fecha = (SELECT max(fecha) FROM mesa)"
                    ."$where ";
            return toba::db('gu_kena')->consultar($sql);
	}

        function get_descripciones($de = null, $sede = null) //$sede es la sede a la que pertenece el acta
	{
            $where = array();
            if(isset($de) && isset($para)){
                $where = "WHERE de = $de AND id_sede = $sede";
            }
            else{
                if(isset($de)){
                    $where = "WHERE de=$de";
                }
                if(isset($para)){
                    $where = "WHERE id_sede=$sede";
                }
            }
            
            $sql = "SELECT id_acta, "
                    . "total_votos_blancos, "
                    . "total_votos_nulos, "
                    . "total_votos_recurridos,"
                    . "t_a.id_tipo,"
                    . "t_t.descripcion as tipo,"
                    . "de," 
                    . "FROM acta as t_a "
                    . "INNER JOIN tipo as t_t ON (t_t.id_tipo = t_a.id_tipo)" 
                    . " $where ORDER BY id_acta";
            
            return toba::db('gu_kena')->consultar($sql);
	}
        
        
        function get_ultimas_descripciones_de($de = null)
	{
            if(isset($de))
                $where = "AND t_a.de = $de";
            else
                $where = "";
            
            $sql = "SELECT id_acta, "
                    . "total_votos_blancos, "
                    . "total_votos_nulos, "
                    . "total_votos_recurridos,"
                    . "t_a.id_tipo,"
                    . "t_t.descripcion as tipo,"
                    . "t_a.de,"
                    //. "t_a.para,"
                    . "t_s.nombre as sede,"
                    . "t_u.nombre as unidad_electoral,"
                    . "t_c.descripcion as claustro,"
                    . "t_m.nro_mesa "
                    . "FROM acta as t_a "
                    . "INNER JOIN tipo as t_t ON (t_t.id_tipo = t_a.id_tipo) "
                    . "INNER JOIN mesa as t_m ON (t_m.id_mesa = t_a.de) "
                    . "INNER JOIN claustro as t_c ON (t_c.id = t_m.id_claustro) "
                    . "INNER JOIN sede as t_s ON (t_s.id_sede = t_m.id_sede) "
                    . "INNER JOIN unidad_electoral as t_u ON (t_u.id_nro_ue = t_s.id_ue) " 
                    . "WHERE t_m.fecha = (SELECT max(fecha) FROM mesa )"
                    . " $where "
                    . "ORDER BY id_acta";
            
            return toba::db('gu_kena')->consultar($sql);
	}
        
        function get_ultimas_descripciones($filtro = null)
	{
            if(isset($filtro)){
                $where = "";
                if(isset($filtro['unidad_electoral']))
                    $where = " AND t_ude.id_nro_ue = ".$filtro['unidad_electoral']['valor'];
                if(isset($filtro['sede']))
                    $where .= " AND (t_sde.id_sede = ".$filtro['sede']['valor'];
                if(isset($filtro['claustro']))
                    $where .= " AND t_de.id_claustro = ".$filtro['claustro']['valor'];
                if(isset($filtro['tipo']))
                    $where .= " AND t_t.id_tipo = ".$filtro['tipo']['valor'];
                if(isset($filtro['estado']))
                    $where .= " AND t_de.estado = ".$filtro['estado']['valor'];
                
                $sql = "SELECT t_a.id_acta,
                                t_de.nro_mesa, 
                                t_de.id_mesa,
                                t_sde.sigla as de, 
                                t_ude.sigla as unidad_electoral,
                                t_e.descripcion as estado, 
                                t_t.descripcion as tipo,
                                t_t.id_tipo,
                                t_de.id_claustro
                            FROM acta t_a
                            LEFT JOIN mesa t_de ON (t_de.id_mesa = t_a.de)
                            LEFT JOIN estado t_e ON (t_e.id_estado = t_de.estado)
                            LEFT JOIN sede t_sde ON (t_sde.id_sede = t_de.id_sede)
                            LEFT JOIN unidad_electoral t_ude ON (t_ude.id_nro_ue = t_sde.id_ue)
                            LEFT JOIN tipo t_t ON (t_t.id_tipo = t_a.id_tipo)
                            WHERE t_de.fecha = (SELECT max(fecha) FROM mesa )
                             
                         $where ORDER BY t_ude.id_nro_ue";
                
                return toba::db('gu_kena')->consultar($sql);       
            }
            else{
            
                $sql = "SELECT id_acta, "
                    . "total_votos_blancos, "
                    . "total_votos_nulos, "
                    . "total_votos_recurridos,"
                    . "t_a.id_tipo,"
                    . "t_t.descripcion as tipo,"
                    . "de "
                    //. "para "
                    . "FROM acta as t_a "
                    . "LEFT JOIN tipo as t_t ON (t_t.id_tipo = t_a.id_tipo) 
                       LEFT JOIN mesa t_de ON (t_de.id_mesa = t_a.de)
                       LEFT JOIN sede t_s ON (t_s.id_sede = t_a.id_sede) "
                    //."LEFT JOIN mesa t_para ON (t_para.id_mesa = t_a.para)"
                    ."WHERE t_de.fecha = (SELECT max(fecha) FROM mesa )"
                    //." AND t_para.fecha = (SELECT max(fecha) FROM mesa )"
                    . "ORDER BY id_acta";
            
                return toba::db('gu_kena')->consultar($sql);
            }
            
	}
        
        //usado por ci_consejeros_superior y direcivo cant de votos blancos, nulos y recurridos 
        //para una u_e, tipo(sup, dir) y claustro
        function cant_b_n_r($id_ue, $id_claustro, $id_tipo){ //modificada ok con BD
            $sql = "SELECT sum(total_votos_blancos) as blancos, "
                    . "sum(total_votos_nulos) as nulos, "
                    . "sum(total_votos_recurridos) as recurridos"
                    . " FROM acta t_a"
                    . " INNER JOIN mesa t_m ON (t_m.id_mesa = t_a.de)"
                    . " INNER JOIN sede t_s ON (t_a.id_sede = t_s.id_sede)"
                    . " WHERE t_s.id_ue = $id_ue "
                    . " AND t_m.id_claustro = $id_claustro "
                    . " AND t_a.id_tipo = $id_tipo"
                    . " AND t_m.fecha = (SELECT max(fecha) FROM mesa)"
                    . " AND t_m.estado > 1";
            return toba::db('gu_kena')->consultar($sql);
        }
        
        function cant_bnr_acta($filtro, $fecha){ 
            $sql = "SELECT sum(total_votos_blancos) as blancos, "
                    . "sum(total_votos_nulos) as nulos, "
                    . "sum(total_votos_recurridos) as recurridos,"
                    . "t_s.id_sede "
                    . " FROM acta t_a"
                    . " INNER JOIN mesa t_m ON (t_m.id_mesa = t_a.de)"
                    . " INNER JOIN sede t_s ON (t_a.id_sede = t_s.id_sede)"
                    . " WHERE t_s.id_ue = ".$filtro['id_ue']
                    . " AND t_m.id_claustro = ".$filtro['id_claustro']
                    . " AND t_a.id_tipo = ".$filtro['id_tipo']
                    . " AND t_m.fecha = '$fecha' "
                    . " GROUP BY t_s.id_sede";
                        
            return toba::db('gu_kena')->consultar($sql);
        }
}
?>