<?php

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class Usuarios
 *
 * ORM para la tabla cuentas_usuarios
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class AccountsUsers extends BaseORM{

	protected $tableName = "cuentas_usuarios";
	protected $id = array("id_cuenta", "id_usuario");
	protected $fields = array(
		"id_cuenta" => "idCuenta",
		"id_usuario" => "idUsuario"
	);
}
?>
