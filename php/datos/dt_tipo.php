<?php
class dt_tipo extends gu_kena_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_tipo, descripcion FROM tipo ORDER BY descripcion";
		return toba::db('gu_kena')->consultar($sql);
	}
        
        function total_rec_sup_claustro($fecha){
            //Este select es solo para superior=1 - rector=4
            $ponderado = "case id_tipo 
                when 1 then datos_ponderados.total_ponderado
                when 4 then 
                    case when datos_ponderados.id_claustro = 1 then 3*cast(datos_ponderados.total_ponderado as decimal) 
                            when datos_ponderados.id_claustro = 2 then 8*cast(datos_ponderados.total_ponderado as decimal) 
                            when datos_ponderados.id_claustro = 3 then 4*cast(datos_ponderados.total_ponderado as decimal) 
                            else cast(datos_ponderados.total_ponderado as decimal) 
                    end
                end as ponderado";
            
            $total_rector_ue_claustro = "
                select votos_totales.id_tipo, votos_totales.categoria,
                        votos_totales.id_nro_ue, votos_totales.sigla as sigla_ue,
                        votos_totales.id_claustro, votos_totales.claustro,
                        votos_totales.id_nro_lista, votos_totales.lista, votos_totales.sigla_lista,
                        votos_totales.total,
                        votos_validos.validos,
                        empadronados.empadronados,
                        case when votos_validos.validos <> 0 then
                            votos_totales.total/cast(votos_validos.validos as decimal)
                        end ponderado
                from (select a.id_tipo, 'Rector'::text as categoria, ue.id_nro_ue, ue.sigla, 
                            m.id_claustro as id_claustro, c.descripcion claustro, 
                            l.id_nro_lista, l.nombre lista, l.sigla sigla_lista,
                            sum(cant_votos) total 
                        from acta a 
                        inner join mesa m on m.id_mesa = a.de 
                        inner join sede s on s.id_sede = a.id_sede 
                        inner join unidad_electoral ue on ue.id_nro_ue = s.id_ue
                        inner join claustro c on c.id = m.id_claustro 
                        inner join voto_lista_rector vl on vl.id_acta = a.id_acta 
                        inner join lista_rector l on l.id_nro_lista = vl.id_lista
                         where m.estado > 1 
                                and m.fecha = '$fecha' 
                         group by ue.id_nro_ue, ue.sigla, 
                                c.descripcion, 
                                l.nombre, l.id_nro_lista, l.sigla,
                                m.id_claustro, a.id_tipo
                    ) votos_totales
                inner join 
                    (select id_ue, id_claustro, 
                            sum(cant_votos) validos
                    from sede s
                    inner join acta a on a.id_sede = s.id_sede
                    inner join mesa m on m.id_mesa = a.de
                    inner join voto_lista_rector vl on vl.id_acta = a.id_acta
                        where m.estado > 1 and m.fecha = '$fecha'
                        group by id_ue, id_claustro
                    ) votos_validos 
                        on votos_validos.id_ue = votos_totales.id_nro_ue
                        and votos_validos.id_claustro = votos_totales.id_claustro
                inner join 
                    (select id_tipo, id_ue, ue.sigla, id_claustro, sum(cant_empadronados) empadronados 
                    from sede s 
                    inner join acta a on a.id_sede = s.id_sede
                    inner join mesa m on m.id_mesa = a.de 
                    inner join unidad_electoral ue on ue.id_nro_ue = s.id_ue
                    where m.fecha = '$fecha' 
                    group by id_ue, id_claustro, ue.sigla, id_tipo
                    ) empadronados 
                        on empadronados.id_ue = votos_totales.id_nro_ue
                        and empadronados.id_claustro = votos_totales.id_claustro        
                    ";
            
            $total_sup_ue_claustro = "
                select votos_totales.id_tipo, votos_totales.categoria,
                        votos_totales.id_nro_ue, votos_totales.sigla as sigla_ue,
                        votos_totales.id_claustro, votos_totales.claustro,
                        votos_totales.id_nro_lista, votos_totales.lista, votos_totales.sigla_lista,
                        votos_totales.total,
                        votos_validos.validos,
                        empadronados.empadronados,
                        case when empadronados.empadronados <> 0 then
                            votos_totales.total/cast(empadronados.empadronados as decimal)
                        end ponderado
                from (select a.id_tipo, 'Consejero Superior'::text as categoria, ue.id_nro_ue, ue.sigla, 
                            m.id_claustro as id_claustro, c.descripcion claustro, 
                            l.id_nro_lista, l.nombre lista, l.sigla sigla_lista,
                            sum(cant_votos) total 
                        from acta a 
                        inner join mesa m on m.id_mesa = a.de 
                        inner join sede s on s.id_sede = a.id_sede 
                        inner join unidad_electoral ue on ue.id_nro_ue = s.id_ue
                        inner join claustro c on c.id = m.id_claustro 
                        inner join voto_lista_csuperior vl on vl.id_acta = a.id_acta 
                        inner join lista_csuperior l on l.id_nro_lista = vl.id_lista
                         where m.estado > 1 
                                and m.fecha = '$fecha' 
                         group by ue.id_nro_ue, ue.sigla, 
                                c.descripcion, 
                                l.nombre, l.id_nro_lista, l.sigla,
                                s.id_ue,  m.id_claustro, a.id_tipo
                         order by s.id_ue,m.id_claustro, l.nombre
                    ) votos_totales
                inner join 
                    (select id_ue, id_claustro, 
                            sum(cant_votos) validos
                    from sede s
                    inner join acta a on a.id_sede = s.id_sede
                    inner join mesa m on m.id_mesa = a.de
                    inner join voto_lista_csuperior vl on vl.id_acta = a.id_acta
                        where m.estado > 1 and m.fecha = '$fecha'
                        group by id_ue, id_claustro
                    ) votos_validos 
                        on votos_validos.id_ue = votos_totales.id_nro_ue
                        and votos_validos.id_claustro = votos_totales.id_claustro 
                inner join 
                    (select id_tipo, id_ue, ue.sigla, id_claustro, sum(cant_empadronados) empadronados 
                    from sede s 
                    inner join acta a on a.id_sede = s.id_sede
                    inner join mesa m on m.id_mesa = a.de 
                    inner join unidad_electoral ue on ue.id_nro_ue = s.id_ue
                    where m.fecha = '$fecha' 
                    group by id_ue, id_claustro, ue.sigla, id_tipo
                    ) empadronados 
                        on empadronados.id_ue = votos_totales.id_nro_ue
                        and empadronados.id_claustro = votos_totales.id_claustro    
                    ";
            
            $sql = "select datos_ponderados.id_tipo,
                        datos_ponderados.categoria,
                        datos_ponderados.id_claustro,
                        datos_ponderados.claustro,
                        datos_ponderados.id_nro_lista,
                        datos_ponderados.lista,
                        datos_ponderados.sigla_lista,
                        datos_ponderados.total,
                        datos_ponderados.total_validos validos,
                        $ponderado 
                    from (select id_tipo, categoria, id_claustro, claustro, 
                                id_nro_lista, lista, sigla_lista, 
                                sum(total) total,
                                sum(validos) total_validos, 
                                sum(ponderado) total_ponderado
                            from (  $total_rector_ue_claustro
                                    UNION
                                    $total_sup_ue_claustro
                            ) datos_totales
                            group by id_tipo, categoria, id_claustro, claustro, 
                                id_nro_lista, lista, sigla_lista
                        ) datos_ponderados
                    ";
           // print_r($total_sup_ue_claustro.'//////');
            return toba::db('gu_kena')->consultar($sql);
        }
        
        function get_datos_ponderados($id_tipo, $fecha){
            //Este select es solo para rector-decano-director asentamiento
            $select = ",case when datos_validos.validos <> 0 then 
                                        case when datos_totales.id_claustro = 1 then 3*datos_totales.total/cast(datos_validos.validos as decimal) 
                                        when datos_totales.id_claustro = 2 then 8*datos_totales.total/cast(datos_validos.validos as decimal) 
                                        when datos_totales.id_claustro = 3 then 4*datos_totales.total/cast(datos_validos.validos as decimal) 
                                        else datos_totales.total/cast(datos_validos.validos as decimal) end
                                        else 0 end as ponderado";
            switch ($id_tipo){
                case 1: //CONSEJO SUPERIOR
                        $tabla_voto = 'voto_lista_csuperior'; 
                        $tabla_lista = 'lista_csuperior'; 
                        $select = ",case when datos_empadronados.empadronados <> 0 then datos_totales.total/cast(datos_empadronados.empadronados as decimal) else 0 end as ponderado"; break;
                case 2: //CONSEJO DIRECTIVO
                        $tabla_voto = 'voto_lista_cdirectivo'; 
                        $tabla_lista = 'lista_cdirectivo'; 
                        $select = ''; break;
                case 3: //CONSEJO DIRECTIVO ASENTAMIENTO
                        $tabla_voto = 'voto_lista_cdirectivo'; 
                        $tabla_lista = 'lista_cdirectivo'; 
                        $select = ''; break;
                case 4: //RECTOR
                        $tabla_voto = 'voto_lista_rector'; 
                        $tabla_lista = 'lista_rector'; 
                        break;
                case 5: //DECANO
                        $tabla_voto = 'voto_lista_decano'; 
                        $tabla_lista = 'lista_decano'; 
                        break;
                case 6: //DIRECTOR ASENTAMIENTO
                        $tabla_voto = 'voto_lista_decano'; 
                        $tabla_lista = 'lista_decano'; 
                        break;
            }
            
            $sql = "select datos_totales.*, 
                        datos_validos.validos, 
                        datos_empadronados.empadronados
                        $select 
                    from (select ue.id_nro_ue, ue.sigla, 
                            m.id_claustro as id_claustro, c.descripcion claustro, 
                            l.id_nro_lista,l.nombre lista, l.sigla sigla_lista,
                            sum(cant_votos) total 
                        from acta a 
                        inner join mesa m on m.id_mesa = a.de 
                        inner join sede s on s.id_sede = a.id_sede 
                        inner join unidad_electoral ue on ue.id_nro_ue = s.id_ue
                        inner join claustro c on c.id = m.id_claustro 
                        inner join $tabla_voto vl on vl.id_acta = a.id_acta 
                        inner join $tabla_lista l on l.id_nro_lista = vl.id_lista
                         where m.estado > 1 
                                and m.fecha = '$fecha' 
                         group by ue.id_nro_ue, ue.sigla, 
                                c.descripcion, 
                                l.nombre, l.id_nro_lista, l.sigla,
                                s.id_ue,  m.id_claustro
                         order by s.id_ue,m.id_claustro, l.nombre) datos_totales  
                    inner join (select id_ue, id_claustro, 
                            sum(cant_votos) validos
                        from sede s
                        inner join acta a on a.id_sede = s.id_sede
                        inner join mesa m on m.id_mesa = a.de
                        inner join $tabla_voto vl on vl.id_acta = a.id_acta
                        where m.estado > 1 and m.fecha = '$fecha'
                        group by id_ue, id_claustro
                        order by id_ue, id_claustro) datos_validos 
                                        on datos_validos.id_ue = datos_totales.id_nro_ue
                                        and datos_validos.id_claustro = datos_totales.id_claustro
                    inner join (select id_ue, m.id_claustro, 
                                sum(cant_empadronados) empadronados
                        from mesa m
                        inner join sede s on m.id_sede = s.id_sede
                        where fecha = '$fecha'
                        group by id_ue, m.id_claustro
                        order by id_ue, m.id_claustro) datos_empadronados 
                                        on datos_empadronados.id_ue = datos_totales.id_nro_ue
					and datos_empadronados.id_claustro = datos_totales.id_claustro";
            //print_r($id_tipo.'='.$sql.'//////');
            return toba::db('gu_kena')->consultar($sql);
        }



























}
?>