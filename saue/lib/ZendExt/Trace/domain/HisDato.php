<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class HisDato extends BaseHisDato
{

    public function setUp()
    {
        $this->hasOne('NomOperacion', array('local' => 'idoperacion',
            'foreign' => 'idoperacion'));
    }

    public function cantidad($idestructura, $idtipotraza, $ip_host, $rol, $dominio, $estructuracomun, $fecha_desde, $fecha_hasta)
    {
        $count = 0;
        $where = 't.idtipotraza = ?';
        $valores[$count] = $idtipotraza;
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
            $where .= ' AND t.fecha >= ?';
            $valores[$count] = $fecha_desde;
        }
        if ($fecha_hasta != 0) {
            $count++;
            $where .= ' AND t.fecha <= ?';
            $valores[$count] = $fecha_hasta;
        }

        $query = Doctrine_Query::create();
        return $query->select('COUNT(t.idtraza) cantidad')
            ->from('HisDato t')
            ->where($where, $valores)
            ->execute()
            ->toArray();
    }

    public function select($idestructura, $idtipotraza, $ip_host, $rol, $dominio, $estructuracomun, $fecha_desde, $fecha_hasta, $offset, $limit)
    {
        $count = 0;
        $where = 't.idtipotraza = ?';
        $valores[$count] = $idtipotraza;
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
            $where .= ' AND t.fecha >= ?';
            $valores[$count] = $fecha_desde;
        }
        if ($fecha_hasta != 0) {
            $count++;
            $where .= ' AND t.fecha <= ?';
            $valores[$count] = $fecha_hasta;
        }
        $query = Doctrine_Query::create();
        $result = $query->select('t.*, o.denominacion')
            ->from('HisDato t, t.NomOperacion o')
            ->where($where, $valores)
            ->offset($offset)
            ->limit($limit)
            ->orderBy('t.fecha DESC')
            ->execute()->toArray(true);

        $data = array();

        foreach ($result as $row) {
            $item = $row;
            $item['idoperacion'] = $row ['NomOperacion'] ['denominacion'];
            $data[] = $item;
            $item = null;
        }

        return $data;
    }

}
