<?php

class Crongukena {

    protected $fecha = '2018-05-22';

    public function __construct($fecha) {
        $time = $this->microtime_float();
        //$fecha=$this->fecha;
        //Genera un JSON de total rector
        $this->datos_rector($fecha);

        //Genera 4 JSONS de total rector por claustro
        $this->datos_rector_claustro($fecha);
        //Genera 4 JSONS de total consejero superior por claustro
        $this->datos_sup_claustro($fecha);

        //Genera 18 JSONS de total rector por unidad electoral
        $this->datos_rector_ue($fecha);
        //Genera 17 JSONS de total decano por unidad electoral
        $this->datos_decano_ue($fecha);

        //Genera 17*4 + 1 = 69 JSONS de total rector por claustro y por unidad electoral
        $this->datos_ue_claustro($fecha, 'voto_lista_rector', 'lista_rector', 'Rector', 'R');
        //Genera 17*4 = 68 JSONS de total decano por claustro y por unidad electoral
        $this->datos_ue_claustro($fecha, 'voto_lista_decano', 'lista_decano', 'Decano', 'D');
        //Genera 17*4 = 68 JSONS de total consejo superior por claustro y por unidad electoral
        $this->datos_ue_claustro($fecha, 'voto_lista_csuperior', 'lista_csuperior', 'Consejero Superior', 'CS');
        //Genera 17*4 = 68 JSONS de total consejo directivo por claustro y por unidad electoral
        $this->datos_ue_claustro($fecha, 'voto_lista_cdirectivo', 'lista_cdirectivo', 'Consejero Directivo', 'CD');
        $time = $this->microtime_float() - $time;
        echo 'Tiempo=' . $time;
    }

    function microtime_float() {
        list($useg, $seg) = explode(" ", microtime());
        return ((float) $useg + (float) $seg);
    }

