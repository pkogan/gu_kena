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
		'ci_claustros' => 'actas/ci_claustros.php',
		'ci_confirmar' => 'actas/ci_confirmar.php',
		'ci_consejeros_directivos' => 'consejeros_directivos/ci_consejeros_directivos.php',
		'ci_principal' => 'consejeros_directivos/ci_principal.php',
		'form_unidad' => 'consejeros_directivos/form_unidad.php',
		'ci_consejeros_superior' => 'consejeros_superior/ci_consejeros_superior.php',
		'cuadro_e' => 'consejeros_superior/cuadro_e.php',
		'dt_acta' => 'datos/dt_acta.php',
		'dt_claustro' => 'datos/dt_claustro.php',
		'dt_estado' => 'datos/dt_estado.php',
		'dt_lista_cdirectivo' => 'datos/dt_lista_cdirectivo.php',
		'dt_lista_csuperior' => 'datos/dt_lista_csuperior.php',
		'dt_mesa' => 'datos/dt_mesa.php',
		'dt_sede' => 'datos/dt_sede.php',
		'dt_tipo' => 'datos/dt_tipo.php',
		'dt_unidad_electoral' => 'datos/dt_unidad_electoral.php',
		'dt_voto_lista_cdirectivo' => 'datos/dt_voto_lista_cdirectivo.php',
		'dt_voto_lista_csuperior' => 'datos/dt_voto_lista_csuperior.php',
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
		'gu_kena_toba_ei_cuadro_salida_excel' => 'extension_toba/componentes/gu_kena_toba_ei_cuadro_salida_excel.php',
		'gu_kena_comando' => 'extension_toba/gu_kena_comando.php',
		'gu_kena_modelo' => 'extension_toba/gu_kena_modelo.php',
		'gu_kena_autoload' => 'gu_kena_autoload.php',
		'ci_login' => 'login/ci_login.php',
		'cuadro_autologin' => 'login/cuadro_autologin.php',
		'pant_login' => 'login/pant_login.php',
		'ci_mesa' => 'mesa/ci_mesa.php',
		'form_datos' => 'mesa/form_datos.php',
		'form_ml_directivo' => 'mesa/form_ml_directivo.php',
		'form_ml_extra' => 'mesa/form_ml_extra.php',
		'form_ml_superior' => 'mesa/form_ml_superior.php',
		'ci_cargar_votos' => 'prueba/ci_cargar_votos.php',
		'tp_gu_kena' => 'tp_gu_kena.php',
		'ci_validar' => 'validar/ci_validar.php',
	);
}
?>