------------------------------------------------------------
--[10000202]--  Consejeros Superior - CI 
------------------------------------------------------------

------------------------------------------------------------
-- apex_objeto
------------------------------------------------------------

--- INICIO Grupo de desarrollo 10
INSERT INTO apex_objeto (proyecto, objeto, anterior, identificador, reflexivo, clase_proyecto, clase, punto_montaje, subclase, subclase_archivo, objeto_categoria_proyecto, objeto_categoria, nombre, titulo, colapsable, descripcion, fuente_datos_proyecto, fuente_datos, solicitud_registrar, solicitud_obj_obs_tipo, solicitud_obj_observacion, parametro_a, parametro_b, parametro_c, parametro_d, parametro_e, parametro_f, usuario, creacion, posicion_botonera) VALUES (
	'gu_kena', --proyecto
	'10000202', --objeto
	NULL, --anterior
	NULL, --identificador
	NULL, --reflexivo
	'toba', --clase_proyecto
	'toba_ci', --clase
	'10000003', --punto_montaje
	'ci_consejeros_superior', --subclase
	'consejeros_superior/ci_consejeros_superior.php', --subclase_archivo
	NULL, --objeto_categoria_proyecto
	NULL, --objeto_categoria
	'Consejeros Superior - CI', --nombre
	NULL, --titulo
	'0', --colapsable
	NULL, --descripcion
	NULL, --fuente_datos_proyecto
	NULL, --fuente_datos
	NULL, --solicitud_registrar
	NULL, --solicitud_obj_obs_tipo
	NULL, --solicitud_obj_observacion
	NULL, --parametro_a
	NULL, --parametro_b
	NULL, --parametro_c
	NULL, --parametro_d
	NULL, --parametro_e
	NULL, --parametro_f
	NULL, --usuario
	'2016-04-26 10:31:49', --creacion
	'arriba'  --posicion_botonera
);
--- FIN Grupo de desarrollo 10

------------------------------------------------------------
-- apex_objeto_eventos
------------------------------------------------------------

--- INICIO Grupo de desarrollo 10
INSERT INTO apex_objeto_eventos (proyecto, evento_id, objeto, identificador, etiqueta, maneja_datos, sobre_fila, confirmacion, estilo, imagen_recurso_origen, imagen, en_botonera, ayuda, orden, ci_predep, implicito, defecto, display_datos_cargados, grupo, accion, accion_imphtml_debug, accion_vinculo_carpeta, accion_vinculo_item, accion_vinculo_objeto, accion_vinculo_popup, accion_vinculo_popup_param, accion_vinculo_target, accion_vinculo_celda, accion_vinculo_servicio, es_seleccion_multiple, es_autovinculo) VALUES (
	'gu_kena', --proyecto
	'10000211', --evento_id
	'10000202', --objeto
	'exportar', --identificador
	'Excel', --etiqueta
	'0', --maneja_datos
	NULL, --sobre_fila
	NULL, --confirmacion
	NULL, --estilo
	'apex', --imagen_recurso_origen
	'extension_excel.png', --imagen
	'1', --en_botonera
	NULL, --ayuda
	'1', --orden
	NULL, --ci_predep
	'0', --implicito
	'0', --defecto
	NULL, --display_datos_cargados
	NULL, --grupo
	'X', --accion
	'0', --accion_imphtml_debug
	NULL, --accion_vinculo_carpeta
	NULL, --accion_vinculo_item
	NULL, --accion_vinculo_objeto
	'0', --accion_vinculo_popup
	NULL, --accion_vinculo_popup_param
	NULL, --accion_vinculo_target
	NULL, --accion_vinculo_celda
	NULL, --accion_vinculo_servicio
	'0', --es_seleccion_multiple
	'0'  --es_autovinculo
);
--- FIN Grupo de desarrollo 10

------------------------------------------------------------
-- apex_objeto_mt_me
------------------------------------------------------------
INSERT INTO apex_objeto_mt_me (objeto_mt_me_proyecto, objeto_mt_me, ev_procesar_etiq, ev_cancelar_etiq, ancho, alto, posicion_botonera, tipo_navegacion, botonera_barra_item, con_toc, incremental, debug_eventos, activacion_procesar, activacion_cancelar, ev_procesar, ev_cancelar, objetos, post_procesar, metodo_despachador, metodo_opciones) VALUES (
	'gu_kena', --objeto_mt_me_proyecto
	'10000202', --objeto_mt_me
	NULL, --ev_procesar_etiq
	NULL, --ev_cancelar_etiq
	'500px', --ancho
	'300px', --alto
	NULL, --posicion_botonera
	'tab_h', --tipo_navegacion
	'1', --botonera_barra_item
	'0', --con_toc
	NULL, --incremental
	NULL, --debug_eventos
	NULL, --activacion_procesar
	NULL, --activacion_cancelar
	NULL, --ev_procesar
	NULL, --ev_cancelar
	NULL, --objetos
	NULL, --post_procesar
	NULL, --metodo_despachador
	NULL  --metodo_opciones
);

