<?php

/**
 * @copyright Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */


namespace Aimeos\MShop\Order\Item\Base;


/**
 * Test class for \Aimeos\MShop\Order\Item\Base\Standard.
 */
class StandardTest extends \PHPUnit_Framework_TestCase
{
	private $object;
	private $values;
	private $locale;
	private $products;
	private $addresses;
	private $services;
	private $coupons;


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		$priceManager = \Aimeos\MShop\Price\Manager\Factory::createManager( \TestHelperMShop::getContext() );
		$orderManager = \Aimeos\MShop\Order\Manager\Factory::createManager( \TestHelperMShop::getContext() );

		$orderBaseManager = $orderManager->getSubManager( 'base' );
		$orderAddressManager = $orderBaseManager->getSubManager( 'address' );
		$orderProductManager = $orderBaseManager->getSubManager( 'product' );
		$orderServiceManager = $orderBaseManager->getSubManager( 'service' );


		$this->values = array(
			'order.base.id' => 1,
			'order.base.siteid' => 99,
			'order.base.customerid' => 'testuser',
			'order.base.comment' => 'this is a comment from unittest',
			'order.base.status' => 0,
			'order.base.mtime' => '2011-01-01 00:00:02',
			'order.base.ctime' => '2011-01-01 00:00:01',
			'order.base.editor' => 'unitTestUser'
		);

		$this->locale = \Aimeos\MShop\Locale\Manager\Factory::createManager( \TestHelperMShop::getContext() )->createItem();

		$this->object = new \Aimeos\MShop\Order\Item\Base\Standard( $priceManager->createItem(), $this->locale, $this->values );


		$price = $priceManager->createItem();
		$price->setRebate( '3.01' );
		$price->setValue( '43.12' );
		$price->setCosts( '1.11' );
		$price->setTaxRate( '0.00' );
		$price->setCurrencyId( 'EUR' );

		$prod1 = $orderProductManager->createItem();
		$prod1->setProductCode( 'prod1' );
		$prod1->setPrice( $price );

		$price = $priceManager->createItem();
		$price->setRebate( '4.00' );
		$price->setValue( '20.00' );
		$price->setCosts( '2.00' );
		$price->setTaxRate( '0.50' );
		$price->setCurrencyId( 'EUR' );

		$prod2 = $orderProductManager->createItem();
		$prod2->setProductCode( 'prod2' );
		$prod2->setPrice( $price );


		$this->products = array( $prod1, $prod2 );

		$this->coupons = array( 'OPQR' => array( $prod1 ) );

		$this->addresses = array(
			\Aimeos\MShop\Order\Item\Base\Address\Base::TYPE_PAYMENT => $orderAddressManager->createItem(),
			\Aimeos\MShop\Order\Item\Base\Address\Base::TYPE_DELIVERY => $orderAddressManager->createItem(),
		);

		$this->services = array(
			'payment' => $orderServiceManager->createItem(),
			'delivery' => $orderServiceManager->createItem(),
		);


		//registering order object for plugin use
		$pluginManager = \Aimeos\MShop\Plugin\Manager\Factory::createManager( \TestHelperMShop::getContext() );
		$pluginManager->register( $this->object, 'order' );
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

		$this->object->setId( 5 );
		$this->assertEquals( 5, $this->object->getId() );
		$this->assertFalse( $this->object->isModified() );

