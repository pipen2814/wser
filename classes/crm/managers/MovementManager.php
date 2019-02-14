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

	public static function getMovementsByFilters(Hashtable $filters){
		$db = DB::getInstance();
		$orderBy = "";
		if($filters->has("orderBy")){
			$orderBy = $filters->orderBy;
		}

		$userId = "";
		if($filters->has("userId")){
			if(is_array($filters->userId)){
				$userId = " and m.id_usuario in (".implode(',',$filters->userId).")";
			}else{
				$userId = " and m.id_usuario = ".$filters->userId;
			}
		}

		$dates = "";
		if($filters->has("dateFrom") || $filters->has("dateTo")){
			if($filters->has("dateFrom")){
				$dates .= " and m.fecha_informe > '".$filters->dateFrom."' ";
			}
			if($filters->has("dateTo")){
				$dates .= " and m.fecha_informe < '".$filters->dateTo."' ";
			}
		}

		if($filters->has("account") && is_array($filters->account) ){
			$account = " and c.id_cuenta in (".implode(',',$filters->account).")";
		}else{
			return false;
		}

		$limit = "";
		if($filters->has("limit") && is_array($filters->limit)){
			$lim = $filters->limit;
			if(!is_null($lim['from']) && !is_null($lim['quantity'])) $limit = " limit ".$lim['from'].", ".$lim['quantity'];
			elseif(is_null($lim['from']) && !is_null($lim['quantity'])) $limit = " limit ".$lim['quantity'];
		}

		$db->query("select m.*, u.id_usuario, u.usuario, c.cuenta
				from movimientos m 
				join usuarios u using (id_usuario)
				join cuentas c using (id_cuenta)
				where 1=1 $userId $account $dates $orderBy $limit");
		$rs = $db->getRs();

		return $rs;
	}

	public static function createNewMovement($userId, $accountId, $type, $movement, $price, $reportDate = null, $categoryId = null){
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
					$mv->tipo = $type;
					$mv->movimiento = $movement;
					$mv->importe = $price;
					if(is_null($reportDate)){
						$reportDate = date("Y-m-d H:i:s");
					}
					$mv->fechaInforme = $reportDate;
					$mv->idCategoria = $categoryId;
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

	public static function modifyMovement($userId, $args){
		//Comprobamos que exista el movimiento, si no existe devolvemos false
		$mv = ORM::Movements()->getByPK($args->movementId);
		if($mv){
			$mv = ORM::Movements();
			$mv->idUsuario = $userId;
			if($args->has('accountId')) $mv->idCuenta = $args->accountId;
			if($args->has('type'))$mv->tipo = $args->type;
			if($args->has('movement'))$mv->movimiento = $args->movement;
			if($args->has("price"))$mv->importe = $args->price;
			if($args->has("categoryId"))$mv->idCategoria = $args->categoryId;
			$mv->save();
		}else{
			return false;
		}
		return $mv;
	}

}
