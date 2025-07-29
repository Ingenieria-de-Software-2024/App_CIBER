<?php

namespace Model;

use Exception;

class Bitacora extends ActiveRecord
{
    protected static $tabla = 'bitacora';
    protected static $idTabla = 'bita_id';
    protected static $errores = [];

    protected static $columnasDB = [
        'bita_id',
        'bita_fecha',
        'bita_malware',
        'bita_pishing',
        'bita_coman_cont',
        'bita_cryptomineria',
        'bita_ddos',
        'bita_conex_bloq',
        'bita_total',
        'bita_situacion' // ✅ necesario
    ];


    public $bita_id;
    public $bita_fecha;
    public $bita_malware;
    public $bita_pishing;
    public $bita_coman_cont;
    public $bita_cryptomineria;
    public $bita_ddos;
    public $bita_conex_bloq;
    public $bita_total;
    public $bita_situacion;

    public function __construct($args = [])
    {
        $this->bita_id = $args['bita_id'] ?? null;
        $this->bita_fecha = $args['bita_fecha'] ?? '';
        $this->bita_malware = (int)($args['bita_malware'] ?? 0);
        $this->bita_pishing = (int)($args['bita_pishing'] ?? 0);
        $this->bita_coman_cont = (int)($args['bita_coman_cont'] ?? 0);
        $this->bita_cryptomineria = (int)($args['bita_cryptomineria'] ?? 0);
        $this->bita_ddos = (int)($args['bita_ddos'] ?? 0);
        $this->bita_conex_bloq = (int)($args['bita_conex_bloq'] ?? 0);
        $this->bita_total = (int)($args['bita_total'] ?? 0);
        $this->bita_situacion = (int)($args['bita_situacion'] ?? 1); // ✅ Valor por defecto: activo
    }

    public function validar(): array
    {
        return self::$errores;
    }

    public static function buscarActivos(?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        $sql = "SELECT * FROM " . self::$tabla . " WHERE BITA_SITUACION = 1";
        $params = [];

        if (!empty($fechaInicio)) {
            $sql .= " AND bita_fecha >= :fecha_inicio";
            $params[':fecha_inicio'] = $fechaInicio;
        }

        if (!empty($fechaFin)) {
            $sql .= " AND bita_fecha <= :fecha_fin";
            $params[':fecha_fin'] = $fechaFin;
        }

        $sql .= " ORDER BY bita_fecha DESC";

        return self::consultarSQL($sql, $params); // 👈 ¡IMPORTANTE! Enviar $params
    }

}
