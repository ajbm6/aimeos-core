<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Product\Selection;


/**
 * Default implementation of product selection JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/product/selection/standard/subparts
	 * List of JQAdm sub-clients rendered within the product selection section
	 *
	 * The output of the frontend is composed of the code generated by the JQAdm
	 * clients. Each JQAdm client can consist of serveral (or none) sub-clients
	 * that are responsible for rendering certain sub-parts of the output. The
	 * sub-clients can contain JQAdm clients themselves and therefore a
	 * hierarchical tree of JQAdm clients is composed. Each JQAdm client creates
	 * the output that is placed inside the container of its parent.
	 *
	 * At first, always the JQAdm code generated by the parent is printed, then
	 * the JQAdm code of its sub-clients. The order of the JQAdm sub-clients
	 * determines the order of the output of these sub-clients inside the parent
	 * container. If the configured list of clients is
	 *
	 *  array( "subclient1", "subclient2" )
	 *
	 * you can easily change the order of the output by reordering the subparts:
	 *
	 *  admin/jqadm/<clients>/subparts = array( "subclient1", "subclient2" )
	 *
	 * You can also remove one or more parts if they shouldn't be rendered:
	 *
	 *  admin/jqadm/<clients>/subparts = array( "subclient1" )
	 *
	 * As the clients only generates structural JQAdm, the layout defined via CSS
	 * should support adding, removing or reordering content by a fluid like
	 * design.
	 *
	 * @param array List of sub-client names
	 * @since 2016.01
	 * @category Developer
	 */
	private $subPartPath = 'admin/jqadm/product/selection/standard/subparts';
	private $subPartNames = array();


	/**
	 * Copies a resource
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function copy()
	{
		$view = $this->getView();

		$this->setData( $view, true );
		$view->selectionBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->selectionBody .= $client->copy();
		}

		$tplconf = 'admin/jqadm/product/selection/template-item';
		$default = 'product/item-selection-default.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}


	/**
	 * Creates a new resource
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function create()
	{
		$view = $this->getView();

		$this->setData( $view );
		$view->selectionBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->selectionBody .= $client->create();
		}

		$tplconf = 'admin/jqadm/product/selection/template-item';
		$default = 'product/item-selection-default.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}


	/**
	 * Returns a single resource
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function get()
	{
		$view = $this->getView();

		$this->setData( $view );
		$view->selectionBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->selectionBody .= $client->get();
		}

		$tplconf = 'admin/jqadm/product/selection/template-item';
		$default = 'product/item-selection-default.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}


	/**
	 * Saves the data
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function save()
	{
		$view = $this->getView();
		$context = $this->getContext();

		$manager = \Aimeos\MShop\Factory::createManager( $context, 'product' );
		$manager->begin();

		try
		{
			$this->updateItems( $view );
			$view->selectionBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->selectionBody .= $client->save();
			}

			$manager->commit();
			return;
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'product-item-selection' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', array() ) + $error;
			$manager->rollback();
		}
		catch( \Exception $e )
		{
			$context->getLogger()->log( $e->getMessage() . ' - ' . $e->getTraceAsString() );
			$error = array( 'product-item-selection' => $e->getMessage() );
			$view->errors = $view->get( 'errors', array() ) + $error;
			$manager->rollback();
		}

		throw new \Aimeos\Admin\JQAdm\Exception();
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Admin\JQAdm\Iface Sub-client object
	 */
	public function getSubClient( $type, $name = null )
	{
		/** admin/jqadm/product/selection/decorators/excludes
		 * Excludes decorators added by the "common" option from the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "admin/jqadm/common/decorators/default" before they are wrapped
		 * around the JQAdm client.
		 *
		 *  admin/jqadm/product/selection/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/selection/decorators/global
		 * @see admin/jqadm/product/selection/decorators/local
		 */

		/** admin/jqadm/product/selection/decorators/global
		 * Adds a list of globally available decorators only to the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/product/selection/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/selection/decorators/excludes
		 * @see admin/jqadm/product/selection/decorators/local
		 */

		/** admin/jqadm/product/selection/decorators/local
		 * Adds a list of local decorators only to the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Product\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/product/selection/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Product\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/selection/decorators/excludes
		 * @see admin/jqadm/product/selection/decorators/global
		 */
		return $this->createSubClient( 'product/selection/' . $type, $name );
	}


	/**
	 * Deletes the removed list items and their referenced items
	 *
	 * @param array $listItems List of items implementing \Aimeos\MShop\Common\Item\Lists\Iface
	 * @param array $listIds List of IDs of the still used list items
	 */
	protected function cleanupItems( array $listItems, array $listIds )
	{
		$context = $this->getContext();
		$manager = \Aimeos\MShop\Factory::createManager( $context, 'product' );
		$listManager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists' );

		$rmIds = array();
		$rmListIds = array_diff( array_keys( $listItems ), $listIds );

		foreach( $rmListIds as $rmListId ) {
			$rmIds[] = $listItems[$rmListId]->getRefId();
		}

		$search = $listManager->createSearch();
		$expr = array(
			$search->compare( '==', 'product.lists.refid', $rmIds ),
			$search->compare( '==', 'product.lists.domain', 'product' ),
			$search->compare( '==', 'product.lists.type.code', 'default' ),
			$search->compare( '==', 'product.lists.type.domain', 'product' ),
		);
		$search->setConditions( $search->combine( '&&', $expr ) );
		$search->setSlice( 0, 0x7fffffff );

		foreach( $listManager->aggregate( $search, 'product.lists.refid' ) as $key => $count )
		{
			if( $count > 1 ) {
				unset( $rmIds[$key] );
			}
		}

		$listManager->deleteItems( $rmListIds  );
		$manager->deleteItems( $rmIds  );
	}


	/**
	 * Creates a new pre-filled item
	 *
	 * @return \Aimeos\MShop\Product\Item\Iface New product item object
	 */
	protected function createItem()
	{
		$context = $this->getContext();
		$manager = \Aimeos\MShop\Factory::createManager( $context, 'product' );
		$typeManager = \Aimeos\MShop\Factory::createManager( $context, 'product/type' );

		$item = $manager->createItem();
		$item->setTypeId( $typeManager->findItem( 'default', array(), 'product' )->getId() );
		$item->setStatus( 1 );

		return $item;
	}


	/**
	 * Creates a new pre-filled list item
	 *
	 * @param string $id Parent ID for the new list item
	 * @return \Aimeos\MShop\Common\Item\Lists\Iface New list item object
	 */
	protected function createListItem( $id )
	{
		$context = $this->getContext();
		$manager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists' );
		$typeManager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists/type' );

		$item = $manager->createItem();
		$item->setTypeId( $typeManager->findItem( 'default', array(), 'product' )->getId() );
		$item->setDomain( 'product' );
		$item->setParentId( $id );
		$item->setStatus( 1 );

		return $item;
	}


	/**
	 * Returns the products for the given codes and IDs
	 *
	 * @param array $codes List of product codes
	 * @param array $ids List of product IDs
	 * @return array List of products with ID as key and items implementing \Aimeos\MShop\Product\Item\Iface as values
	 */
	protected function getProductItems( array $codes, array $ids )
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'product' );

		$search = $manager->createSearch();
		$expr = array(
			$search->compare( '==', 'product.id', $ids ),
			$search->compare( '==', 'product.code', $codes ),
		);
		$search->setConditions( $search->combine( '||', $expr ) );
		$search->setSlice( 0, 0x7fffffff );

		return $manager->searchItems( $search, array( 'attribute' ) );
	}


	/**
	 * Maps the existing product variants to an associative array as expected by the template
	 *
	 * @param \Aimeos\MW\View\Iface $view View object with helpers and assigned parameters
	 * @param boolean $copy True if items should be copied
	 * @return array Multi-dimensional associative array
	 */
	protected function getDataExisting( \Aimeos\MW\View\Iface $view, $copy = false )
	{
		$data = array();
		$variants = $view->item->getRefItems( 'product', null, 'default' );
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'product' );

		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'product.id', array_keys( $variants ) ) );
		$search->setSlice( 0, 0x7fffffff );

		$products = $manager->searchItems( $search, array( 'attribute' ) );

		foreach( $view->item->getListItems( 'product', 'default' ) as $listid => $listItem )
		{
			if( ( $refItem = $listItem->getRefItem() ) === null ) {
				continue;
			}

			if( $copy === false )
			{
				$code = $refItem->getCode();
				$data[$code]['product.lists.id'] = $listid;
				$data[$code]['product.id'] = $listItem->getRefId();
			}
			else
			{
				$code = $refItem->getCode() . '_copy';
				$data[$code]['product.lists.id'] = '';
				$data[$code]['product.id'] = '';
			}

			$data[$code]['product.label'] = $refItem->getLabel();

			if( isset( $products[$refItem->getId()] ) )
			{
				$attributes = $products[$refItem->getId()]->getRefItems( 'attribute', null, 'variant' );

				foreach( $attributes as $attrid => $attrItem )
				{
					$data[$code]['attr'][$attrid]['ref'] = $code;
					$data[$code]['attr'][$attrid]['label'] = $attrItem->getLabel();
				}
			}
		}

		return $data;
	}


	/**
	 * Maps the input parameter to an associative array as expected by the template
	 *
	 * @param \Aimeos\MW\View\Iface $view View object with helpers and assigned parameters
	 * @return array Multi-dimensional associative array
	 */
	protected function getDataParams( \Aimeos\MW\View\Iface $view )
	{
		$data = array();

		foreach( (array) $view->param( 'selection/product.code', array() ) as $pos => $code )
		{
			if( !empty( $code ) )
			{
				$data[$code]['product.lists.id'] = $view->param( 'selection/product.lists.id/' . $pos );
				$data[$code]['product.id'] = $view->param( 'selection/product.id/' . $pos );
				$data[$code]['product.label'] = $view->param( 'selection/product.label/' . $pos );
			}
		}

		foreach( (array) $view->param( 'selection/attr/ref', array() ) as $pos => $code )
		{
			if( !empty( $code ) )
			{
				$id = $view->param( 'selection/attr/id/' . $pos );

				$data[$code]['attr'][$id]['ref'] = $code;
				$data[$code]['attr'][$id]['label'] = $view->param( 'selection/attr/label/' . $pos );
			}
		}

		return $data;
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames()
	{
		return $this->getContext()->getConfig()->get( $this->subPartPath, $this->subPartNames );
	}


	/**
	 * Returns the mapped input parameter or the existing items as expected by the template
	 *
	 * @param \Aimeos\MW\View\Iface $view View object with helpers and assigned parameters
	 * @param boolean $copy True if items should be copied
	 */
	protected function setData( \Aimeos\MW\View\Iface $view, $copy = false )
	{
		if( $view->item->getType() !== 'select' ) {
			return;
		}

		$view->selectionData = $this->getDataParams( $view );

		if( !empty( $view->selectionData ) ) {
			return;
		}

		$view->selectionData = $this->getDataExisting( $view, $copy );
	}


	/**
	 * Updates the product variants
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $product Product item with referenced domain items
	 */
	protected function updateItems( \Aimeos\MW\View\Iface $view )
	{
		if( $view->item->getType() !== 'select' ) {
			return;
		}

		$id = $view->item->getId();
		$context = $this->getContext();
		$data = $this->getDataParams( $view );

		$manager = \Aimeos\MShop\Factory::createManager( $context, 'product' );
		$listManager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists' );

		$product = $manager->getItem( $id, array( 'product' ) );
		$listItems = $product->getListItems( 'product', 'default' );
		$refItems = $product->getRefItems( 'product', null, 'default' );

		$products = $this->getProductItems( array_keys( $data ), array_keys( $refItems ) );
		$listIds = (array) $view->param( 'selection/product.lists.id', array() );

		$listItem = $this->createListItem( $id );
		$prodItem = $this->createItem();


		foreach( $listIds as $idx => $listid )
		{
			if( !isset( $listItems[$listid] ) )
			{
				$litem = $listItem;
				$litem->setId( null );

				$item = $prodItem;
				$item->setId( null );
			}
			else
			{
				$litem = $listItems[$listid];
				$item = $litem->getRefItem();
			}

			$code = $view->param( 'selection/product.code/' . $idx );

			$item->setCode( $code );
			$item->setLabel( $view->param( 'selection/product.label/' . $idx ) );

			$manager->saveItem( $item );

			$litem->setPosition( $idx );
			$litem->setRefId( $item->getId() );

			$listManager->saveItem( $litem, false );

			$variant = ( isset( $products[$item->getId()] ) ? $products[$item->getId()] : $item );
			$attr = ( isset( $data[$code]['attr'] ) ? (array) $data[$code]['attr'] : array() );

			$manager->updateListItems( $variant, $attr, 'attribute', 'variant' );
		}

		$this->cleanupItems( $listItems, $listIds );
	}
}