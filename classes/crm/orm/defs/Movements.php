<?php

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class Usuarios
 *
 * ORM para la tabla movimientos
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class Movements extends BaseORM{

	protected $tableName = "movimientos";
	protected $id = array("id_movimiento");
	protected $fields = array(
		"id_movimiento" => "idMovimiento",
		"id_cuenta" => "idCuenta",
		"id_usuario" => "idUsuario",
		"movimiento" => "movimiento",
		"tipo" => "tipo",
		"importe" => "importe",
		"fecha_creacion" => "fechaCreacion",
		"fecha_informe" => "fechaInforme",
		"id_grupo" => "idGrupo",
		"id_movimiento_programado" => "idMovimientoProgramado"
	);
}
