#!/bin/bash

#script actualizacion json

cd /home/pkogan/Proyectos/toba/toba_2.7.4
. entorno_toba.env
cd proyectos/gu_kena/www
toba item ejecutar -p gu_kena -t 3790
