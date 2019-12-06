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
        $config->addCustomHydrationMode('coast_array', 'Coast\Doctrine\ORM\Internal\Hydration\ArrayHydrator');
    } 

    public function getWrapped()
    {
        return $this->wrapped;
    }

    public function repository($class)
    {
        return $this->getRepository($class);
    }

    public function reference($class, $id)
    {
        return $this->getReference($class, $id);
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

    public function identifier($entity)
    {
        $uow = $this->getUnitOfWork();
        return $uow->getEntityIdentifier($entity);
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

    public function __invoke($class)
    {
        return $this->repository($class);
    }
}