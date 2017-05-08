<?php
class dt_unidad_electoral extends gu_kena_datos_tabla
{
	function get_listado()
	{
		$sql = "SELECT
			t_ue.id_nro_ue,
			t_ue.nombre,
			t_ue.cant_empadronados,
			t_ue.cant_empadronados_nd,
			t_ue.cant_empadronados_d,
			t_ue.cant_empadronados_g
		FROM
			unidad_electoral as t_ue
		ORDER BY nombre";
		return toba::db('gu_kena')->consultar($sql);
	}

//HACER OTRO METODO PARA GET_DESCRIPCIONES POR TIPO (AGREGANDO ATRIBUTONIVEL O TIPO: RECTORADO, FACULTAD, ASENTAMIENTO)
	function get_descripciones($id = null)
	{
            $where = "";
            if(isset($id))
                $where = " WHERE id_nro_ue = $id ";
            $sql = "SELECT id_nro_ue, nombre, sigla FROM unidad_electoral $where ORDER BY nombre";
            return toba::db('gu_kena')->consultar($sql);
	}
        
        function get_descripciones_edirectivo($id = null)
	{
            $where = "";
            if(isset($id))
                $where = " WHERE id_nro_ue = $id ";
            $sql = "SELECT id_nro_ue, nombre, sigla FROM unidad_electoral $where ORDER BY nombre";
            return toba::db('gu_kena')->consultar($sql);
	}
        
        function get_descripciones_por_nivel($niveles = NULL)
	{
            $where = "";
            if(isset($niveles)){
                $where = " WHERE nivel in (".  implode(',', $niveles).")";
            }        
            $sql = "SELECT id_nro_ue, nombre, sigla FROM unidad_electoral $where ORDER BY nombre";
            return toba::db('gu_kena')->consultar($sql);
	}
        
        function get_descripciones_nivel_directivo()
	{
            $niveles = array(2,3);
            $where = " WHERE nivel in (".  implode(',', $niveles).")"; 
            $sql = "SELECT id_nro_ue, nombre, sigla FROM unidad_electoral $where ORDER BY nombre";
            return toba::db('gu_kena')->consultar($sql);
        }
        
        function get_nivel($id_nro_ue){
            $sql = "SELECT nivel FROM unidad_electoral WHERE id_nro_ue = $id_nro_ue";
            $ar = toba::db('gu_kena')->consultar($sql);
            return $ar[0];
        }
        /*function get_nombre($id_nro_ue){
            $sql = "SELECT nombre FROM unidad_electoral WHERE id_nro_ue = $id_nro_ue";
            $ar = toba::db('gu_kena')->consultar($sql);
            //print_r($ar['nombre']);
            return $ar['nombre'];
        }*/

}
?>
