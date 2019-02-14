<?php

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class Accounts
 *
 * ORM para la tabla categorias
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class Categories extends BaseORM{

	protected $tableName = "categorias";
	protected $id = array("id_categoria");
	protected $fields = array(
		"id_categoria" => "idCategoria",
		"id_categoria_padre" => "idCategoriaPadre",
		"categoria" => "categoria"
	);
}
?>
