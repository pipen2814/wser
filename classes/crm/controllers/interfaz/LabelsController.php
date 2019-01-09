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
class LabelsController extends CRMController {

	/**
	 * @api
	 *
	 * @param $appNode
	 *
	 * @return string
	 */
	public function adminLabelsAction(\stdClass $appNode, Hashtable $args){	

		$page = "admin_labels";
		$data = array('labels' => array() );

		$labels = ORM::Labels()->getAll();
		while($label = $labels->next()){
			$lb = array();
			$lb['id_etiqueta'] = $label->id_etiqueta;
			$lb['etiqueta'] = $label->etiqueta;
			$lb['color'] = $label->color;
			$data['labels'][] = $lb;
		}

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
	public function getLabelAction(\stdClass $appNode, Hashtable $args){	

		$page = "label";

		$data = array();
		$label= ORM::Labels()->getByPK($args->labelId);
		if($label){
			$lb = array();
			$lb['id_etiqueta'] = $label->idEtiqueta;
			$lb['etiqueta'] = $label->etiqueta;
			$lb['color'] = $label->color;
			$data['label'] = $label;
		}

		$view = new ViewGenerator($page);
		$view->setData($data);
		echo $view->getOutput();
		die;
	}

}
