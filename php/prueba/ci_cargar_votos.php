<?php

class ci_cargar_votos extends toba_ci {

    protected $s__actual_d; //acta del directivo
    protected $s__sede;
    protected $s__es_nuevo;
    protected $datos = array(); //para los efs dinamicos
    protected $datos2 = array(); //para los efs dinamicos 
    protected $s__colapsar; //variable utilizada para colapsar y descolapsar el formulario form_votos (votos de la lista superior)
    protected $s__ue;
    protected $s__resultados = null;
    protected $s__tipo;
    protected $total_val;

    function total_validos() {
        return $this->total_val;
    }

    function ini() {//inicializa el formulario form_votos
        $this->total_val = 0;
        //print_r("UE al inicio");
        //print_r($this->s__ue);
        //print_r("tipo: " . $this->s__tipo);
        $this->s__colapsar = ''; //para que aparezca colapsado
        $clave = array('componente' => '3021', 'proyecto' => 'ccomputos'); //form_votos
        $metadatos = toba_cargador::instancia()->get_metadatos_extendidos($clave, 'toba_ei_formulario');
        $sql="";
        if (isset($this->s__tipo)) {
            if ($this->s__tipo == 'superior') {
                $sql = "select * from lista_csuperior order by nombre"; //recupero todas las listas del consejo superior
            } else {
                if ($this->s__tipo == 'directivo') {
                    if (isset($this->s__ue)) {
                        //recupero las listas del consejo directivo de la ue correspondiente
                        $sql = "select * from lista_cdirectivo where id_ue=" . $this->s__ue . " order by nombre";
                    }
                }
            }
            if ($sql!=""){
            $resultado = toba::db('ccomputos')->consultar($sql);
            //luego genero el formulario con los efs correspondientes a las listas 
            //print_r($resultado);
            $tot = count($resultado);
            $i = 0;
            $datos = array();
            $efs = array();
            foreach ($resultado as $valor) {//para cada lista del cons superior
                $nombre = utf8_decode($valor['nombre']); //obtengo nombre de la lista para colocar en la etiqueta
                $id_lista = $valor['id_nro_lista']; //obtengo id de la lista para colocar como nombre de la columna
                $ident = 'nuevo_ef' . $i;
                $nuevo_ef = array(
                    'identificador' => $ident,
                    'columnas' => $id_lista,
                    'obligatorio' => 1,
                    'elemento_formulario' => 'ef_editable',
                    'etiqueta' => $nombre,
                    'descripcion' => 'Lista del Consejo Superior',
                    'inicializacion' => '0',
                    'colapsado' => 0,
                    'oculto_relaja_obligatorio' => 0,
                );
                $efs[$i] = $nuevo_ef;
                $i = $i + 1;

                //me fijo si esa lista para ese acta tiene votos
                if ($this->s__tipo == "superior") {
                    if (isset($this->s__resultados[0]['id_acta'])) {
                        $consulta = "select cant_votos from  lista_csuperior t_l  LEFT JOIN voto_lista_csuperior t_v ON t_l.id_nro_lista=t_v.id_lista where t_l.id_nro_lista =" . $valor['id_nro_lista'] . " and t_v.id_acta=" . $this->s__resultados[0]['id_acta'];
                        $res = toba::db('ccomputos')->consultar($consulta);
                        if (count($res) > 0) {//si hay resultados
                            $datos[$id_lista] = $res[0]['cant_votos'];
                            $this->total_val+=$res[0]['cant_votos'];
                        } else {
                            $datos[$id_lista] = '0';
                        }
                    } else {
                        $datos[$id_lista] = '0';
                    }
                }
                if ($this->s__tipo == "directivo") {
                    if (isset($this->s__resultados[1]['id_acta'])) {
                        $consulta = "select cant_votos from  lista_cdirectivo t_l  LEFT JOIN voto_lista_cdirectivo t_v ON t_l.id_nro_lista=t_v.id_lista where t_l.id_nro_lista =" . $valor['id_nro_lista'] . " and t_v.id_acta=" . $this->s__resultados[1]['id_acta'];
                        $res = toba::db('ccomputos')->consultar($consulta);
                        if (count($res) > 0) {//si hay resultados
                            $datos[$id_lista] = $res[0]['cant_votos'];
                            $this->total_val+=$res[0]['cant_votos'];
                        } else {
                            $datos[$id_lista] = '0';
                        }
                    } else {
                        $datos[$id_lista] = '0';
                    }
                }
            }
            $metadatos['_info_formulario_ef'] = array();

            $i = 0;
            while ($i < $tot) {
                $metadatos['_info_formulario_ef'][$i] = $efs[$i];
                $i = $i + 1;
            }

            //-----------elementos de la lista del directivo
            /* print_r("UE al inicio");
              print_r($this->s__ue);
              print_r($i);
              if (isset($this->s__ue)){
              //recupero las listas del consejo directivo de la ue correspondiente
              $sql="select * from lista_cdirectivo where id_ue=".$this->s__ue." order by nombre";
              //luego genero el formulario con los efs correspondientes a las listas
              $resultado=toba::db('ccomputos')->consultar($sql);
              $tot2=$tot+count($resultado);
              $j=$i;

              $efs=array();
              foreach ($resultado as $val) {//para cada lista del cons superior
              $nombre=  utf8_decode($val['nombre']);//obtengo nombre de la lista para colocar en la etiqueta
              $id_lista=$val['id_nro_lista'];//obtengo id de la lista para colocar como nombre de la columna
              $colum=$id_lista.'d';
              $ident='nuevo_ef'.$j;
              $nuevo_ef = array(
              'identificador'  => $ident,
              'columnas' => $colum ,
              'obligatorio' => 1,
              'elemento_formulario' => 'ef_editable',
              'etiqueta' => $nombre,
              'descripcion' => 'Lista del Consejo Directivo',
              'inicializacion' => '0',
              'colapsado' => 0,
              'oculto_relaja_obligatorio' => 0,

              );
              $efs[$j]=$nuevo_ef;
              $j++;
              //me fijo si esa lista para ese acta tiene votos
              if(isset($this->s__actual_d)){
              $consulta="select cant_votos from  lista_cdirectivo t_l  LEFT JOIN voto_lista_cdirectivo t_v ON t_l.id_nro_lista=t_v.id_lista where t_l.id_nro_lista =".$val['id_nro_lista']." and t_v.id_acta=".$this->s__actual_d['id_acta'];
              $res=toba::db('ccomputos')->consultar($consulta);
              if (count($res)>0){//si hay resultados
              $datos[$colum]=$res[0]['cant_votos'];
              }else{$datos[$colum]='0';}
              }else{
              $datos[$colum]='0';
              }
              }


              $j=$i;
              while($j<$tot2){
              $metadatos['_info_formulario_ef'][$j] = $efs[$j];
              $j++;
              }
             */
            $this->datos = $datos;
            toba_cargador::instancia()->set_metadatos_extendidos($metadatos, $clave);
        }
        }
    }

