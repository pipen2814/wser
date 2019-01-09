<?php

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class Accounts
 *
 * ORM para la tabla cuentas
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class Accounts extends BaseORM{

	protected $tableName = "cuentas";
	protected $id = array("id_cuenta");
	protected $fields = array(
		"id_cuenta" => "idCuenta",
		"cuenta" => "cuenta",
		"fecha_creacion" => "fechaCreacion",
		"fecha_baja" => "fechaBaja",
		"activo" => "activo"
	);
}
?>
