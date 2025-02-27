<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Product\Text;


/**
 * Default implementation of product text JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/product/text/standard/subparts
	 * List of JQAdm sub-clients rendered within the product text section
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
	private $subPartPath = 'admin/jqadm/product/text/standard/subparts';
	private $subPartNames = array();
	private $types;


	/**
	 * Copies a resource
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function copy()
	{
		$view = $this->getView();

		$this->setData( $view );
		$view->textBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->textBody .= $client->copy();
		}

		$tplconf = 'admin/jqadm/product/text/template-item';
		$default = 'product/item-text-default.php';

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
		$view->textBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->textBody .= $client->create();
		}

		$tplconf = 'admin/jqadm/product/text/template-item';
		$default = 'product/item-text-default.php';

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
		$view->textBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->textBody .= $client->get();
		}

		$tplconf = 'admin/jqadm/product/text/template-item';
		$default = 'product/item-text-default.php';

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

		$manager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists' );
		$textManager = \Aimeos\MShop\Factory::createManager( $context, 'text' );

		$manager->begin();
		$textManager->begin();

		try
		{
			$this->updateItems( $view );
			$view->textBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->textBody .= $client->save();
			}

			$textManager->commit();
			$manager->commit();
			return;
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'product-item-text' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', array() ) + $error;

			$textManager->rollback();
			$manager->rollback();
		}
		catch( \Exception $e )
		{
			$context->getLogger()->log( $e->getMessage() . ' - ' . $e->getTraceAsString() );
			$error = array( 'product-item-text' => $e->getMessage() );
			$view->errors = $view->get( 'errors', array() ) + $error;

			$textManager->rollback();
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
		/** admin/jqadm/product/text/decorators/excludes
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
		 *  admin/jqadm/product/text/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/text/decorators/global
		 * @see admin/jqadm/product/text/decorators/local
		 */

		/** admin/jqadm/product/text/decorators/global
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
		 *  admin/jqadm/product/text/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/text/decorators/excludes
		 * @see admin/jqadm/product/text/decorators/local
		 */

		/** admin/jqadm/product/text/decorators/local
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
		 *  admin/jqadm/product/text/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Product\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/text/decorators/excludes
		 * @see admin/jqadm/product/text/decorators/global
		 */
		return $this->createSubClient( 'product/text/' . $type, $name );
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
	 * Returns the ID for the given text type
	 *
	 * @param string $type Text type
	 * @return integer Type ID for the given type
	 * @throws \Aimeos\Admin\JQAdm\Exception If the given type is unknown
	 */
	protected function getTypeId( $type )
	{
		if( $this->types === null )
		{
			$this->types = array();
			$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'text/type' );

			$search = $manager->createSearch();
			$search->setConditions( $search->compare( '==', 'text.type.domain', 'product' ) );

			foreach( $manager->searchItems( $search ) as $id => $typeItem ) {
				$this->types[$typeItem->getCode()] = $id;
			}
		}

		if( isset( $this->types[$type] ) ) {
			return $this->types[$type];
		}

		throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Unknown type "%1$s"', $type ) );
	}


	/**
	 * Returns the mapped input parameter or the existing items as expected by the template
	 *
	 * @param \Aimeos\MW\View\Iface $view View object with helpers and assigned parameters
	 * @return array Multi-dimensional associative array
	 */
	protected function setData( \Aimeos\MW\View\Iface $view )
	{
		$context = $this->getContext();
		$manager = \Aimeos\MShop\Factory::createManager( $context, 'text/type' );

		$view->textTypes = $manager->searchItems( $manager->createSearch() );
		$view->textData = (array) $view->param( 'text', array() );

		if( !empty( $view->textData ) || ( $id = $view->item->getId() ) === null ) {
			return;
		}

		$data = array();
		$data['langid'] = array();

		foreach( $view->item->getListItems( 'text', 'default' ) as $listItem )
		{
			$refItem = $listItem->getRefItem();
			$type = $refItem->getType();

			$data[$type]['listid'][] = $listItem->getId();
			$data[$type]['content'][] = $refItem->getContent();

			if( count( $data[$type]['listid'] ) > count( $data['langid'] ) ) {
				$data['langid'][] = $refItem->getLanguageId();
			}
		}

		$view->textData = $data;
	}


	/**
	 * Updates existing product text items or creates new ones
	 *
	 * @param \Aimeos\MW\View\Iface $view View object with helpers and assigned parameters
	 */
	protected function updateItems( \Aimeos\MW\View\Iface $view )
	{
		$id = $view->item->getId();
		$context = $this->getContext();
		$types = array( 'name', 'short', 'long', 'url', 'meta-keyword', 'meta-description' );

		$manager = \Aimeos\MShop\Factory::createManager( $context, 'product' );
		$textManager = \Aimeos\MShop\Factory::createManager( $context, 'text' );
		$listManager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists' );
		$listTypeManager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists/type' );

		$listIds = array();
		$langIds = (array) $view->param( 'text/langid', array() );
		$listItems = $manager->getItem( $id, array( 'text' ) )->getListItems( 'text' );


		$listItem = $listManager->createItem();
		$listItem->setTypeId( $listTypeManager->findItem( 'default', array(), 'text' )->getId() );
		$listItem->setDomain( 'text' );
		$listItem->setParentId( $id );
		$listItem->setStatus( 1 );

		$textItem = $textManager->createItem();
		$textItem->setDomain( 'product' );
		$textItem->setStatus( 1 );


		foreach( $langIds as $idx => $langid )
		{
			foreach( $types as $type )
			{
				$content = trim( $view->param( 'text/' . $type . '/content/' . $idx ) );
				$listid = $view->param( 'text/' . $type . '/listid' . $idx );
				$listIds[] = $listid;

				if( !isset( $listItems[$listid] ) )
				{
					$litem = $listItem;
					$litem->setId( null );

					$item = $textItem;
					$item->setId( null );
				}
				else
				{
					$litem = $listItems[$listid];
					$item = $litem->getRefItem();
				}

				$item->setContent( $content );
				$item->setLabel( mb_strcut( $item->getContent(), 0, 255 ) );
				$item->setTypeId( $this->getTypeId( $type ) );
				$item->setLanguageId( $langid );

				$textManager->saveItem( $item );

				$litem->setPosition( $idx );
				$litem->setRefId( $item->getId() );

				$listManager->saveItem( $litem, false );
			}
		}


		$rmIds = array();
		$rmListIds = array_diff( array_keys( $listItems ), $listIds );

		foreach( $rmListIds as $id ) {
			$rmIds[] = $listItems[$id]->getRefId();
		}

		$listManager->deleteItems( $rmListIds  );
		$textManager->deleteItems( $rmIds  );
	}
}
