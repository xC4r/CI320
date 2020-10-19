<?php
/*
|--------------------------------------------------------------------------
| Constantes GLOBALES para el Proyecto
|--------------------------------------------------------------------------
*/
if(!function_exists('GET_STATE'))
{
  function GET_STATE($code)
  {
  	switch ($code) {
    case 01:
        return 'Activo';
        break;
    case 02:
        return 'Inactivo';
        break;
    case 03:
        return 'Suspendido';
        break;
    default:
    	return 'Desconocido';
    	break;
	}
  }
}

if(!function_exists('GET_INDDEL'))
{
  function GET_INDDEL($code)
  {
    switch ($code) {
    case 0:
        return 'No Eliminado';
        break;
    case 1:
        return 'Eliminado';
        break;
    default:
      return 'Desconocido';
      break;
    }
  }
}