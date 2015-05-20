<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class HisRendimiento extends BaseHisRendimiento
{

    public function setUp()
    {

    }

    public function cantidad($idestructura, $idtipotraza, $ip_host, $rol, $dominio, $estructuracomun, $fecha_desde, $fecha_hasta, $campos)
    {
        $where = 't.idtipotraza = ?';
        $valores[] = $idtipotraza;
        if (isset($campos->idusuario) && $campos->idusuario) {
            $where .= ' AND t.usuario LIKE ?';
            $valores[] = '%' . $campos->idusuario . '%';
        }
        if (isset($campos->idreferencia) && $campos->idreferencia) {
            $where .= ' AND t.referencia LIKE ?';
            $valores[] = '%' . $campos->idreferencia . '%';
        }
        if (isset($campos->idcontrolador) && $campos->idcontrolador) {
            $where .= ' AND t.controlador LIKE ?';
            $valores[] = '%' . $campos->idcontrolador . '%';
        }
        if (isset($campos->idaccion) && $campos->idaccion) {
            $where .= ' AND t.accion LIKE ?';
            $valores[] = '%' . $campos->idaccion . '%';
        }
        if (isset($campos->idcomp1) && isset($campos->idtiempoejecucion) && $campos->idcomp1 && $campos->idtiempoejecucion) {
            $where .= ' AND t.tiempoejecucion ' . $campos->idcomp1 . ' ?';
            $valores[] = $campos->idtiempoejecucion;
        }
        if (isset($campos->idcomp2) && isset($campos->idmemoria) && $campos->idcomp2 && $campos->idmemoria) {
            $where .= ' AND t.memoria ' . $campos->idcomp2 . ' ?';
            $valores[] = $campos->idmemoria;
        }
        if ($idestructura != 0) {
            $where .= ' AND t.idestructuracomun = ?';
            $valores[] = $idestructura;
        }
        if ($ip_host != 0) {
            $where .= ' AND t.ip_host = ?';
            $valores[] = $ip_host;
        }
        if ($rol != 0) {
            $where .= ' AND t.rol = ?';
            $valores[] = $rol;
        }
        if ($dominio != 0) {
            $where .= ' AND t.dominio = ?';
            $valores[] = $dominio;
        }
        if ($estructuracomun != 0) {
            $where .= ' AND t.estructuracomun = ?';
            $valores[] = $estructuracomun;
        }
        if ($fecha_desde != 0) {
            $where .= ' AND t.fecha >= ?';
            $valores[] = $fecha_desde;
        }
        if ($fecha_hasta != 0) {
            $where .= ' AND t.fecha <= ?';
            $valores[] = $fecha_hasta;
        }
        $query = Doctrine_Query::create();
        $result = $query->select('COUNT(t.idtraza) cantidad')
            ->from('HisRendimiento t')
            ->where($where, $valores)
            ->execute()
            ->toArray();
        $query->free();
        return $result;
    }

    public function select($idestructura, $idtipotraza, $ip_host, $rol, $dominio, $estructuracomun, $fecha_desde, $fecha_hasta, $offset, $limit, $campos)
    {
        $where = 't.idtipotraza = ?';
        $valores[] = $idtipotraza;
        if (isset($campos->idusuario) && $campos->idusuario) {
            $where .= ' AND t.usuario LIKE ?';
            $valores[] = '%' . $campos->idusuario . '%';
        }
        if (isset($campos->idreferencia) && $campos->idreferencia) {
            $where .= ' AND t.referencia LIKE ?';
            $valores[] = '%' . $campos->idreferencia . '%';
        }
        if (isset($campos->idcontrolador) && $campos->idcontrolador) {
            $where .= ' AND t.controlador LIKE ?';
            $valores[] = '%' . $campos->idcontrolador . '%';
        }
        if (isset($campos->idaccion) && $campos->idaccion) {
            $where .= ' AND t.accion LIKE ?';
            $valores[] = '%' . $campos->idaccion . '%';
        }
        if (isset($campos->idcomp1) && isset($campos->idtiempoejecucion) && $campos->idcomp1 && $campos->idtiempoejecucion) {
            $where .= ' AND t.tiempoejecucion ' . $campos->idcomp1 . ' ?';
            $valores[] = $campos->idtiempoejecucion;
        }
        if (isset($campos->idcomp2) && isset($campos->idmemoria) && $campos->idcomp2 && $campos->idmemoria) {
            $where .= ' AND t.memoria ' . $campos->idcomp2 . ' ?';
            $valores[] = $campos->idmemoria;
        }
        if ($idestructura != 0) {
            $where .= ' AND t.idestructuracomun = ?';
            $valores[] = $idestructura;
        }
        if ($ip_host != 0) {
            $where .= ' AND t.ip_host = ?';
            $valores[] = $ip_host;
        }
        if ($rol != 0) {
            $where .= ' AND t.rol = ?';
            $valores[] = $rol;
        }
        if ($dominio != 0) {
            $where .= ' AND t.dominio = ?';
            $valores[] = $dominio;
        }
        if ($estructuracomun != 0) {
            $where .= ' AND t.estructuracomun = ?';
            $valores[] = $estructuracomun;
        }
        if ($fecha_desde != 0) {
            $where .= ' AND t.fecha >= ?';
            $valores[] = $fecha_desde;
        }
        if ($fecha_hasta != 0) {
            $where .= ' AND t.fecha <= ?';
            $valores[] = $fecha_hasta;
        }
        $query = Doctrine_Query::create();
        return $query->select('t.*')
            ->from('HisRendimiento t')
            ->where($where, $valores)
            ->offset($offset)
            ->limit($limit)
            ->orderBy('t.fecha DESC')
            ->execute()->toArray(true);
    }

}