    function datos_ue_claustro($fecha, $tabla_voto, $tabla_lista, $categoria, $sigla_cat) {
        $sql = "select ue.nombre as unidad_electoral, ue.sigla as sigla_ue, cl.descripcion as claustro, 
                    l.id_nro_lista, l.nombre as lista,
                    s.sigla as sede, m.nro_mesa,
                    l.sigla as sigla_lista, vl.cant_votos, total.total
            from acta a 
            inner join mesa m on m.id_mesa = a.de
            inner join claustro cl on cl.id = m.id_claustro
            inner join $tabla_voto vl on vl.id_acta = a.id_acta
            inner join $tabla_lista l on l.id_nro_lista = vl.id_lista
            inner join sede s on s.id_sede = a.id_sede
            inner join unidad_electoral ue on ue.id_nro_ue = s.id_ue
            inner join (
                    select m.id_claustro, s.id_ue,
                            vl.id_lista,  
                            sum(vl.cant_votos) total
                    from acta a 
                    inner join mesa m on m.id_mesa = a.de
                    inner join $tabla_voto vl on vl.id_acta = a.id_acta
                    inner join sede s on s.id_sede = a.id_sede
                    where m.fecha = '" . $fecha . "'
                    group by s.id_ue, m.id_claustro, vl.id_lista 
            ) total on total.id_claustro = cl.id
                    and total.id_lista = l.id_nro_lista
                    and total.id_ue = s.id_ue
            where l.fecha = '" . $fecha . "'
            order by unidad_electoral, claustro, lista, sede 
                ";
        $datos = toba::db('gu_kena')->consultar($sql);

        $nom_ue = null;
        $nom_claustro = null;
        $nom_lista = null;
        $nro_lista = null;
        $sedes = array();
        $columns_sedes = array();

        $data = array();
        $labels = array();
        $total = array();
        $lista = array();

        foreach ($datos as $un_registro) {
            if ($nro_lista != null && $nro_lista != $un_registro['id_nro_lista']) {
                $r = array();
                $r['lista'] = utf8_encode(trim($lista['lista']));
                $r['sigla_lista'] = trim($lista['sigla_lista']);

                foreach ($sedes as $sigla_sede => $cant_votos) {
                    $r[$sigla_sede] = $cant_votos;
                    $columns_sedes[$sigla_sede] = $sigla_sede;
                }
                $r['total'] = $lista['total'];
                $data[] = $r;

                if (($nom_ue != null && $nom_ue != $un_registro['sigla_ue']) || ($nom_claustro != null && $nom_claustro != $un_registro['claustro'])) {
                    if (sizeof($data) > 0) {//Solo si existen datos ent crea el json
                        $json = array();
                        $columns = array();
                        $columns[] = array('field' => 'lista', 'title' => 'Listas');
                        $columns[] = array('field' => 'sigla_lista', 'title' => 'Sigla Listas');
                        foreach ($columns_sedes as $key => $sigla_sede) {
                            $columns[] = array('field' => $key, 'title' => $sigla_sede);
                        }
                        $columns[] = array('field' => 'total', 'title' => 'Total');

                        $json['columns'] = $columns;
                        $json['data'] = $data;
                        $json['labels'] = $labels;
                        $json['total'] = $total;
                        $json['fecha'] = date('d/m/Y G:i:s');
                        $json['titulo'] = 'Votos ' . $nom_ue . ' ' . $categoria . ' ' . $nom_claustro;
                        if ($sigla_cat == 'CD') {

                            $dont = $this->dont($labels, $total, 'CD', strtoupper($nom_claustro[0]));
                            $json['columns2'] = $dont[0];
                            $json['data2'] = $dont[1];
                        }
                        $string_json = json_encode($json);
                        $nom_archivo = 'e' . str_replace('-', '', $fecha) . '/' . $sigla_cat . '_' . strtoupper($nom_ue) . '_' . strtoupper($nom_claustro[0]) . '.json';
                        file_put_contents('resultados_json/' . $nom_archivo, $string_json);
                    }
                    $data = array();
                    $labels = array();
                    $total = array();
                    $columns_sedes = array();

                    $nom_ue = $un_registro['sigla_ue'];
                    $nom_claustro = $un_registro['claustro'];
                    $lista = array();
                    $sedes = array();
                } elseif ($nom_ue == null) {
                    $nom_ue = $un_registro['sigla_ue'];
                    $nom_claustro = $un_registro['claustro'];
                }

                $lista = array();
                $lista['lista'] = $un_registro['lista'];
                $lista['sigla_lista'] = $un_registro['sigla_lista'];
                $lista['total'] = $un_registro['total'];
                $nro_lista = $un_registro['id_nro_lista'];
                $sedes = array();
            } elseif ($nro_lista == null) {
                $lista['lista'] = $un_registro['lista'];
                $lista['sigla_lista'] = $un_registro['sigla_lista'];
                $lista['total'] = $un_registro['total'];
                $nro_lista = $un_registro['id_nro_lista'];

                $nom_ue = $un_registro['sigla_ue'];
                $nom_claustro = $un_registro['claustro'];
            }

            $labels[] = $un_registro['sigla_lista'];
            $total[] = $un_registro['total'];

            $sedes[$un_registro['sede'] . ' mesa ' . $un_registro['nro_mesa']] = $un_registro['cant_votos'];
        }

        if (sizeof($data) > 0) {//Solo si existen datos finales ent crea el json
            $json = array();
            $columns = array();
            $columns[] = array('field' => 'lista', 'title' => 'Listas');
            $columns[] = array('field' => 'sigla_lista', 'title' => 'Sigla Listas');
            foreach ($columns_sedes as $key => $sigla_sede) {
                $columns[] = array('field' => $key, 'title' => $sigla_sede);
            }
            $columns[] = array('field' => 'total', 'title' => 'Total');

            $json['columns'] = $columns;
            $json['data'] = $data;
            $json['labels'] = $labels;
            $json['total'] = $total;
            $json['fecha'] = date('d/m/Y G:i:s');
            $json['titulo'] = 'Votos ' . $nom_ue . ' ' . $categoria . ' ' . $nom_claustro;
            if ($sigla_cat == 'CD') {

                $dont = $this->dont($labels, $total, 'CD', strtoupper($nom_claustro[0]));
                $json['columns2'] = $dont[0];
                $json['data2'] = $dont[1];
            }
            $string_json = json_encode($json);
            $nom_archivo = 'e' . str_replace('-', '', $fecha) . '/' . $sigla_cat . '_' . strtoupper($nom_ue) . '_' . strtoupper($nom_claustro[0]) . '.json';
            file_put_contents('resultados_json/' . $nom_archivo, $string_json);
        }
    }

