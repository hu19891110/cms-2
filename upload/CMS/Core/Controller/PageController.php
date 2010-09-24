<?php

namespace Core\Controller;

/**
 * Controller for actions on pages
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class PageController extends \Zend_Controller_Action
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;

    /**
     * @var \Core\Model\Page
     */
    protected $_page;

    public function init()
    {
        $this->_em = \Zend_Registry::get('doctrine');
        if (!$pageId = $this->getRequest()->getParam('id', false)) {
            throw new \Exception('Page not set.');
        }
        if (!$this->_page = $this->_em->getRepository('Core\Model\Page')->getPageForRender($pageId)) {
            throw new \Exception('Page does not exist.');
        }
    }

    public function viewAction()
    {
        if (!\Core\Auth\Auth::getInstance()->getIdentity()->isAllowed($this->_page, 'view')) {
            throw new \Exception('Not allowed to view page.');
        }

        // Initialize blocks
        foreach ($this->_page->getBlocks() as $block) {
            if ($block instanceof \Core\Model\Block\DynamicBlock) {
                // Initialize the dynamic block
                $block->setRequest($this->getRequest());
                $block->setEntityManager($this->_em);
                $block->init();
            }
        }

        // Render blocks into block wrapper
        $blockActions = array();
        foreach ($this->_page->getBlocks() as $block) {
            if ($block->canView(\Core\Auth\Auth::getInstance()->getIdentity())) {
                $view = new \Zend_View();
                $view->assign('content', $block->render());
                $view->assign('block', $block);
                $view->assign('page', $this->_page);
                $edit = $this->getRequest()->getParam('edit', true);
                $view->assign('edit', $edit);
                $view->setBasePath(APPLICATION_ROOT . '/themes/default/layouts');
                $block->getLocation()->addContent($view->render('partials/block.phtml'));
            }
        }

        // Set the layout
        $this->_page->getLayout()->assign('page', $this->_page);
        $this->getResponse()->setBody($this->_page->getLayout()->render());
    }

    /**
     * @todo implement this
     */
    public function addBlockAction()
    {
        if (!\Core\Auth\Auth::getInstance()->getIdentity()->isAllowed($this->_page, 'edit')) {
            throw new \Exception('Not allowed to edit page.');
        }

        //$this->_em->getRepository('Core\Model\Content\Text')->findSharedContent();
        if ($this->getRequest()->isPost()) {
        }
        throw new \Exception('Adding pages not implemented yet.');
    }

    public function editAction()
    {
        if (!\Core\Auth\Auth::getInstance()->getIdentity()->isAllowed($this->_page, 'edit')) {
            throw new \Exception('Not allowed to edit page.');
        }

        $frontend = new \Core\Model\Frontend\Simple();
        
        $form = ($this->_page instanceof \Core\Model\Page) ? new \Core\Form\Page()
                                                          : new \Core\Form\AbstractPage();
        $form->setAction('/direct/page/edit?id=' . $this->_page->getId());

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            if ($form->isValid($data)) {
                unset($data['id']);

                $this->_page->setLayout($this->_em->getReference('Core\Model\Layout', $data['layout']));
                unset($data['layout']);

                $this->_page->setData($data);
                $this->_em->flush();
                $frontend->success();
            } else {
                $frontend->fail();
            }
        }

        $form->setObject($this->_page);
        $frontend->html = (string)$form;
        
        $html = $this->getRequest()->getParam('html');
        if (isset($html)) {
            $this->_page->getLayout()->getLocation('main')->addContent($frontend->html);
            $this->_page->getLayout()->assign('page', $this->_page);
            echo $this->_page->getLayout()->render();
        } else {
            echo $frontend;
        }
    }

    /**
     * Rearranges the blocks on the page
     */
    public function rearrangeAction()
    {
        if (!\Core\Auth\Auth::getInstance()->getIdentity()->isAllowed($this->_page, 'edit')) {
            throw new \Exception('Not allowed to edit page.');
        }

        $receivedfrontendObject = $this->getRequest()->getParam('page', null);
        if (!isset($receivedfrontendObject)) {
            $frontendObject = new \Core\Model\Frontend\Simple();
            die($frontendObject->fail('Page object not sent.'));
        }

        try {
            $receivedfrontendObject = \Zend_Json::decode($receivedfrontendObject, \Zend_Json::TYPE_OBJECT);
            foreach($receivedfrontendObject->data[0]->locations AS $frontendLocation) {
                foreach ($frontendLocation->blocks AS $frontendKey => $frontendBlock) {
                    foreach ($this->_page->blocks AS $key => $block) {
                        if ($frontendBlock->id == $block->id) {
                            $this->_page->blocks[$key]->location = $this->_em->getReference('Core\Model\Layout\Location', $frontendLocation->sysname);
                            $this->_page->blocks[$key]->weight = $frontendKey;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $frontendObject = new \Core\Model\Frontend\Simple();
            die($frontendObject->fail('Page object not sent.'));
        }
        
        $this->_em->flush();

        $frontendObject = new \Core\Model\Frontend\PageInfo();
        echo $frontendObject->success($this->_page);
    }

    /**
     * Gets information on the current page
     */
    public function infoAction()
    {
        if (!\Core\Auth\Auth::getInstance()->getIdentity()->isAllowed($this->_page, 'edit')) {
            throw new \Exception('Not allowed to edit page.');
        }

        $frontendObject = new \Core\Model\Frontend\PageInfo();

        echo $frontendObject->success($this->_page);
    }


    /**
     * Deletes the current page
     */
    public function deleteAction()
    {
        if (!\Core\Auth\Auth::getInstance()->getIdentity()->isAllowed($this->_page, 'delete')) {
            throw new \Exception('Not allowed to delete page.');
        }
        /*
         * @todo message notifying users if content exists on other pages
         * @todo message notifying users where content exists
         */
        
        $page = $this->_page;

        foreach($page->dependentContent as $content)
        {
            $staticBlocks = $this->_em->getRepository('Core\Model\Block\StaticBlock')->getContentStaticBlocks($content);
            foreach($staticBlocks as $block)
            {
                $this->_em->remove($block);
            }
            $this->_em->remove($content);
        }

        $this->_em->remove($page);
        $this->_em->flush();
    }
}