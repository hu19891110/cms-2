<?php

namespace User\Model\Acl;

/**
 * Representation of a resource that can have permissions restricted or allowed
 *
 * @package     CMS
 * @subpackage  User
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 * @Table(name="resource")
 *
 * @property int $id
 */
class Resource extends \Core\Model\AbstractModel implements \Zend_Acl_Resource_Interface
{
    /**
     * @var string
     * @Id @Column(type="string", name="sysname", length="50", nullable="true")
     */
    protected $sysname;

    /**
     * @var string
     * @Column(type="string", name="parent", length="50", nullable="true")
     */
    protected $parent;

    public function __construct($resource, $parent = null)
    {
        $this->setSysname($resource);
        $this->setParent($parent);
    }

    public function setSysname($sysname)
    {
        $validator = new \Zend_Validate_StringLength(1, 50);
        if (!$validator->isValid($sysname)) {
            throw new \Core\Model\Exception('Sysname must be between 1 and 50 characters.');
        }
        $this->sysname = $sysname;
        return $this;
    }

    public function setParent($parent = null)
    {
        if (null !== $parent) {
            $validator = new \Zend_Validate_StringLength(1, 50);
            if (!$validator->isValid($parent)) {
                throw new \Core\Model\Exception('Sysname must be between 1 and 50 characters.');
            }
        }
        $this->parent = $parent;
        return $this;
    }

    public function getResourceId() {
        return $this->sysname;
    }
}