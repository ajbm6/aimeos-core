<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2011
 * @copyright Aimeos (aimeos.org), 2015
 * @package MShop
 * @subpackage Product
 */


namespace Aimeos\MShop\Product\Item;


/**
 * Default impelementation of a product item.
 *
 * @package MShop
 * @subpackage Product
 */
class Standard
	extends \Aimeos\MShop\Common\Item\ListRef\Base
	implements \Aimeos\MShop\Product\Item\Iface
{
	private $values;


	/**
	 * Initializes the item object.
	 *
	 * @param array $values Parameter for initializing the basic properties
	 * @param \Aimeos\MShop\Common\Lists\Item\Iface[] $listItems List of list items
	 * @param \Aimeos\MShop\Common\Item\Iface[] $refItems List of referenced items
	 */
	public function __construct( array $values = array(), array $listItems = array(), array $refItems = array() )
	{
		parent::__construct( 'product.', $values, $listItems, $refItems );

		$this->values = $values;
	}


	/**
	 * Returns the type ID of the product item.
	 *
	 * @return integer|null Type ID of the product item
	 */
	public function getTypeId()
	{
		if( isset( $this->values['product.typeid'] ) ) {
			return (int) $this->values['product.typeid'];
		}

		return null;
	}


	/**
	 * Sets the new type ID of the product item.
	 *
	 * @param integer $typeid New type ID of the product item
	 */
	public function setTypeId( $typeid )
	{
		if( $typeid == $this->getTypeId() ) { return; }

		$this->values['product.typeid'] = (int) $typeid;
		$this->setModified();
	}


	/**
	 * Returns the type of the product item.
	 *
	 * @return string|null Type of the product item
	 */
	public function getType()
	{
		if( isset( $this->values['product.type'] ) ) {
			return (string) $this->values['product.type'];
		}

		return null;
	}


	/**
	 * Returns the status of the product item.
	 *
	 * @return integer Status of the product item
	 */
	public function getStatus()
	{
		if( isset( $this->values['product.status'] ) ) {
			return (int) $this->values['product.status'];
		}

		return 0;
	}


	/**
	 * Sets the new status of the product item.
	 *
	 * @param integer $status New status of the product item
	 */
	public function setStatus( $status )
	{
		if( $status == $this->getStatus() ) { return; }

		$this->values['product.status'] = (int) $status;
		$this->setModified();
	}


	/**
	 * Returns the code of the product item.
	 *
	 * @return string Code of the product item
	 */
	public function getCode()
	{
		if( isset( $this->values['product.code'] ) ) {
			return (string) $this->values['product.code'];
		}

		return '';
	}


	/**
	 * Sets the new code of the product item.
	 *
	 * @param string $code New code of product item
	 */
	public function setCode( $code )
	{
		$this->checkCode( $code );

		if( $code == $this->getCode() ) { return; }

		$this->values['product.code'] = (string) $code;
		$this->setModified();
	}


	/**
	 * Returns the label of the product item.
	 *
	 * @return string Label of the product item
	 */
	public function getLabel()
	{
		if( isset( $this->values['product.label'] ) ) {
			return (string) $this->values['product.label'];
		}

		return '';
	}


	/**
	 * Sets a new label of the product item.
	 *
	 * @param string $label New label of the product item
	 */
	public function setLabel( $label )
	{
		if( $label == $this->getLabel() ) { return; }

		$this->values['product.label'] = (string) $label;
		$this->setModified();
	}


	/**
	 * Returns the starting point of time, in which the product is available.
	 *
	 * @return string|null ISO date in YYYY-MM-DD hh:mm:ss format
	 */
	public function getDateStart()
	{
		if( isset( $this->values['product.datestart'] ) ) {
			return (string) $this->values['product.datestart'];
		}

		return null;
	}


	/**
	 * Sets a new starting point of time, in which the product is available.
	 *
	 * @param string|null New ISO date in YYYY-MM-DD hh:mm:ss format
	 */
	public function setDateStart( $date )
	{
		if( $date === $this->getDateStart() ) { return; }

		$this->values['product.datestart'] = $this->checkDateFormat( $date );
		$this->setModified();
	}


	/**
	 * Returns the ending point of time, in which the product is available.
	 *
	 * @return string|null ISO date in YYYY-MM-DD hh:mm:ss format
	 */
	public function getDateEnd()
	{
		if( isset( $this->values['product.dateend'] ) ) {
			return (string) $this->values['product.dateend'];
		}

		return null;
	}


	/**
	 * Sets a new ending point of time, in which the product is available.
	 *
	 * @param string|null New ISO date in YYYY-MM-DD hh:mm:ss format
	 */
	public function setDateEnd( $date )
	{
		if( $date === $this->getDateEnd() ) { return; }

		$this->values['product.dateend'] = $this->checkDateFormat( $date );
		$this->setModified();
	}


	/**
	 * Returns the configuration values of the item
	 *
	 * @return array Configuration values
	 */
	public function getConfig()
	{
		if( isset( $this->values['product.config'] ) ) {
			return (array) $this->values['product.config'];
		}

		return array();
	}


	/**
	 * Sets the configuration values of the item.
	 *
	 * @param array $config Configuration values
	 */
	public function setConfig( array $config )
	{
		$this->values['product.config'] = $config;
		$this->setModified();
	}


	/**
	 * Returns the item type
	 *
	 * @return Item type, subtypes are separated by slashes
	 */
	public function getResourceType()
	{
		return 'product';
	}


	/**
	 * Sets the item values from the given array.
	 *
	 * @param array $list Associative list of item keys and their values
	 * @return array Associative list of keys and their values that are unknown
	 */
	public function fromArray( array $list )
	{
		$unknown = array();
		$list = parent::fromArray( $list );

		foreach( $list as $key => $value )
		{
			switch( $key )
			{
				case 'product.typeid': $this->setTypeId( $value ); break;
				case 'product.code': $this->setCode( $value ); break;
				case 'product.label': $this->setLabel( $value ); break;
				case 'product.status': $this->setStatus( $value ); break;
				case 'product.datestart': $this->setDateStart( $value ); break;
				case 'product.dateend': $this->setDateEnd( $value ); break;
				case 'product.config': $this->setConfig( $value ); break;
				default: $unknown[$key] = $value;
			}
		}

		return $unknown;
	}


	/**
	 * Returns the item values as array.
	 *
	 * @return Associative list of item properties and their values
	 */
	public function toArray()
	{
		$list = parent::toArray();

		$list['product.typeid'] = $this->getTypeId();
		$list['product.type'] = $this->getType();
		$list['product.code'] = $this->getCode();
		$list['product.label'] = $this->getLabel();
		$list['product.status'] = $this->getStatus();
		$list['product.datestart'] = $this->getDateStart();
		$list['product.dateend'] = $this->getDateEnd();
		$list['product.config'] = $this->getConfig();

		return $list;
	}
}
