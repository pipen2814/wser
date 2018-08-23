<?php

namespace crm\controllers\admin\network;

use crm\CRMController;
use crm\factories\ORM;
use php\exceptions\Exception;
use php\Hashtable;
use php\sql\MySQLResultSet;
use php\sql\ResultSetRow;
use php\util\Paginator;
use php\XMLModel;

/**
 * Class EventTypesAdmin
 *
 * Admin de tipos de eventos disponibles.
 *
 * @package crm
 * @subpackage controllers
 * @subpackage admin
 * @subpackage network
 */
class EventTypesAdminController extends CRMController  {

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
		$p->modifiedField('color_rgb','input',array('type'=>'color','valueAttr' => '$color_rgb','disabled' => 'disabled' ));
		$p->addRowButton('Eliminar','delete','id_tipo_evento','deleteEventType(this.id);');
		$p->addRowButton('Editar','edit','id_tipo_evento','editEventType(this.id);');
		$p->run();

		return 'event_types_admin';
	}
	/**
	 * Método genérico para gestionar los tipos de eventos.
	 *
	 * @app
	 *
	 * @param XMLModel $model
	 * @param Hashtable $args
	 */
	public function defaultAction(XMLModel $model, Hashtable $args){

		$eventTypes = ORM::TiposEventos()->getAll();

		return $this->paginateAndReturnView($model, $eventTypes);
	}

	/**
	 * Método encargado de buscar entre los tipos de eventos.
	 *
	 * @app
	 *
	 * @param XMLModel $model
	 * @param Hashtable $args
	 */
	public function searchAction(XMLModel $model, Hashtable $args) {

		$eventTypes = ORM::TiposEventos()->searchLike($args->busqueda);
		$req = $model->addChild('request');
		$req['string'] = $args->busqueda;

		return $this->paginateAndReturnView($model, $eventTypes);
	}

	/**
	 * Método que se encarga de borrar de base de datos un registro de tipos de eventos.
	 *
	 * @api
	 *
	 * @param XMLModel $model
	 * @param Hashtable $args
	 */
	public function deleteAction( XMLModel $model, Hashtable $args ){
		ORM::TiposEventos()->deleteById($args->deletedId);
	}

	/**
	 * Método que se encarga de editar un tipo de evento
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

		$eventType = ORM::TiposEventos()->getByPK($args->id);

		$eventModel = $model->addChild('event');

		$eventModel->appendORMAsAttributes($eventType);

		return "single_event_type";
	}

	/**
	 * Método que se encarga de crear un tipo de evento
	 *
	 * @app
	 *
	 * @param XMLModel $model
	 * @param Hashtable $args
	 */
	public function newAction( XMLModel $model, Hashtable $args ){
		return "single_event_type";
	}

	/**
	 * Método que se encarga de guardar un tipo de evento en base de datos.
	 *
	 * @app
	 *
	 * @param XMLModel $model
	 * @param Hashtable $args
	 */
	public function saveAction( XMLModel $model, Hashtable $args ){

		$update = false;
		if ($args->has('eventTypeId')){
			$orm = ORM::TiposEventos()->getByPK($args->eventTypeId);
		}else{
			$orm = ORM::TiposEventos();
		}

		if ($args->has('eventTypeName')) {
			$update = true;
			$orm->tipoEvento = utf8_decode($args->eventTypeName);
		}
		if ($args->has('eventTypeColor')) {
			$update = true;
			$orm->colorRgb = $args->eventTypeColor;
		}

		$orm->save();

		$model['close_and_reload'] = 1;
		if ($args->has('eventTypeId')){
			$eventType = ORM::TiposEventos()->getByPK($args->eventTypeId);

			$eventModel = $model->addChild('event');

			$eventModel->appendORMAsAttributes($eventType);

			return "single_event_type";
		}else{
			return "single_event_type";
		}
	}
}