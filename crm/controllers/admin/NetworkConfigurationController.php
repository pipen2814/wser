<?php

namespace crm\controllers\admin;


use crm\CRMController;
use php\Hashtable;
use php\XMLModel;

/**
 * Class NetworkConfiguration
 *
 * Clase encargada de configurar todos los datos de la red.
 *
 * @package crm
 * @subpackage controllers
 * @subpackage admin
 */
class NetworkConfigurationController extends CRMController {

	/**
	 * Método principal para mostrar todos los datos de configuración de la red.
	 *
	 * @app
	 *
	 * @param XMLModel $model Modelo del admin.
	 * @param Hashtable $args Argumentos que se le pasa.
	 */
	public function defaultAction(XMLModel $model, Hashtable $args){
		return 'network_configuration';
	}
}