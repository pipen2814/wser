<?php

namespace crm\controllers\interfaz;

use crm\CRMController;
use php\Hashtable;
use php\Session;
use php\XMLModel;
use php\ViewGenerator;
use crm\factories\Managers;
use crm\factories\ORM;
use crm\factories\I18N;

/**
 * Pagina de logueo
 *
 * @package crm
 * @subpackage controllers
 */
class AccountsController extends CRMController {

	/**
	 * @api
	 *
	 * @param $appNode
	 *
	 * @return string
	 */
	public function getAccountAction(\stdClass $appNode, Hashtable $args){	

		$template= "account";
		$page = "account";
		$data = array('page' => $page);
		$data['pageNumber'] = 1;
		$data['nextPageNumber'] = 2;
		$data['movementTypes'] = array();
		$movementTypes = I18N::MovementTypesI18N()->getAll();
		if(is_array($movementTypes)){
			foreach($movementTypes as $movementId => $type){
				$data['movementTypes'][] = array('id_tipo' => $movementId, 'tipo_movimiento' => $type);
			}
		}
		$accounts = Managers::AccountsManager()->getAcountsByUserId($args->APIUserId);
		while($account = $accounts->next()){
			$ac = array();
			$ac['id_cuenta'] = $account->id_cuenta;
			$ac['cuenta'] = $account->cuenta;
			$ac['fecha_creacion'] = $account->fecha_creacion;
			$ac['fecha_baja'] = $account->fecha_baja;
			$ac['activo'] = $account->activo;
			$ac['id_usuario'] = $account->id_usuario;
			$ac['color'] = ($account->color!=''?$account->color:'bg-aqua');
			$data['accounts'][] = $ac;
		}

		$data['accountId'] = $args->accountId;
		$data['monthStart'] = date("Y-m")."-01";
		$data['monthEnd'] = date("Y-m-d");

		$filters = new Hashtable();
		$filters->account = array($args->accountId);
		$filters->orderBy = "order by fecha_creacion desc";
		$filters->limit = array("from" => 0, "quantity" => DEFAULT_LIST_SIZE);
		if($args->has("movementType")){ 
			if($args->movementType == -1){
				/*
				$filters->movementType = 'all';

				$movementTypes = I18N::MovementTypesI18N()->getAll();
				if(is_array($movementTypes)){
					foreach($movementTypes as $movementId => $type){

					}
				}
				 */
			}else{
				$filters->movementType = $args->movementType;
			}
		}
		$data['movements'] = array();

		$account = ORM::Accounts()->getByPK($args->accountId);
		$data['account'] = array("name" => $account->cuenta);
		$this->fillMovements($filters, $data);

		$view = new ViewGenerator($template);
		$view->setData($data);
		echo $view->getOutput();
		die;
	}

	/**
	 * @api
	 *
	 * @param $appNode
	 *
	 * @return string
	 */
	public function getMoreMovementsForAccountAction(\stdClass $appNode, Hashtable $args){	

		$template = "account";
		$page = "movements";

		$pageNumber = 0;
		if($args->has("pageNumber")) $pageNumber = $args->pageNumber + 1;
		$data = array('page' => $page);
		$data['pageNumber'] = $pageNumber;
		$data['nextPageNumber'] = $pageNumber+1;
		$data['moreMovements'] = 1; //Definirlo haciendo un query para ver si hay mas registros sin mostrar.
		$data['accountId'] = $args->accountId; 

		$filters = new Hashtable();
		$filters->account = array($args->accountId);
		$filters->orderBy = "order by fecha_creacion desc";
		$limitFrom = 0;
		if($pageNumber == 0) $limitFrom = 0;
		else $limitFrom = $args->pageNumber * DEFAULT_LIST_SIZE;
		$filters->limit = array("from" => $limitFrom, "quantity" => DEFAULT_LIST_SIZE);
		$data['movements'] = array();

		$this->fillMovements($filters, $data);

		$view = new ViewGenerator($template);
		$view->setData($data);
		echo $view->getOutput();
		die;
	}



	protected function fillMovements($filters, &$data){

		$movements = Managers::MovementManager()->getMovementsByFilters($filters);
		$data['movements'] = array();
		$data['moreMovements'] = 1; //Definirlo haciendo un query para ver si hay mas registros sin mostrar.
		while($movement = $movements->next()){
			$mv = array();
			$mv['id_usuario'] = $movement->id_usuario;
			$mv['tipo'] = $movement->tipo;
			$mv['usuario'] = $movement->usuario;
			$mv['id_cuenta'] = $movement->id_cuenta;
			$mv['cuenta'] = $movement->cuenta;
			$mv['id_movimiento'] = $movement->id_movimiento;
			$mv['movimiento'] = $movement->movimiento;
			$mv['importe'] = $movement->importe;

			$category = ORM::Categories()->getByPK($movement->id_categoria);
			if($category){
				$mv['categoria'] = $category->categoria;
			}

			$mv['fecha'] = explode(" ", $movement->fecha_informe)[0];
			$mv['hora'] = explode(" ", $movement->fecha_informe)[1];
			$data['movements'][] = $mv;
		}
	}
}