    function conf__form_inicial(toba_ei_formulario $form) {
        $this->pantalla()->tab("pant_superior")->desactivar();
        $this->pantalla()->tab("pant_directivo")->desactivar();
    }

    /**
     * Atrapa la interacci�n del usuario con el bot�n asociado
     * @param array $datos Estado del componente al momento de ejecutar el evento. El formato es el mismo que en la carga de la configuraci�n
     */
    function evt__form_inicial__modificacion($datos) {
        $this->s__ue = $datos['id_nro_ue'];
        $this->s__tipo = "superior";
        print_r("Al presionar boton");
        print_r($this->s__ue);

        $id_sede = $datos['id_sede'];
        $this->s__sede = $datos['id_sede'];
        //busca el acta de la sede seleccionada previamente
        $sql = "select * from acta where id_sede=" . $id_sede . " order by id_tipo";
        $resultado = toba::db('ccomputos')->consultar($sql);

        //hay un acta por cada sede (particularmente en este caso). 
        if (count($resultado) <= 0) {// si la sede seleccionada previamente no tiene actas entonces la cargo
            //siempre cargo  las dos actas
            $sql1 = "insert into acta ( total_votos_blancos, total_votos_nulos, total_votos_recurridos,id_sede, id_tipo) values(0,0,0," . $datos['id_sede'] . ",1);";
            toba::db('ccomputos')->consultar($sql1);
            $sql2 = "insert into acta ( total_votos_blancos, total_votos_nulos, total_votos_recurridos,id_sede, id_tipo) values(0,0,0," . $datos['id_sede'] . ",2);";
            toba::db('ccomputos')->consultar($sql2);
            $this->s__resultados = array(
                '0' => array('total_votos_blancos' => 0, 'total_votos_nulos' => 0, 'total_votos_recurridos' => 0, 'id_sede' => $datos['id_sede'], 'id_tipo' => 1),
                '1' => array('total_votos_blancos' => 0, 'total_votos_nulos' => 0, 'total_votos_recurridos' => 0, 'id_sede' => $datos['id_sede'], 'id_tipo' => 2)
            );
        } else {//recupero los datos de las actas que ya estan. Si estan seguro estan las 2
            $this->s__resultados = $resultado;
        }

        $this->set_pantalla("pant_superior");
    }

