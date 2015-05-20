<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class HisIoc extends BaseHisIoc
{

    public function cantidad($idestructura, $idtipotraza, $ip_host, $rol, $dominio, $estructuracomun, $fecha_desde, $fecha_hasta, $campos)
    {
        $where = 't.idtipotraza = ?';
        $valores[] = $idtipotraza;
        if (isset($campos->idusuario) && $campos->idusuario) {
            $where .= ' AND t.usuario LIKE ?';
            $valores[] = '%' . $campos->idusuario . '%';
        }
        if (isset($campos->idinterno) && ($campos->idinterno == "0" || $campos->idinterno == "1")) {
            $where .= ' AND t.interno = ?';
            $valores[] = $campos->idinterno;
        }
        if (isset($campos->idorigen) && $campos->idorigen) {
            $where .= ' AND t.origen LIKE ?';
            $valores[] = '%' . $campos->idorigen . '%';
        }
        if (isset($campos->iddestino) && $campos->iddestino) {
            $where .= ' AND t.destino LIKE ?';
            $valores[] = '%' . $campos->iddestino . '%';
        }
        if (isset($campos->clase) && $campos->clase) {
            $where .= ' AND t.clase LIKE ?';
            $valores[] = '%' . $campos->clase . '%';
        }
        if (isset($campos->metodo) && $campos->metodo) {
            $where .= ' AND t.metodo LIKE ?';
            $valores[] = '%' . $campos->metodo . '%';
        }
        if (isset($campos->idaccion) && $campos->idaccion) {
            $where .= ' AND t.accion LIKE ?';
            $valores[] = '%' . $campos->idaccion . '%';
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
            ->from('HisIoc t')
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
        if (isset($campos->idinterno) && ($campos->idinterno == "0" || $campos->idinterno == "1")) {
            $where .= ' AND t.interno = ?';
            $valores[] = $campos->idinterno;
        }
        if (isset($campos->idorigen) && $campos->idorigen) {
            $where .= ' AND t.origen LIKE ?';
            $valores[] = '%' . $campos->idorigen . '%';
        }
        if (isset($campos->iddestino) && $campos->iddestino) {
            $where .= ' AND t.destino LIKE ?';
            $valores[] = '%' . $campos->iddestino . '%';
        }
        if (isset($campos->idaccion) && $campos->idaccion) {
            $where .= ' AND t.accion LIKE ?';
            $valores[] = '%' . $campos->idaccion . '%';
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
        $result = $query->select('t.*')
            ->from('HisIoc t')
            ->where($where, $valores)
            ->offset($offset)
            ->limit($limit)
            ->orderBy('t.fecha DESC')
            ->execute()->toArray(true);
        $query->free();
        return $result;
    }

}
