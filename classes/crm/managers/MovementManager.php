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
class MovementManager extends parentClass {

	public static function getMovementById($movementId){
		$db = DB::getInstance();
		$db->query("select m.*, u.id_usuario
				from movimientos m 
				join usuarios u using (id_usuario)
				where m.id_movimiento = ".$movementId);
		$rs = $db->getRs();

		return $rs;
	}

	public static function getMovementsFromAccountId($accountId){
		$db = DB::getInstance();
		$db->query("select m.*, u.id_usuario
				from movimientos m 
				join usuarios u using (id_usuario)
				where m.id_cuenta = ".$accountId);
		$rs = $db->getRs();

		return $rs;
	}

	public static function createNewMovement($userId, $accountId, $movement, $price, $reportDate = null, $groupId = null){
		$movementRegistered = false;
		//Comprobamos que exista la cuenta, si no existe devolvemos false
		$account = ORM::Accounts()->getByPK($accountId);
		if($account){
			//Comprobamos si la asociacion ya existe, de ser asi devolvemos false
			$users = ORM::AccountsUsers()->getByAccountId($accountId);
			while($user = $users->next()){
				//Comprobamos si el usuario que esta realizando la accion es uno de los usuarios de la cuenta
				if($user->id_usuario == $userId){
					$mv = ORM::Movements();
					$mv->idUsuario = $userId;
					$mv->idCuenta = $accountId;
					$mv->movimiento = $movement;
					$mv->importe = $price;
					$mv->fechaInforme = $reportDate;
					$mv->idGrupo= $groupId;
					$mv->save();
					$movementRegistered = true;
				}
			}
			if($movementRegistered == false){
				return false;
			}
		}else{
			return false;
		}
	}
}
