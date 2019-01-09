<?php

namespace crm\controllers\users;

use crm\CRMController;
use php\Hashtable;
//use crm\factories\Managers;
use crm\factories\ORM;

/**
 * Pagina de logueo
 *
 * @package crm
 * @subpackage controllers
 */
class UserController extends CRMController {

	/**
	 * @api
	 * Metodo para obtener los datos basicos de un usuario
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function getInfoAction(\stdClass $model, $args){
		$user = ORM::Users()->getByPK($args->APIUserId);

		if(!is_null($user)){
			$model->username=$user->nombre;
			$model->status = "OK";
		}else{
			$model->status = "KO";
			$model->message = "Falta el parametro requerido 'movementId'.";
		}
	}
}
