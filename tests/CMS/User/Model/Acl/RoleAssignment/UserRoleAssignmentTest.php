<?php
namespace User\Model\Acl\RoleAssignment;

require_once 'PHPUnit/Framework.php';

/**
 * Test class for AbstractRoleAssignment.
 * Generated by PHPUnit on 2010-03-31 at 09:52:48.
 */
class UserRoleAssignmentTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var AbstractRoleAssignment
     */
    protected $roleAssignment;

    protected $role;

    protected $user;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $group = new \User\Model\Group('test', 'test');
        $this->user = new \User\Model\User($group, 'test@test.com', 'Test', 'User');
        $this->role = new \User\Model\Acl\Role('test');
        $this->roleAssignment = new UserRoleAssignment($this->user, $this->role);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
    }

    public function test__construct() {
        $this->assertEquals($this->user, $this->roleAssignment->getUser());
        $this->assertEquals($this->role, $this->roleAssignment->getRole());
    }
}
?>
