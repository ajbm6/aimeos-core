<?php

/**
 * @copyright Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package MShop
 * @subpackage Locale
 */


namespace Aimeos\MShop\Locale\Item\Language;


/**
 * Default implementation of a Language item.
 *
 * @package MShop
 * @subpackage Locale
 */
class Standard
	extends \Aimeos\MShop\Common\Item\Base
	implements \Aimeos\MShop\Locale\Item\Language\Iface
{

	private $modified = false;
	private $values;


	/**
	 * Initialize the language object.
	 *
	 * @param array $values Possible params to be set on init.
	 */
	public function __construct( array $values = array( ) )
	{
		parent::__construct( 'locale.language.', $values );

		$this->values = $values;

		if( isset( $values['locale.language.id'] ) ) {
			$this->setId( $values['locale.language.id'] );
		}
	}


	/**
	 * Returns the id of the language.
	 *
	 * @return string|null Id of the language
	 */
	public function getId()
	{
		if( isset( $this->values['locale.language.id'] ) ) {
			return (string) $this->values['locale.language.id'];
		}

		return null;
	}


	/**
	 * Sets the id of the language.
	 *
	 * @param string $key Id to set
	 */
	public function setId( $key )
	{
		if( $key !== null )
		{
			$this->setCode( $key );
			$this->values['locale.language.id'] = $this->values['locale.language.code'];
			$this->modified = false;
		}
		else
		{
			$this->values['locale.language.id'] = null;
			$this->modified = true;
		}
	}


	/**
	 * Returns the two letter ISO language code.
	 *
	 * @return string two letter ISO language code
	 */
	public function getCode()
	{
		if( isset( $this->values['locale.language.code'] ) ) {
			return (string) $this->values['locale.language.code'];
		}

		return '';
	}


	/**
	 * Sets the two letter ISO language code.
	 *
	 * @param string $key two letter ISO language code
	 */
	public function setCode( $key )
	{
		if( $key == $this->getCode() ) { return; }

		$len = strlen( $key );
		if( $len < 2 || $len > 5 || preg_match( '/^[a-z]{2,3}((-|_)[a-zA-Z]{2})?$/', $key ) !== 1 ) {
			throw new \Aimeos\MShop\Locale\Exception( sprintf( 'Invalid characters in ISO language code "%1$s"', $key ) );
		}

		$this->values['locale.language.code'] = (string) $key;
		$this->modified = true;
	}


	/**
	 * Returns the label property.
	 *
	 * @return string Returns the label of the language
	 */
	public function getLabel()
	{
		if( isset( $this->values['locale.language.label'] ) ) {
			return (string) $this->values['locale.language.label'];
		}

		return '';
	}


	/**
	 * Sets the label property.
	 *
	 * @param string $label Label of the language
	 */
	public function setLabel( $label )
	{
		if( $label == $this->getLabel() ) { return; }

		$this->values['locale.language.label'] = (string) $label;
		$this->setModified();
	}


	/**
	 * Returns the status of the item.
	 *
	 * @return integer Status of the item
	 */
	public function getStatus()
	{
		if( isset( $this->values['locale.language.status'] ) ) {
			return (int) $this->values['locale.language.status'];
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

		$this->values['locale.language.status'] = (int) $status;
		$this->setModified();
	}


	/**
	 * Returns the item type
	 *
	 * @return Item type, subtypes are separated by slashes
	 */
	public function getResourceType()
	{
		return 'locale/language';
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
				case 'locale.language.id': $this->setId( $value ); break;
				case 'locale.language.code': $this->setCode( $value ); break;
				case 'locale.language.label': $this->setLabel( $value ); break;
				case 'locale.language.status': $this->setStatus( $value ); break;
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

		$list['locale.language.id'] = $this->getId();
		$list['locale.language.code'] = $this->getCode();
		$list['locale.language.label'] = $this->getLabel();
		$list['locale.language.status'] = $this->getStatus();

		return $list;
	}


	/**
	 * Tests if the needed object properties are modified.
	 *
	 * @return boolean True if modiefied flag was set otherwise false
	 */
	public function isModified()
	{
		return $this->modified || parent::isModified();
	}

}
