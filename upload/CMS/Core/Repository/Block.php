<?php

namespace Core\Repository;

/**
 * Repository for the block model
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Repository
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Block extends \Doctrine\ORM\EntityRepository
{

    public function getBlocksForPage(\Core\Model\AbstractPage $page)
    {
        $qb = $this->_em->getRepository('Core\Model\Block')->createQueryBuilder('b');
        $qb->select('b, cv');
        $qb->leftJoin('b.configValues', 'cv');
        $qb->where('b.page = :page');
        $qb->setParameter('page', $page);

        return $qb->getQuery()->getResult();
    }
    
    public function getDependentValues(\Core\Model\Block $block)
    {
        $qb = $this->_em->getRepository('Core\Model\Block\Config\Value')->createQueryBuilder('v');
        $qb->innerJoin('v.inheritsFrom', 'i');
        $qb->where('i.id = :block_id');
        $qb->setParameter('block_id', $block->id);

        return $qb->getQuery()->getResult();
    }

    public function findAddableBlocks()
    {
        return $this->_em->getRepository('Core\Model\Module\Block')->findByAddable(true);
    }
}