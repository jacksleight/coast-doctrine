<?php
/* 
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Coast\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder,
    Doctrine\ORM\Query,
    Doctrine\ORM\Tools\Pagination\Paginator,
    Doctrine\Common\EventSubscriber;

class EntityManager extends \Doctrine\ORM\Decorator\EntityManagerDecorator implements \Coast\App\Access
{
    protected $_listeners = [];
    
    use \Coast\App\Access\Implementation;
    
    public function __construct($wraped)
    {
        parent::__construct($wraped);

        $config = $this->getConfiguration();
        $functions = [
            'CEILING'         => 'Coast\Doctrine\ORM\Query\MySql\Ceiling',
            'DATE_FORMAT'     => 'Coast\Doctrine\ORM\Query\MySql\DateFormat',
            'FIELD'           => 'Coast\Doctrine\ORM\Query\MySql\Field',
            'FLOOR'           => 'Coast\Doctrine\ORM\Query\MySql\Floor',
            'IF'              => 'Coast\Doctrine\ORM\Query\MySql\IfElse',
            'MONTH'           => 'Coast\Doctrine\ORM\Query\MySql\Month',
            'RAND'            => 'Coast\Doctrine\ORM\Query\MySql\Rand',
            'ROUND'           => 'Coast\Doctrine\ORM\Query\MySql\Round',
            'UTC_DATE'        => 'Coast\Doctrine\ORM\Query\MySql\UtcDate',
            'UTC_TIME'        => 'Coast\Doctrine\ORM\Query\MySql\UtcTime',
            'UTC_TIMESTAMP'   => 'Coast\Doctrine\ORM\Query\MySql\UtcTimestamp',
            'YEAR'            => 'Coast\Doctrine\ORM\Query\MySql\Year',
            'SUBSTRING_INDEX' => 'Coast\Doctrine\ORM\Query\MySql\SubstringIndex',
        ];
        foreach ($functions as $name => $class) {
            $config->addCustomStringFunction($name, $class);
        }
        
        $config->addCustomHydrationMode('coast_array', 'Coast\Doctrine\ORM\Internal\Hydration\ArrayHydrator');
    } 

    public function getWrapped()
    {
        return $this->wrapped;
    }

    public function __invoke($class)
    {
        return $this->getRepository($class);
    }

    public function ref($class, $id)
    {
        return $this->getReference(Chalk::info($class)->class, $id);
    }

    public function dir($name, \Coast\Dir $dir)
    {
        $this
            ->getConfiguration()
            ->getMetadataDriverImpl()
            ->addPaths([$name => $dir->name()]);
    }

    public function listener($name, EventSubscriber $listener = null)
    {
        if (func_num_args() > 1) {
            $this->_listeners[$name] = $listener;
            $this
                ->getEventManager()
                ->addEventSubscriber($listener);
            return $this;
        }
        return isset($this->_listeners[$name])
            ? $this->_listeners[$name]
            : null;
    }

    public function isPersisted($entity)
    {
        $uow = $this->getUnitOfWork();
        return $uow->getEntityState($entity) == \Doctrine\ORM\UnitOfWork::STATE_MANAGED;
    }

    public function changes($entity)
    {
        $uow = $this->getUnitOfWork();
        $uow->computeChangeSets();
        return $uow->getEntityChangeSet($entity);
    }

    public function paginate(QueryBuilder $query, $limit, $page)
    {
        $query
            ->setMaxResults($limit)
            ->setFirstResult($limit * ($page - 1));
        $paginator = new Paginator($query->getQuery());
        $paginator->setUseOutputWalkers(false);
        return $paginator;
    }
}