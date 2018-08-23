<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class Usuarios
 *
 * ORM para la tabla movimientos programados
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class ScheduledMovements extends BaseORM{

	protected $tableName = "movimientos_movements";
	protected $id = array("id_movimiento_programado");
	protected $fields = array(
		"id_movimiento_programado" => "idMovimientoProgramado",
		"movimiento" => "movimiento",
		"id_cuenta" => "idCuenta",
		"id_usuario" => "idUsuario",
		"importe" => "importe",
		"fecha_creacion" => "fechaCreacion",
		"fecha_desde_aplicacion" => "fechaDesdeAplicacion",
		"fecha_hasta_aplicacion" => "fechaHastaAplicacion",
		"fecha_informe" => "fechaInforme",
		"periodicidad" => "periodicidad",
		"id_grupo" => "idGrupo"
	);
}
