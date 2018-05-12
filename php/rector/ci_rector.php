
<?php

class ci_rector extends toba_ci {

    protected $s__votos_e;
    protected $s__votos_g;
    protected $s__votos_nd;
    protected $s__votos_d;
    protected $s__total_emp;
    protected $s__fecha = '2016-05-17';
    
    //---- Cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------
    //---- cuadro_rector_e ------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_rector_e(gu_kena_ei_cuadro $cuadro) {
        $res = $this->cargar_cuadro_rector('cuadro_rector_e', 3); // 3 = Estudiantes 
        return $res;
    }

    //-----------------------------------------------------------------------------------
    //---- cuadro_rector_g ------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_rector_g(gu_kena_ei_cuadro $cuadro) {
        $res = $this->cargar_cuadro_rector('cuadro_rector_g', 4); // 4 = Graduados
        return $res;
    }

    //-----------------------------------------------------------------------------------
    //---- cuadro_rector_nd -----------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_rector_nd(gu_kena_ei_cuadro $cuadro) {
        $res = $this->cargar_cuadro_rector('cuadro_rector_nd', 1); // 1 = No docente
        return $res;
    }

    //-----------------------------------------------------------------------------------
    //---- cuadro_rector_d ------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_rector_d(gu_kena_ei_cuadro $cuadro) {
        $res = $this->cargar_cuadro_rector('cuadro_rector_d', 2); // 2 = Docente
        return $res;
    }

    function cargar_cuadro_rector($cuadro, $id_claustro) {
        //Obtengo todas las unidades electorales
        $unidades = $this->dep('datos')->tabla('unidad_electoral')->get_descripciones();

        //Cargar la cantidad de empadronados para el claustro $id_claustro
        // en cada unidad como segunda columna
        $ar = $this->cargar_cant_empadronados($unidades, $id_claustro);
        //Cargar la cantidad de votantes para el claustro estudiantes=3
        // en cada unidad como tercer columna
        $ar = $this->cargar_cant_votantes($ar, $listas);
        //Ante ultima fila carga los votos totales de cada lista
        $pos = sizeof($ar);
        $ar[$pos]['sigla'] = "<span style='color:blue; font-weight:bold'>TOTAL</span>";
        $ar[$pos]['cant_empadronados'] = "<span style='color:blue; font-weight:bold'>" . $this->s__total_emp . "</span>";

        //Ultima fila carga los votos ponderados de cada lista
        $pos = sizeof($ar);
        $ar[$pos]['sigla'] = "<span style='color:red; font-weight:bold'>PONDERADOS</span>";

        //Obtener las listas del claustro estudiantes=3
        $listas = $this->dep('datos')->tabla('lista_rector')->get_listas($this->s__fecha);

        //Agregar las etiquetas de todas las listas (columnas dinámicas)
        $i = 1;
        foreach ($listas as $lista) {
            $l['clave'] = $lista['id_nro_lista'];
            $l['titulo'] = substr($lista['nombre'], 0, 11) . $lista['sigla'];
            $l['estilo'] = 'col-cuadro-resultados';
            $l['estilo_titulo'] = 'tit-cuadro-resultados';

            $l['permitir_html'] = true;

            $grupo[$i] = $lista['id_nro_lista'];

            $columnas[$i] = $l;
            $this->dep($cuadro)->agregar_columnas($columnas);

            //Cargar la cantidad de votos para cada lista de claustro $id_claustro 
            //en cada unidad
            $ar = $this->cargar_cant_votos($lista['id_nro_lista'], $ar, $id_claustro);

            //Cargar los votos totales/ponderados para cada lista agregado como ante/última fila
            //para claustro estudiantes=3
            $ar[$pos - 1][$lista['id_nro_lista']] = 0;
            $ar[$pos][$lista['id_nro_lista']] = 0;
            $ar = $this->cargar_votos_totales_ponderados($lista['id_nro_lista'], $ar, $id_claustro);

            $i++;
        }
        $this->dep($cuadro)->set_grupo_columnas('Listas', $grupo);


        $this->s__votos_e = $ar; //Guardar los votos para el calculo dhondt
        //Agregar datos totales de blancos, nulos y recurridos
        $b['clave'] = 'total_votos_blancos';
        $b['titulo'] = 'Blancos';
        $b['estilo'] = 'col-cuadro-resultados';
        $b['estilo_titulo'] = 'tit-cuadro-resultados';
        $bnr[0] = $b;

        $n['clave'] = 'total_votos_nulos';
        $n['titulo'] = 'Nulos';
        $n['estilo'] = 'col-cuadro-resultados';
        $n['estilo_titulo'] = 'tit-cuadro-resultados';
        $bnr[1] = $n;

        $r['clave'] = 'total_votos_recurridos';
        $r['titulo'] = 'Recurridos';
        $r['estilo'] = 'col-cuadro-resultados';
        $r['estilo_titulo'] = 'tit-cuadro-resultados';
        $bnr[2] = $r;

        $this->dep($cuadro)->agregar_columnas($bnr);


        $ar = $this->cargar_cant_b_n_r($ar, $id_claustro);

        //$this->cambiar_estilo_total($ar); //ver porqué no agrega estilo
        //print_r($ar);
        return $ar;
    }

    //Metodo responsable de cargar los votos blancos, nulos y recurridos de cada unidad electoral
    function cargar_cant_b_n_r($unidades, $id_claustro) {
        $p = sizeof($unidades) - 2;
        //Inicializo para realizar la sumatoria
        $unidades[$p]['total_votos_blancos'] = 0;
        $unidades[$p]['total_votos_nulos'] = 0;
        $unidades[$p]['total_votos_recurridos'] = 0;
        for ($i = 0; $i < $p; $i++) {//Recorro las unidades
            //Agrega la cantidad de votos blancos,nulos y recurridos calculado en acta para cada unidad con claustro y tipo rector=4            
            $ar = $this->dep('datos')->tabla('acta')->cant_b_n_r($unidades[$i]['id_nro_ue'], $id_claustro, 4);
            if (sizeof($ar) > 0) {
                $unidades[$i]['total_votos_blancos'] = $ar[0]['blancos'];
                $unidades[$i]['total_votos_nulos'] = $ar[0]['nulos'];
                $unidades[$i]['total_votos_recurridos'] = $ar[0]['recurridos'];

                //Agrego en la anteultima fila la sumatoria total

                $unidades[$p]['total_votos_blancos'] += $ar[0]['blancos'];
                $unidades[$p]['total_votos_nulos'] += $ar[0]['nulos'];
                $unidades[$p]['total_votos_recurridos'] += $ar[0]['recurridos'];
            }
        }
        return $unidades;
    }

    //Metodo responsable de cargar la segunda columna con la cantidad de empadronados
    // en cada unidad electoral
    function cargar_cant_empadronados($unidades, $id_claustro) {
        $this->s__total_emp = 0;
        for ($i = 0; $i < sizeof($unidades); $i++) {//Recorro las unidades
            //Agrega la cantidad de empadronados calculado en acta para cada unidad con claustro
            $unidades[$i]['cant_empadronados'] = $this->dep('datos')->tabla('mesa')->cant_empadronados($unidades[$i]['id_nro_ue'], $id_claustro);
            $this->s__total_emp += $unidades[$i]['cant_empadronados'];
        }
        return $unidades;
    }

    function cargar_cant_votos($id_lista, $unidades, $id_claustro) {
        for ($i = 0; $i < sizeof($unidades) - 2; $i++) {//Recorro las unidades
            //Agrega la cantidad de empadronados calculado en acta para cada unidad con claustro  y tipo 'rector'
            $unidades[$i][$id_lista] = $this->dep('datos')->tabla('voto_lista_rector')->cant_votos($id_lista, $unidades[$i]['id_nro_ue'], $id_claustro);
        }
        return $unidades;
    }

    function cargar_cant_votantes($unidades, $listas) {
        //realiza la suma de los votos que hay en las distintas columnas
        $p = sizeof($unidades) - 2;
        $cant_columnas = sizeof($listas);
        $unidades[$p]['cant_votantes'] = 0;
        //Inicializo para realizar la sumatoria
        $cant_total = 0;
        for ($i = 0; $i < $p; $i++) { //Recorre las unidades
            $votantes_ue = 0;
            for ($c = 0; $c < $cant_columnas; $c++) {
                $votantes_ue += $unidades[$i][($listas[$c]['id_nro_lista'])];
            }
            $votantes_ue += $unidades[$i]['total_votos_blancos'];
            $votantes_ue += $unidades[$i]['total_votos_nulos'];
            $votantes_ue += $unidades[$i]['total_votos_recurridos'];
            $unidades[$i]['cant_votantes'] = $votantes_ue;
            $cant_total += $votantes_ue;
        }
        $unidades[$p]['cant_votantes'] = $cant_total;
        return $unidades;
    }

    function cargar_votos_totales_ponderados($id_lista, $unidades, $id_claustro) {
        $pos_total = sizeof($unidades) - 2; //Fila que contiene los votos totales
        $pos_pond = sizeof($unidades) - 1; //Fila que contiene los votos ponderados
        //Recorro las unidades exluyendo las dos últimas filas que tiene los votos totales y ponderados
        for ($i = 0; $i < $pos_total; $i++) {
            if (isset($unidades[$i][$id_lista]) && isset($unidades[$i]['cant_validos'])) {
                //Suma el cociente entre cant de votos de la 
                //lista en la UEn / cant votos validos del claustro en la UEn
                $cociente = $unidades[$i][$id_lista] / $unidades[$i]['cant_votantes'];
                $unidades[$pos_pond][$id_lista] += $cociente;
            }

            if (isset($unidades[$i][$id_lista])) {
                //Suma los votos 
                $unidades[$pos_total][$id_lista] += $unidades[$i][$id_lista];
            }
        }
        $unidades[$pos_pond][$id_lista] = "<span style='color:red'>" . round($unidades[$pos_pond][$id_lista], 6) . "</span>";

        return $unidades;
    }

    //-----------------------------------------------------------------------------------
    //---- Configuraciones --------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf() {
        $claustros = $this->dep('datos')->tabla('mesa')->get_claustro_novota($this->s__fecha);

        foreach ($claustros as $key => $claustro) {
            switch ($claustro['id']) {
                case 1: //No hay votacion de no docentes, ent ocultar pantalla
                    $this->pantalla()->tab('pant_no_docente')->ocultar();
                    break;
                case 2: //No hay votacion de docentes, ent ocultar pantalla
                    $this->pantalla()->tab('pant_docente')->ocultar();
                    break;
                case 3: //No hay votacion de estudiantes, ent ocultar pantalla
                    $this->pantalla()->tab('pant_estudiantes')->ocultar();
                    break;
                case 4: //No hay votacion de graduados, ent ocultar pantalla
                    $this->pantalla()->tab('pant_graduados')->ocultar();
                    break;
            }
        }

        $this->generar_json('2015-06-16');
        
    }

    function generar_json($fecha) {
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
    }
    
    //Metodo que calcula y genera JSONS de la categoria $categoria para cada unidad
    //electoral y cada claustro
    function datos_ue_claustro($fecha, $tabla_voto, $tabla_lista, $categoria, $sigla_cat){
        $sql = "select ue.nombre as unidad_electoral, ue.sigla as sigla_ue, cl.descripcion as claustro, 
                    l.id_nro_lista, l.nombre as lista,
                    s.sigla as sede, 
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
                    where m.fecha = '".$fecha."'
                    group by s.id_ue, m.id_claustro, vl.id_lista 
            ) total on total.id_claustro = cl.id
                    and total.id_lista = l.id_nro_lista
            where l.fecha = '".$fecha."'
            order by unidad_electoral, claustro, lista, sede 
                ";
        $datos = toba::db('gu_kena')->consultar($sql);
        
        $nom_ue = null;
        $nom_claustro = null;
        $nom_lista = null;
        $sedes = array();
        $columns_sedes = array();
        
        $data = array();
        $labels = array();
        $total = array();
        $lista = array();
        
        foreach($datos as $un_registro){
            if($nro_lista != null && $nro_lista != $un_registro['id_nro_lista']){
                $r = array();
                $r['lista'] = utf8_encode(trim($lista['lista']));
                $r['sigla_lista'] = trim($lista['sigla_lista']);
                
                foreach($sedes as $sigla_sede => $cant_votos){
                    $r[$sigla_sede] = $cant_votos;
                    $columns_sedes[$sigla_sede] = $sigla_sede;
                }
                $r['total'] = $lista['total'];
                $data[] = $r;
                
                if(($nom_ue != null && $nom_ue != $un_registro['sigla_ue'])
                        || ($nom_claustro != null && $nom_claustro != $un_registro['claustro'])){
                    if(sizeof($data) > 0){//Solo si existen datos ent crea el json
                        $json = array();
                        $columns = array();
                        $columns[] = array('field' => 'lista', 'title' => 'Listas');
                        $columns[] = array('field' => 'sigla_lista', 'title' => 'Sigla Listas');
                        foreach($columns_sedes as $key => $sigla_sede){
                            $columns[] = array('field' => $key, 'title' => $sigla_sede);
                        }
                        $columns[] = array('field' => 'total', 'title' => 'Total');

                        $json['columns'] = $columns;
                        $json['data'] = $data;
                        $json['labels'] = $labels;
                        $json['total'] = $total;
                        $json['fecha'] = date('d/m/Y G:i:s');
                        $json['titulo'] = 'Votos '.$nom_ue.' '.$categoria.' '.$nom_claustro;

                        $string_json = json_encode($json);
                        $nom_archivo = 'e'.str_replace('-','',$fecha).'/'.$sigla_cat.'_'.strtoupper($nom_ue).'_'.strtoupper($nom_claustro[0]).'.json';
                        file_put_contents('resultados_json/'. $nom_archivo , $string_json);
                    }
                    $data = array();
                    $labels = array();
                    $total = array();
                    $columns_sedes = array();

                    $nom_ue = $un_registro['sigla_ue'];  
                    $nom_claustro = $un_registro['claustro'];
                    $lista = array();
                    $sedes = array();
                }elseif($nom_ue == null){
                    $nom_ue = $un_registro['sigla_ue'];
                    $nom_claustro = $un_registro['claustro'];
                }
                
                $lista = array();
                $lista['lista'] = $un_registro['lista'];
                $lista['sigla_lista'] = $un_registro['sigla_lista'];
                $lista['total'] = $un_registro['total'];
                $nro_lista = $un_registro['id_nro_lista'];
                $sedes = array();
                
            }elseif($nro_lista == null){
                $lista['lista'] = $un_registro['lista'];
                $lista['sigla_lista'] = $un_registro['sigla_lista'];
                $lista['total'] = $un_registro['total'];
                $nro_lista = $un_registro['id_nro_lista'];
                
                $nom_ue = $un_registro['sigla_ue'];
                $nom_claustro = $un_registro['claustro'];
            }
            
            $labels[] = $un_registro['sigla_lista'];
            $total[] = $un_registro['total'];
            
            $sedes[$un_registro['sede']] = $un_registro['cant_votos'];
            
        }
        
        if(sizeof($data) > 0){//Solo si existen datos finales ent crea el json
            $json = array();
            $columns = array();
            $columns[] = array('field' => 'lista', 'title' => 'Listas');
            $columns[] = array('field' => 'sigla_lista', 'title' => 'Sigla Listas');
            foreach($columns_sedes as $key => $sigla_sede){
                $columns[] = array('field' => $key, 'title' => $sigla_sede);
            }
            $columns[] = array('field' => 'total', 'title' => 'Total');

            $json['columns'] = $columns;
            $json['data'] = $data;
            $json['labels'] = $labels;
            $json['total'] = $total;
            $json['fecha'] = date('d/m/Y G:i:s');
            $json['titulo'] = 'Votos '.$nom_ue.' '.$categoria.' '.$nom_claustro;

            $string_json = json_encode($json);
            $nom_archivo = 'e'.str_replace('-','',$fecha).'/'.$sigla_cat.'_'.strtoupper($nom_ue).'_'.strtoupper($nom_claustro[0]).'.json';
            file_put_contents('resultados_json/'. $nom_archivo , $string_json);
        }
    }
        
    //Metodo que calcula y genera archivos JSONS ubicados en /resultados_json/$fecha
    //con datos de resultados rector por cada unidad electoral
    function datos_rector_ue($fecha){
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
                  where m.estado > 1 and m.fecha = '".$fecha."' 
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
                                        where m.estado > 1 and m.fecha = '".$fecha."' 
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
        foreach($datos as $un_registro){
            if($nom_ue != null && $nom_ue != $un_registro['sigla_ue']){
                $json = array();
                
                $json['columns'] = $columns;
                $json['data'] = $data;
                $json['labels'] = $labels;
                $json['total'] = $total;
                $json['fecha'] = date('d/m/Y G:i:s');
                $json['titulo'] = 'Votos '.$nom_ue.' Rector';
                
                $data = array();
                $labels = array();
                $total = array();
                
                $string_json = json_encode($json);
                $nom_archivo = 'e'.str_replace('-','',$fecha).'/R_'.strtoupper($nom_ue).'_T.json';
                file_put_contents('resultados_json/'. $nom_archivo , $string_json);
                
                $nom_ue = $un_registro['sigla_ue'];                
            }elseif($nom_ue == null)
                $nom_ue = $un_registro['sigla_ue'];
            
            $r['lista'] = utf8_encode(trim($un_registro['lista']));
            $r['sigla_lista'] = trim($un_registro['sigla_lista']);
            $r['ponderado'] = $un_registro['ponderado'];
            
            $labels[] = $un_registro['sigla_lista'];
            $total[] = $un_registro['ponderado'];
            
            $data[] = $r;
        }
        
        if(isset($data) && $nom_ue != null){//Quedo un ultimo claustro sin guardar
            $json = array();
            $json['columns'] = $columns;
            $json['data'] = $data;
            $json['labels'] = $labels;
            $json['total'] = $total;
            $json['fecha'] = date('d/m/Y G:i:s');
            $json['titulo'] = 'Votos '.$nom_ue.' Rector';
            
            $string_json = json_encode($json);

            $nom_archivo = 'e'.str_replace('-','',$fecha).'/R_'.strtoupper($nom_ue).'_T.json';
            file_put_contents('resultados_json/'.$nom_archivo, $string_json);
        }
    }
    
    //Metodo que calcula y genera archivos JSONS ubicados en /resultados_json/$fecha
    //con datos de resultados decano por cada unidad electoral
    function datos_decano_ue($fecha){
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
                  where m.estado > 1 and m.fecha = '".$fecha."' 
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
                                        where m.estado > 1 and m.fecha = '".$fecha."' 
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
        foreach($datos as $un_registro){
            if($nom_ue != null && $nom_ue != $un_registro['sigla_ue']){
                $json = array();
                $json['columns'] = $columns;
                $json['data'] = $data;
                $json['labels'] = $labels;
                $json['total'] = $total;
                $json['fecha'] = date('d/m/Y G:i:s');
                $json['titulo'] = 'Votos '.$nom_ue.' Decano';
                
                $data = array();
                $labels = array();
                $total = array();
                
                $string_json = json_encode($json);
                $nom_archivo = 'e'.str_replace('-','',$fecha).'/D_'.strtoupper($nom_ue).'_T.json';
                file_put_contents('resultados_json/'. $nom_archivo , $string_json);
                
                $nom_ue = $un_registro['sigla_ue'];                
            }elseif($nom_ue == null)
                $nom_ue = $un_registro['sigla_ue'];
            
            $r['lista'] = utf8_encode(trim($un_registro['lista']));
            $r['sigla_lista'] = trim($un_registro['sigla_lista']);
            $r['ponderado'] = $un_registro['ponderado'];
            
            $labels[] = $un_registro['sigla_lista'];
            $total[] = $un_registro['ponderado'];
            
            $data[] = $r;
        }
        
        if(isset($data) && $nom_ue != null){//Quedo un ultimo claustro sin guardar
            $json = array();
            $json['columns'] = $columns;
            $json['data'] = $data;
            $json['labels'] = $labels;
            $json['total'] = $total;
            $json['fecha'] = date('d/m/Y G:i:s');
            $json['titulo'] = 'Votos '.$nom_ue.' Decano';
            
            $string_json = json_encode($json);

            $nom_archivo = 'e'.str_replace('-','',$fecha).'/D_'.strtoupper($nom_ue).'_T.json';
            file_put_contents('resultados_json/'.$nom_archivo, $string_json);
        }
    }
    
    //Metodo que calcula y genera archivos JSONS ubicados en /resultados_json/$fecha
    //con datos de resultados consejero superior por cada claustro
    function datos_sup_claustro($fecha){
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
                  where m.estado > 1 and m.fecha = '".$fecha."' 
                  group by ue.id_nro_ue, ue.sigla, c.descripcion, l.nombre, l.id_nro_lista, l.sigla, s.id_ue, 
                            m.id_claustro, a.id_tipo order by s.id_ue,m.id_claustro, l.nombre 
            ) votos_totales
            inner join (select id_tipo, id_ue, ue.sigla, id_claustro, sum(cant_empadronados) empadronados 
                        from sede s 
                        inner join acta a on a.id_sede = s.id_sede
                        inner join mesa m on m.id_mesa = a.de 
                        inner join unidad_electoral ue on ue.id_nro_ue = s.id_ue 
                        where m.fecha = '".$fecha."' 
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
        foreach($datos as $un_registro){
            if($nom_claustro != null && $nom_claustro != $un_registro['claustro']){
                $json = array();
                $json['columns'] = $columns;
                $json['data'] = $data;
                $json['labels'] = $labels;
                $json['total'] = $total;
                $json['fecha'] = date('d/m/Y G:i:s');
                $json['titulo'] = 'Votos Universidad Consejero Superior '.$nom_claustro;
                
                $data = array();
                $labels = array();
                $total = array();
                
                $string_json = json_encode($json);
                $nom_archivo = 'e'.str_replace('-','',$fecha).'/CS_TODO_'.strtoupper($nom_claustro[0]).'.json';
                file_put_contents('resultados_json/'.$nom_archivo , $string_json);
                
                $nom_claustro = $un_registro['claustro'];                
            }elseif($nom_claustro == null)
                $nom_claustro = $un_registro['claustro'];
            
            $r['lista'] = utf8_encode(trim($un_registro['lista']));
            $r['sigla_lista'] = trim($un_registro['sigla_lista']);
            $r['ponderado'] = $un_registro['ponderado'];
            
            $labels[] = $un_registro['sigla_lista'];
            $total[] = $un_registro['ponderado'];
            
            $data[] = $r;
        }
        
        if(isset($data) && $nom_claustro != null){//Quedo un ultimo claustro sin guardar
            $json = array();
            $json['columns'] = $columns;
            $json['data'] = $data;
            $json['labels'] = $labels;
            $json['total'] = $total;
            $json['fecha'] = date('d/m/Y G:i:s');
            $json['titulo'] = 'Votos Universidad Consejero Superior '.$nom_claustro;
             
            $string_json = json_encode($json);

            $nom_archivo = 'e'.str_replace('-','',$fecha).'/CS_TODO_'.strtoupper($nom_claustro[0]);
            file_put_contents('resultados_json/'.$nom_archivo . '.json', $string_json);
        }
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
                  where m.estado > 1 and m.fecha = '".$fecha."' 
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
			    where m.estado > 1 and m.fecha = '".$fecha."' 
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
        foreach($datos as $un_registro){
            if($nom_claustro != null && $nom_claustro != $un_registro['claustro']){
                $json = array();
                $json['columns'] = $columns;
                $json['data'] = $data;
                $json['labels'] = $labels;
                $json['total'] = $total;
                $json['fecha'] = date('d/m/Y G:i:s');
                $json['titulo'] = 'Votos Universidad Rector '.$nom_claustro;
                
                $data = array();
                $labels = array();
                $total = array();
                
                $string_json = json_encode($json);
                $nom_archivo = 'e'.str_replace('-','',$fecha).'/R_TODO_'.strtoupper($nom_claustro[0]).'.json';
                file_put_contents('resultados_json/'.$nom_archivo , $string_json);
                
                $nom_claustro = $un_registro['claustro'];                
            }elseif($nom_claustro == null)
                $nom_claustro = $un_registro['claustro'];
            
            $r['lista'] = utf8_encode(trim($un_registro['lista']));
            $r['sigla_lista'] = trim($un_registro['sigla_lista']);
            $r['ponderado'] = $un_registro['ponderado'];
            
            $labels[] = $un_registro['sigla_lista'];
            $total[] = $un_registro['ponderado'];
            
            $data[] = $r;
        }
        
        if(isset($data) && $nom_claustro != null){//Quedo un ultimo claustro sin guardar
            $json = array();
            $json['columns'] = $columns;
            $json['data'] = $data;
            $json['labels'] = $labels;
            $json['total'] = $total;
            $json['fecha'] = date('d/m/Y G:i:s');
            $json['titulo'] = 'Votos Universidad Rector '.$nom_claustro;
            
            $string_json = json_encode($json);

            $nom_archivo = 'e'.str_replace('-','',$fecha).'/R_TODO_'.strtoupper($nom_claustro[0]);
            file_put_contents('resultados_json/'.$nom_archivo . '.json', $string_json);
        }
    }
    
    // Resultado general de rector con lista | ponderado
    function datos_rector($fecha) {
        $sql = "select trim(lista) as lista, sigla_lista, sum(pond) as ponderados 
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
                        where m.fecha = '".$fecha."'
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
                        where m.fecha = '".$fecha."'
		group by ue,claustro,ponderacion
		
	)vv on vl.ue=vv.ue and vl.claustro=vv.claustro
	group by lista, sigla_lista,vv.claustro,ponderacion
        order by lista,vv.claustro
)a group by lista, sigla_lista

                    ";
        $datos = toba::db('gu_kena')->consultar($sql);

        $columns = array();
        $columns[] = array('field' => 'lista', 'title' => 'Listas');
        $columns[] = array('field' => 'ponderado', 'title' => 'Resultado');
        
        $json = array();
        $json['data'] = $datos;
        $json['columns'] = $columns;
        
        foreach($datos as $un_registro){
            $labels[] = $un_registro['sigla_lista'];
            $total[] = $un_registro['ponderados'];
        }
        
        $json['labels'] = $labels;
        $json['total'] = $total;
        $json['fecha'] = date('d/m/Y G:i:s');
        $json['titulo'] = 'Votos Universidad Rector';
        
        $string_json = json_encode($json);

        $nom_archivo = 'e'.str_replace('-','',$fecha).'/R_TODO_T';
        file_put_contents('resultados_json/'.$nom_archivo . '.json', $string_json);
    }
    
    //-----------------------------------------------------------------------------------
    //---- formulario que muestra datos de mesas enviadas, confirmadas y definitivas -----------------------------------------------------------------
    //-----------------------------------------------------------------------------------
    //---- form_mesas_e -----------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_mesas_e(gu_kena_ei_formulario $form) {
        /* $cargadas = $this->dep('datos')->tabla('mesa')->get_cant_cargadas(3);
          $confirmadas = $this->dep('datos')->tabla('mesa')->get_cant_confirmadas(3);
          $definitivas = $this->dep('datos')->tabla('mesa')->get_cant_definitivas(3);

          $total = $this->dep('datos')->tabla('mesa')->get_total_mesas(3);
          if ($total != 0){
          $datos['cargadas'] = ($cargadas * 100 / $total);
          $datos['cargadas'] = round($datos['cargadas'], 2). " % ($cargadas de $total)";
          $datos['confirmadas'] = ($confirmadas * 100 / $total);
          $datos['confirmadas'] = round($datos['confirmadas'],2). " % ($confirmadas de $total)";
          $datos['definitivas'] = ($definitivas * 100 / $total);
          $datos['definitivas'] = round($datos['definitivas'],2). " % ($definitivas de $total)";
          }
          else {
          $datos['cargadas'] = $cargadas . " % ($cargadas de $total)";
          $datos['confirmadas'] = $confirmadas . " % ($confirmadas de $total)";
          $datos['definitivas'] = $definitivas . " % ($definitivas de $total)";
          }
          return $datos; */
    }

    //-----------------------------------------------------------------------------------
    //---- form_mesas_g -----------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_mesas_g(gu_kena_ei_formulario $form) {
        /* $cargadas = $this->dep('datos')->tabla('mesa')->get_cant_cargadas(4);
          $confirmadas = $this->dep('datos')->tabla('mesa')->get_cant_confirmadas(4);
          $definitivas = $this->dep('datos')->tabla('mesa')->get_cant_definitivas(4);

          $total = $this->dep('datos')->tabla('mesa')->get_total_mesas(4);
          if ($total != 0) {
          $datos['cargadas'] = ($cargadas * 100 / $total);
          $datos['cargadas'] = round($datos['cargadas'], 2) . " % ($cargadas de $total)";
          $datos['confirmadas'] = ($confirmadas * 100 / $total);
          $datos['confirmadas'] = round($datos['confirmadas'], 2) . " % ($confirmadas de $total)";
          $datos['definitivas'] = ($definitivas * 100 / $total);
          $datos['definitivas'] = round($datos['definitivas'], 2) . " % ($definitivas de $total)";
          }
          else{
          $datos['cargadas'] = $cargadas . " % ($cargadas de $total)";
          $datos['confirmadas'] = $confirmadas . " % ($confirmadas de $total)";
          $datos['definitivas'] = $definitivas .  " % ($definitivas de $total)";
          }

          return $datos; */
    }

    //-----------------------------------------------------------------------------------
    //---- form_mesas_nd ----------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_mesas_nd(gu_kena_ei_formulario $form) {
        /* $cargadas = $this->dep('datos')->tabla('mesa')->get_cant_cargadas(1);
          $confirmadas = $this->dep('datos')->tabla('mesa')->get_cant_confirmadas(1);
          $definitivas = $this->dep('datos')->tabla('mesa')->get_cant_definitivas(1);

          $total = $this->dep('datos')->tabla('mesa')->get_total_mesas(1);

          if ($total != 0) {
          $datos['cargadas'] = ($cargadas * 100 / $total);
          $datos['cargadas'] = round($datos['cargadas'], 2) . " % ($cargadas de $total)";
          $datos['confirmadas'] = ($confirmadas * 100 / $total);
          $datos['confirmadas'] = round($datos['confirmadas'], 2) . " % ($confirmadas de $total)";
          $datos['definitivas'] = ($definitivas * 100 / $total);
          $datos['definitivas'] = round($datos['definitivas'], 2) . " % ($definitivas de $total)";
          }
          else {
          $datos['cargadas'] = $cargadas . " % ($cargadas de $total)";
          $datos['confirmadas'] = $confirmadas . " % ($confirmadas de $total)";
          $datos['definitivas'] = $definitivas . " % ($definitivas de $total)";
          }
          return $datos; */
    }

    //-----------------------------------------------------------------------------------
    //---- form_mesas_d ----------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_mesas_d(gu_kena_ei_formulario $form) {
        /* $cargadas = $this->dep('datos')->tabla('mesa')->get_cant_cargadas(2);
          $confirmadas = $this->dep('datos')->tabla('mesa')->get_cant_confirmadas(2);
          $definitivas = $this->dep('datos')->tabla('mesa')->get_cant_definitivas(2);
          $total = $this->dep('datos')->tabla('mesa')->get_total_mesas(2);

          if ($total != 0) {
          $datos['cargadas'] = ($cargadas * 100 / $total);
          $datos['cargadas'] = round($datos['cargadas'], 2) . " % ($cargadas de $total)";
          $datos['confirmadas'] = ($confirmadas * 100 / $total);
          $datos['confirmadas'] = round($datos['confirmadas'], 2) . " % ($confirmadas de $total)";
          $datos['definitivas'] = ($definitivas * 100 / $total);
          $datos['definitivas'] = round($datos['definitivas'], 2) . " % ($definitivas de $total)";
          }
          else {
          $datos['cargadas'] = $cargadas . " % ($cargadas de $total)";
          $datos['confirmadas'] = $confirmadas . " % ($confirmadas de $total)";
          $datos['definitivas'] = $definitivas . " % ($definitivas de $total)";
          }
          return $datos; */
    }

    //-----------------------------------------------------------------------------------
    //---- EXPORTACION EXCEL ----------------------------------------------------------------
    //-----------------------------------------------------------------------------------
    function vista_excel(toba_vista_excel $salida) {
        $salida->set_nombre_archivo("EscrutinioRector.xls");
        $excel = $salida->get_excel();


        $this->dependencia('cuadro_rector_e')->vista_excel($salida);
        $salida->separacion(3);
        $this->dependencia('cuadro_dhondt_e')->vista_excel($salida);
        $salida->set_hoja_nombre("Estudiantes");

        $salida->crear_hoja();
        $this->dependencia('cuadro_rector_g')->vista_excel($salida);
        $salida->separacion(3);
        $this->dependencia('cuadro_dhondt_g')->vista_excel($salida);
        $salida->set_hoja_nombre("Graduados");

        $salida->crear_hoja();
        $this->dependencia('cuadro_rector_nd')->vista_excel($salida);
        $salida->separacion(3);
        $this->dependencia('cuadro_dhondt_nd')->vista_excel($salida);
        $salida->set_hoja_nombre("No Docente");
//            $excel->getActiveSheet()->setTitle('Parte de Novedades');
//            $excel->getActiveSheet()->getStyle('A5')->getFill()->applyFromArray(array(
//        'type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array( 'rgb' => 'F28A8C' ) ));
    }

}

?>
