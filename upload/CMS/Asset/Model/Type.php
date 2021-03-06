<?php

namespace Asset\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Representation of a type of asset
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 * @Table(name="asset_type")
 * @property string $sysname
 * @property string $title
 * @property Asset\Model\Type $type
 */
class Type extends \Core\Model\AbstractModel
{
    /**
     * @var integer
     * @Id @Column(name="sysname", type="string", length="50")
     */
    protected $sysname;

    /**
     * @var string
     * @Column(name="title", type="string", length="150")
     */
    protected $title;

    /**
     * @var Asset\Model\MimeType[]
     * @OneToMany(targetEntity="Asset\Model\MimeType", mappedBy="type")
     */
    protected $mimeTypes;

    /**
     * @param string $sysname
     * @param string $title
     */
    public function __construct($sysname, $title)
    {
        $this->setSysname($sysname);
        $this->setTitle($title);
        $this->setMimeTypes(new ArrayCollection());
    }

    /**
     * @param string $sysname
     * @return Type
     */
    public function setSysname($sysname)
    {
        $validator = new \Zend_Validate_StringLength(0, 50);
        if (!$validator->isValid($sysname)) {
            throw new \Core\Model\Exception('Sysname must be between 0 and 50 characters.');
        }
        $this->sysname = $sysname;
        return $this;
    }

    /**
     * @param string $title
     * @return Type
     */
    public function setTitle($title)
    {
        $validator = new \Zend_Validate_StringLength(0, 150);
        if (!$validator->isValid($title)) {
            throw new \Core\Model\Exception('Title must be between 0 and 150 characters.');
        }
        $this->title = $title;
        return $this;
    }
}