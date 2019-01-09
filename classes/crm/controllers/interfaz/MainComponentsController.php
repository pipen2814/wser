<?php

namespace crm\controllers\interfaz;

use crm\CRMController;
use php\Hashtable;
use php\Session;
use php\XMLModel;
use php\ViewGenerator;
use crm\factories\Managers;

/**
 * Pagina de logueo
 *
 * @package crm
 * @subpackage controllers
 */
class MainComponentsController extends CRMController {

	/**
	 * @api
	 *
	 * @param $appNode
	 *
	 * @return string
	 */
	public function getDashboardContentAction(\stdClass $appNode, Hashtable $args){	

		$data = array();
		$data['monthStart'] = date("Y-m")."-01";
		$data['monthEnd'] = date("Y-m-d");
		$page = "dashboard";
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

		$userId = $args->APIUserId;
		$this->fillLastMovements($userId, $data);
		$this->fillMonthChart($userId, $data);

		$view = new ViewGenerator($page);
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
	public function getMenuOptionsAction(\stdClass $appNode, Hashtable $args){
		$data = array();
		$page = "menu-left";
		$view = new ViewGenerator($page);
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
	public function getNotificationsAction(\stdClass $appNode, Hashtable $args){
		$data = array();
		$page = "notifications";
		$view = new ViewGenerator($page);
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
	public function getTasksAction(\stdClass $appNode, Hashtable $args){
		$data = array();
		$page = "tasks";
		$view = new ViewGenerator($page);
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
	public function getMessagesAction(\stdClass $appNode, Hashtable $args){	
		$data = array();
		$page = "messages";
		$view = new ViewGenerator($page);
		$view->setData($data);
		echo $view->getOutput();
		die;
	}

	protected function fillLastMovements($userId, &$data = array()){
		$filters = new Hashtable();
		$filters->id_usuario = $userId;
		$accounts = Managers::AccountsManager()->getAcountsByUserId($userId);
		$acs =  array();
		while($account = $accounts->next()){
			$acs[] = $account->id_cuenta;
		}

		$filters->account = $acs;
		$filters->orderBy = "order by fecha_creacion desc";
		$filters->limit = array("quantity" => 10);
		$movements = Managers::MovementManager()->getMovementsByFilters($filters);
		$data['movements'] = array();
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
			$mv['fecha_informe'] = explode(" ", $movement->fecha_informe)[0];
			$data['movements'][] = $mv;
		}
	}

	protected function fillMonthChart($userId, &$data = array()){

		$filters = new Hashtable();
		$filters->id_usuario = $userId;
		$accounts = Managers::AccountsManager()->getAcountsByUserId($userId);
		$acs =  array();
		while($account = $accounts->next()){
			$acs[] = $account->id_cuenta;
		}

		$today = date("Y-m-d 23:59:59");
		$sixMonthsAgo = strtotime(date("Y-m-d", strtotime($today)) . " -5 month");
		$sixMonthsAgo = date("Y-m-01 00:00:00",$sixMonthsAgo);

		$filters->account = $acs;
		$filters->dateFrom = $sixMonthsAgo;
		$filters->dateTo = $today;
		$movements = Managers::MovementManager()->getMovementsByFilters($filters);

		$data['monthChart'] = array();
		$data['monthChart']['months'] = array();
		$data['monthChart']['incomes'] = array();
		$data['monthChart']['expenses'] = array();

		$monthNumber = date("m", strtotime(date("Y-m-d", strtotime($sixMonthsAgo))." -1 month" ));
		for($i=0;$i<6;$i++){
			if($monthNumber>11){
				$monthNumber = 1;
			}else{
				$monthNumber++;
			}
			$date = \DateTime::createFromFormat('!m', $monthNumber);
			$month = substr(ucwords(strftime("%B", $date->getTimestamp())),0,3);
			$data['monthChart']['months'][] = $month;
			$data['monthChart']['incomes'][] = 0;
			$data['monthChart']['expenses'][] = 0;
		}

		while($movement = $movements->next()){

			$date1 = new \DateTime($sixMonthsAgo);
			$date2 = new \DateTime($movement->fecha_informe);
			$month = $date1->diff($date2)->m;
			if($movement->tipo == 0){
				$data['monthChart']['expenses'][$month] += $movement->importe;
			}else{
				$data['monthChart']['incomes'][$month] += $movement->importe;
			}
		}
	}
}
