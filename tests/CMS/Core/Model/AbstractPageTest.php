<?php
namespace Core\Model;

require_once 'PHPUnit/Framework.php';
require_once __DIR__ . '/../../../bootstrap.php';

/**
 * Test class for AbstractPage.
 * Generated by PHPUnit on 2010-01-14 at 10:00:52.
 */
class AbstractPageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractPage
     */
    protected $page;

    protected $block1;
    protected $block2;
    protected $block3;

    protected $left;
    protected $right;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->page = new Page(new Layout('test'));

        $this->left = new \Core\Model\Layout\Location('left');
        $this->right = new \Core\Model\Layout\Location('right');

        $this->block1 = new \Mock\Block();
        $this->block1->setLocation($this->right)->setWeight(0);
        $this->block2 = new \Mock\Block();
        $this->block2->setLocation($this->right)->setWeight(0);
        $this->block3 = new \Mock\Block();
        $this->block3->setLocation($this->right)->setWeight(0);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testAddBlock()
    {
        $this->page->addBlock($this->block1, $this->left, 0);
        $this->assertEquals(1, count($this->page->blocks));
        $this->assertEquals($this->left, $this->block1->location);
        $this->assertEquals(0, $this->block1->weight);
    }

    public function testAddBlockWithOutWeightOrLocation()
    {
        $this->block1->setWeight(4);

        $this->page->addBlock($this->block1);
        $this->assertEquals(1, count($this->page->blocks));
        $this->assertEquals($this->right, $this->block1->location);
        $this->assertEquals(4, $this->block1->weight);
    }

    public function testAddBlockFailLocation()
    {
    $this->setExpectedException('Core\Model\Exception');
        $this->block1->setLocation(null);
        $this->page->addBlock($this->block1);
    }

    public function testAddBlockFailWeight()
    {
        $this->block1->setWeight(null);
        $this->page->addBlock($this->block1, $this->left);
        $this->assertEquals(0, $this->block1->weight);
    }

    public function testAddBlocks()
    {
        $this->page->addBlocks(array($this->block1, $this->block2));
        $this->assertEquals(2, count($this->page->blocks));
    }

    public function testSetTitle()
    {
        $this->page->setTitle('Test');
        $this->assertEquals('Test', $this->page->getTitle());

        $this->page->setTitle(null);
        $this->assertEquals(null, $this->page->getTitle());

        $this->setExpectedException('Core\Model\Exception');
        $this->page->setTitle('This is a really long title that shouldn\'t fit and should
            therefore throw an exception. This is a really long title that shouldn\'t fit and should
            therefore throw an exception. This is a really long title that shouldn\'t fit and should
            therefore throw an exception.');
    }

    public function testSetAndGetTitle()
    {
        $this->page->setTitle('Test');
        $this->assertEquals('Test', $this->page->getTitle());

        $this->page->setTitle();
        $this->assertEquals(null, $this->page->getTitle());
    }

    public function testSetAndGetDescription()
    {
        $this->page->setDescription('This is a description');
        $this->assertEquals('This is a description', $this->page->getDescription());

        $this->page->setDescription();
        $this->assertEquals(null, $this->page->getDescription());

        $this->setExpectedException('Core\Model\Exception');
        $this->page->setDescription('This is a really long title that shouldn\'t fit and should
            therefore throw an exception. This is a really long title that shouldn\'t fit and should
            therefore throw an exception. This is a really long title that shouldn\'t fit and should
            therefore throw an exception. This is a really long title that shouldn\'t fit and should
            therefore throw an exception. This is a really long title that shouldn\'t fit and should
            therefore throw an exception. This is a really long title that shouldn\'t fit and should
            therefore throw an exception.');
    }

    public function testSetAndGetBlocks()
    {
        $blocks = new \Doctrine\Common\Collections\ArrayCollection(array($this->block1, $this->block2, $this->block3));
        $this->page->setBlocks($blocks);
        $this->assertEquals($blocks, $this->page->getBlocks());

        $this->page->setBlocks(null);
        $this->assertEquals(new \Doctrine\Common\Collections\ArrayCollection(), $this->page->getBlocks());
    }

    public function testSetAndGetBlocksFailedLocation()
    {
        $blocks = array($this->block1, $this->block2, $this->block3);
        $this->setExpectedException('Core\Model\Exception');
        $this->block3->setLocation();
        $this->page->setBlocks($blocks);
    }

    public function testSetAndGetBlocksFailedType()
    {
        $blocks = array($this->block1, $this->block2, new \stdClass());
        $this->setExpectedException('Core\Model\Exception');
        $this->page->setBlocks($blocks);
    }

    public function testSetAndGetLayout()
    {
        $layout = new \Core\Model\Layout('test');
        $this->page->setLayout($layout);
        $this->assertEquals($layout, $this->page->getLayout());
    }

    public function testSetAndGetDependentContent()
    {
        $content = new \Core\Model\Content\Placeholder('test', 'Core\Model\Content\Text');
        $this->page->setDependentContent($content);
        $this->assertEquals($content, $this->page->getDependentContent());
    }

    public function testAddDependentContent()
    {
        $content = new \Core\Model\Content\Placeholder('test', 'Core\Model\Content\Text');
        $this->page->addDependentContent($content);
        $this->assertEquals($content, $this->page->dependentContent[0]);
    }

    public function testGetResourceId()
    {
        $this->assertEquals('Page.' . $this->page->getId(), $this->page->getResourceId());
    }

    public function testCanEdit()
    {
        $acl = new \Zend_Acl();
        $acl->addRole(new \Zend_Acl_Role('admin'));
        $acl->addRole(new \Zend_Acl_Role('user'));
        $acl->addResource(new \Zend_Acl_Resource('Page.'));
        $acl->allow('admin', null, 'edit');
        \Zend_Registry::set('acl', $acl);

        $this->assertEquals(TRUE, $this->page->canEdit('admin'));
        $this->assertEquals(FALSE, $this->page->canEdit('user'));
    }
}
