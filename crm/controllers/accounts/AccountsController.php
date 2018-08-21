<?php

namespace crm\controllers\accounts;

use crm\CRMController;
use php\Hashtable;
use crm\factories\Managers;
use crm\factories\ORM;

/**
 * Pagina de logueo
 *
 * @package crm
 * @subpackage controllers
 */
class AccountsController extends CRMController {

	/**
	 * @api
	 * Metodo para obtener las cuentas de un usuario.
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function getUserAccountsAction(\stdClass $model, $args){

		if(!is_null($args->APIUserId)){
			$rs = Managers::AccountsManager()->getAcountsByUserId($args->APIUserId);
			$model->accounts = array();
			while($acc = $rs->next()){
				$account = new \stdClass();
				$account->userId = $acc->id_usuario;
				$account->accountId = $acc->id_cuenta;
				$account->account = $acc->cuenta;
				$account->creationDate = $acc->fecha_creacion;
				$account->finishDate = $acc->fecha_baja;
				$model->accounts[] = $account;
			}
		}else{
			$model->status = "KO";
			$model->message = "Not a valid user has been received";
		}
	}

	/**
	 * @api
	 * Metodo para obtener los datos de una cuenta a partir de su id. El parametro se llama accountId.
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function getByIdAction(\stdClass $model, $args){
		if(!is_null($args->accountId)){
			$rs = Managers::AccountsManager()->getAcountById($args->accountId);
			$model->accounts = array();
			while($acc = $rs->next()){
				$account = new \stdClass();
				$account->userId = $acc->id_usuario;
				$account->accountId = $acc->id_cuenta;
				$account->account = $acc->cuenta;
				$account->creationDate = $acc->fecha_creacion;
				$account->finishDate = $acc->fecha_baja;
				$model->accounts[] = $account;
			}
			$model->status = "OK";
		}else{
			$model->status = "KO";
			$model->message = "Falta el parametro requerido 'accountId'.";
		}
	}

	/**
	 * @api
	 * Metodo para crear una nueva cuenta.
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function createAction(\stdClass $model, $args){
		if(!is_null($args->APIUserId) && !is_null($args->accountName)){
			$acc = Managers::AccountsManager()->createNewAccountForUser($args->APIUserId, $args->accountName);
			$model->accounts = array();
			$account = new \stdClass();
			$account->userId = $acc->idUsuario;
			$account->accountId = $acc->idCuenta;
			$account->account = $acc->cuenta;
			$account->creationDate = $acc->fechaCreacion;
			$account->finishDate = $acc->fechaBaja;
			$model->accounts[] = $account;
			$model->status = "OK";
		}else{
			$model->status = "KO";
			$model->message = "Falta el parametro requerido 'accountName' o no tiene un token correcto.";
		}
	}

	/**
	 * @api
	 * Metodo para crear una nueva cuenta.
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function deleteAction(\stdClass $model, $args){
		if(!is_null($args->APIUserId) && !is_null($args->accountId)){
			$response = Managers::AccountsManager()->deleteAccount($args->APIUserId, $args->accountId);
			if($response)
				$model->status = "OK";
			else
				$model->status = "KO";
		}else{
			$model->status = "KO";
			$model->message = "Falta el parametro requerido 'accountId' o no tiene un token correcto.";
		}
	}


	/**
	 * @api
	 * Metodo para agregar un usuario a una cuenta.
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function addUserAction(\stdClass $model, $args){
		if(!is_null($args->APIUserId) && !is_null($args->accountId) && !is_null($args->newUserId)){
			$result = Managers::AccountsManager()->addUserToAccount($args->APIUserId, $args->accountId, $args->newUserId);
			if($result){
				$model->status = "OK";
			}else{
				$model->status = "KO";
			}
		}else{
			$model->status = "KO";
			$model->message = "Falta alguno de los parametros requeridos o no tiene un token correcto.";
		}
	}

	/**
	 * @api
	 * Metodo para eliminar un usuario de una cuenta.
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function deleteUserAction(\stdClass $model, $args){
		if(!is_null($args->APIUserId) && !is_null($args->accountId) && !is_null($args->deletedUserId)){
			$result = Managers::AccountsManager()->deleteUserFromAccount($args->APIUserId, $args->accountId, $args->deletedUserId);
			if($result){
				$model->status = "OK";
			}else{
				$model->status = "KO";
			}
		}else{
			$model->status = "KO";
			$model->message = "Falta alguno de los parametros requeridos o no tiene un token correcto.";
		}
	}

	/**
	 * @api
	 * Metodo para modificar datos de una cuenta.
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function modifyAction(\stdClass $model, $args){
		if(!is_null($args->accountId)){
			$fields = new Hashtable();
			$fields->accountId = $args->accountId;
			if(!is_null($args->accountName)) $fields->accountName = $args->accountName;
			if(!is_null($args->active)) $fields->active = $args->active;
			$result = Managers::AccountsManager()->modifyAccount($args->APIUserId, $fields);
			if($result){
				$model->status = "OK";
			}else{
				$model->status = "KO";
			}
		}else{
			$model->status = "KO";
			$model->message = "Falta alguno de los parametros requeridos o no tiene un token correcto.";
		}
	}


	/**
	 * @api
	 * Metodo que devuelve los usuarios asociados a una cuenta
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function getUsersForAccountAction(\stdClass $model, $args){
		if(!is_null($args->accountId)){
			$users = ORM::AccountsUsers()->getByAccountId($args->accountId);
			if($users){
				$model->users = array();
				while($usr = $users->next()){
					$usrNode = new \stdClass();
					$usrNode->userId = $usr->id_usuario;
					$model->users[] = $usrNode;
				}
				$model->status = "OK";
			}else{
				$model->status = "KO";
			}
		}else{
			$model->status = "KO";
			$model->message = "Falta el parametro requerido 'accountId' o no tiene un token correcto.";
		}
	}

}