------------------------------------------------------------
-- apex_objeto_dependencias
------------------------------------------------------------

--- INICIO Grupo de desarrollo 10
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'gu_kena', --proyecto
	'10000279', --dep_id
	'10000202', --objeto_consumidor
	'10000165', --objeto_proveedor
	'cuadro_dhondt_d', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'gu_kena', --proyecto
	'10000280', --dep_id
	'10000202', --objeto_consumidor
	'10000159', --objeto_proveedor
	'cuadro_dhondt_e', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'gu_kena', --proyecto
	'10000281', --dep_id
	'10000202', --objeto_consumidor
	'10000161', --objeto_proveedor
	'cuadro_dhondt_g', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'gu_kena', --proyecto
	'10000282', --dep_id
	'10000202', --objeto_consumidor
	'10000163', --objeto_proveedor
	'cuadro_dhondt_nd', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'gu_kena', --proyecto
	'10000283', --dep_id
	'10000202', --objeto_consumidor
	'10000166', --objeto_proveedor
	'cuadro_superior_d', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'gu_kena', --proyecto
	'10000284', --dep_id
	'10000202', --objeto_consumidor
	'10000160', --objeto_proveedor
	'cuadro_superior_e', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'gu_kena', --proyecto
	'10000285', --dep_id
	'10000202', --objeto_consumidor
	'10000162', --objeto_proveedor
	'cuadro_superior_g', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'gu_kena', --proyecto
	'10000286', --dep_id
	'10000202', --objeto_consumidor
	'10000164', --objeto_proveedor
	'cuadro_superior_nd', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'gu_kena', --proyecto
	'10000287', --dep_id
	'10000202', --objeto_consumidor
	'10000155', --objeto_proveedor
	'datos', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
--- FIN Grupo de desarrollo 10

--- INICIO Grupo de desarrollo 2
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'gu_kena', --proyecto
	'2000056', --dep_id
	'10000202', --objeto_consumidor
	'2000049', --objeto_proveedor
	'form_mesas_d', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
--- FIN Grupo de desarrollo 2

--- INICIO Grupo de desarrollo 10
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'gu_kena', --proyecto
	'10000288', --dep_id
	'10000202', --objeto_consumidor
	'10000203', --objeto_proveedor
	'form_mesas_e', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'gu_kena', --proyecto
	'10000289', --dep_id
	'10000202', --objeto_consumidor
	'10000204', --objeto_proveedor
	'form_mesas_g', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'gu_kena', --proyecto
	'10000290', --dep_id
	'10000202', --objeto_consumidor
	'10000205', --objeto_proveedor
	'form_mesas_nd', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
--- FIN Grupo de desarrollo 10

------------------------------------------------------------
-- apex_objeto_ci_pantalla
------------------------------------------------------------

