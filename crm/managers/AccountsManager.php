<?php

namespace crm\managers;

use php\sql\DB;
use php\Hashtable;
use crm\factories\ORM;
use php\managers\BaseManager as parentClass;
//use php\sql\DB;

/**
 * Class UserManager
 *
 * Manager de usuarios
 *
 * @package crm
 * @subpackage managers
 */
class AccountsManager extends parentClass {

	public static function getAcountsByUserId($userId){
		$db = DB::getInstance();
		$password = md5($password);
		$db->query("select c.*, u.id_usuario
				from usuarios u
				join cuentas_usuarios cu using (id_usuario)
				join cuentas c using(id_cuenta)
				where u.id_usuario = ".$userId." and c.activo = 1");
		$rs = $db->getRs();

		return $rs;
	}

	public static function getAcountById($accountId){
		$db = DB::getInstance();
		$db->query("select c.*, u.id_usuario
				from cuentas c 
				join cuentas_usuarios cu using (id_cuenta)
				join usuarios u using (id_usuario)
				where c.id_cuenta = ".$accountId." and c.activo = 1");
		$rs = $db->getRs();

		return $rs;
	}

	public static function createNewAccountForUser($userId, $accountName){
		$account = ORM::Accounts();
//$accountName = mb_convert_encoding($accountName, 'utf-8', 'iso-8859-15');
//var_dump('<pre>', $accountName, utf8_decode($accountName));die;
		$account->cuenta = $accountName." ".utf8_decode($accountName)." ".utf8_encode($accountName);
		$account->fechaCreacion = date("Y-m-d H:i:s");
		$account->fechaBaja = "0000-00-00 00:00:00";
		$account->activo = 1;
		$account->save();

		//Asociamos la cuenta recien creada al usuario que nos llega por parametro.
		$accountUser = ORM::AccountsUsers();
		$accountUser->idUsuario = $userId;
		$accountUser->idCuenta = $account->idCuenta;
		$accountUser->save();
		return $account;
	}

	public static function addUserToAccount($userId, $accountId, $newUserId){

		$userCanAdd = false;
		//Comprobamos que exista la cuenta, si no existe devolvemos false
		$account = ORM::Accounts()->getByPK($accountId);
		if($account){
			//Comprobamos si la asociacion ya existe, de ser asi devolvemos false
			$users = ORM::AccountsUsers()->getByAccountId($accountId);
			while($user = $users->next()){
				//Comprobamos si el usuario que esta realizando la accion es uno de los usuarios de la cuenta
				if($user->id_usuario == $userId){
					$userCanAdd = true;
				}
				//El usuario al que intentan agregar ya esta asociado a la cuenta
				if($user->id_usuario == $newUserId){
					return false;
				}

			}
			if($userCanAdd){
				$reg = ORM::AccountsUsers();
				$reg->idUsuario = $newUserId;
				$reg->idCuenta = $accountId;
				$reg->save();
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
		return false;
	}

	public static function deleteUserFromAccount($userId, $accountId, $deletedUserId){

		$userCanDelete = false;
		//Comprobamos que exista la cuenta, si no existe devolvemos false
		$account = ORM::Accounts()->getByPK($accountId);
		if($account){
			//Comprobamos si la asociacion ya existe, de ser asi devolvemos false
			$users = ORM::AccountsUsers()->getByAccountId($accountId);
			while($user = $users->next()){
				//Comprobamos si el usuario que esta realizando la accion es uno de los usuarios de la cuenta
				if($user->id_usuario == $userId){
					$userCanDelete= true;
					$reg = ORM::AccountsUsers()->getByPK($accountId, $deletedUserId);
					if($reg){
						$reg->deleteById($accountId, $deletedUserId);
						return true;
					}
				}
			}
		}else{
			return false;
		}
		return false;
	}

	public static function modifyAccount($userId, Hashtable $fields){
		
		if(is_null($fields->accountId)) return false;

		$account = ORM::Accounts()->getByPK($fields->accountId);
		if(!$account) return false;

		if(!is_null($fields->accountName)) $account->cuenta = $fields->accountName;
		if(!is_null($fields->active)) $account->activo = $fields->active;

		$account->save();
		return true;
	}

	public static function deleteAccount($userId, $accountId){

		//Comprobamos que exista la cuenta, si no existe devolvemos false
		$account = ORM::Accounts()->getByPK($accountId);
		if($account){
			//Comprobamos si la asociacion ya existe, de ser asi devolvemos false
			$users = ORM::AccountsUsers()->getByAccountId($accountId);
			while($user = $users->next()){
				//Comprobamos si el usuario que esta realizando la accion es uno de los usuarios de la cuenta
				if($user->id_usuario == $userId){
					
					//Borramos todos las asociaciones de usuarios a la cuenta
					ORM::AccountsUsers()->deleteAllForAccount();

					//TODO:: Cuando creemos la tabla de movimientos y su correspondiente ORM, crearemos el metodo y descomentaremos esta parte.
					//Borramos todos los movimientos de la cuenta
					//ORM::Movements()->deleteAllForAccount();

					//Borramos la cuenta
					$account->deleteById($accountId);

					return true;
				}
			}
		}
		return false;
	}
}
