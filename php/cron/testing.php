<?php

/*
select ue.sigla as unidad,s.sigla as sede, id_claustro,id_mesa, cant_empadronados, a.id_acta, total_votos_blancos+total_votos_nulos+total_votos_recurridos as otros, sum(cant_votos) as validos
, total_votos_blancos+total_votos_nulos+total_votos_recurridos+sum(cant_votos)-cant_empadronados as diferencia
from mesa m inner join acta a on a.de=m.id_mesa
          inner join voto_lista_rector v on a.id_acta=v.id_acta
inner join sede s on m.id_sede =s.id_sede
inner join unidad_electoral ue on s.id_ue=ue.id_nro_ue

where fecha='2018-05-22' and id_tipo=4
group by ue.sigla,s.sigla,id_claustro,id_mesa, cant_empadronados, a.id_acta
,total_votos_blancos,total_votos_nulos,total_votos_recurridos
order by ue.sigla,s.sigla,id_claustro,id_mesa



 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Testing {

    public function __construct($fecha) {
        $this->eleccion_a_0($fecha);
        $this->generar_eleccion_random($fecha, 'voto_lista_csuperior', 'lista_csuperior', 1);
        $this->generar_eleccion_random($fecha, 'voto_lista_cdirectivo', 'lista_cdirectivo', 2);
        $this->generar_eleccion_random($fecha, 'voto_lista_cdirectivo', 'lista_cdirectivo', 3);
        $this->generar_eleccion_random($fecha, 'voto_lista_rector', 'lista_rector', 4);
        $this->generar_eleccion_random($fecha, 'voto_lista_decano', 'lista_decano', 5);
        $this->generar_eleccion_random($fecha, 'voto_lista_decano', 'lista_decano', 6);
        $this->actualizarMesas($fecha);
    }

    public function eleccion_a_0($fecha) {
        //borro todos los votos de las actas de la eleccion
        $categorias = array('csuperior', 'cdirectivo', 'rector', 'decano');
        foreach ($categorias as $categoria) {
            $sql = "delete from voto_lista_$categoria
where id_acta in
(select a.id_acta from acta a inner join mesa m on a.de=m.id_mesa
where m.fecha='$fecha')";
            $datos = toba::db('gu_kena')->consultar($sql);
        }

        $sql = "update acta set total_votos_blancos=0, total_votos_nulos=0, total_votos_recurridos=0
        
where id_acta in (
select a.id_acta from acta a inner join mesa m on a.de=m.id_mesa
where m.fecha='$fecha')";
        $datos = toba::db('gu_kena')->consultar($sql);

        $sql = "update mesa set estado=-1
           where fecha='$fecha'";
        $datos = toba::db('gu_kena')->consultar($sql);



        //actualizo todos los blancos nulos y recurriodos de las actas de la eleccion
    }

    public function generar_eleccion_random($fecha, $tabla_voto, $tabla_lista, $id_tipo) {

        //Recorro todas las actas de una categoria
        $sql = "select a.id_acta,a.id_tipo,s.id_ue,m.id_mesa,m.id_claustro,m.cant_empadronados from
acta a inner join mesa m on a.de=m.id_mesa
     inner join sede s on a.id_sede=s.id_sede
where m.fecha='$fecha' and a.id_tipo=$id_tipo";
        $datos = toba::db('gu_kena')->consultar($sql);

        //por cada acta busco listas para el caso y le doy valores random dentro
        foreach ($datos as $key => $acta) {
            $listas = $this->buscoListas($acta, $fecha, $tabla_lista);
            $empadronados = $acta['cant_empadronados'];
            shuffle($listas);
            foreach ($listas as $lista) {
                $votos_lista = rand(0, $empadronados);
                $empadronados-=$votos_lista;
                $this->insertoVotos($acta,$tabla_voto, $lista, $votos_lista);
            }
            $votos_blancos = rand(0, $empadronados);
            $empadronados-=$votos_blancos;
            $votos_nulos = rand(0, $empadronados);
            $empadronados-=$votos_nulos;
            $votos_recurridos = rand(0, $empadronados);
            $empadronados-=$votos_recurridos;
            $this->actualizaActa($acta, $votos_blancos + $empadronados, $votos_nulos, $votos_recurridos);
        }
        
    }

    function buscoListas($acta, $fecha, $tabla_lista) {
        if ($acta['id_tipo'] == 1) {
            $where = ' and id_claustro=' . $acta['id_claustro'];
        } elseif ($acta['id_tipo'] == 2 or $acta['id_tipo'] == 3) {
            $where = ' and id_claustro=' . $acta['id_claustro'] . ' and id_ue=' . $acta['id_ue'];
        } elseif ($acta['id_tipo'] == 4) {
            $where = '';
        } elseif ($acta['id_tipo'] == 5 or $acta['id_tipo'] == 6) {
            $where = ' and id_ue=' . $acta['id_ue'];
        }
        $sql = "select * from
               $tabla_lista
                   where fecha='$fecha' $where";
        return toba::db('gu_kena')->consultar($sql);
    }

    function insertoVotos($acta,$tabla_voto, $lista, $votos_lista) {

        $sql = "insert into
               $tabla_voto
                   Values (" . $acta['id_acta'] . "," . $lista['id_nro_lista'] . ",$votos_lista)";
        return toba::db('gu_kena')->consultar($sql);
    }

    function actualizaActa($acta, $votos_blancos, $votos_nulos, $votos_recurridos) {

        $sql = "update
               acta
               set total_votos_blancos=$votos_blancos, total_votos_nulos=$votos_nulos, total_votos_recurridos=$votos_recurridos
               where id_acta=".$acta['id_acta'];
        return toba::db('gu_kena')->consultar($sql);
    }

    function actualizarMesas($fecha) {
        $sql = "update mesa set estado=2
           where fecha='$fecha'";
        return toba::db('gu_kena')->consultar($sql);
    }

}

new Testing('2018-05-22');