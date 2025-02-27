<?php

/**
 * @copyright Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package MShop
 * @subpackage Service
 */


namespace Aimeos\MShop\Service\Item;


/**
 * Service item with common methods.
 *
 * @package MShop
 * @subpackage Service
 */
class Standard
	extends \Aimeos\MShop\Common\Item\ListRef\Base
	implements \Aimeos\MShop\Service\Item\Iface
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
		parent::__construct( 'service.', $values, $listItems, $refItems );

		$this->values = $values;
	}


	/**
	 * Returns the code of the service item payment if available.
	 *
	 * @return string
	 */
	public function getCode()
	{
		if( isset( $this->values['service.code'] ) ) {
			return (string) $this->values['service.code'];
		}

		return '';
	}


	/**
	 * Sets the code of the service item payment.
	 *
	 * @param string code of the service item payment
	 */
	public function setCode( $code )
	{
		$this->checkCode( $code );

		if( $code == $this->getCode() ) { return; }

		$this->values['service.code'] = (string) $code;
		$this->setModified();
	}


	/**
	 * Returns the type of the service item if available.
	 *
	 * @return string Service item type
	 */
	public function getType()
	{
		if( isset( $this->values['service.type'] ) ) {
			return (string) $this->values['service.type'];
		}

		return null;
	}


	/**
	 * Returns the type ID of the service item if available.
	 *
	 * @return integer Service item type ID
	 */
	public function getTypeId()
	{
		if( isset( $this->values['service.typeid'] ) ) {
			return (int) $this->values['service.typeid'];
		}

		return null;
	}


	/**
	 * Sets the type ID of the service item.
	 *
	 * @param integer Type ID of the service item
	 */
	public function setTypeId( $typeId )
	{
		if( $typeId == $this->getTypeId() ) { return; }

		$this->values['service.typeid'] = (int) $typeId;
		$this->setModified();
	}


	/**
	 * Returns the name of the service provider the item belongs to.
	 *
	 * @return string Name of the service provider
	 */
	public function getProvider()
	{
		if( isset( $this->values['service.provider'] ) ) {
			return (string) $this->values['service.provider'];
		}

		return '';
	}


	/**
	 * Sets the new name of the service provider the item belongs to.
	 *
	 * @param string $provider Name of the service provider
	 */
	public function setProvider( $provider )
	{
		if( $provider == $this->getProvider() ) { return; }

		$this->values['service.provider'] = (string) $provider;
		$this->setModified();
	}


	/**
	 * Returns the label of the service item payment if available.
	 *
	 * @return string
	 */
	public function getLabel()
	{
		if( isset( $this->values['service.label'] ) ) {
			return (string) $this->values['service.label'];
		}

		return '';
	}


	/**
	 * Sets the label of the service item payment.
	 *
	 * @param string label of the service item payment
	 */
	public function setLabel( $label )
	{
		if( $label == $this->getLabel() ) { return; }

		$this->values['service.label'] = (string) $label;
		$this->setModified();
	}


	/**
	 * Returns the configuration values of the item
	 *
	 * @return array Configuration values
	 */
	public function getConfig()
	{
		if( isset( $this->values['service.config'] ) ) {
			return (array) $this->values['service.config'];
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
		$this->values['service.config'] = $config;
		$this->setModified();
	}


	/**
	 * Returns the position of the service item in the list of deliveries.
	 *
	 * @return integer Position in item list
	 */
	public function getPosition()
	{
		if( isset( $this->values['service.position'] ) ) {
			return (int) $this->values['service.position'];
		}

		return 0;
	}


	/**
	 * Sets the new position of the service item in the list of deliveries.
	 *
	 * @param integer $pos Position in item list
	 */
	public function setPosition( $pos )
	{
		if( $pos == $this->getPosition() ) { return; }

		$this->values['service.position'] = (int) $pos;
		$this->setModified();
	}


	/**
	 * Returns the status of the item.
	 *
	 * @return integer Status of the item
	 */
	public function getStatus()
	{
		if( isset( $this->values['service.status'] ) ) {
			return (int) $this->values['service.status'];
		}

		return 0;
	}


	/**
	 * Sets the status of the item.
	 *
	 * @param integer $status Status of the item
	 */
	public function setStatus( $status )
	{
		if( $status == $this->getStatus() ) { return; }

		$this->values['service.status'] = (int) $status;
		$this->setModified();
	}


	/**
	 * Returns the item type
	 *
	 * @return Item type, subtypes are separated by slashes
	 */
	public function getResourceType()
	{
		return 'service';
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
				case 'service.typeid': $this->setTypeId( $value ); break;
				case 'service.code': $this->setCode( $value ); break;
				case 'service.label': $this->setLabel( $value ); break;
				case 'service.provider': $this->setProvider( $value ); break;
				case 'service.position': $this->setPosition( $value ); break;
				case 'service.config': $this->setConfig( $value ); break;
				case 'service.status': $this->setStatus( $value ); break;
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

		$list['service.typeid'] = $this->getTypeId();
		$list['service.type'] = $this->getType();
		$list['service.code'] = $this->getCode();
		$list['service.label'] = $this->getLabel();
		$list['service.provider'] = $this->getProvider();
		$list['service.position'] = $this->getPosition();
		$list['service.config'] = $this->getConfig();
		$list['service.status'] = $this->getStatus();

		return $list;
	}

}