    //formulario para mostrar el acta tipo superior
    function conf__form_votos(toba_ei_formulario $form) {
        $this->pantalla()->tab("pant_inicial")->desactivar();
        $this->pantalla()->tab("pant_directivo")->desactivar();

        $sql = "select * from acta where id_sede=" . $this->s__sede . " order by id_tipo";
        $this->s__resultados = toba::db('ccomputos')->consultar($sql);

        if (isset($this->s__resultados)) {
            $form->set_datos($this->s__resultados[0]); //superior    
            //$this->s__acta = array('id_acta' => $this->s__resultados[0]['id_acta']);
        } else {//no estaba cargada entonces muestro todo en 0
            $datos = array();
            $datos['id_sede'] = $this->s__sede;
            $datos['id_tipo'] = 1; //superior
            $datos['total_votos_blancos'] = 0;
            $datos['total_votos_nulos'] = 0;
            $datos['total_votos_recurridos'] = 0;
            $form->set_datos($datos);
        }
    }

    //boton mostrar mas
    //actualiza los datos del acta
    function evt__form_votos__modificacion($datos) {
        $this->s__colapsar = "alta";
        //para guardar los cambios en caso en que haga modificaciones
        $sql = "update acta set total_votos_blancos=" . $datos['total_votos_blancos'] . " ,total_votos_nulos=" . $datos['total_votos_nulos'] . ",total_votos_recurridos=" . $datos['total_votos_recurridos'] . " where id_acta=" . $this->s__resultados[0]['id_acta'];
        $res = toba::db('ccomputos')->consultar($sql);
        
    }

    //-----------------------------------------------------------------------------------
    //---- Configuraciones --------------------------------------------------------------
    //-----------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------
    //---- form_vsup --------------------------------------------------------------------
    //-----------------------------------------------------------------------------------
    //formulario con ef dinamicos para mostrar las listas del superior
    function conf__form_vsup(toba_ei_formulario $form) {
        if ($this->s__colapsar == 'alta') {// si presiono el boton seleccion entonces muestra el formulario form_seccion para dar de alta una nueva seccion
            $this->dep('form_vsup')->descolapsar();
        } else {
            $this->dep('form_vsup')->colapsar();
        }
        return $this->datos;
    }

