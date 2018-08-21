<?php

namespace crm\controllers\admin\network;

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
 * Class AlertAdminController
 *
 * Admin de alertas.
 *
 * @package crm
 * @subpackage controllers
 * @subpackage admin
 * @subpackage network
 */
class AlertAdminController extends CRMController  {

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

		$p->addRowButton('Eliminar','delete','id_alerta','deleteAlert(this.id);');
		$p->addRowButton('Editar','edit','id_alerta','editAlert(this.id);');

		$p->run();

		return 'alert_admin';
	}
	/**
	 * Método principal del admin de alertas.
	 *
	 * @app
	 *
	 * @param XMLModel $model
	 * @param Hashtable $args
	 */
	public function defaultAction(XMLModel $model, Hashtable $args){

		$alerts = ORM::Alertas()->getAll();

		return $this->paginateAndReturnView($model, $alerts);
	}

	/**
	 * Método encargado de buscar entre las alertas.
	 *
	 * @app
	 *
	 * @param XMLModel $model
	 * @param Hashtable $args
	 */
	public function searchAction(XMLModel $model, Hashtable $args) {

		$alerts = ORM::Alertas()->searchLike($args->busqueda);
		$req = $model->addChild('request');
		$req['string'] = $args->busqueda;

		return $this->paginateAndReturnView($model, $alerts);
	}

	/**
	 * Método que se encarga de borrar de base de datos un registro de alertas.
	 *
	 * @api
	 *
	 * @param XMLModel $model
	 * @param Hashtable $args
	 */
	public function deleteAction( XMLModel $model, Hashtable $args ){
		ORM::AlertasOrganizaciones()->deleteByAlertId($args->deletedId);
		ORM::Alertas()->deleteById($args->deletedId);
	}

	/**
	 * Método que se encarga de editar una alerta
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

		$alert = ORM::Alertas()->getByPK($args->id);

		$alertModel = $model->addChild('alert');

		$alertModel->appendORMAsAttributes($alert);

		$startDate = new Date($alert->fechaInicio);
		$endDate = new Date($alert->fechaFin);

		$alertModel['startDateFormat'] = $startDate->formatDate(Date::FORMAT_INMOGESTOR_CALENDAR);
		$alertModel['endDateFormat'] = $endDate->formatDate(Date::FORMAT_INMOGESTOR_CALENDAR);

		// Recuperamos las organizaciones:
		$organizations = ORM::Organizaciones()->getAll();

		$orgModel = $model->addChild('organizations');
		$orgModel->addRS($organizations);

		// Buscamos las alertasOrganizaciones
		$aoList = ORM::AlertasOrganizaciones()->getByAlertId($args->id);
		$selectedOrgs = array();
		// Primero preparamos un array de las organizaciones que tenemos.
		foreach ( $aoList as $ao ){
			$selectedOrgs[] = $ao->id_organizacion;
		}

		// Ahora recorremos el modelo y marcamos los nodos que ya estaban seleccionados.
		foreach( $orgModel as $om ){
			if ( in_array($om['id_organizacion'], $selectedOrgs) ){
				$om['selected'] = 1;
			}
		}

		$prioModel = $model->addChild('priorities');
		$prioModel->addI18N(I18N::NotificationPriorityI18N());

		return "single_alert";
	}

	/**
	 * Método que se encarga de crear una nueva alerta
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
