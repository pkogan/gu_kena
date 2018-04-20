<?php
class dt_voto_lista_decano extends gu_kena_datos_tabla
{
	function evt__validar_ingreso($fila, $id=null)
	{
	}

	/**
	 * Ventana de validacion que se invoca antes de sincronizar una fila con la base
	 * El proceso puede ser abortado con un toba_error, el mensaje se muestra al usuario
	 * @param array $fila Asociativo clave-valor de la fila a validar
	 */
	function evt__validar_fila($fila)
	{
	}
        //obtiene el listado de voto_lista_cdirectivo correspondientes al acta que recibe como parametro 
        function get_listado_votos($id_acta)
	{
            $sql = "SELECT t_l.id_nro_lista, 
                           t_l.nombre,
                           t_v.cant_votos as votos
                    FROM voto_lista_decano t_v
                    INNER JOIN lista_decano t_l ON (t_l.id_nro_lista = t_v.id_lista)
                    INNER JOIN acta t_a ON (t_a.id_acta = t_v.id_acta)
                    INNER JOIN sede t_s ON (t_s.id_sede = t_a.id_sede) " //ver! creo que no es necesario
                    ."WHERE t_l.id_ue = t_s.id_ue " //ver! creo que no es necesario
                    ." AND t_a.id_acta = $id_acta 
                    ORDER BY t_l.id_nro_lista";
                    
            return toba::db('gu_kena')->consultar($sql);
	}

}
?>