    //Metodo que calcula y genera archivos JSONS ubicados en /resultados_json/$fecha
    //con datos de resultados rector por cada unidad electoral
    function datos_rector_ue($fecha) {
        $sql = "select sigla_ue, unidad_electoral,
                    lista, sigla_lista, 
                    sum(ponderado) ponderado
            from(
            select votos_totales.id_tipo, votos_totales.id_nro_ue, votos_totales.nombre_ue unidad_electoral,
                    votos_totales.sigla as sigla_ue, votos_totales.id_claustro, votos_totales.claustro, 
                    votos_totales.id_nro_lista, votos_totales.lista, votos_totales.sigla_lista, 
                    votos_totales.total, votos_validos.validos,  
                    case when votos_validos.validos <> 0 then 
                            votos_totales.total/cast(votos_validos.validos as decimal) * votos_validos.mult
                    end ponderado 
            from (select a.id_tipo, ue.id_nro_ue, ue.sigla, ue.nombre nombre_ue,
                    m.id_claustro as id_claustro, c.descripcion claustro, l.id_nro_lista, l.nombre lista, 
                    l.sigla sigla_lista, sum(cant_votos) total 
                  from acta a 
                  inner join mesa m on m.id_mesa = a.de 
                  inner join sede s on s.id_sede = a.id_sede 
                  inner join unidad_electoral ue on ue.id_nro_ue = s.id_ue 
                  inner join claustro c on c.id = m.id_claustro 
                  inner join voto_lista_rector vl on vl.id_acta = a.id_acta 
                  inner join lista_rector l on l.id_nro_lista = vl.id_lista 
                  where m.estado > 1 and m.fecha = '" . $fecha . "' 
                  group by ue.id_nro_ue, ue.sigla, c.descripcion, l.nombre, l.id_nro_lista, l.sigla, s.id_ue, 
                            m.id_claustro, a.id_tipo order by s.id_ue,m.id_claustro, l.nombre 
            ) votos_totales
            inner join (select id_ue, id_claustro, cargos_cdirectivo as mult, 
                                    sum(cant_votos) validos 
                                            from sede s 
                                            inner join acta a on a.id_sede = s.id_sede 
                                            inner join mesa m on m.id_mesa = a.de 
                                            inner join voto_lista_rector vl on vl.id_acta = a.id_acta 
                                            inner join claustro cl on cl.id = m.id_claustro
                                            inner join unidad_electoral ue on ue.id_nro_ue = s.id_ue
                                        where m.estado > 1 and m.fecha = '" . $fecha . "' 
                                        group by id_ue, id_claustro, cargos_csuperior, ue.nivel, cargos_cdirectivo, 
                                        cargos_cdiras 
                    ) votos_validos on votos_validos.id_ue = votos_totales.id_nro_ue 
                    and votos_validos.id_claustro = votos_totales.id_claustro 
            ) t
            group by id_nro_ue, sigla_ue, unidad_electoral,
                    id_nro_lista, lista, sigla_lista
            order by sigla_ue
                ";
        $datos = toba::db('gu_kena')->consultar($sql);

        $columns = array();
        $columns[] = array('field' => 'lista', 'title' => 'Listas');
        $columns[] = array('field' => 'sigla_lista', 'title' => 'Sigla Listas');
        $columns[] = array('field' => 'ponderado', 'title' => 'Resultado');

        $nom_ue = null;
        $data = array();
        $labels = array();
        $total = array();
        foreach ($datos as $un_registro) {
            if ($nom_ue != null && $nom_ue != $un_registro['sigla_ue']) {
                $json = array();

                $json['columns'] = $columns;
                $json['data'] = $data;
                $json['labels'] = $labels;
                $json['total'] = $total;
                $json['fecha'] = date('d/m/Y G:i:s');
                $json['titulo'] = 'Votos ' . $nom_ue . ' Rector';

                $data = array();
                $labels = array();
                $total = array();

                $string_json = json_encode($json);
                $nom_archivo = 'e' . str_replace('-', '', $fecha) . '/R_' . strtoupper($nom_ue) . '_T.json';
                file_put_contents('resultados_json/' . $nom_archivo, $string_json);

                $nom_ue = $un_registro['sigla_ue'];
            } elseif ($nom_ue == null)
                $nom_ue = $un_registro['sigla_ue'];

            $r['lista'] = utf8_encode(trim($un_registro['lista']));
            $r['sigla_lista'] = trim($un_registro['sigla_lista']);
            $r['ponderado'] = $un_registro['ponderado'];

            $labels[] = $un_registro['sigla_lista'];
            $total[] = $un_registro['ponderado'];

            $data[] = $r;
        }

        if (isset($data) && $nom_ue != null) {//Quedo un ultimo claustro sin guardar
            $json = array();
            $json['columns'] = $columns;
            $json['data'] = $data;
            $json['labels'] = $labels;
            $json['total'] = $total;
            $json['fecha'] = date('d/m/Y G:i:s');
            $json['titulo'] = 'Votos ' . $nom_ue . ' Rector';

            $string_json = json_encode($json);

            $nom_archivo = 'e' . str_replace('-', '', $fecha) . '/R_' . strtoupper($nom_ue) . '_T.json';
            file_put_contents('resultados_json/' . $nom_archivo, $string_json);
        }
    }

