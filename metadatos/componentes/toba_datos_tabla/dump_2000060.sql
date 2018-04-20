------------------------------------------------------------
--[2000060]--  DT - lista_decano 
------------------------------------------------------------

------------------------------------------------------------
-- apex_objeto
------------------------------------------------------------

--- INICIO Grupo de desarrollo 2
INSERT INTO apex_objeto (proyecto, objeto, anterior, identificador, reflexivo, clase_proyecto, clase, punto_montaje, subclase, subclase_archivo, objeto_categoria_proyecto, objeto_categoria, nombre, titulo, colapsable, descripcion, fuente_datos_proyecto, fuente_datos, solicitud_registrar, solicitud_obj_obs_tipo, solicitud_obj_observacion, parametro_a, parametro_b, parametro_c, parametro_d, parametro_e, parametro_f, usuario, creacion, posicion_botonera) VALUES (
	'gu_kena', --proyecto
	'2000060', --objeto
	NULL, --anterior
	NULL, --identificador
	NULL, --reflexivo
	'toba', --clase_proyecto
	'toba_datos_tabla', --clase
	'10000003', --punto_montaje
	'dt_lista_decano', --subclase
	'datos/dt_lista_decano.php', --subclase_archivo
	NULL, --objeto_categoria_proyecto
	NULL, --objeto_categoria
	'DT - lista_decano', --nombre
	NULL, --titulo
	NULL, --colapsable
	NULL, --descripcion
	'gu_kena', --fuente_datos_proyecto
	'gu_kena', --fuente_datos
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
	'2017-06-25 00:32:48', --creacion
	NULL  --posicion_botonera
);
--- FIN Grupo de desarrollo 2

------------------------------------------------------------
-- apex_objeto_db_registros
------------------------------------------------------------
INSERT INTO apex_objeto_db_registros (objeto_proyecto, objeto, max_registros, min_registros, punto_montaje, ap, ap_clase, ap_archivo, tabla, tabla_ext, alias, modificar_claves, fuente_datos_proyecto, fuente_datos, permite_actualizacion_automatica, esquema, esquema_ext) VALUES (
	'gu_kena', --objeto_proyecto
	'2000060', --objeto
	NULL, --max_registros
	NULL, --min_registros
	'10000003', --punto_montaje
	'1', --ap
	NULL, --ap_clase
	NULL, --ap_archivo
	'lista_decano', --tabla
	NULL, --tabla_ext
	NULL, --alias
	'0', --modificar_claves
	'gu_kena', --fuente_datos_proyecto
	'gu_kena', --fuente_datos
	'1', --permite_actualizacion_automatica
	'public', --esquema
	'public'  --esquema_ext
);

------------------------------------------------------------
-- apex_objeto_db_registros_col
------------------------------------------------------------

--- INICIO Grupo de desarrollo 2
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'gu_kena', --objeto_proyecto
	'2000060', --objeto
	'2000071', --col_id
	'id_nro_lista', --columna
	'E', --tipo
	'1', --pk
	'lista_decano_id_nro_lista_seq', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'1', --no_nulo_db
	'0', --externa
	'lista_decano'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'gu_kena', --objeto_proyecto
	'2000060', --objeto
	'2000072', --col_id
	'id_ue', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'lista_decano'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'gu_kena', --objeto_proyecto
	'2000060', --objeto
	'2000073', --col_id
	'nombre', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'90', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'lista_decano'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'gu_kena', --objeto_proyecto
	'2000060', --objeto
	'2000074', --col_id
	'fecha', --columna
	'F', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'lista_decano'  --tabla
);
--- FIN Grupo de desarrollo 2
