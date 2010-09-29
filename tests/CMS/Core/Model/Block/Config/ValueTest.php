<?php
namespace Core\Model\Block\Config;

require_once 'PHPUnit/Framework.php';

/**
 * Test class for Value.
 * Generated by PHPUnit on 2010-01-28 at 17:04:45.
 */
class ValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Value
     */
    protected $value;

    protected $content;
    protected $view;
    protected $block;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->value = new Value('awesome', 'test');
        $this->content = new \Mock\Content();
        $this->view = new \Core\Model\View('Core', 'Text', 'text');
        $this->block = new \Core\Model\Block\StaticBlock($this->content, $this->view);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testGetValue()
    {
        $this->assertEquals('test', $this->value->getValue('awesome'));

        $this->value->setInheritsFrom($this->block);
        $this->assertEquals('test', $this->value->getValue(true));
    }

    public function testSetBlock()
    {
        $this->value->setBlock($this->block);
        $this->assertEquals($this->block, $this->value->getBlock());
    }

    /**
     * @todo Implement testSetName().
     */
    public function testSetName()
    {
        $n = 'name';
        $this->value->setName($n);
        $this->assertEquals($n, $this->value->name);

        for($i = 0; $i < 20; $i++)
        {
            $n .= 'hereis10ch';
        }

        $this->setExpectedException('Exception');
        $this->value->setName($n);
    }

    /**
     * @todo Implement testSetValue().
     */
    public function testSetValue()
    {
        $v = 'value';
        $this->value->setValue($v);
        $this->assertEquals($v, $this->value->value);

        for($i = 0; $i < 51; $i++)
        {
            $v .= 'hereis10ch';
        }

        $this->setExpectedException('Exception');
        $this->value->setValue($v);
    }

    /**
     * @todo Implement testSetInheritsFrom().
     */
    public function testSetInheritsFrom()
    {
        $this->value->setInheritsFrom($this->block);
        $this->assertEquals($this->block, $this->value->inheritsFrom);

        $this->setExpectedException('Exception');
        $this->value->setInheritsFrom(new \stdClass());
    }
}
?>