    //Metodo que calcula y genera archivos JSONS ubicados en /resultados_json/$fecha
    //con datos de resultados decano por cada unidad electoral
    function datos_decano_ue($fecha) {
        $sql = "select sigla_ue, unidad_electoral,
                    lista, sigla_lista, 
                    sum(ponderado) ponderado
            from(
            select votos_totales.id_tipo, votos_totales.id_nro_ue, votos_totales.nombre_ue unidad_electoral,
                    votos_totales.sigla as sigla_ue, votos_totales.id_claustro, votos_totales.claustro, 
                    votos_totales.id_nro_lista, votos_totales.lista, votos_totales.sigla_lista, 
                    votos_totales.total, votos_validos.validos,  
                    case when votos_validos.validos <> 0 then 
                            votos_totales.total/cast(votos_validos.validos as decimal) * votos_validos.mult
                    end ponderado 
            from (select a.id_tipo, ue.id_nro_ue, ue.sigla, ue.nombre nombre_ue,
                    m.id_claustro as id_claustro, c.descripcion claustro, l.id_nro_lista, l.nombre lista, 
                    l.sigla sigla_lista, sum(cant_votos) total 
                  from acta a 
                  inner join mesa m on m.id_mesa = a.de 
                  inner join sede s on s.id_sede = a.id_sede 
                  inner join unidad_electoral ue on ue.id_nro_ue = s.id_ue 
                  inner join claustro c on c.id = m.id_claustro 
                  inner join voto_lista_decano vl on vl.id_acta = a.id_acta 
                  inner join lista_decano l on l.id_nro_lista = vl.id_lista 
                  where m.estado > 1 and m.fecha = '" . $fecha . "' 
                  group by ue.id_nro_ue, ue.sigla, c.descripcion, l.nombre, l.id_nro_lista, l.sigla, s.id_ue, 
                            m.id_claustro, a.id_tipo order by s.id_ue,m.id_claustro, l.nombre 
            ) votos_totales
            inner join (select id_ue, id_claustro, cargos_cdirectivo as mult, 
                                    sum(cant_votos) validos 
                                            from sede s 
                                            inner join acta a on a.id_sede = s.id_sede 
                                            inner join mesa m on m.id_mesa = a.de 
                                            inner join voto_lista_decano vl on vl.id_acta = a.id_acta 
                                            inner join claustro cl on cl.id = m.id_claustro
                                            inner join unidad_electoral ue on ue.id_nro_ue = s.id_ue
                                        where m.estado > 1 and m.fecha = '" . $fecha . "' 
                                        group by id_ue, id_claustro, cargos_csuperior, ue.nivel, cargos_cdirectivo, 
                                        cargos_cdiras 
                    ) votos_validos on votos_validos.id_ue = votos_totales.id_nro_ue 
                    and votos_validos.id_claustro = votos_totales.id_claustro 
            ) t
            group by id_nro_ue, sigla_ue, unidad_electoral,
                    id_nro_lista, lista, sigla_lista
            order by sigla_ue
                ";
        $datos = toba::db('gu_kena')->consultar($sql);

        $columns = array();
        $columns[] = array('field' => 'lista', 'title' => 'Listas');
        $columns[] = array('field' => 'sigla_lista', 'title' => 'Sigla Listas');
        $columns[] = array('field' => 'ponderado', 'title' => 'Resultado');

        $nom_ue = null;
        $data = array();
        foreach ($datos as $un_registro) {
            if ($nom_ue != null && $nom_ue != $un_registro['sigla_ue']) {
                $json = array();
                $json['columns'] = $columns;
                $json['data'] = $data;
                $json['labels'] = $labels;
                $json['total'] = $total;
                $json['fecha'] = date('d/m/Y G:i:s');
                $json['titulo'] = 'Votos ' . $nom_ue . ' Decano';

                $data = array();
                $labels = array();
                $total = array();

                $string_json = json_encode($json);
                $nom_archivo = 'e' . str_replace('-', '', $fecha) . '/D_' . strtoupper($nom_ue) . '_T.json';
                file_put_contents('resultados_json/' . $nom_archivo, $string_json);

                $nom_ue = $un_registro['sigla_ue'];
            } elseif ($nom_ue == null)
                $nom_ue = $un_registro['sigla_ue'];

            $r['lista'] = utf8_encode(trim($un_registro['lista']));
            $r['sigla_lista'] = trim($un_registro['sigla_lista']);
            $r['ponderado'] = $un_registro['ponderado'];

            $labels[] = $un_registro['sigla_lista'];
            $total[] = $un_registro['ponderado'];

            $data[] = $r;
        }

        if (isset($data) && $nom_ue != null) {//Quedo un ultimo claustro sin guardar
            $json = array();
            $json['columns'] = $columns;
            $json['data'] = $data;
            $json['labels'] = $labels;
            $json['total'] = $total;
            $json['fecha'] = date('d/m/Y G:i:s');
            $json['titulo'] = 'Votos ' . $nom_ue . ' Decano';

            $string_json = json_encode($json);

            $nom_archivo = 'e' . str_replace('-', '', $fecha) . '/D_' . strtoupper($nom_ue) . '_T.json';
            file_put_contents('resultados_json/' . $nom_archivo, $string_json);
        }
    }

