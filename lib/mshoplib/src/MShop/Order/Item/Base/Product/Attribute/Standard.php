<?php

/**
 * @copyright Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package MShop
 * @subpackage Order
 */


namespace Aimeos\MShop\Order\Item\Base\Product\Attribute;


/**
 * Default product attribute item implementation.
 *
 * @package MShop
 * @subpackage Order
 */
class Standard
	extends \Aimeos\MShop\Common\Item\Base
	implements \Aimeos\MShop\Order\Item\Base\Product\Attribute\Iface
{
	private $values;

	/**
	 * Initializes the order product attribute instance.
	 *
	 * @param array $values Associative array of order product attribute values. Possible
	 * keys: 'id', 'ordprodid', 'value', 'code', 'mtime'
	 */
	public function __construct( array $values = array() )
	{
		parent::__construct( 'order.base.product.attribute.', $values );

		$this->values = $values;
	}


	/**
	 * Returns the original attribute ID of the product attribute item.
	 *
	 * @return string Attribute ID of the product attribute item
	 */
	public function getAttributeId()
	{
		if( isset( $this->values['order.base.product.attribute.attributeid'] ) ) {
			return (string) $this->values['order.base.product.attribute.attributeid'];
		}

		return '';
	}


	/**
	 * Sets the original attribute ID of the product attribute item.
	 *
	 * @param string $id Attribute ID of the product attribute item
	 */
	public function setAttributeId( $id )
	{
		if( $id == $this->getAttributeId() ) { return; }

		$this->values['order.base.product.attribute.attributeid'] = (string) $id;
		$this->setModified();
	}


	/**
	 * Returns the ID of the ordered product as parent
	 *
	 * @return string|null ID of the ordered product
	 */
	public function getParentId()
	{
		if( isset( $this->values['order.base.product.attribute.parentid'] ) ) {
			return (string) $this->values['order.base.product.attribute.parentid'];
		}

		return null;
	}


	/**
	 * Sets the ID of the ordered product as parent
	 *
	 * @param string $id ID of the ordered product
	 */
	public function setParentId( $id )
	{
		if( $id == $this->getParentId() ) { return; }

		$this->values['order.base.product.attribute.parentid'] = (string) $id;
		$this->setModified();
	}


	/**
	 * Returns the value of the product attribute.
	 *
	 * @return string Value of the product attribute
	 */
	public function getType()
	{
		if( isset( $this->values['order.base.product.attribute.type'] ) ) {
			return (string) $this->values['order.base.product.attribute.type'];
		}

		return '';
	}


	/**
	 * Sets the value of the product attribute.
	 *
	 * @param string $type Type of the product attribute
	 */
	public function setType( $type )
	{
		if( $type == $this->getType() ) { return; }

		$this->values['order.base.product.attribute.type'] = (string) $type;
		$this->setModified();
	}


	/**
	 * Returns the code of the product attibute.
	 *
	 * @return string Code of the attribute
	 */
	public function getCode()
	{
		if( isset( $this->values['order.base.product.attribute.code'] ) ) {
			return (string) $this->values['order.base.product.attribute.code'];
		}

		return '';
	}


	/**
	 * Sets the code of the product attribute.
	 *
	 * @param string $code Code of the attribute
	 */
	public function setCode( $code )
	{
		$this->checkCode( $code );

		if( $code == $this->getCode() ) { return; }

		$this->values['order.base.product.attribute.code'] = (string) $code;
		$this->setModified();
	}


	/**
	 * Returns the value of the product attribute.
	 *
	 * @return string|array Value of the product attribute
	 */
	public function getValue()
	{
		if( isset( $this->values['order.base.product.attribute.value'] ) ) {
			return (string) $this->values['order.base.product.attribute.value'];
		}

		return '';
	}


	/**
	 * Sets the value of the product attribute.
	 *
	 * @param string|array $value Value of the product attribute
	 */
	public function setValue( $value )
	{
		if( $value == $this->getValue() ) { return; }

		$this->values['order.base.product.attribute.value'] = $value;
		$this->setModified();
	}


	/**
	 * Returns the localized name of the product attribute.
	 *
	 * @return string Localized name of the product attribute
	 */
	public function getName()
	{
		if( isset( $this->values['order.base.product.attribute.name'] ) ) {
			return (string) $this->values['order.base.product.attribute.name'];
		}

		return '';
	}


	/**
	 * Sets the localized name of the product attribute.
	 *
	 * @param string $name Localized name of the product attribute
	 */
	public function setName( $name )
	{
		if( $name == $this->getName() ) { return; }

		$this->values['order.base.product.attribute.name'] = (string) $name;
		$this->setModified();
	}


	/**
	 * Returns the item type
	 *
	 * @return Item type, subtypes are separated by slashes
	 */
	public function getResourceType()
	{
		return 'order/base/product/attribute';
	}


	/**
	 * Copys all data from a given attribute item.
	 *
	 * @param \Aimeos\MShop\Attribute\Item\Iface $item Attribute item to copy from
	 */
	public function copyFrom( \Aimeos\MShop\Attribute\Item\Iface $item )
	{
		$this->setAttributeId( $item->getId() );
		$this->setName( $item->getName() );
		$this->setCode( $item->getType() );
		$this->setValue( $item->getCode() );

		$this->setModified();
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
				case 'order.base.product.attribute.attrid': $this->setAttributeId( $value ); break;
				case 'order.base.product.attribute.parentid': $this->setParentId( $value ); break;
				case 'order.base.product.attribute.type': $this->setType( $value ); break;
				case 'order.base.product.attribute.code': $this->setCode( $value ); break;
				case 'order.base.product.attribute.value': $this->setValue( $value ); break;
				case 'order.base.product.attribute.name': $this->setName( $value ); break;
				default: $unknown[$key] = $value;
			}
		}

		return $unknown;
	}


	/**
	 * Returns the item values as array.
	 *
	 * @return array Associative list of item properties and their values
	 */
	public function toArray()
	{
		$list = parent::toArray();

		$list['order.base.product.attribute.attrid'] = $this->getAttributeId();
		$list['order.base.product.attribute.parentid'] = $this->getParentId();
		$list['order.base.product.attribute.type'] = $this->getType();
		$list['order.base.product.attribute.code'] = $this->getCode();
		$list['order.base.product.attribute.value'] = $this->getValue();
		$list['order.base.product.attribute.name'] = $this->getName();

		return $list;
	}

}
