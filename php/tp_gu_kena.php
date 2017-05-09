<?php
/**
	* Tipo de p�gina pensado para pantallas de login, presenta un logo y un pie de p�gina b�sico
	* 
	* @package SalidaGrafica
	*/
class tp_gu_kena extends toba_tp_basico
{
	function barra_superior()
	{
		echo "
			<style type='text/css'>
				.cuerpo {
					
				}
			</style>
		";
		echo "<div id='barra-superior' class='barra-superior-login'>\n";        
	}    

	function pre_contenido()
	{
		echo "<div class='login-titulo'>". toba_recurso::imagen_proyecto("inicio.png",true);
				echo "<div>2017</div>";
//        echo "<div>".utf8_decode("versión")." ".toba::proyecto()->get_version()."</div>";
				echo "<div><a style='color:blue' href='ord_1386_2013_46.pdf'>Ver ".utf8_decode('Ordenanza N°1386')."</a></div>";
				echo "<div><a style='color:blue' href='instructivo_autoridades_mesa_2017.pdf'>Ver Instructivo</a></div>";
		echo "</div>";                
		echo "<div align='center' class='cuerpo'>\n";        
	}

	function post_contenido()
	{
		echo "</div>";        
		echo "<div class='login-pie'>";
		//echo "<div>Desarrollado por <strong><a href='http://www.siu.edu.ar' style='text-decoration: none' target='_blank'>SIU</a></strong></div>
		//    <div>2002-".date('Y')."</div>";
		echo "</div>";
	}
}
?>