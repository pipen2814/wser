<?php

namespace crm;

use crm\factories\Managers;
use php\Controller;
use php\Session;
use crm\models\userModel;
use php\XMLModel;

/**
 * Class CRMController
 *
 * @package crm
 */
class CRMController extends Controller {

	/**
	 * CRMController constructor.
	 */
	public function __construct(){
		date_default_timezone_set('Europe/Madrid');
		parent::__construct();
		
	}

	/**
	 * Metodo de formateo de fechas.
	 *
	 * @param $stringDate
	 * @param string $format
	 *
	 * @return string
	 */
	protected function defaultDateFormat($stringDate, $format = "d/m/Y"){
		return $this->dateFormat($stringDate, $format);
	}

	/**
	 * Metodo de formateo de fecha/horas.
	 *
	 * @param $stringDate
	 * @param string $format
	 *
	 * @return string
	 */
	protected function defaultDateTimeFormat($stringDate, $format = "d/m/Y H:i:s"){
		return $this->dateFormat($stringDate, $format);
	}

	/**
	 * Metodo base de formateo de fechas y fecha/horas.
	 *
	 * @param $stringDate
	 * @param $format
	 *
	 * @return string
	 */
	private function dateFormat($stringDate, $format){
		$formatter = new \DateTime($stringDate);
		return $formatter->format($format);
	}

	/**
	 * Crea en el modelo, un elemento de datos apto para ser paginado de manera automatica
	 *
	 * @param SimpleXMLElement model Modelo
	 * @param ResultSet $rs Datos a paginar
	 * @param Array $hidden Campos a ocultar del $rs
	 * @return void
	 */
	protected function paginatorData($model,$rs,$hidden=array()) {

		$items = $model->addChild('items');
		$fields = $rs->getFields();
		foreach($fields as $count => $field) {
			if(!in_array($field[1],$hidden)) {
				$items["item_$count"] = $field[1];
			}
		}

		while($data = $rs->next()) {
			$item = $items->addChild('item');
			foreach($fields as $count => $field) {
				$pos = $field[1];
				if(!in_array($pos,$hidden)) {
					$d = $data->$pos;
					if(is_string($d)) {
						$d = $this->encode($d);
					}
					$item["item_$count"] = $d;
				}
			}
		}
	}

	/**
	 * Metodo que agrega los datos del nodo principal.
	 *
	 * @return \php\XMLModel
	 */
	public function addMainNode(){
		$app = parent::addMainNode();
		if(!$this->isAPI()){
			$app['css'] = $this->getCSS();
			$this->addRequestNode($app);
		}
		return $app;
	}

	/**
	 * Metodo que devuelve el CSS que tiene que tener la aplicacion.
	 *
	 * @return string
	 */
	public function getCSS(){
		$cssPath = '/css/';
		if (ENVIRONMENT == 'DEV')
		{
			$cssPath .= 'styles.css';
		}else{
			$cssPath .= 'styles.min.css';
		}

		return $cssPath;
	}

	protected function renoveToken($token){
		return Managers::UserManager()->checkAPIToken($token);
	}
}