    //Metodo que calcula y genera archivos JSONS ubicados en /resultados_json/$fecha
    //con datos de resultados consejero superior por cada claustro
    function datos_sup_claustro($fecha) {
        $sql = "select claustro, 
                    lista, sigla_lista, 
                    sum(ponderado) ponderado
            from(
            select votos_totales.id_tipo, votos_totales.id_nro_ue, 
                    votos_totales.sigla as sigla_ue, votos_totales.id_claustro, votos_totales.claustro, 
                    votos_totales.id_nro_lista, votos_totales.lista, votos_totales.sigla_lista, 
                    votos_totales.total, empadronados.empadronados, 
                    case when empadronados.empadronados <> 0 then 
                            votos_totales.total/cast(empadronados.empadronados as decimal) 
                    end ponderado 
            from (select a.id_tipo, ue.id_nro_ue, ue.sigla, 
                    m.id_claustro as id_claustro, c.descripcion claustro, l.id_nro_lista, l.nombre lista, 
                    l.sigla sigla_lista, sum(cant_votos) total 
                  from acta a 
                  inner join mesa m on m.id_mesa = a.de 
                  inner join sede s on s.id_sede = a.id_sede 
                  inner join unidad_electoral ue on ue.id_nro_ue = s.id_ue 
                  inner join claustro c on c.id = m.id_claustro 
                  inner join voto_lista_csuperior vl on vl.id_acta = a.id_acta 
                  inner join lista_csuperior l on l.id_nro_lista = vl.id_lista 
                  where m.estado > 1 and m.fecha = '" . $fecha . "' 
                  group by ue.id_nro_ue, ue.sigla, c.descripcion, l.nombre, l.id_nro_lista, l.sigla, s.id_ue, 
                            m.id_claustro, a.id_tipo order by s.id_ue,m.id_claustro, l.nombre 
            ) votos_totales
            inner join (select id_tipo, id_ue, ue.sigla, id_claustro, sum(cant_empadronados) empadronados 
                        from sede s 
                        inner join acta a on a.id_sede = s.id_sede
                        inner join mesa m on m.id_mesa = a.de 
                        inner join unidad_electoral ue on ue.id_nro_ue = s.id_ue 
                        where m.fecha = '" . $fecha . "' 
                        group by id_ue, id_claustro, ue.sigla, id_tipo 
            ) empadronados on empadronados.id_ue = votos_totales.id_nro_ue 
                            and empadronados.id_claustro = votos_totales.id_claustro
                            and empadronados.id_tipo = votos_totales.id_tipo
            ) t
            group by id_tipo, claustro, lista, sigla_lista
            order by claustro, lista
                ";
        $datos = toba::db('gu_kena')->consultar($sql);

        $columns = array();
        $columns[] = array('field' => 'lista', 'title' => 'Listas');
        $columns[] = array('field' => 'sigla_lista', 'title' => 'Sigla Listas');
        $columns[] = array('field' => 'ponderado', 'title' => 'Resultado');

        $nom_claustro = null;
        $data = array();
        foreach ($datos as $un_registro) {
            if ($nom_claustro != null && $nom_claustro != $un_registro['claustro']) {
                $json = array();
                $json['columns'] = $columns;
                $json['data'] = $data;
                $json['labels'] = $labels;
                $json['total'] = $total;
                $json['fecha'] = date('d/m/Y G:i:s');
                $json['titulo'] = 'Votos Universidad Consejero Superior ' . $nom_claustro;
                $dont = $this->dont($labels, $total, 'CS', strtoupper($nom_claustro[0]));
                $json['columns2'] = $dont[0];
                $json['data2'] = $dont[1];
                //print_r($dont);exit;
                $data = array();
                $labels = array();
                $total = array();



                $string_json = json_encode($json);
                $nom_archivo = 'e' . str_replace('-', '', $fecha) . '/CS_TODO_' . strtoupper($nom_claustro[0]) . '.json';
                file_put_contents('resultados_json/' . $nom_archivo, $string_json);

                $nom_claustro = $un_registro['claustro'];
            } elseif ($nom_claustro == null)
                $nom_claustro = $un_registro['claustro'];

            $r['lista'] = utf8_encode(trim($un_registro['lista']));
            $r['sigla_lista'] = trim($un_registro['sigla_lista']);
            $r['ponderado'] = $un_registro['ponderado'];

            $labels[] = $un_registro['sigla_lista'];
            $total[] = $un_registro['ponderado'];

            $data[] = $r;
        }

        if (isset($data) && $nom_claustro != null) {//Quedo un ultimo claustro sin guardar
            $json = array();
            $json['columns'] = $columns;
            $json['data'] = $data;
            $json['labels'] = $labels;
            $json['total'] = $total;
            $json['fecha'] = date('d/m/Y G:i:s');
            $json['titulo'] = 'Votos Universidad Consejero Superior ' . $nom_claustro;
            $escanos = 10;
            $dont = $this->dont($labels, $total, 'CS', strtoupper($nom_claustro[0]));
            $json['columns2'] = $dont[0];
            $json['data2'] = $dont[1];
            $string_json = json_encode($json);

            $nom_archivo = 'e' . str_replace('-', '', $fecha) . '/CS_TODO_' . strtoupper($nom_claustro[0]);
            file_put_contents('resultados_json/' . $nom_archivo . '.json', $string_json);
        }
    }

