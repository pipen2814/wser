<?php

namespace crm\controllers\movements;

use crm\CRMController;
use php\Hashtable;
//use crm\factories\Managers;
use crm\factories\Managers;

/**
 * Pagina de logueo
 *
 * @package crm
 * @subpackage controllers
 */
class MovementsController extends CRMController {

	/**
	 * @api
	 * Metodo para obtener los datos de un movimiento a partir de su id. El parametro se llama movementId.
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function getByIdAction(\stdClass $model, $args){
		if(!is_null($args->movementId)){
			$rs = Managers::MovementManager()->getMovementById($args->movementId);
			$model->movements = array();
			$this->fillMovement($model->movements, $rs);
			$model->status = "OK";
		}else{
			$model->status = "KO";
			$model->message = "Falta el parametro requerido 'movementId'.";
		}
	}

	/**
	 * @api
	 * Metodo para obtener los datos de los movimientos de una cuenta a partir del accountId. 
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function getFromAccountAction(\stdClass $model, $args){
		if(!is_null($args->accountId)){
			$rs = Managers::MovementManager()->getMovementsFromAccountId($args->accountId);
			$model->movements = array();
			$this->fillMovement($model->movements, $rs);
			$model->status = "OK";
		}else{
			$model->status = "KO";
			$model->message = "Falta el parametro requerido 'movementId'.";
		}
	}

	/**
	 * @api
	 * Metodo para crear una nuevo movimiento
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function createAction(\stdClass $model, $args){
		//TODO:: Crear el metodo MovementManager()->createNewMovement
		if(!is_null($args->APIUserId) && !is_null($args->accountId) && !is_null($args->type) && !is_null($args->movement) && !is_null($args->price) ){
			$reportDate = (is_null($args->reportDate)?null:$args->reportDate);
			$mov = Managers::MovementManager()->createNewMovement($args->APIUserId, $args->accountId, $args->type, $args->movement, $args->price, $reportDate);
			$model->status = "OK";
		}else{
			$model->status = "KO";
			$model->message = "Falta el parametro requerido 'accountName' o no tiene un token correcto.";
		}
	}

	/**
	 * @api
	 * Metodo para modificar un movimiento existente
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function modifyAction(\stdClass $model, $args){
		//TODO:: Crear el metodo MovementManager()->createNewMovement
		if(!is_null($args->APIUserId) && !is_null($args->movementId) ){
				//&& !is_null($args->type) && !is_null($args->movement) && !is_null($args->price) ){
			$mov = Managers::MovementManager()->modifyMovement($args->APIUserId, $args);
			$model->status = "OK";
		}else{
			$model->status = "KO";
			$model->message = "Falta el parametro requerido 'accountName' o no tiene un token correcto.";
		}
	}



	protected function fillMovement(&$node, $resultSet){
		while($mov = $resultSet->next()){
			$movement = new \stdClass();
			$movement->userId = $mov->id_usuario;
			$movement->movementId = $mov->id_movimiento;
			$movement->accountId = $mov->id_cuenta;
			$movement->type = $mov->tipo;
			$movement->movement = $mov->movimiento;
			$movement->amount = $mov->importe;
			$movement->creationDate = $mov->fecha_creacion;
			$movement->reportDate = $mov->fecha_informe;
			$movement->groupId = $mov->id_grupo;
			$node[] = $movement;
		}
	}
}
