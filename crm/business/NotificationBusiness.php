<?php

namespace crm\business;

use crm\factories\Managers;
use crm\factories\ORM;
use crm\util\notifications\Alert;
use crm\util\notifications\BaseNotification;
use php\business\BaseBusiness;
use php\Hashtable;

/**
 * Class NotificationBusiness
 *
 * Clase encargada de recuperar todas las notificaciones y devolverlas al controlador para
 * mostrárselas al usuario.
 *
 * @package crm
 * @subpackage business
 */
class NotificationBusiness extends BaseBusiness {

	/**
	 * @var array Array de alertas.
	 */
	protected $alerts = array();

	/**
	 * @var array Array de mensajes del sistema.
	 */
	protected $systemMessages = array();

	/**
	 * @var array Array de notificaciones.
	 */
	protected $notifications = array();

	/**
	 * Método con el que cargaremos todas las alertas de organización.
	 *
	 * @param Hashtable $params
	 */
	protected function loadAlerts( Hashtable $params ){

		$rs = Managers::NotificationManager()->listAlertsByFilters($params);

		while ($rs->next()){
			$alertORM = ORM::Alertas()->hydrate($rs);

			$alertObject = new Alert($alertORM);
			$this->alerts[] = $alertObject;
		}
	}

	/**
	 * Método que se encarga de generar todas las alertas de sistema.
	 *
	 * @param Hashtable $params
	 */
	protected function loadSystemMessages( Hashtable $params ){

	}

	/**
	 * Método con el que cargamos las notificaciones propias de la organización.
	 *
	 * @param Hashtable $params
	 */
	protected function loadNotifications( Hashtable $params ){

		Managers::NotificationManager()->listNotificationByFilters($params);
	}

	/**
	 * Método con el que cargamos todos los tipos de notificaciones que haya.
	 *
	 * @param Hashtable $params
	 */
	protected function load( Hashtable $params ){

		$this->loadAlerts( $params );
		$this->loadNotifications( $params );
	}

	/**
	 * Método encargado de exportar todas las notificaciones a un único array ordenado por prioridad.
	 *
	 * @return array
	 */
	protected function exportData(){

		$arr = array_merge($this->alerts, $this->systemMessages, $this->notifications);

		usort ($arr, function($a, $b){
			return strcmp($a->getPriority(), $b->getPriority());
		});

		return $arr;
	}
	/**
	 * Método que se encarga de devolver todas las notificaciones.
	 *
	 * @param Hashtable $params
	 *
	 * @return array
	 */
	public function getNotifications( Hashtable $params ){
		$this->load($params);


		return $this->exportData();
	}
}