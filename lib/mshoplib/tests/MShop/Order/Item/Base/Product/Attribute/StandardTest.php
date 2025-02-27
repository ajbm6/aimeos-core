<?php

/**
 * @copyright Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */


namespace Aimeos\MShop\Order\Item\Base\Product\Attribute;


/**
 * Test class for \Aimeos\MShop\Order\Item\Base\Product\Attribute\Standard.
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
			'order.base.product.attribute.id' => 4,
			'order.base.product.attribute.siteid' => 99,
			'order.base.product.attribute.attributeid' => 22,
			'order.base.product.attribute.parentid' => 11,
			'order.base.product.attribute.type' => 'UnitType',
			'order.base.product.attribute.code' => 'size',
			'order.base.product.attribute.value' => '30',
			'order.base.product.attribute.name' => 'small',
			'order.base.product.attribute.mtime' => '2011-01-06 13:20:34',
			'order.base.product.attribute.ctime' => '2011-01-01 00:00:01',
			'order.base.product.attribute.editor' => 'unitTestUser'
		);

		$this->object = new \Aimeos\MShop\Order\Item\Base\Product\Attribute\Standard( $this->values );
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
		$this->assertEquals( 4, $this->object->getId() );
	}


	public function testSetId()
	{
		$this->object->setId( null );
		$this->assertEquals( null, $this->object->getId() );
		$this->assertTrue( $this->object->isModified() );

		$this->object->setId( 8 );
		$this->assertEquals( 8, $this->object->getId() );
		$this->assertFalse( $this->object->isModified() );

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
		$this->assertEquals( 11, $this->object->getParentId() );
	}


	public function testSetParentId()
	{
		$this->object->setParentId( 33 );
		$this->assertEquals( 33, $this->object->getParentId() );
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
		$this->assertEquals( 'size', $this->object->getCode() );
	}


	public function testSetCode()
	{
		$this->object->setCode( 'weight' );
		$this->assertEquals( 'weight', $this->object->getCode() );
		$this->assertTrue( $this->object->isModified() );
	}


	public function testGetValue()
	{
		$this->assertEquals( '30', $this->object->getValue() );
	}


	public function testSetValue()
	{
		$this->object->setValue( 36 );
		$this->assertEquals( 36, $this->object->getValue() );
		$this->assertTrue( $this->object->isModified() );
	}


	public function testGetName()
	{
		$this->assertEquals( 'small', $this->object->getName() );
	}


	public function testSetName()
	{
		$this->object->setName( 'medium' );
		$this->assertEquals( 'medium', $this->object->getName() );
		$this->assertTrue( $this->object->isModified() );
	}


	public function testGetTimeModified()
	{
		$regexp = '/^[0-9]{4}-[0-1][0-9]-[0-3][0-9] [0-2][0-9]:[0-5][0-9]:[0-5][0-9]$/';
		$this->assertRegExp( $regexp, $this->object->getTimeModified() );
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
		$this->assertEquals( 'order/base/product/attribute', $this->object->getResourceType() );
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
		$item = new \Aimeos\MShop\Order\Item\Base\Product\Attribute\Standard();

		$list = array(
			'order.base.product.attribute.id' => 1,
			'order.base.product.attribute.attrid' => 2,
			'order.base.product.attribute.parentid' => 3,
			'order.base.product.attribute.type' => 'variant',
			'order.base.product.attribute.code' => 'test',
			'order.base.product.attribute.value' => 'value',
			'order.base.product.attribute.name' => 'test item',
		);

		$unknown = $item->fromArray( $list );

		$this->assertEquals( array(), $unknown );

		$this->assertEquals( $list['order.base.product.attribute.id'], $item->getId() );
		$this->assertEquals( $list['order.base.product.attribute.attrid'], $item->getAttributeId() );
		$this->assertEquals( $list['order.base.product.attribute.parentid'], $item->getParentId() );
		$this->assertEquals( $list['order.base.product.attribute.type'], $item->getType() );
		$this->assertEquals( $list['order.base.product.attribute.code'], $item->getCode() );
		$this->assertEquals( $list['order.base.product.attribute.value'], $item->getValue() );
		$this->assertEquals( $list['order.base.product.attribute.name'], $item->getName() );
	}


	public function testToArray()
	{
		$list = $this->object->toArray();

		$this->assertEquals( count( $this->values ), count( $list ) );

		$this->assertEquals( $this->object->getId(), $list['order.base.product.attribute.id'] );
		$this->assertEquals( $this->object->getSiteId(), $list['order.base.product.attribute.siteid'] );
		$this->assertEquals( $this->object->getAttributeId(), $list['order.base.product.attribute.attrid'] );
		$this->assertEquals( $this->object->getParentId(), $list['order.base.product.attribute.parentid'] );
		$this->assertEquals( $this->object->getType(), $list['order.base.product.attribute.type'] );
		$this->assertEquals( $this->object->getCode(), $list['order.base.product.attribute.code'] );
		$this->assertEquals( $this->object->getValue(), $list['order.base.product.attribute.value'] );
		$this->assertEquals( $this->object->getName(), $list['order.base.product.attribute.name'] );
		$this->assertEquals( $this->object->getTimeModified(), $list['order.base.product.attribute.mtime'] );
		$this->assertEquals( $this->object->getTimeCreated(), $list['order.base.product.attribute.ctime'] );
		$this->assertEquals( $this->object->getEditor(), $list['order.base.product.attribute.editor'] );
	}


	public function testIsModified()
	{
		$this->assertFalse( $this->object->isModified() );
	}
}
