<?php

namespace crm\controllers\categories;

use crm\CRMController;
use php\Hashtable;
//use crm\factories\Managers;
use crm\factories\ORM;

/**
 * Pagina de logueo
 *
 * @package crm
 * @subpackage controllers
 */
class CategoriesController extends CRMController {

	/**
	 * @api
	 * Metodo para obtener los datos de una etiqueta a partir de su id. El parametro se llama labelId.
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function getByIdAction(\stdClass $model, $args){
		if(!is_null($args->categoryId)){
			$ct = ORM ::Categories()->getByPK($args->categoryId);
			$model->categories = array();
			$this->fillCategory($model->categories, $ct);
			$model->status = "OK";
		}else{
			$model->status = "KO";
			$model->message = "Falta el parametro requerido 'categoryId'.";
		}
	}

	/**
	 * @api
	 * Metodo para crear una nueva categoria.
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function createAction(\stdClass $model, $args){
		if(!is_null($args->APIUserId) && !is_null($args->categoryName)){
			$cat = ORM::Categories();
			$model->categories = array();
			$cat->idCategoriaPadre = ($args->has("categoryParentId")?$args->categoryParentId:0);
			$cat->categoria = $args->categoryName;
			$cat->save();

			$c = new \stdClass();
			$c->categoryId = $cat->idCategoria;
			$c->parentCategoryId = $cat->idCategoriaPadre;
			$c->category = $cat->categoria;

			$model->categories[] = $c;
			$model->status = "OK";
		}else{
			$model->status = "KO";
			$model->message = "Falta el parametro requerido 'categoryName' o no tiene un token correcto.";
		}
	}

	/**
	 * @api
	 * Metodo para crear una nueva cuenta.
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function deleteAction(\stdClass $model, $args){
		if(!is_null($args->APIUserId) && !is_null($args->categoryId)){
			$orm = ORM::Categories()->getByPK($args->categoryId);
			if($orm){
				$orm->delete();
				$model->status = "OK";
			}else
				$model->status = "KO";
		}else{
			$model->status = "KO";
			$model->message = "Falta el parametro requerido 'categoryId' o no tiene un token correcto.";
		}
	}

	/**
	 * @api
	 * Metodo para modificar una categoria.
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function modifyAction(\stdClass $model, $args){
		if(!is_null($args->APIUserId) && !is_null($args->categoryId)){
			$cat = ORM::Categories()->getByPK($args->categoryId);
			$model->categories = array();
			$cat->idCategoriaPadre = ($args->has("categoryParentId")?$args->categoryParentId:$cat->idCategoriaPadre);
			$cat->categoria = ($args->has("categoryName")?$args->categoryName:$cat->categoria);
			$cat->save();

			$c = new \stdClass();
			$c->categoryId = $cat->idCategoria;
			$c->parentCategoryId = $cat->idCategoriaPadre;
			$c->category = $cat->categoria;

			$model->categories[] = $c;
			$model->status = "OK";
		}else{
			$model->status = "KO";
			$model->message = "Falta el parametro requerido 'categoryName' o no tiene un token correcto.";
		}
	}


	protected function fillCategory(&$node, $ormObj){
		$ct = new \stdClass();
		$ct->categoryId = $ormObj->idCategoria;
		$ct->categoryParentId = $ormObj->idCategoriaPadre;
		$ct->categoria = $ormObj->categoria;
		$node[] = $ct;
	}
}
