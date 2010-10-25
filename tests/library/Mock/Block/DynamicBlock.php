<?php

namespace Mock\Block;

class DynamicBlock extends \Core\Model\Block\DynamicBlock
{
    public function init(){}

    public function configure()
    {
        $this->addConfigProperty(new \Core\Model\Block\Config\Property('configPropertyName', 'defaultValue', true, true, 'Core\Model\Block\StaticBlock'));
        $this->addConfigProperty(new \Core\Model\Block\Config\Property('id', 0, true, true, 'Core\Model\Block\StaticBlock'));
    }
}