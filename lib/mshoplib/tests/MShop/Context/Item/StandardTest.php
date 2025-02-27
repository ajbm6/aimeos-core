<?php

namespace Aimeos\MShop\Context\Item;


/**
 * @copyright Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */
class StandardTest extends \PHPUnit_Framework_TestCase
{
	private $object;


	protected function setUp()
	{
		$this->object = new \Aimeos\MShop\Context\Item\Standard();
	}

	public function testGetConfig()
	{
		$this->setExpectedException( '\\Aimeos\\MShop\\Exception' );
		$this->object->getConfig();
	}

	public function testGetDatabaseManager()
	{
		$this->setExpectedException( '\\Aimeos\\MShop\\Exception' );
		$this->object->getDatabaseManager();
	}

	public function testGetFilesystemManager()
	{
		$this->setExpectedException( '\\Aimeos\\MShop\\Exception' );
		$this->object->getFilesystemManager();
	}

	public function testGetLocale()
	{
		$this->setExpectedException( '\\Aimeos\\MShop\\Exception' );
		$this->object->getLocale();
	}

	public function testGetI18n()
	{
		$this->setExpectedException( '\\Aimeos\\MShop\\Exception' );
		$this->object->getI18n();
	}

	public function testGetLogger()
	{
		$this->setExpectedException( '\\Aimeos\\MShop\\Exception' );
		$this->object->getLogger();
	}

	public function testGetSession()
	{
		$this->setExpectedException( '\\Aimeos\\MShop\\Exception' );
		$this->object->getSession();
	}

	public function testGetMail()
	{
		$this->setExpectedException( '\\Aimeos\\MShop\\Exception' );
		$this->object->getMail();
	}

	public function testGetView()
	{
		$this->setExpectedException( '\\Aimeos\\MShop\\Exception' );
		$this->object->getView();
	}

	public function testSetConfig()
	{
		$context = \TestHelperMShop::getContext();
		$this->object->setConfig( $context->getConfig() );
		$this->assertSame( $context->getConfig(), $this->object->getConfig() );
	}

	public function testSetDatabaseManager()
	{
		$context = \TestHelperMShop::getContext();
		$this->object->setDatabaseManager( $context->getDatabaseManager() );
		$this->assertSame( $context->getDatabaseManager(), $this->object->getDatabaseManager() );
	}

	public function testSetFilesystemManager()
	{
		$context = \TestHelperMShop::getContext();
		$this->object->setFilesystemManager( $context->getFilesystemManager() );
		$this->assertSame( $context->getFilesystemManager(), $this->object->getFilesystemManager() );
	}

	public function testSetI18n()
	{
		$context = \TestHelperMShop::getContext();

		$locale = \Aimeos\MShop\Locale\Manager\Factory::createManager( \TestHelperMShop::getContext() )->createItem();
		$locale->setLanguageId( 'en' );
		$this->object->setLocale( $locale );

		$this->object->setI18n( array( 'en' => $context->getI18n() ) );
		$this->assertSame( $context->getI18n(), $this->object->getI18n() );
	}

	public function testSetLocale()
	{
		$locale = \Aimeos\MShop\Locale\Manager\Factory::createManager( \TestHelperMShop::getContext() )->createItem();
		$this->object->setLocale( $locale );
		$this->assertSame( $locale, $this->object->getLocale() );
	}

	public function testSetLogger()
	{
		$context = \TestHelperMShop::getContext();
		$this->object->setLogger( $context->getLogger() );
		$this->assertSame( $context->getLogger(), $this->object->getLogger() );
	}

	public function testSetSession()
	{
		$context = \TestHelperMShop::getContext();
		$this->object->setSession( $context->getSession() );
		$this->assertSame( $context->getSession(), $this->object->getSession() );
	}

	public function testSetMail()
	{
		$mail = new \Aimeos\MW\Mail\None();
		$this->object->setMail( $mail );
		$this->assertInstanceOf( '\\Aimeos\\MW\\Mail\\Iface', $this->object->getMail() );
	}

	public function testSetView()
	{
		$view = new \Aimeos\MW\View\Standard();
		$this->object->setView( $view );
		$this->assertInstanceOf( '\\Aimeos\\MW\\View\\Iface', $this->object->getView() );
	}

	public function testGetSetEditor()
	{
		$this->assertEquals( '', $this->object->getEditor() );

		$this->object->setEditor( 'testuser' );
		$this->assertEquals( 'testuser', $this->object->getEditor() );
	}

	public function testGetSetUserId()
	{
		$this->assertEquals( null, $this->object->getUserId() );

		$this->object->setUserId( 123 );
		$this->assertEquals( '123', $this->object->getUserId() );

		$this->object->setUserId( function() { return 456; } );
		$this->assertEquals( '456', $this->object->getUserId() );
	}

	public function testGetSetGroupIds()
	{
		$this->assertEquals( array(), $this->object->getGroupIds() );

		$this->object->setGroupIds( array( 123 ) );
		$this->assertEquals( array( '123' ), $this->object->getGroupIds() );

		$this->object->setGroupIds( function() { return array( 456 ); } );
		$this->assertEquals( array( '456' ), $this->object->getGroupIds() );
	}
}