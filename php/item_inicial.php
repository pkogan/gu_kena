<?php
//	echo '<div class="logo">';
//	echo toba_recurso::imagen_proyecto('logo_grande.gif', true);
//	echo '</div>';
$u = toba::manejador_sesiones()->get_perfiles_funcionales();

            if(strcasecmp($u[0], 'junta_electoral') == 0){
                //Accede un participante de la junta electoral, por lo tanto primero tiene que ir 
                    // a la operación confirmar
                toba::vinculador()->navegar_a("",10000045,true);
            }
            else{
                 if(strcasecmp($u[0], 'secretaria') == 0){
                //Accede la secretaria que valida, por lo tanto primero tiene que ir 
                    // a la operación confirmar                
                     toba::vinculador()->navegar_a("",10000045,true);
                 }
                 else{
                      if(strcasecmp($u[0], 'autoridad_mesa') == 0){
                        //Accede una autoridad, por lo tanto primero tiene que ir 
                        // a la operación mesa               
                         toba::vinculador()->navegar_a("",10000044,true);
                     }
                     else{
                         //Accede un miembro externo, por lo tanto solo tiene opción de ver
                         // las operaciones de resultados en cons. superior y directivo
//                         toba::vinculador()->navegar_a("",10000043,true);
                        echo '<div class="logo">';
                        echo toba_recurso::imagen_proyecto('inicio.png', true);
                        echo '</div>';
                     }
                 }
            }
            
        
?>