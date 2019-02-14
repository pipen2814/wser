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
class MovementsController extends CRMController {

	/**
	 * @api
	 *
	 * @param $appNode
	 *
	 * @return string
	 */
	public function getMovementAction(\stdClass $appNode, Hashtable $args){	

		$page = "movement";
		$data = array();

		$movement = ORM::Movements()->getByPK($args->movementId);
		if($movement){
			$mv = array();
			$mv['id_usuario'] = $movement->idUsuario;
			$mv['tipo'] = $movement->tipo;
			$mv['usuario'] = $movement->usuario;
			$mv['id_cuenta'] = $movement->idCuenta;
			$mv['cuenta'] = $movement->cuenta;
			$mv['id_movimiento'] = $movement->idMovimiento;
			$mv['movimiento'] = $movement->movimiento;
			$mv['importe'] = $movement->importe;
			$mv['id_categoria'] = $movement->idCategoria;
			$mv['fecha'] = explode(" ", $movement->fechaInforme)[0];
			$mv['hora'] = explode(" ", $movement->fechaInforme)[1];
			$data['movement'] = $mv;
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

		$cats = array();
		$categories = ORM::Categories()->getAll();
		while($cat = $categories->next()){
			$cats[] = array(
				'id_categoria' => $cat->id_categoria
				,'id_categoria_padre' => $cat->id_categoria_padre
				,'categoria' => $cat->categoria
			);
		}
		$data['categories'] = $cats;

		$view = new ViewGenerator($page);
		$view->setData($data);
		echo $view->getOutput();
		die;
	}
}
