<?php

class HisProceso extends BaseHisProceso
{

    public function setUp()
    {
        parent :: setUp();
    }

    public function cantidad($idestructura, $idtipotraza, $ip_host, $rol, $dominio, $estructuracomun, $fecha_desde, $fecha_hasta, $campos)
    {
        $count = 0;
        $where = 't.idtipotraza = ?';
        $valores[$count] = $idtipotraza;
        if (isset($campos->idusuario) && $campos->idusuario) {
            $count++;
            $where .= ' AND t.usuario LIKE ?';
            $valores[$count] = '%' . $campos->idusuario . '%';
        }
        if ($idestructura != 0) {
            $count++;
            $where .= ' AND t.idestructuracomun = ?';
            $valores[$count] = $idestructura;
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
            $count++;
            $where .= ' AND fecha >= ?';
            $valores[$count] = $fecha_desde;
        }
        if ($fecha_hasta != 0) {
            $count++;
            $where .= ' AND fecha <= ?';
            $valores[$count] = $fecha_hasta;
        }

        $query = Doctrine_Query::create();
        return $query->select('COUNT(t.idtraza) cantidad')
            ->from('HisIocexception t')
            ->where($where, $valores)
            ->execute()
            ->toArray();
    }

    public function select($idestructura, $idtipotraza, $ip_host, $rol, $dominio, $estructuracomun, $fecha_desde, $fecha_hasta, $offset, $limit, $campos)
    {
        $count = 0;
        $where = 't.idtipotraza = ?';
        $valores[$count] = $idtipotraza;
        if (isset($campos->idusuario) && $campos->idusuario) {
            $count++;
            $where .= ' AND t.usuario LIKE ?';
            $valores[$count] = '%' . $campos->idusuario . '%';
        }
        if ($idestructura != 0) {
            $count++;
            $where .= ' AND t.idestructuracomun = ?';
            $valores[$count] = $idestructura;
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
            $count++;
            $where .= ' AND fecha >= ?';
            $valores[$count] = $fecha_desde;
        }
        if ($fecha_hasta != 0) {
            $count++;
            $where .= ' AND fecha <= ?';
            $valores[$count] = $fecha_hasta;
        }
        $query = Doctrine_Query::create();
        return $query->select('t.*')
            ->from('HisIocexception t')
            ->where($where, $valores)
            ->offset($offset)
            ->limit($limit)
            ->orderBy('t.fecha DESC')
            ->execute()->toArray(true);
    }

}

