<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StoreV2
 *
 * @author ino
 */
class StoreV2 {

//put your code here
    public static function body_OrderBy($arr = array()) {

        return "\$_result=\$_em->createQuery(\"SELECT _o FROM {$arr['table']} _o ORDER BY _o.\$arr['field'] ASC\")\n"
                . "->getArrayResult();\n"
                . "return \$_result;";
    }

    public static function body_find($arr = array()) {
        return "\$_result=\$_em->createQuery(\"SELECT _r FROM {$arr['table']} _r WHERE _r.\$arr['field']=\$arr['value']\")\n"
                . "->setFirstResult(1)\n"
                . "->setMaxResults(1)\n"
                . "->getArrayResult();\n"
                . "return \$_result;";
    }

    public static function body_findAll($arr = array()) {
        return "\$_result=\$_em->createQuery(\"SELECT _s FROM {$arr['table']} _s\")\n"
                . "->getArrayResult();\n"
                . "return \$_result;";
    }
    public static function body_getCount($arr = array()) {
        $table=$arr['table'];
        return "\$_result=\$_em->createQuery(\"SELECT _s FROM {$arr['table']} _s\")\n"
                . "->getArrayResult();\n"
                . "return count(\$_result);";
    }

    public static function body_findOneBy($arr = array()) {
        return "\$_result=\$_em->createQuery(\"SELECT _r FROM {$arr['table']} _r WHERE _r.\$arr['field']=\$arr['value']\")\n"
                . "->setFirstResult(\$arr['start'])\n"
                . "->setMaxResults(\$arr['limit'])\n"
                . "->getArrayResult();\n"
                . "return \$_result;";
    }

    public static function body_getKey($arr = array()) {
        return "\$_result=\$_em->createQuery(\"SELECT _o FROM {$arr['table']} _o \")\n"
                . "->getArrayResult();\n"
                . "return \$_result;";
    }

    public static function body_xLimite($arr = array()) {

        return "\$_result=\$_em->createQuery(\"SELECT _r FROM {$arr['table']} _r\")\n"
                . "->setFirstResult({\$arr['start']})\n"
                . "->setMaxResults({\$arr['limit']})\n"
                . "->getArrayResult();\n"
                . "return \$_result;";
    }

    public static function body_save($arr = array()) {
        return "\$_em->persist({\$arr['obj']});\n"
                . "\$_em->flush();\n"
                . "return;";
    }

}
