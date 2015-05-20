<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StoreV1
 *
 * @author ino
 */
class StoreV1
{

//put your code here
    public static function body_OrderBy($arr = array())
    {

        return "\$query = Doctrine_Query::create();\n"
        . " \$datos = \$query ->select('s')\n"
        . "->from('{$arr['table']}')\n"
        . "->orderBy('{$arr['field']}')\n"
        . "->execute();\n"
        . "return \$datos;";
    }

    public static function body_find($arr = array())
    {
        return "\$result = Doctrine::getTable('{$arr['table']}')->find({$arr['field']});\n"
        . "return \$_result;";
    }

    public static function body_findAll($arr = array())
    {
        return "\$result = Doctrine::getTable('{$arr['table']}')->find({$arr['field']});\n"
        . "return \$_result;";
    }

    public static function body_getCount($arr = array())
    {
        return "\$query = Doctrine_Query::create();\n"
        . " \$datos = \$query ->select('t')\n"
        . "->from('{$arr['table']}')\n"
        . "->execute();\n"
        . "return count(\$datos)";
    }

    public static function body_findOneBy($arr = array())
    {
        return "\$result = Doctrine::getTable('{$arr['table']}')->findOneBy{$arr['field']}({$arr['field']});\n"
        . "return \$_result;";
    }

    public static function body_getKey($arr = array())
    {
        return "\$query = Doctrine_Query::create();\n"
        . " \$datos = \$query ->select('t')\n"
        . "->from('{$arr['table']}')\n"
        . "->execute();\n"
        . "return count(\$datos)";
    }

    public static function body_xLimite($arr = array())
    {
        return "\$query = Doctrine_Query::create();\n"
        . " \$datos = \$query ->select('t')\n"
        . "->from('{$arr['table']}')\n"
        . "->limit('{$arr['limit']}')\n"
        . " ->offset('{$arr['start']}')"
        . "->execute();\n"
        . "return count(\$datos)";
    }

    public static function body_save($arr = array())
    {
        return "{$arr['obj']}->save();\n"
        . "return;";
    }

}
