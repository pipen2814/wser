<?php

namespace crm\controllers\labels;

use crm\CRMController;
use php\Hashtable;
//use crm\factories\Managers;
use crm\factories\Managers;

/**
 * Pagina de logueo
 *
 * @package crm
 * @subpackage controllers
 */
class LabelsController extends CRMController {

	/**
	 * @api
	 * Metodo para obtener los datos de una etiqueta a partir de su id. El parametro se llama labelId.
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function getByIdAction(\stdClass $model, $args){
		if(!is_null($args->labelId)){
			$rs = Managers::LabelManager()->getLabelById($args->labelId);
			$model->labels = array();
			$this->fillLabel($model->labels, $rs);
			$model->status = "OK";
		}else{
			$model->status = "KO";
			$model->message = "Falta el parametro requerido 'labelId'.";
		}
	}

	protected function fillLabel(&$node, $resultSet){
		while($lb = $resultSet->next()){
			$label = new \stdClass();
			$label->labelId = $lb->id_etiqueta;
			$label->label = $mov->etiqueta;
			$label->color = $lb->color;
			$node[] = $label;
		}
	}
}
