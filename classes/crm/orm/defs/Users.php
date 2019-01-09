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
class Users extends BaseORM{

	protected $tableName = "usuarios";
	protected $id = array("id_usuario");
	protected $fields = array(
		"id_usuario" => "idUsuario",
		"usuario" => "usuario",
		"clave" => "clave",
		"id_perfil" => "idPerfil",
		"fecha_creacion" => "fechaCreacion",
		"fecha_baja" => "fechaBaja",
		"activo" => "activo",
		"nombre" => "nombre",
		"apellidos" => "apellidos"
	);
}
?>
