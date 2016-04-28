<?php
/**
 * Esta clase fue y será generada automáticamente. NO EDITAR A MANO.
 * @ignore
 */
class gu_kena_autoload 
{
	static function existe_clase($nombre)
	{
		return isset(self::$clases[$nombre]);
	}

	static function cargar($nombre)
	{
		if (self::existe_clase($nombre)) { 
			 require_once(dirname(__FILE__) .'/'. self::$clases[$nombre]); 
		}
	}

	static protected $clases = array(
		'gu_kena_ci' => 'extension_toba/componentes/gu_kena_ci.php',
		'gu_kena_cn' => 'extension_toba/componentes/gu_kena_cn.php',
		'gu_kena_datos_relacion' => 'extension_toba/componentes/gu_kena_datos_relacion.php',
		'gu_kena_datos_tabla' => 'extension_toba/componentes/gu_kena_datos_tabla.php',
		'gu_kena_ei_arbol' => 'extension_toba/componentes/gu_kena_ei_arbol.php',
		'gu_kena_ei_archivos' => 'extension_toba/componentes/gu_kena_ei_archivos.php',
		'gu_kena_ei_calendario' => 'extension_toba/componentes/gu_kena_ei_calendario.php',
		'gu_kena_ei_codigo' => 'extension_toba/componentes/gu_kena_ei_codigo.php',
		'gu_kena_ei_cuadro' => 'extension_toba/componentes/gu_kena_ei_cuadro.php',
		'gu_kena_ei_esquema' => 'extension_toba/componentes/gu_kena_ei_esquema.php',
		'gu_kena_ei_filtro' => 'extension_toba/componentes/gu_kena_ei_filtro.php',
		'gu_kena_ei_firma' => 'extension_toba/componentes/gu_kena_ei_firma.php',
		'gu_kena_ei_formulario' => 'extension_toba/componentes/gu_kena_ei_formulario.php',
		'gu_kena_ei_formulario_ml' => 'extension_toba/componentes/gu_kena_ei_formulario_ml.php',
		'gu_kena_ei_grafico' => 'extension_toba/componentes/gu_kena_ei_grafico.php',
		'gu_kena_ei_mapa' => 'extension_toba/componentes/gu_kena_ei_mapa.php',
		'gu_kena_servicio_web' => 'extension_toba/componentes/gu_kena_servicio_web.php',
		'gu_kena_comando' => 'extension_toba/gu_kena_comando.php',
		'gu_kena_modelo' => 'extension_toba/gu_kena_modelo.php',
	);
}
?>