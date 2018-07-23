<?php

namespace Astra\SharedBundle\Utils;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\UnitOfWork;

class SqlUtils
{
    public static function generateFieldHydrate(array $Objects, EntityManager $em)
    {
        $request_fields = [];
        $sql_fields = [];
        $aliases = [];
        $tables = [];
        $entity = [];
        foreach($Objects as $alias => $object)
        {
            if(!$metaData = $em->getClassMetadata($object))continue;
            if($table = $metaData->table)$tables[$alias] = $table['name'];
            $entity[$alias] = $metaData->rootEntityName;

            if(!$fields = $metaData->columnNames)continue;
            foreach($fields as $name)
            {
                $aliasName = $alias.'_'.$name;

                $request_fields[$alias.'.'.$name] = $aliasName;
                $sql_fields[$alias.'.'.$name] = "`{$alias}`.`{$name}` AS `{$aliasName}`";
                $aliases[$alias][$name] = $aliasName;
            }
        }

        return ['entity' => $entity, 'tables' => $tables, 'request_fields' => $request_fields, 'sql_fields' => $sql_fields, 'alias' => $aliases];
    }
}
