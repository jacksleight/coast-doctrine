<?php
/* 
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Coast\Doctrine\ORM\Internal\Hydration;

use Doctrine\ORM\Internal\Hydration\ArrayHydrator as DoctrineArrayHydrator;

class ArrayHydrator extends DoctrineArrayHydrator
{
    protected function hydrateAllData()
    {
        return \Coast\array_object_smart(parent::hydrateAllData());
    }
}