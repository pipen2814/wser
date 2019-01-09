<?php

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class Accounts
 *
 * ORM para la tabla etiquetas
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class Labels extends BaseORM{

	protected $tableName = "etiquetas";
	protected $id = array("id_etiqueta");
	protected $fields = array(
		"id_etiqueta" => "idEtiqueta",
		"etiqueta" => "etiqueta",
		"color" => "color"
	);
}
?>