    function dont($listas, $valores, $cat, $claustro) {
        /*
         * Se multiplicarán por diez (10.000) los votos ponderados obtenidos por cada lista y se los
          dividirá desde uno (1) y hasta el total de cargos a ocupar.
          Luego, se agruparán en forma decreciente tantos cocientes como cargos a ocupar, sin
          considerar en que Listas se han obtenido. De esta manera se establecerá el "número
          repartidor", que es el menor de los cocientes citados.
          A continuación se dividirá la cantidad de votos lograda por cada Lista por el "número
          repartidor". El resultado obtenido dará el número de cargos que se adjudicará a cada una de
          ellas.

         */
        $cant_escanos = array('CS' => array('E' => 10, 'N' => 10, 'D' => 10, 'G' => 4),
            'CD' => array('E' => 4, 'N' => 3, 'D' => 8, 'G' => 1),
            'CDA' => array('E' => 3, 'N' => 2, 'D' => 6, 'G' => 1));
        $escanos = $cant_escanos[$cat][$claustro];
        $datos = array();
        $escano_max = 0;
        if (count($listas) > 0 && count($listas) == count($valores)) {
            $cocientes = array();
            if ($cat == 'CS') {
                for ($index1 = 0; $index1 < count($valores); $index1++) {
                    $valores[$index1] = $valores[$index1] * 10000;
                }
            }
            foreach ($valores as $value) {
                for ($index = 1; $index <= $escanos; $index++) {
                    $cocientes[] = $value / $index;
                }
            }
            sort($cocientes);
            //print_r($cocientes);
            $repartidor = $cocientes[count($cocientes) - $escanos];

            $datos = array();

            foreach ($listas as $key => $lista) {
                $fila = array('lista' => $lista,
                    'escanos' => floor($valores[$key] / $repartidor));
                for ($index2 = 1; $index2 <= $escanos; $index2++) {
                    $fila[$index2] = floor($valores[$key] / $index2);
                    if ($index2 <= $fila['escanos']) {
                        if ($index2 > $escano_max)
                            $escano_max = $index2;
                        $orden = count($cocientes) - array_search($valores[$key] / $index2, $cocientes);
                        $fila[$index2] = "($orden)" . $fila[$index2];
                    }
                }
                $datos[] = $fila;
            }
        }
        $columns = array();
        $columns[] = array('field' => 'lista', 'title' => 'Listas');
        $columns[] = array('field' => 'escanos', 'title' => 'Escaños');
        for ($index3 = 1; $index3 <= $escano_max; $index3++) {
            $columns[] = array('field' => $index3, 'title' => $index3);
        }

        return array($columns, $datos);
    }

