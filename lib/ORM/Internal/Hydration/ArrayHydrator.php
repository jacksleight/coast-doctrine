<?php
/* 
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Coast\Doctrine\ORM\Internal\Hydration;

use Doctrine\ORM\Internal\Hydration\ArrayHydrator as DoctrineArrayHydrator;

class ArrayHydrator extends DoctrineArrayHydrator
{
    protected function gatherRowData(array $data, array &$id, array &$nonemptyComponents)
    {
        $rowData = parent::gatherRowData($data, $id, $nonemptyComponents);

        foreach ($rowData['data'] as $dqlAlias => $data) {
            $class = $this->_rsm->aliasMap[$dqlAlias];
            $meta  = $this->getClassMetadata($class);
            if ($meta->discriminatorMap) {
                $class = $meta->discriminatorMap[$data[$meta->discriminatorColumn['name']]];
            }
            $rowData['data'][$dqlAlias]['__CLASS__'] = $class;
        }

        return $rowData;
    }
}