    //boton que guarda todos los votos y ademas guarda modificaciones en las actas por si las hubiera
    function evt__form_vsup__alta($datos) {


        foreach ($datos as $clave => $valor) {
            //Notice: Undefined index: valor in C:\proyectos\toba_2.6.3\php\nucleo\componentes\interface\toba_ei_formulario.php on line 403 Fatal error: Call to a member function validar_estado() on a non-object in C:\proyectos\toba_2.6.3\php\nucleo\componentes\interface\toba_ei_formulario.php on line 403 
            if ($this->s__tipo == 'superior') {//superior
                $sql = "select * from voto_lista_csuperior where id_lista=" . $clave . " and id_acta=" . $this->s__resultados[0]['id_acta'];
                $res = toba::db('ccomputos')->consultar($sql);
                if (count($res) > 0) {//ya estaba lo actualizo
                    $modif = "update voto_lista_csuperior set cant_votos=" . $valor . " where id_lista=" . $clave . " and id_acta=" . $this->s__resultados[0]['id_acta'];
                    toba::db('ccomputos')->consultar($modif);
                } else {//no estaba asi que lo agrego
                    $nuevov = "insert into voto_lista_csuperior(id_acta, id_lista, cant_votos) values(" . $this->s__resultados[0]['id_acta'] . "," . $clave . "," . $valor . ")";
                    toba::db('ccomputos')->consultar($nuevov);
                }
                $this->total_val+=$valor;
            }
            if ($this->s__tipo == 'directivo') {//directivo
                
                $sql = "select * from voto_lista_cdirectivo where id_lista=" . $clave . " and id_acta=" . $this->s__resultados[1]['id_acta'];
                $res = toba::db('ccomputos')->consultar($sql);
                if (count($res) > 0) {//ya estaba lo actualizo
                    $modif = "update voto_lista_cdirectivo set cant_votos=" . $valor . " where id_lista=" . $clave . " and id_acta=" . $this->s__resultados[1]['id_acta'];
                    toba::db('ccomputos')->consultar($modif);
                } else {//no estaba asi que lo agrego
                    $nuevov = "insert into voto_lista_cdirectivo(id_acta, id_lista, cant_votos) values(" . $this->s__resultados[1]['id_acta'] . "," . $clave . "," . $valor . ")";
                    toba::db('ccomputos')->consultar($nuevov);
                }
            }
            $this->s__colapsar = "";
        }
        switch ($this->s__tipo) {
            case "superior":$this->s__tipo = "directivo";
                $this->set_pantalla("pant_directivo");
                break;
            case "directivo":$this->s__tipo = "";
                $this->set_pantalla("pant_inicial");

                break;
        }
    }

    //-----------------------------------------------------------------------------------
    //---- JAVASCRIPT -------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function extender_objeto_js() {
        echo "
		//---- Eventos ---------------------------------------------
		
		{$this->objeto_js}.evt__volver = function()
		{
		}
		//---- Eventos ---------------------------------------------
		
		{$this->objeto_js}.evt__guardar = function()
		{
		}
		";
    }

    //-----------------------------------------------------------------------------------
    //---- form_directivo ---------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_directivo(ccomputos_ei_formulario $form) {
        $this->pantalla()->tab("pant_inicial")->desactivar();
        $this->pantalla()->tab("pant_superior")->desactivar();

        $sql = "select * from acta where id_sede=" . $this->s__sede . " order by id_tipo";
        $this->s__resultados = toba::db('ccomputos')->consultar($sql);
        
        if (isset($this->s__resultados)) {
            $form->set_datos($this->s__resultados[1]); //directivo 
        } else {//no estaba cargada entonces muestro todo en 0
            $datos = array();
            $datos['id_sede'] = $this->s__sede;
            $datos['id_tipo'] = 2; //directivo
            $datos['total_votos_blancos'] = 0;
            $datos['total_votos_nulos'] = 0;
            $datos['total_votos_recurridos'] = 0;
            $form->set_datos($datos);
        }
    }

    function evt__form_directivo__modificacion($datos) {
        
        $this->s__colapsar = "alta";
        //para guardar los cambios en caso en que haga modificaciones
        $sql = "update acta set total_votos_blancos=" . $datos['total_votos_blancos'] . " ,total_votos_nulos=" . $datos['total_votos_nulos'] . ",total_votos_recurridos=" . $datos['total_votos_recurridos'] . " where id_acta=" . $this->s__resultados[1]['id_acta'];
        toba::db('ccomputos')->consultar($sql);
    }

    //-----------------------------------------------------------------------------------
    //---- form_total_sup ---------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_total_sup(ccomputos_ei_formulario $form) {
        if ($this->s__colapsar == 'alta') {// si presiono el boton seleccion entonces muestra el formulario form_seccion para dar de alta una nueva seccion
            $this->dep('form_total_sup')->descolapsar();
        } else {
            $this->dep('form_total_sup')->colapsar();
        }
    }

    function evt__form_total_sup__modificacion($datos) {
        
    }

}

?>