    //Metodo que calcula y genera archivos JSONS ubicados en /resultados_json/$fecha
    //con datos de resultados rector por cada claustro
    function datos_rector_claustro($fecha) {
        $sql = "select claustro, 
                    lista, sigla_lista, 
                    sum(ponderado) ponderado
            from(
            select votos_totales.id_tipo, votos_totales.id_nro_ue, 
                    votos_totales.sigla as sigla_ue, votos_totales.id_claustro, votos_totales.claustro, 
                    votos_totales.id_nro_lista, votos_totales.lista, votos_totales.sigla_lista, 
                    votos_totales.total, validos.validos, 
                    case when validos.validos <> 0 then 
                            votos_totales.total/cast(validos.validos as decimal) * validos.mult 
                    end ponderado 
            from (select a.id_tipo, ue.id_nro_ue, ue.sigla, 
                    m.id_claustro as id_claustro, c.descripcion claustro, l.id_nro_lista, l.nombre lista, 
                    l.sigla sigla_lista, sum(cant_votos) total 
                  from acta a 
                  inner join mesa m on m.id_mesa = a.de 
                  inner join sede s on s.id_sede = a.id_sede 
                  inner join unidad_electoral ue on ue.id_nro_ue = s.id_ue 
                  inner join claustro c on c.id = m.id_claustro 
                  inner join voto_lista_rector vl on vl.id_acta = a.id_acta 
                  inner join lista_rector l on l.id_nro_lista = vl.id_lista 
                  where m.estado > 1 and m.fecha = '" . $fecha . "' 
                  group by ue.id_nro_ue, ue.sigla, c.descripcion, l.nombre, l.id_nro_lista, l.sigla, s.id_ue, 
                            m.id_claustro, a.id_tipo 
                  order by s.id_ue,m.id_claustro, l.nombre 
            ) votos_totales
            inner join (select id_ue, id_claustro, 
			case ue.nivel when 2 then cargos_cdirectivo
				when 3 then cargos_cdiras
			end as mult, 
			sum(cant_votos) validos 
				from sede s 
				inner join acta a on a.id_sede = s.id_sede 
				inner join mesa m on m.id_mesa = a.de 
				inner join voto_lista_rector vl on vl.id_acta = a.id_acta 
				inner join claustro cl on cl.id = m.id_claustro
				inner join unidad_electoral ue on ue.id_nro_ue = s.id_ue
			    where m.estado > 1 and m.fecha = '" . $fecha . "' 
			    group by id_ue, id_claustro, cargos_csuperior, ue.nivel, 
                            cargos_cdirectivo, cargos_cdiras  
            ) validos on validos.id_ue = votos_totales.id_nro_ue 
                            and validos.id_claustro = votos_totales.id_claustro
            ) t
            group by id_tipo, claustro, lista, sigla_lista
            order by claustro, lista
                    ";
        $datos = toba::db('gu_kena')->consultar($sql);

        $columns = array();
        $columns[] = array('field' => 'lista', 'title' => 'Listas');
        $columns[] = array('field' => 'sigla_lista', 'title' => 'Sigla Listas');
        $columns[] = array('field' => 'ponderado', 'title' => 'Resultado');

        $nom_claustro = null;
        $data = array();
        foreach ($datos as $un_registro) {
            if ($nom_claustro != null && $nom_claustro != $un_registro['claustro']) {
                $json = array();
                $json['columns'] = $columns;
                $json['data'] = $data;
                $json['labels'] = $labels;
                $json['total'] = $total;
                $json['fecha'] = date('d/m/Y G:i:s');
                $json['titulo'] = 'Votos Universidad Rector ' . $nom_claustro;

                $data = array();
                $labels = array();
                $total = array();

                $string_json = json_encode($json);
                $nom_archivo = 'e' . str_replace('-', '', $fecha) . '/R_TODO_' . strtoupper($nom_claustro[0]) . '.json';
                file_put_contents('resultados_json/' . $nom_archivo, $string_json);

                $nom_claustro = $un_registro['claustro'];
            } elseif ($nom_claustro == null)
                $nom_claustro = $un_registro['claustro'];

            $r['lista'] = utf8_encode(trim($un_registro['lista']));
            $r['sigla_lista'] = trim($un_registro['sigla_lista']);
            $r['ponderado'] = $un_registro['ponderado'];

            $labels[] = $un_registro['sigla_lista'];
            $total[] = $un_registro['ponderado'];

            $data[] = $r;
        }

        if (isset($data) && $nom_claustro != null) {//Quedo un ultimo claustro sin guardar
            $json = array();
            $json['columns'] = $columns;
            $json['data'] = $data;
            $json['labels'] = $labels;
            $json['total'] = $total;
            $json['fecha'] = date('d/m/Y G:i:s');
            $json['titulo'] = 'Votos Universidad Rector ' . $nom_claustro;

            $string_json = json_encode($json);

            $nom_archivo = 'e' . str_replace('-', '', $fecha) . '/R_TODO_' . strtoupper($nom_claustro[0]);
            file_put_contents('resultados_json/' . $nom_archivo . '.json', $string_json);
        }
    }

