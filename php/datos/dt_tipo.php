<?php
class dt_tipo extends gu_kena_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_tipo, descripcion FROM tipo ORDER BY descripcion";
		return toba::db('gu_kena')->consultar($sql);
	}
        
        function get_datos_ponderados($id_tipo, $fecha){
            switch ($id_tipo){
                case 1: $tabla_voto = 'voto_lista_csuperior'; 
                        $tabla_lista = 'lista_csuperior'; break;
                case 2: $tabla_voto = 'voto_lista_cdirectivo'; 
                        $tabla_lista = 'lista_cdirectivo'; break;
                case 3: $tabla_voto = 'voto_lista_cdirectivo'; 
                        $tabla_lista = 'lista_cdirectivo'; break;
                case 4: $tabla_voto = 'voto_lista_rector'; 
                        $tabla_lista = 'lista_rector'; break;
                case 5: $tabla_voto = 'voto_lista_decano'; 
                        $tabla_lista = 'lista_decano'; break;
                case 6: $tabla_voto = 'voto_lista_decano'; 
                        $tabla_lista = 'lista_decano'; break;
            }
            
            $sql = "select 
                    ue.sigla,
                    c.descripcion,
                    tr.nombre,
                    concat(ue.sigla, s.sigla, m.nro_mesa) nom_mesa, 
                    datos_totales.total 
                    from acta a 
                    inner join mesa m on m.id_mesa = a.de 
                    inner join claustro c on c.id = m.id_claustro
                    inner join $tabla_voto tv on tv.id_acta = a.id_acta 
                    inner join $tabla_lista tr on tr.id_nro_lista = tv.id_lista 
                    inner join sede s on s.id_sede = a.id_sede 
                    inner join unidad_electoral ue on ue.id_nro_ue = s.id_ue
                    inner join ( 
                            select s.id_ue, m.id_claustro, l.id_nro_lista, l.nombre, sum(cant_votos) total 
                            from acta a 
                            inner join mesa m on m.id_mesa = a.de 
                            inner join sede s on s.id_sede = a.id_sede 
                            inner join voto_lista_csuperior vl on vl.id_acta = a.id_acta 
                            inner join lista_csuperior l on l.id_nro_lista = vl.id_lista 
                            where m.fecha = '$fecha' 
                            group by l.nombre, m.id_claustro, s.id_ue, l.id_nro_lista 
                            order by id_ue,id_claustro, l.nombre 
                    ) datos_totales on datos_totales.id_ue = s.id_ue 
                                    and datos_totales.id_claustro = m.id_claustro 
                                    and datos_totales.id_nro_lista = tr.id_nro_lista 
                    where m.estado > 1 and m.fecha = '$fecha'
                    order by s.id_ue, m.id_claustro, tr.id_nro_lista";
            print_r($sql);
        }



























}
?>