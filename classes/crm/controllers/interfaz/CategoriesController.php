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
class CategoriesController extends CRMController {

	/**
	 * @api
	 *
	 * @param $appNode
	 *
	 * @return string
	 */
	public function adminCategoriesAction(\stdClass $appNode, Hashtable $args){	

		$page = "admin_categories";
		$data = array('categories' => array() );

		$filters = new Hashtable();
		$filters->id_categoria_padre = 0;
		$categories = ORM::Categories()->getElementsByFilters($filters);
		$cats = $this->fillChildrenForCategory($filters);
		$data['categories'] = $cats;


		$cats = array();
		$allCategories = ORM::Categories()->getAll();
		while($cat = $allCategories->next()){
			$cats[] = array(
				'id_categoria' => $cat->id_categoria
				,'categoria' => $cat->categoria
				,'id_categoria_padre' => $cat->id_categoria_padre
			);
		}
		$data['allCategories'] = $cats;


		$view = new ViewGenerator($page);
		$view->setData($data);
		echo $view->getOutput();
		die;
	}

	protected function fillChildrenForCategory($filters){
		$categories = ORM::Categories()->getElementsByFilters($filters);
		$cats = array();
		while($category = $categories->next()){
			$ct = array();
			$ct['id_categoria'] = $category->id_categoria;
			$ct['categoria'] = $category->categoria;
			$ct['id_categoria_padre'] = $category->id_categoria_padre;
			if($category->id_categoria_padre != 0){
				$parent = ORM::Categories()->getByPK($category->id_categoria_padre);
				if($parent) $ct['categoria_padre'] = $parent->categoria;
			}
			$filters = new Hashtable();
			$filters->id_categoria_padre = $category->id_categoria;
			$children = $this->fillChildrenForCategory($filters);
			if(count($children) > 0){
				$ct['categories'] = $children;
			}
			$cats[] = $ct;
		}
		return $cats;
	}


	/**
	 * @api
	 *
	 * @param $appNode
	 *
	 * @return string
	 */
	public function getCategoryAction(\stdClass $appNode, Hashtable $args){	

		$page = "category";

		$data = array();
		$category = ORM::Categories()->getByPK($args->categoryId);
		if($category){
			$ct = array();
			$ct['id_categoria'] = $category->idCategoria;
			$ct['categoria'] = $category->categoria;
			$ct['id_categoria_padre'] = $category->idCategoriaPadre;
			$data['category'] = $ct;
		}

		$cats = array();
		$categories = ORM::Categories()->getAll();
		while($cat = $categories->next()){
			$cats[] = array(
				'id_categoria' => $cat->id_categoria
				,'categoria' => $cat->categoria
				,'id_categoria_padre' => $cat->id_categoria_padre
			);
		}
		$data['categories'] = $cats;

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
	public function newCategoryAction(\stdClass $appNode, Hashtable $args){	

		$page = "category_new";

		$data = array();

		$cats = array();
		$categories = ORM::Categories()->getAll();
		while($cat = $categories->next()){
			$cats[] = array(
				'id_categoria' => $cat->id_categoria
				,'categoria' => $cat->categoria
				,'id_categoria_padre' => $cat->id_categoria_padre
			);
		}
		$data['categories'] = $cats;

		$view = new ViewGenerator($page);
		$view->setData($data);
		echo $view->getOutput();
		die;
	}

}