--- INICIO Grupo de desarrollo 10
INSERT INTO apex_objeto_ci_pantalla (objeto_ci_proyecto, objeto_ci, pantalla, identificador, orden, etiqueta, descripcion, tip, imagen_recurso_origen, imagen, objetos, eventos, subclase, subclase_archivo, template, template_impresion, punto_montaje) VALUES (
	'gu_kena', --objeto_ci_proyecto
	'10000202', --objeto_ci
	'10000089', --pantalla
	'pant_docente', --identificador
	'4', --orden
	'Docente', --etiqueta
	NULL, --descripcion
	NULL, --tip
	'apex', --imagen_recurso_origen
	NULL, --imagen
	NULL, --objetos
	NULL, --eventos
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --template
	NULL, --template_impresion
	'10000003'  --punto_montaje
);
INSERT INTO apex_objeto_ci_pantalla (objeto_ci_proyecto, objeto_ci, pantalla, identificador, orden, etiqueta, descripcion, tip, imagen_recurso_origen, imagen, objetos, eventos, subclase, subclase_archivo, template, template_impresion, punto_montaje) VALUES (
	'gu_kena', --objeto_ci_proyecto
	'10000202', --objeto_ci
	'10000090', --pantalla
	'pant_estudiantes', --identificador
	'1', --orden
	'Estudiantes', --etiqueta
	NULL, --descripcion
	NULL, --tip
	'apex', --imagen_recurso_origen
	NULL, --imagen
	NULL, --objetos
	NULL, --eventos
	NULL, --subclase
	NULL, --subclase_archivo
	'', --template
	NULL, --template_impresion
	'10000003'  --punto_montaje
);
INSERT INTO apex_objeto_ci_pantalla (objeto_ci_proyecto, objeto_ci, pantalla, identificador, orden, etiqueta, descripcion, tip, imagen_recurso_origen, imagen, objetos, eventos, subclase, subclase_archivo, template, template_impresion, punto_montaje) VALUES (
	'gu_kena', --objeto_ci_proyecto
	'10000202', --objeto_ci
	'10000091', --pantalla
	'pant_graduados', --identificador
	'2', --orden
	'Graduados', --etiqueta
	NULL, --descripcion
	NULL, --tip
	'apex', --imagen_recurso_origen
	NULL, --imagen
	NULL, --objetos
	NULL, --eventos
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --template
	NULL, --template_impresion
	'10000003'  --punto_montaje
);
INSERT INTO apex_objeto_ci_pantalla (objeto_ci_proyecto, objeto_ci, pantalla, identificador, orden, etiqueta, descripcion, tip, imagen_recurso_origen, imagen, objetos, eventos, subclase, subclase_archivo, template, template_impresion, punto_montaje) VALUES (
	'gu_kena', --objeto_ci_proyecto
	'10000202', --objeto_ci
	'10000092', --pantalla
	'pant_no_docente', --identificador
	'3', --orden
	'No Docente', --etiqueta
	NULL, --descripcion
	NULL, --tip
	'apex', --imagen_recurso_origen
	NULL, --imagen
	NULL, --objetos
	NULL, --eventos
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --template
	NULL, --template_impresion
	'10000003'  --punto_montaje
);
--- FIN Grupo de desarrollo 10

------------------------------------------------------------
-- apex_objetos_pantalla
------------------------------------------------------------
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'gu_kena', --proyecto
	'10000089', --pantalla
	'10000202', --objeto_ci
	'0', --orden
	'2000056'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'gu_kena', --proyecto
	'10000089', --pantalla
	'10000202', --objeto_ci
	'2', --orden
	'10000279'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'gu_kena', --proyecto
	'10000089', --pantalla
	'10000202', --objeto_ci
	'1', --orden
	'10000283'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'gu_kena', --proyecto
	'10000090', --pantalla
	'10000202', --objeto_ci
	'2', --orden
	'10000280'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'gu_kena', --proyecto
	'10000090', --pantalla
	'10000202', --objeto_ci
	'1', --orden
	'10000284'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'gu_kena', --proyecto
	'10000090', --pantalla
	'10000202', --objeto_ci
	'0', --orden
	'10000288'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'gu_kena', --proyecto
	'10000091', --pantalla
	'10000202', --objeto_ci
	'2', --orden
	'10000281'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'gu_kena', --proyecto
	'10000091', --pantalla
	'10000202', --objeto_ci
	'1', --orden
	'10000285'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'gu_kena', --proyecto
	'10000091', --pantalla
	'10000202', --objeto_ci
	'0', --orden
	'10000289'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'gu_kena', --proyecto
	'10000092', --pantalla
	'10000202', --objeto_ci
	'2', --orden
	'10000282'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'gu_kena', --proyecto
	'10000092', --pantalla
	'10000202', --objeto_ci
	'1', --orden
	'10000286'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'gu_kena', --proyecto
	'10000092', --pantalla
	'10000202', --objeto_ci
	'0', --orden
	'10000290'  --dep_id
);

------------------------------------------------------------
-- apex_eventos_pantalla
------------------------------------------------------------
INSERT INTO apex_eventos_pantalla (pantalla, objeto_ci, evento_id, proyecto) VALUES (
	'10000089', --pantalla
	'10000202', --objeto_ci
	'10000211', --evento_id
	'gu_kena'  --proyecto
);
INSERT INTO apex_eventos_pantalla (pantalla, objeto_ci, evento_id, proyecto) VALUES (
	'10000090', --pantalla
	'10000202', --objeto_ci
	'10000211', --evento_id
	'gu_kena'  --proyecto
);
INSERT INTO apex_eventos_pantalla (pantalla, objeto_ci, evento_id, proyecto) VALUES (
	'10000091', --pantalla
	'10000202', --objeto_ci
	'10000211', --evento_id
	'gu_kena'  --proyecto
);
INSERT INTO apex_eventos_pantalla (pantalla, objeto_ci, evento_id, proyecto) VALUES (
	'10000092', --pantalla
	'10000202', --objeto_ci
	'10000211', --evento_id
	'gu_kena'  --proyecto
);
