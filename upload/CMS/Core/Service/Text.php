<?php

namespace Core\Service;

/**
 * Service for text content
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Text extends \Core\Service\AbstractService
{

    /**
     *
     * @param array $data
     * @return Core\Form\Text
     */
    public function getAddForm($data = null)
    {
        return new \Core\Form\Text();
    }

    /**
     *
     * @param Core\Model\Text $route
     * @param array $data
     * @return Core\Form\Text
     */
    public function getEditForm(\Core\Model\Content\Text $text, $data = null)
    {
        $form = new \Core\Form\Text;
        $form->setObject($text);
        if (null !== $data) {
            $form->populate($data);
        }
        return $form;
    }

    public function getShared()
    {
        return $this->_em->getRepository('Core\Model\Content\Text')->findSharedText();
    }
}