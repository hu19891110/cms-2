<?php

namespace Asset\Form;

/**
 * Form for searching the asset library
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Library extends \Core\Form\AbstractForm
{
    public static $sorts = array (
            0 => array(
                'text'  => 'Alphabetical: A to Z',
                'field' => 'name',
                'order' => 'ASC'
            ),
            1 => array(
                'text'  => 'Alphabetical: Z to A',
                'field' => 'name',
                'order' => 'DESC'
            ),
            2 => array(
                'text'  => 'Date: Newest to Oldest',
                'field' => 'uploadDate',
                'order' => 'DESC'
            ),
            3 => array(
                'text'  => 'Date: Oldest to Newest',
                'field' => 'uploadDate',
                'order' => 'ASC'
            )
        );

    public function init()
    {
        $this->setAction('/direct/asset/manager/list/');
        $this->setName('filter');

        $search = new \Core\Form\Element\Text('search');
        $search->setLabel('Search');

        $type = new \Core\Form\Element\Select('type');
        $type->setLabel('Type');
        $type->addMultiOption('all', 'All Types');
        $type->setValue('all');

        $sort = new \Core\Form\Element\Select('sort');
        $sort->setLabel('Sort');
        foreach(self::$sorts AS $key => $sortType) {
            $sort->addMultiOption($key, $sortType['text']);
        }
        $sort->setValue(2);

        $submit = new \Core\Form\Element\Submit('submit');
        $submit->setLabel('Search');

        $this->addElements(array($search, $type, $sort, $submit));
    }

    public function setTypes(array $types)
    {
        $element = $this->getElement('type');
        foreach ($types AS $type) {
            $element->addMultiOption($type->sysname, $type->title);
        }
    }
}