<?php
/**
 * @copyright Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */

namespace Aimeos\MShop\Order\Item\Status;


/**
 * Test class for \Aimeos\MShop\Order\Item\Status\Standard.
 */
class StandardTest extends \PHPUnit_Framework_TestCase
{
	private $object;
	private $values;


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		$this->values = array(
			'order.status.id' => 1,
			'order.status.siteid' => 99,
			'order.status.parentid'=>11,
			'order.status.type' => 'teststatus',
			'order.status.value' => 'this is a value from unittest',
			'order.status.mtime' => '2011-01-01 00:00:02',
			'order.status.ctime' => '2011-01-01 00:00:01',
			'order.status.editor' => 'unitTestUser'
		);

		$this->object = new \Aimeos\MShop\Order\Item\Status\Standard( $this->values );

	}


	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
		$this->object = null;
	}

	public function testGetId()
	{
		$this->assertEquals( 1, $this->object->getId() );
	}

	public function testSetId()
	{
		$this->object->setId( null );
		$this->assertEquals( null, $this->object->getId() );
		$this->assertTrue( $this->object->isModified() );

		$this->object->setId( 15 );
		$this->assertEquals( 15, $this->object->getId() );
		$this->assertFalse( $this->object->isModified() );

		$this->setExpectedException( '\\Aimeos\\MShop\\Exception' );
		$this->object->setId( 6 );
	}

	public function testGetSiteId()
	{
		$this->assertEquals( 99, $this->object->getSiteId() );
	}

	public function testGetParentId()
	{
		$this->assertEquals( 11, $this->object->getParentId() );
	}

	public function testSetParentId()
	{
		$this->object->setParentId( 12 );
		$this->assertEquals( 12, $this->object->getParentId() );
		$this->assertTrue( $this->object->isModified() );
	}

	public function testGetType()
	{
		$this->assertEquals( 'teststatus', $this->object->getType() );
	}

	public function testSetType()
	{
		$this->object->setType( 'unittest' );
		$this->assertEquals( 'unittest', $this->object->getType() );
		$this->assertTrue( $this->object->isModified() );
	}

	public function testGetValue()
	{
		$this->assertEquals( "this is a value from unittest", $this->object->getValue() );
	}

	public function testSetValue()
	{
		$this->object->setValue( 'was changed by unittest' );
		$this->assertEquals( 'was changed by unittest', $this->object->getValue() );
		$this->assertTrue( $this->object->isModified() );
	}

	public function testGetTimeModified()
	{
		$this->assertEquals( '2011-01-01 00:00:02', $this->object->getTimeModified() );
	}

	public function testGetTimeCreated()
	{
		$this->assertEquals( '2011-01-01 00:00:01', $this->object->getTimeCreated() );
	}

	public function testGetEditor()
	{
		$this->assertEquals( 'unitTestUser', $this->object->getEditor() );
	}


	public function testGetResourceType()
	{
		$this->assertEquals( 'order/status', $this->object->getResourceType() );
	}


	public function testFromArray()
	{
		$item = new \Aimeos\MShop\Order\Item\Status\Standard();

		$list = array(
			'order.status.id' => 1,
			'order.status.parentid' => 2,
			'order.status.type' => \Aimeos\MShop\Order\Item\Status\Base::STATUS_PAYMENT,
			'order.status.value' => 'value',
		);

		$unknown = $item->fromArray( $list );

		$this->assertEquals( array(), $unknown );

		$this->assertEquals( $list['order.status.id'], $item->getId() );
		$this->assertEquals( $list['order.status.parentid'], $item->getParentId() );
		$this->assertEquals( $list['order.status.type'], $item->getType() );
		$this->assertEquals( $list['order.status.value'], $item->getValue() );
	}


	public function testToArray()
	{
		$list = $this->object->toArray();
		$this->assertEquals( count( $this->values ), count( $list ) );

		$this->assertEquals( $this->object->getId(), $list['order.status.id'] );
		$this->assertEquals( $this->object->getSiteId(), $list['order.status.siteid'] );
		$this->assertEquals( $this->object->getParentId(), $list['order.status.parentid'] );
		$this->assertEquals( $this->object->getType(), $list['order.status.type'] ); ;
		$this->assertEquals( $this->object->getValue(), $list['order.status.value'] );
		$this->assertEquals( $this->object->getTimeModified(), $list['order.status.mtime'] );
		$this->assertEquals( $this->object->getTimeCreated(), $list['order.status.ctime'] );
		$this->assertEquals( $this->object->getEditor(), $list['order.status.editor'] );

	}


}