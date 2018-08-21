<?php

namespace crm\controllers\admin;

use crm\CRMController;
use crm\factories\I18N;
use crm\factories\ORM;
use php\exceptions\Exception;
use php\Hashtable;
use php\sql\MySQLResultSet;
use php\sql\ResultSetRow;
use php\util\Date;
use php\util\Paginator;
use php\XMLModel;

/**
 * Class NotificationsAdminController
 *
 * Admin de notificaciones.
 *
 * @package crm
 * @subpackage controllers
 * @subpackage admin
 */
class NotificationsAdminController extends CRMController  {

	/**
	 * Método para unificar el paginador y la vista de retorno.
	 *
	 * @param XMLModel $model
	 * @param MySQLResultSet $rs
	 *
	 * @return strings Vista.
	 */
	protected function paginateAndReturnView(XMLModel $model, $rs){

		$p = new Paginator( $model, $rs );

		$p->addHiddenFields('fecha_hora_creacion');
		$p->addHiddenFields('fecha_hora_modificacion');

		$p->addRowButton('Eliminar','delete','id_notificacion','deleteNotification(this.id);');
		$p->addRowButton('Editar','edit','id_notificacion','editNotification(this.id);');

		$p->run();

		return 'notifications_admin';
	}
	/**
	 * Método principal del admin de notificaciones.
	 *
	 * @app
	 *
	 * @param XMLModel $model
	 * @param Hashtable $args
	 */
	public function defaultAction(XMLModel $model, Hashtable $args){

		$notifications = ORM::Notificaciones()->getAll();

		return $this->paginateAndReturnView($model, $notifications);
	}

	/**
	 * Método encargado de buscar entre las notificaciones.
	 *
	 * @app
	 *
	 * @param XMLModel $model
	 * @param Hashtable $args
	 */
	public function searchAction(XMLModel $model, Hashtable $args) {

		$notification = ORM::Notificaciones()->searchLike($args->busqueda);
		$req = $model->addChild('request');
		$req['string'] = $args->busqueda;

		return $this->paginateAndReturnView($model, $notification);
	}

	/**
	 * Método que se encarga de borrar de base de datos un registro de notificaciones.
	 *
	 * @api
	 *
	 * @param XMLModel $model
	 * @param Hashtable $args
	 */
	public function deleteAction( XMLModel $model, Hashtable $args ){
		ORM::NotificacionesRelaciones()->deleteByNotificationId($args->deletedId);
		ORM::Notificaciones()->deleteById($args->deletedId);
	}

	/**
	 * Método que se encarga de editar una notificacion
	 *
	 * @app
	 *
	 * @param XMLModel $model
	 * @param Hashtable $args
	 */
	public function editAction( XMLModel $model, Hashtable $args ){
		if (!$args->has('id')){
			throw new Exception('Required param \'id\' not found');
		}

		$notification = ORM::Notificaciones()->getByPK($args->id);

		$notificationModel = $model->addChild('notification');

		$notificationModel->appendORMAsAttributes($notification);

		$startDate = new Date($notification->fechaInicio);
		$endDate = new Date($notification->fechaFin);

		$notificationModel['startDateFormat'] = $startDate->formatDate(Date::FORMAT_INMOGESTOR_CALENDAR);
		$notificationModel['endDateFormat'] = $endDate->formatDate(Date::FORMAT_INMOGESTOR_CALENDAR);

		// Recuperar todas las posibles relaciones.


		return "single_notification";
	}

	/**
	 * Método que se encarga de crear una nueva notificacion
	 *
	 * @app
	 *
	 * @param XMLModel $model
	 * @param Hashtable $args
	 */
	public function newAction( XMLModel $model, Hashtable $args ){

		// Recuperamos las organizaciones:
		$organizations = ORM::Organizaciones()->getAll();

		$orgModel = $model->addChild('organizations');
		$orgModel->addRS($organizations);

		$prioModel = $model->addChild('priorities');
		$prioModel->addI18N(I18N::NotificationPriorityI18N());

		return "single_alert";
	}

	/**
	 * Método que se encarga de guardar una alerta con sus relaciones con las organizaciones.
	 *
	 * @app
	 *
	 * @param XMLModel $model
	 * @param Hashtable $args
	 */
	public function saveAction( XMLModel $model, Hashtable $args ){

		$update = false;

		if ($args->has('alertId')){
			$orm = ORM::Alertas()->getByPK($args->alertId);
		}else{
			$orm = ORM::Alertas();
		}

		if ($args->has('alertName')) {
			$update = true;
			$orm->nombre = utf8_decode($args->alertName);
		}

		if ($args->has('alertDescription')) {
			$update = true;
			$orm->descripcion = utf8_decode($args->alertDescription);
		}

		if ($args->has('alertPriority')) {
			$update = true;
			$orm->prioridad = $args->alertPriority;
		}

		if ($args->has('AlertStartDate')) {
			$startDate = new Date($args->AlertStartDate);
			$update = true;
			$orm->fechaInicio =$startDate->formatDate(Date::FORMAT_SQL_DATETIME);
		}

		if ($args->has('AlertEndDate')) {
			$endDate = new Date($args->AlertEndDate);
			$update = true;
			$orm->fechaFin = $endDate->formatDate(Date::FORMAT_SQL_DATETIME);
		}

		if ( !$args->has('alertId') || $update ) $orm->save();

		// SI VENIMOS DE EDITAR, ANTES DE HACER ESTO, NOS FUFAMOS LAS ALERTAS_ORGANIZACIONES DE ESTE ID.
		if ($args->has('organizations')){
			foreach ( $args->organizations as $org ){
				$ao = ORM::AlertasOrganizaciones();
				$ao->idAlerta = $orm->idAlerta;
				$ao->idOrganizacion = $org;
				$ao->save();
			}
		}

		$model['close_and_reload'] = 1;
		if ($args->has('alertId')){
			$alert = ORM::Alertas()->getByPK($args->alertId);

			$alertModel = $model->addChild('alert');

			$alertModel->appendORMAsAttributes($alert);

			return "single_alert";
		}else{
			return "single_alert";
		}
	}
}
