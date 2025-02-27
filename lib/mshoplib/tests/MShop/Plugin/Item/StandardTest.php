<?php

/**
 * @copyright Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */


namespace Aimeos\MShop\Plugin\Item;


/**
 * Test class for \Aimeos\MShop\Plugin\Item\Standard
 */
class StandardTest extends \PHPUnit_Framework_TestCase
{
	private $object;
	private $values;


	/**
	 * Sets up the fixture. This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->values = array(
			'plugin.id' => 123,
			'plugin.siteid' => 99,
			'plugin.typeid' => 2,
			'plugin.label' => 'unitTestPlugin',
			'plugin.type' => 'order',
			'plugin.provider' => 'provider',
			'plugin.config' => array( 'limit' => '40' ),
			'plugin.position' => 0,
			'plugin.status' => 1,
			'plugin.mtime' => '2011-01-01 00:00:02',
			'plugin.ctime' => '2011-01-01 00:00:01',
			'plugin.editor' => 'unitTestUser'
		);

		$this->object = new \Aimeos\MShop\Plugin\Item\Standard( $this->values );
	}


	/**
	 * Tears down the fixture. This method is called after a test is executed.
	 */
	protected function tearDown()
	{
		unset( $this->object );
	}


	public function testGetId()
	{
		$this->assertEquals( 123, $this->object->getId() );
	}


	public function testSetId()
	{
		$this->object->setId( null );
		$this->assertEquals( null, $this->object->getId() );
		$this->assertEquals( true, $this->object->isModified() );
	}


	public function testGetSiteId()
	{
		$this->assertEquals( 99, $this->object->getSiteId() );
	}

	public function testGetTypeId()
	{
		$this->assertEquals( 2, $this->object->getTypeId() );
	}


	public function testSetTypeId()
	{
		$this->object->setTypeId( 99 );
		$this->assertEquals( 99, $this->object->getTypeId() );

		$this->assertTrue( $this->object->isModified() );
	}


	public function testGetLabel()
	{
		$this->assertEquals( 'unitTestPlugin', $this->object->getLabel() );
	}


	public function testSetLabel()
	{
		$this->object->setLabel( 'anotherLabel' );
		$this->assertEquals( 'anotherLabel', $this->object->getLabel() );
		$this->assertEquals( true, $this->object->isModified() );
	}


	public function testGetProvider()
	{
		$this->assertEquals( 'provider', $this->object->getProvider() );
	}


	public function testSetProvider()
	{
		$this->object->setProvider( 'newProvider' );
		$this->assertEquals( 'newProvider', $this->object->getProvider() );
		$this->assertEquals( true, $this->object->isModified() );
	}


	public function testGetConfig()
	{
		$this->assertEquals( array( 'limit'=>'40' ), $this->object->getConfig() );
	}


	public function testSetConfig()
	{
		$this->object->setConfig( array( 'threshold'=>'20.00' ) );
		$this->assertEquals( array( 'threshold'=>'20.00' ), $this->object->getConfig() );
		$this->assertEquals( true, $this->object->isModified() );
	}


	public function testGetPosition()
	{
		$this->assertEquals( 0, $this->object->getPosition() );
	}


	public function testSetPosition()
	{
		$this->object->setPosition( 1 );
		$this->assertEquals( 1, $this->object->getPosition() );
		$this->assertTrue( $this->object->isModified() );
	}


	public function testGetStatus()
	{
		$this->assertEquals( 1, $this->object->getStatus() );
	}


	public function testSetStatus()
	{
		$this->object->setStatus( 0 );
		$this->assertEquals( 0, $this->object->getStatus() );
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
		$this->assertEquals( 'plugin', $this->object->getResourceType() );
	}


	public function testFromArray()
	{
		$item = new \Aimeos\MShop\Plugin\Item\Standard();

		$list = array(
			'plugin.id' => 1,
			'plugin.typeid' => 2,
			'plugin.label' => 'test item',
			'plugin.provider' => 'FreeShipping',
			'plugin.config' => array( 'test' ),
			'plugin.status' => 0,
		);

		$unknown = $item->fromArray( $list );

		$this->assertEquals( array(), $unknown );

		$this->assertEquals( $list['plugin.id'], $item->getId() );
		$this->assertEquals( $list['plugin.typeid'], $item->getTypeId() );
		$this->assertEquals( $list['plugin.label'], $item->getLabel() );
		$this->assertEquals( $list['plugin.provider'], $item->getProvider() );
		$this->assertEquals( $list['plugin.config'], $item->getConfig() );
		$this->assertEquals( $list['plugin.status'], $item->getStatus() );
	}


	public function testToArray()
	{
		$arrayObject = $this->object->toArray();
		$this->assertEquals( count( $this->values ), count( $arrayObject ) );

		$this->assertEquals( $this->object->getId(), $arrayObject['plugin.id'] );
		$this->assertEquals( $this->object->getSiteId(), $arrayObject['plugin.siteid'] );
		$this->assertEquals( $this->object->getType(), $arrayObject['plugin.type'] );
		$this->assertEquals( $this->object->getTypeId(), $arrayObject['plugin.typeid'] );
		$this->assertEquals( $this->object->getLabel(), $arrayObject['plugin.label'] );
		$this->assertEquals( $this->object->getProvider(), $arrayObject['plugin.provider'] );
		$this->assertEquals( $this->object->getConfig(), $arrayObject['plugin.config'] );
		$this->assertEquals( $this->object->getStatus(), $arrayObject['plugin.status'] );
		$this->assertEquals( $this->object->getTimeCreated(), $arrayObject['plugin.ctime'] );
		$this->assertEquals( $this->object->getTimeModified(), $arrayObject['plugin.mtime'] );
		$this->assertEquals( $this->object->getEditor(), $arrayObject['plugin.editor'] );
	}
}
