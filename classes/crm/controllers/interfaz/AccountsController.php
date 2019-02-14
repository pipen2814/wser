<?php

namespace crm\controllers\interfaz;

use crm\CRMController;
use php\Hashtable;
use php\Session;
use php\XMLModel;
use php\ViewGenerator;
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
	 *
	 * @param $appNode
	 *
	 * @return string
	 */
	public function getAccountAction(\stdClass $appNode, Hashtable $args){	

		$page = "account";
		$data = array();
		$data['monthStart'] = date("Y-m")."-01";
		$data['monthEnd'] = date("Y-m-d");

		$filters = new Hashtable();
		$filters->account = array($args->accountId);
		$filters->orderBy = "order by fecha_creacion desc";
		$filters->limit = array("from" => 0, "quantity" => DEFAULT_LIST_SIZE);
		$data['movements'] = array();

		$account = ORM::Accounts()->getByPK($args->accountId);
		$data['account'] = array("name" => $account->cuenta);
		$this->fillMovements($filters, $data);

		$view = new ViewGenerator($page);
		$view->setData($data);
		echo $view->getOutput();
		die;
	}

	protected function fillMovements($filters, &$data){

		$movements = Managers::MovementManager()->getMovementsByFilters($filters);
		$data['movements'] = array();
		$data['moreMovements'] = 1;
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
