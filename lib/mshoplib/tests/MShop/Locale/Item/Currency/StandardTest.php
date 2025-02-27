<?php

/**
 * @copyright Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */

namespace Aimeos\MShop\Locale\Item\Currency;


/**
 * Test class for \Aimeos\MShop\Locale\Item\Currency\Standard.
 */
class StandardTest extends \PHPUnit_Framework_TestCase
{
	private $object;
	private $values;


	protected function setUp()
	{
		$this->values = array(
			'locale.currency.id' => 'EUR',
			'locale.currency.label' => 'Euro',
			'locale.currency.siteid' => 1,
			'locale.currency.status' => 1,
			'locale.currency.mtime' => '2011-01-01 00:00:02',
			'locale.currency.ctime' => '2011-01-01 00:00:01',
			'locale.currency.editor' => 'unitTestUser'
		);
		$this->object = new \Aimeos\MShop\Locale\Item\Currency\Standard( $this->values );
	}


	protected function tearDown()
	{
		$this->object = null;
	}


	public function testIsModified()
	{
		$this->assertFalse( $this->object->isModified() );
	}


	public function testGetId()
	{
		$this->assertEquals( 'EUR', $this->object->getId() );
	}


	public function testSetIdBasic()
	{
		// test 1: set id and compare to be the same
		$this->object->setId( 'XXX' );
		$this->assertEquals( 'XXX', $this->object->getId() );
		// test modifier
		$this->assertFalse( $this->object->isModified() );

		// test 2: set id to null, mdified should be true, id=null
		$var = null;
		$this->object->setId( $var );
		$this->assertEquals( null, $this->object->getId() );
		// test modifier
		$this->assertTrue( $this->object->isModified() );
	}


	public function testSetIdLength()
	{
		$this->setExpectedException( '\\Aimeos\\MShop\\Locale\\Exception' );
		$this->object->setId( 'EU' );
	}


	public function testSetIdNumeric()
	{
		$this->setExpectedException( '\\Aimeos\\MShop\\Locale\\Exception' );
		$this->object->setId( 123 );
	}


	public function testGetCode()
	{
		$this->assertEquals( 'EUR', $this->object->getCode() );
	}


	public function testSetCode()
	{
		$this->object->setCode( 'USD' );
		$this->assertEquals( 'USD', $this->object->getCode() );
		$this->assertTrue( $this->object->isModified() );
	}


	public function testGetLabel()
	{
		$this->assertEquals( 'Euro', $this->object->getLabel() );
	}


	public function testSetLabel()
	{
		$this->object->setLabel( 'OtherName' );
		$this->assertEquals( 'OtherName', $this->object->getLabel() );
		$this->assertTrue( $this->object->isModified() );
	}


	public function testGetSiteId()
	{
		$this->assertEquals( 1, $this->object->getSiteId() );
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
		$this->assertEquals( 'locale/currency', $this->object->getResourceType() );
	}


	public function testFromArray()
	{
		$item = new \Aimeos\MShop\Locale\Item\Currency\Standard();

		$list = array(
			'locale.currency.id' => 'EUR',
			'locale.currency.code' => 'EUR',
			'locale.currency.label' => 'test item',
			'locale.currency.status' => 1,
		);

		$unknown = $item->fromArray( $list );

		$this->assertEquals( array(), $unknown );

		$this->assertEquals( $list['locale.currency.id'], $item->getId() );
		$this->assertEquals( $list['locale.currency.code'], $item->getCode() );
		$this->assertEquals( $list['locale.currency.label'], $item->getLabel() );
		$this->assertEquals( $list['locale.currency.status'], $item->getStatus() );
	}


	public function testToArray()
	{
		$arrayObject = $this->object->toArray();
		$this->assertEquals( ( count( $this->values ) + 1 ), count( $arrayObject ) );

		$this->assertEquals( $this->object->getId(), $arrayObject['locale.currency.id'] );
		$this->assertEquals( $this->object->getCode(), $arrayObject['locale.currency.code'] );
		$this->assertEquals( $this->object->getLabel(), $arrayObject['locale.currency.label'] );
		$this->assertEquals( $this->object->getSiteId(), $arrayObject['locale.currency.siteid'] );
		$this->assertEquals( $this->object->getStatus(), $arrayObject['locale.currency.status'] );
		$this->assertEquals( $this->object->getTimeCreated(), $arrayObject['locale.currency.ctime'] );
		$this->assertEquals( $this->object->getTimeModified(), $arrayObject['locale.currency.mtime'] );
		$this->assertEquals( $this->object->getEditor(), $arrayObject['locale.currency.editor'] );
	}

}
