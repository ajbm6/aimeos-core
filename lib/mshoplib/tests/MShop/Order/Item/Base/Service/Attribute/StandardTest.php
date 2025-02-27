<?php

/**
 * @copyright Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */


namespace Aimeos\MShop\Order\Item\Base\Service\Attribute;


/**
 * Test class for \Aimeos\MShop\Order\Item\Base\Service\Attribute\Standard.
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
			'order.base.service.attribute.id' => 3,
			'order.base.service.attribute.siteid' => 99,
			'order.base.service.attribute.attributeid' => 22,
			'order.base.service.attribute.parentid' => 42,
			'order.base.service.attribute.type' => 'UnitType',
			'order.base.service.attribute.name' => 'UnitName',
			'order.base.service.attribute.code' => 'UnitCode',
			'order.base.service.attribute.value' => 'UnitValue',
			'order.base.service.attribute.mtime' => '2020-12-31 23:59:59',
			'order.base.service.attribute.ctime' => '2011-01-01 00:00:01',
			'order.base.service.attribute.editor' => 'unitTestUser'
		);

		$this->object = new \Aimeos\MShop\Order\Item\Base\Service\Attribute\Standard( $this->values );
	}


	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
		unset( $this->object );
	}


	public function testGetId()
	{
		$this->assertEquals( 3, $this->object->getId() );
	}


	public function testSetId()
	{
		$this->object->setId( null );
		$this->assertEquals( null, $this->object->getId() );
		$this->assertTrue( $this->object->isModified() );

		$this->object->setId( 99 );
		$this->assertEquals( 99, $this->object->getId() );

		$this->setExpectedException( '\\Aimeos\\MShop\\Exception' );
		$this->object->setId( 3 );
	}


	public function testGetSiteId()
	{
		$this->assertEquals( 99, $this->object->getSiteId() );
	}


	public function testGetAttributeId()
	{
		$this->assertEquals( 22, $this->object->getAttributeId() );
	}


	public function testSetAttributeId()
	{
		$this->object->setAttributeId( 44 );
		$this->assertEquals( 44, $this->object->getAttributeId() );
		$this->assertTrue( $this->object->isModified() );
	}


	public function testGetParentId()
	{
		$this->assertEquals( 42, $this->object->getParentId() );
		$this->assertFalse( $this->object->isModified() );
	}


	public function testSetParentId()
	{
		$this->object->setParentId( 98 );
		$this->assertEquals( 98, $this->object->getParentId() );
		$this->assertTrue( $this->object->isModified() );
	}


	public function testGetType()
	{
		$this->assertEquals( 'UnitType', $this->object->getType() );
	}


	public function testSetType()
	{
		$this->object->setType( 'testType' );
		$this->assertEquals( 'testType', $this->object->getType() );
		$this->assertTrue( $this->object->isModified() );
	}


	public function testGetCode()
	{
		$this->assertEquals( 'UnitCode', $this->object->getCode() );
	}


	public function testSetCode()
	{
		$this->object->setCode( 'testCode' );
		$this->assertEquals( 'testCode', $this->object->getCode() );
		$this->assertTrue( $this->object->isModified() );
	}


	public function testGetValue()
	{
		$this->assertEquals( 'UnitValue', $this->object->getValue() );
	}


	public function testSetValue()
	{
		$this->object->setValue( 'custom' );
		$this->assertEquals( 'custom', $this->object->getValue() );
		$this->assertTrue( $this->object->isModified() );
	}


	public function testGetName()
	{
		$this->assertEquals( 'UnitName', $this->object->getName() );
	}


	public function testSetName()
	{
		$this->object->setName( 'testName' );
		$this->assertEquals( 'testName', $this->object->getName() );
		$this->assertTrue( $this->object->isModified() );
	}


	public function testGetTimeModified()
	{
		$this->assertEquals( '2020-12-31 23:59:59', $this->object->getTimeModified() );
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
		$this->assertEquals( 'order/base/service/attribute', $this->object->getResourceType() );
	}


	public function testCopyFrom()
	{
		$attrManager = \Aimeos\MShop\Attribute\Manager\Factory::createManager( \TestHelperMShop::getContext() );

		$items = $attrManager->searchItems( $attrManager->createSearch() );
		if( ( $item = reset( $items ) ) === false ) {
			throw new \Exception( 'No attribute item found' );
		}

		$this->object->copyFrom( $item );

		$this->assertEquals( $item->getId(), $this->object->getAttributeId() );
		$this->assertEquals( $item->getLabel(), $this->object->getName() );
		$this->assertEquals( $item->getType(), $this->object->getCode() );
		$this->assertEquals( $item->getCode(), $this->object->getValue() );
	}


	public function testFromArray()
	{
		$item = new \Aimeos\MShop\Order\Item\Base\Service\Attribute\Standard();

		$list = array(
			'order.base.service.attribute.id' => 1,
			'order.base.service.attribute.attrid' => 2,
			'order.base.service.attribute.parentid' => 3,
			'order.base.service.attribute.type' => 'delivery',
			'order.base.service.attribute.code' => 'test',
			'order.base.service.attribute.value' => 'value',
			'order.base.service.attribute.name' => 'test item',
		);

		$unknown = $item->fromArray( $list );

		$this->assertEquals( array(), $unknown );

		$this->assertEquals( $list['order.base.service.attribute.id'], $item->getId() );
		$this->assertEquals( $list['order.base.service.attribute.attrid'], $item->getAttributeId() );
		$this->assertEquals( $list['order.base.service.attribute.parentid'], $item->getParentId() );
		$this->assertEquals( $list['order.base.service.attribute.type'], $item->getType() );
		$this->assertEquals( $list['order.base.service.attribute.code'], $item->getCode() );
		$this->assertEquals( $list['order.base.service.attribute.value'], $item->getValue() );
		$this->assertEquals( $list['order.base.service.attribute.name'], $item->getName() );
	}


	public function testToArray()
	{
		$list = $this->object->toArray();
		$this->assertEquals( count( $this->values ), count( $list ) );

		$this->assertEquals( $this->object->getId(), $list['order.base.service.attribute.id'] );
		$this->assertEquals( $this->object->getSiteId(), $list['order.base.service.attribute.siteid'] );
		$this->assertEquals( $this->object->getAttributeId(), $list['order.base.service.attribute.attrid'] );
		$this->assertEquals( $this->object->getParentId(), $list['order.base.service.attribute.parentid'] );
		$this->assertEquals( $this->object->getType(), $list['order.base.service.attribute.type'] );
		$this->assertEquals( $this->object->getCode(), $list['order.base.service.attribute.code'] );
		$this->assertEquals( $this->object->getValue(), $list['order.base.service.attribute.value'] );
		$this->assertEquals( $this->object->getName(), $list['order.base.service.attribute.name'] );
		$this->assertEquals( $this->object->getTimeModified(), $list['order.base.service.attribute.mtime'] );
		$this->assertEquals( $this->object->getTimeCreated(), $list['order.base.service.attribute.ctime'] );
		$this->assertEquals( $this->object->getTimeModified(), $list['order.base.service.attribute.mtime'] );
		$this->assertEquals( $this->object->getEditor(), $list['order.base.service.attribute.editor'] );
	}

	public function testIsModified()
	{
		$this->assertFalse( $this->object->isModified() );
	}
}