		$this->setExpectedException( '\\Aimeos\\MShop\\Exception' );
		$this->object->setId( 6 );
	}


	public function testSetId2()
	{
		$this->setExpectedException( '\\Aimeos\\MShop\\Exception' );
		$this->object->setId( 'test' );
	}


	public function testGetSiteId()
	{
		$this->assertEquals( 99, $this->object->getSiteId() );
	}


	public function testGetCustomerId()
	{
		$this->assertEquals( 'testuser', $this->object->getCustomerId() );
	}


	public function testSetCustomerId()
	{
		$this->object->setCustomerId( '44' );
		$this->assertEquals( '44', $this->object->getCustomerId() );
		$this->assertTrue( $this->object->isModified() );
	}


	public function testGetLocale()
	{
		$this->assertEquals( $this->locale, $this->object->getLocale() );
	}


	public function testSetLocale()
	{
		$locale = \Aimeos\MShop\Locale\Manager\Factory::createManager( \TestHelperMShop::getContext() )->createItem();
		$this->object->setLocale( $locale );

		$this->assertEquals( $locale, $this->object->getLocale() );
		$this->assertTrue( $this->object->isModified() );
	}


	public function testGetPrice()
	{
		foreach( $this->products as $product ) {
			$this->object->addProduct( $product );
		}

		$priceItem = $this->object->getPrice();

		$this->assertEquals( $priceItem->getCurrencyId(), 'EUR' );
		$this->assertEquals( $priceItem->getTaxRate(), '0.00' );
		$this->assertEquals( $priceItem->getRebate(), '7.01' );
		$this->assertEquals( $priceItem->getCosts(), '3.11' );
		$this->assertEquals( $priceItem->getValue(), '63.12' );
	}


	public function testGetComment()
	{
		$this->assertEquals( 'this is a comment from unittest', $this->object->getComment() );
	}


	public function testSetComment()
	{
		$this->object->setComment( 'New unit test comment' );
		$this->assertEquals( 'New unit test comment', $this->object->getComment() );

		$this->assertTrue( $this->object->isModified() );
	}

	public function testGetStatus()
	{
		$this->assertEquals( 0, $this->object->getStatus() );
	}

	public function testSetStatus()
	{
		$this->object->setStatus( 1 );
		$this->assertEquals( 1, $this->object->getStatus() );

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


	public function testFromArray()
	{
		$item = new \Aimeos\MShop\Order\Item\Base\Standard( new \Aimeos\MShop\Price\Item\Standard(), new \Aimeos\MShop\Locale\Item\Standard() );

		$list = array(
			'order.base.id' => 1,
			'order.base.comment' => 'test comment',
			'order.base.languageid' => 'de',
			'order.base.customerid' => 3,
			'order.base.status' => 4,
		);

		$unknown = $item->fromArray( $list );

		$this->assertEquals( array(), $unknown );

		$this->assertEquals( $list['order.base.id'], $item->getId() );
		$this->assertEquals( $list['order.base.customerid'], $item->getCustomerId() );
		$this->assertEquals( $list['order.base.languageid'], $item->getLocale()->getLanguageId() );
		$this->assertEquals( $list['order.base.comment'], $item->getComment() );
		$this->assertEquals( $list['order.base.status'], $item->getStatus() );
	}


	public function testToArray()
	{
		$list = $this->object->toArray();
		$price = $this->object->getPrice();

		$this->assertEquals( $this->object->getId(), $list['order.base.id'] );
		$this->assertEquals( $this->object->getSiteId(), $list['order.base.siteid'] );
		$this->assertEquals( $this->object->getCustomerId(), $list['order.base.customerid'] );
		$this->assertEquals( $this->object->getLocale()->getLanguageId(), $list['order.base.languageid'] );
		$this->assertEquals( $this->object->getComment(), $list['order.base.comment'] );
		$this->assertEquals( $this->object->getStatus(), $list['order.base.status'] );
		$this->assertEquals( $this->object->getTimeCreated(), $list['order.base.ctime'] );
		$this->assertEquals( $this->object->getTimeModified(), $list['order.base.mtime'] );
		$this->assertEquals( $this->object->getEditor(), $list['order.base.editor'] );

		$this->assertEquals( $price->getValue(), $list['order.base.price'] );
		$this->assertEquals( $price->getCosts(), $list['order.base.costs'] );
		$this->assertEquals( $price->getRebate(), $list['order.base.rebate'] );
		$this->assertEquals( $price->getCurrencyId(), $list['order.base.currencyid'] );
	}


	public function testIsModified()
	{
		$this->assertFalse( $this->object->isModified() );
	}


	public function testFinish()
	{
		$this->object->finish();
	}


	public function testGetProducts()
	{
		foreach( $this->products as $product ) {
			$this->object->addProduct( $product );
		}

		$this->assertSame( $this->products, $this->object->getProducts() );
		$this->assertSame( $this->products[1], $this->object->getProduct( 1 ) );
	}


	public function testAddProductAppend()
	{
		foreach( $this->products as $product ) {
			$this->object->addProduct( $product );
		}

		$products = $this->object->getProducts();
		$product = $this->createProduct( 'prodid3' );
		$products[] = $product;

		$pos = $this->object->addProduct( $product );

		$this->assertSame( $products, $this->object->getProducts() );
		$this->assertSame( $product, $this->object->getProduct( $pos ) );
		$this->assertTrue( $this->object->isModified() );
	}


	public function testAddProductInsert()
	{
		foreach( $this->products as $product ) {
			$this->object->addProduct( $product );
		}

		$products = $this->object->getProducts();
		$product = $this->createProduct( 'prodid3' );
		array_splice( $products, 1, 0, array( $product ) );

		$pos = $this->object->addProduct( $product, 1 );

		$this->assertEquals( $products, $this->object->getProducts() );
		$this->assertSame( $product, $this->object->getProduct( $pos ) );
		$this->assertTrue( $this->object->isModified() );
	}


	public function testAddProductInsertEnd()
	{
		foreach( $this->products as $product ) {
			$this->object->addProduct( $product );
		}

		$products = $this->object->getProducts();
		$product = $this->createProduct( 'prodid3' );
		array_splice( $products, 2, 0, array( $product ) );

		$pos = $this->object->addProduct( $product, 2 );

		$this->assertEquals( $products, $this->object->getProducts() );
		$this->assertSame( $product, $this->object->getProduct( $pos ) );
		$this->assertTrue( $this->object->isModified() );
	}


	public function testAddProductSame()
	{
		foreach( $this->products as $product ) {
			$this->object->addProduct( $product );
		}

		$products = $this->object->getProducts();
		$product = $this->createProduct( 'prodid3' );
		$product->setQuantity( 5 );
		$products[] = $product;

		$pos1 = $this->object->addProduct( $product );
		$pos2 = $this->object->addProduct( $product );

		$this->assertEquals( $products, $this->object->getProducts() );
		$this->assertEquals( 10, $this->object->getProduct( $pos2 )->getQuantity() );
		$this->assertEquals( $pos1, $pos2 );
		$this->assertTrue( $this->object->isModified() );
	}


	public function testAddProductStablePosition()
	{
		foreach( $this->products as $product ) {
			$this->object->addProduct( $product );
		}

		$product = $this->createProduct( 'prodid3' );
		$product->setQuantity( 5 );
		$this->object->addProduct( $product );

		$this->object->deleteProduct( 0 );
		$testProduct = $this->object->getProduct( 1 );

		$this->object->deleteProduct( 1 );
		$this->object->addProduct( $testProduct, 1 );

		$expected = array( 1 => $testProduct, 2 => $product );
		$this->assertEquals( $expected, $this->object->getProducts() );
	}


	public function testAddProductExceedLimit()
	{
		$product = $this->createProduct( 'prodid3' );
		$product->setQuantity( 11 );

		// Exceed limit for single product
		$this->setExpectedException( '\\Aimeos\\MShop\\Plugin\\Exception' );
		$this->object->addProduct( $product );
	}


	public function testDeleteProduct()
	{
		foreach( $this->products as $product ) {
			$this->object->addProduct( $product );
		}

		unset( $this->products[1] );
		$this->object->deleteProduct( 1 );
		$this->assertSame( $this->products, $this->object->getProducts() );
		$this->assertTrue( $this->object->isModified() );
	}


	public function testGetAddress()
	{
		foreach( $this->addresses as $type => $address ) {
			$address->setId( null );
			$this->object->setAddress( $address, $type );
		}

		$this->assertEquals( $this->addresses, $this->object->getAddresses() );

		$address = $this->object->getAddress( \Aimeos\MShop\Order\Item\Base\Address\Base::TYPE_PAYMENT );
		$this->assertEquals( $this->addresses[\Aimeos\MShop\Order\Item\Base\Address\Base::TYPE_PAYMENT], $address );
	}


	public function testSetAddress()
	{
		foreach( $this->addresses as $type => $address ) {
			$this->object->setAddress( $address, $type );
		}

		$orderManager = \Aimeos\MShop\Order\Manager\Factory::createManager( \TestHelperMShop::getContext() );
		$orderAddressManager = $orderManager->getSubManager( 'base' )->getSubManager( 'address' );
		$address = $orderAddressManager->createItem();

		$result = $this->object->setAddress( $address, \Aimeos\MShop\Order\Item\Base\Address\Base::TYPE_PAYMENT );
		$item = $this->object->getAddress( \Aimeos\MShop\Order\Item\Base\Address\Base::TYPE_PAYMENT );

		$this->assertInstanceOf( '\\Aimeos\\MShop\\Order\\Item\\Base\\Address\\Iface', $result );
		$this->assertEquals( $result, $item );
		$this->assertEquals( \Aimeos\MShop\Order\Item\Base\Address\Base::TYPE_PAYMENT, $item->getType() );
		$this->assertTrue( $item->isModified() );
		$this->assertNull( $item->getId() );
	}


	public function testDeleteAddress()
	{
		foreach( $this->addresses as $type => $address ) {
			$this->object->setAddress( $address, $type );
		}

		$this->object->getAddress( \Aimeos\MShop\Order\Item\Base\Address\Base::TYPE_PAYMENT );
		$this->object->deleteAddress( \Aimeos\MShop\Order\Item\Base\Address\Base::TYPE_PAYMENT );
		$this->assertTrue( $this->object->isModified() );

		$this->setExpectedException( '\\Aimeos\\MShop\\Order\\Exception' );
		$this->object->getAddress( \Aimeos\MShop\Order\Item\Base\Address\Base::TYPE_PAYMENT );
	}


	public function testGetService()
	{
		foreach( $this->services as $type => $service ) {
			$service->setId( null );
			$this->object->setService( $service, $type );
		}

		$this->assertEquals( $this->services, $this->object->getServices() );

		$type = 'payment';
		$this->assertEquals( $this->services[$type], $this->object->getService( $type ) );
	}


	public function testSetService()
	{
		foreach( $this->services as $type => $service ) {
			$this->object->setService( $service, $type );
		}

		$type = 'delivery';
		$orderManager = \Aimeos\MShop\Order\Manager\Factory::createManager( \TestHelperMShop::getContext() );
		$orderServiceManager = $orderManager->getSubManager( 'base' )->getSubManager( 'service' );
		$service = $orderServiceManager->createItem();

		$result = $this->object->setService( $service, $type );
		$item = $this->object->getService( $type );

		$this->assertInstanceOf( '\\Aimeos\\MShop\\Order\\Item\\Base\\Service\\Iface', $result );
		$this->assertEquals( $result, $item );
		$this->assertEquals( $type, $item->getType() );
		$this->assertTrue( $item->isModified() );
		$this->assertNull( $item->getId() );
	}


	public function testDeleteService()
	{
		foreach( $this->services as $type => $service ) {
			$this->object->setService( $service, $type );
		}

		$this->object->getService( \Aimeos\MShop\Order\Item\Base\Service\Base::TYPE_PAYMENT );
		$this->object->deleteService( \Aimeos\MShop\Order\Item\Base\Service\Base::TYPE_PAYMENT );
		$this->assertTrue( $this->object->isModified() );

		$this->setExpectedException( '\\Aimeos\\MShop\\Order\\Exception' );
		$this->object->getService( \Aimeos\MShop\Order\Item\Base\Service\Base::TYPE_PAYMENT );
	}


	public function testCoupons()
	{
		foreach( $this->coupons as $code => $products ) {
			$this->object->addCoupon( $code, $products );
		}

		foreach( $this->object->getCoupons() as $coupon => $products ) {
			$this->assertEquals( $this->coupons[$coupon], $products );
		}
	}


	public function testDeleteCoupon()
	{
		foreach( $this->coupons as $code => $products ) {
			$this->object->addCoupon( $code, $products );
		}

		$this->object->deleteCoupon( 'OPQR' );

		foreach( $this->object->getCoupons() as $coupon => $products ) {
			$this->assertEquals( array(), $products );
		}

		$this->object->deleteCoupon( 'OPQR', true );
		$this->assertEquals( array(), $this->object->getCoupons() );

		$this->assertTrue( $this->object->isModified() );
	}


	public function testCheckInvalid()
	{
		$this->setExpectedException( '\\Aimeos\\MShop\\Order\\Exception' );
		$this->object->check( -1 );
	}


	public function testCheckAllFailure()
	{
		$this->setExpectedException( '\\Aimeos\\MShop\\Order\\Exception' );
		$this->object->check( \Aimeos\MShop\Order\Item\Base\Base::PARTS_ALL );
	}


	public function testCheckProductsFailure()
	{
		$this->setExpectedException( '\\Aimeos\\MShop\\Order\\Exception' );
		$this->object->check( \Aimeos\MShop\Order\Item\Base\Base::PARTS_PRODUCT );
	}


	public function testCheckAddresses()
	{
		foreach( $this->products as $product ) {
			$this->object->addProduct( $product );
		}

		foreach( $this->addresses as $type => $address ) {
			$this->object->setAddress( $address, $type );
		}

		$this->object->check( \Aimeos\MShop\Order\Item\Base\Base::PARTS_ADDRESS );
	}


	public function testCheckNoAddresses()
	{
		foreach( $this->products as $product ) {
			$this->object->addProduct( $product );
		}

		$this->setExpectedException( '\\Aimeos\\MShop\\Plugin\\Provider\\Exception' );
		$this->object->check( \Aimeos\MShop\Order\Item\Base\Base::PARTS_ADDRESS );
	}


	public function testCheckServices()
	{
		foreach( $this->products as $product ) {
			$this->object->addProduct( $product );
		}

		foreach( $this->addresses as $type => $address ) {
			$this->object->setAddress( $address, $type );
		}

		foreach( $this->services as $type => $service ) {
			$this->object->setService( $service, $type );
		}

		$this->object->check( \Aimeos\MShop\Order\Item\Base\Base::PARTS_SERVICE );
	}


	public function testCheckNoServices()
	{

		foreach( $this->products as $product ) {
			$this->object->addProduct( $product );
		}

		foreach( $this->addresses as $type => $address ) {
			$this->object->setAddress( $address, $type );
		}

		$this->setExpectedException( '\\Aimeos\\MShop\\Plugin\\Provider\\Exception' );
		$this->object->check( \Aimeos\MShop\Order\Item\Base\Base::PARTS_SERVICE );
	}


	public function testGetResourceType()
	{
		$this->assertEquals( 'order/base', $this->object->getResourceType() );
	}


	/**
	 * @param string $code
	 */
	protected function createProduct( $code )
	{
		$orderManager = \Aimeos\MShop\Order\Manager\Factory::createManager( \TestHelperMShop::getContext() );
		$orderProductManager = $orderManager->getSubManager( 'base' )->getSubManager( 'product' );
		$product = $orderProductManager->createItem();

		$price = \Aimeos\MShop\Price\Manager\Factory::createManager( \TestHelperMShop::getContext() )->createItem();
		$price->setValue( '2.99' );

		$product->setPrice( $price );
		$product->setProductCode( $code );

		return $product;
	}
}