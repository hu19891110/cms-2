<?php

namespace Core\Module\Registry;

/**
 * Loads modules and components from the module.ini files.
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Module
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class ConfigStorage extends AbstractStorage
{
    public function load()
    {
        $modules = array();
        $controllerDirs = \Zend_Controller_Front::getInstance()->getDispatcher()->getControllerDirectory();
        foreach ($controllerDirs AS $controllerDir) {
            if ($configPath = realpath($controllerDir . '/../module.ini')) {
                $config = new \Zend_Config_Ini($configPath);
                $module = \Core\Service\Module::createModuleFromConfig($config);
                $modules[$module->sysname] = $module;
            }
        }
        return $modules;
    }
}