    // Resultado general de rector con lista | ponderado
    function datos_rector($fecha) {
        $sql = "select trim(sigla_lista) as lista, trim(sigla_lista) as sigla_lista, sum(pond) as ponderados 
            from (
                select lista as Lista,  sigla_lista, vv.claustro as claustro, sum(cast(votos_lista as real)/votos_validos)*ponderacion pond
	from(

		select ue.sigla as ue,c.descripcion claustro, 
                    l.nombre as lista, l.sigla as sigla_lista,
                    sum(cant_votos) as votos_lista
		from acta a 
                        inner join voto_lista_rector vl on a.id_acta=vl.id_acta and a.id_tipo=4
			inner join lista_rector l on vl.id_lista=l.id_nro_lista
			inner join mesa m on a.de=m.id_mesa
			inner join claustro c on m.id_claustro=c.id
			inner join sede s on a.id_sede=s.id_sede
			inner join unidad_electoral ue on s.id_ue=ue.id_nro_ue
                        where m.fecha = '" . $fecha . "'
		group by ue,claustro, lista, l.sigla
	)vl inner join 
	(
		select ue.sigla as ue,c.descripcion claustro, cargos_cdirectivo as ponderacion, sum(cant_votos) as votos_validos
		from acta a inner join voto_lista_rector vl on a.id_acta=vl.id_acta
			inner join lista_rector l on vl.id_lista=l.id_nro_lista
			inner join mesa m on a.de=m.id_mesa
			inner join claustro c on m.id_claustro=c.id
			inner join sede s on a.id_sede=s.id_sede
			inner join unidad_electoral ue on s.id_ue=ue.id_nro_ue
                        where m.fecha = '" . $fecha . "'
		group by ue,claustro,ponderacion
		
	)vv on vl.ue=vv.ue and vl.claustro=vv.claustro
	group by lista, sigla_lista,vv.claustro,ponderacion
        order by lista,vv.claustro
)a group by lista, sigla_lista
order by a.lista

                    ";
        $datos = toba::db('gu_kena')->consultar($sql);

        $columns = array();
        $columns[] = array('field' => 'lista', 'title' => 'Listas');
        $columns[] = array('field' => 'ponderado', 'title' => 'Resultado');

        $json = array();
        $json['data'] = $datos;
        $json['columns'] = $columns;

        foreach ($datos as $un_registro) {
            $labels[] = $un_registro['sigla_lista'];
            $total[] = $un_registro['ponderados'];
        }

        $json['labels'] = $labels;
        $json['total'] = $total;
        $json['fecha'] = date('d/m/Y G:i:s');
        $json['titulo'] = 'Votos Universidad Rector';

        $string_json = json_encode($json);
        //print_r($string_json);exit;
        $nom_archivo = 'e' . str_replace('-', '', $fecha) . '/R_TODO_T';
        file_put_contents('resultados_json/' . $nom_archivo . '.json', $string_json);
    }

}

new Crongukena('2018-05-22');

