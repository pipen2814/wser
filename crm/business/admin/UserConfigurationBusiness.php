<?php

namespace crm\business\admin;

use crm\factories\Managers;
use php\exceptions\Exception;
use php\Hashtable;
use php\Session;

/**
 * Class UserConfigurationBusiness
 *
 * Clase encargada de gestionar la lógica del administrador de datos de configuración del usuario.
 *
 * @package crm
 * @subpackage business
 * @subpackage admin
 */
class UserConfigurationBusiness extends \php\business\BaseBusiness {

	/**
	 * Aquí definiremos todos los datos que pueden venirnos en la configuración del crm del usuario.
	 */
	const CRM_CONFIGURATION_DATA = ['agendaView'];


	/****************
	 * PERSONALINFO *
	 ****************/


	/********************
	 * CRMCONFIGURATION *
	 ********************/

	/**
	 * @var null|int $agendaView null o valor del id del tipo de vista de la agenda.
	 */
	protected $agendaView = null;




	/**
	 * Método encargado de gestionar los datos que recibimos para actualizarlos.
	 *
	 * @param Hashtable $data Aquí vendrán los datos que guardaremos.
	 *
	 */
	public function saveUserData(Hashtable $data){

		// Comprobamos que tenga el parámetro de selección.
		if (!$data->has('crmConfiguration')){
			throw new Exception('Required Param. Missing');
			return;
		}


		// Dependiendo del valor que venga en el dato: crmConfiguration redirigiremos al
		// método encargado de gestionar esa parte del menú de administración.

		switch ($data->crmConfiguration){

			case 'crmConfiguration':
				$this->saveCrmConfigurationData($data);

				break;

			default:
				throw new Exception('Save method not defined. Go to UserConfigurationBusiness please...');

				break;
		}
	}

	/**
	 * Método encargado de guardar la información sobre la configuración del CRM para ese usuario.
	 *
	 * @param Hashtable $data Datos a guardar
	 */
	protected function saveCrmConfigurationData(Hashtable $data){

		// Cargamos los datos.
		$this->loadGenericData($data, self::CRM_CONFIGURATION_DATA);

		// Ahora a montar el envío de datos al manager.
		$saveData = new Hashtable();
		foreach (self::CRM_CONFIGURATION_DATA as $cdata){
			if (isset($this->{$cdata}) && !is_null($this->{$cdata})){
				$saveData->{$cdata} = $this->{$cdata};
			}
		}

		$userId = Session::getSessionVar('idUsuario');

		Managers::UserConfigurationManager(array('extraNS' => 'admin'))->saveCrmConfigurationData($saveData, $userId);

	}

	/**
	 * Método genérico para cargar los datos que se van a guardar.
	 *
	 * @param Hashtable $data Datos a guardar
	 * @param array $allowedParams constante definida con los parámetros posibles.
	 */
	protected function loadGenericData(Hashtable $data, array $allowedParams){

		foreach ($data as $key => $val) {
			if (in_array($key, $allowedParams)){

				if (!property_exists($this, $key)){
					throw new Exception('UserConfigurationBusiness needs this as attribute: \'protected $'.$key.' = null;\'');
				}

				$this->{$key} = $val;
			}
		};

	}

}