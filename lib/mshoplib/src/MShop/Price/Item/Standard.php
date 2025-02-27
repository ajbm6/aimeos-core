<?php

/**
 * @copyright Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package MShop
 * @subpackage Price
 */


namespace Aimeos\MShop\Price\Item;


/**
 * Default implementation of a price object.
 *
 * @package MShop
 * @subpackage Price
 */
class Standard
	extends \Aimeos\MShop\Common\Item\ListRef\Base
	implements \Aimeos\MShop\Price\Item\Iface
{
	private $values;


	/**
	 * Initalizes the object with the given values
	 *
	 * @param array $values Associative array of key/value pairs for price, costs, rebate and currencyid
	 * @param \Aimeos\MShop\Common\Lists\Item\Iface[] $listItems List of list items
	 * @param \Aimeos\MShop\Common\Item\Iface[] $refItems List of referenced items
	 */
	public function __construct( array $values = array(), array $listItems = array(), array $refItems = array() )
	{
		parent::__construct( 'price.', $values, $listItems, $refItems );

		$this->values = $values;
	}


	/**
	 * Returns the type of the price.
	 *
	 * @return string|null Type of the price
	 */
	public function getType()
	{
		if( isset( $this->values['price.type'] ) ) {
			return (string) $this->values['price.type'];
		}

		return null;
	}


	/**
	 * Returns the type ID of the price.
	 *
	 * @return integer|null Type ID of the price
	 */
	public function getTypeId()
	{
		if( isset( $this->values['price.typeid'] ) ) {
			return (int) $this->values['price.typeid'];
		}

		return null;
	}


	/**
	 * Sets the new type ID of the price.
	 *
	 * @param integer $typeid Type ID of the price
	 */
	public function setTypeId( $typeid )
	{
		if( $typeid == $this->getTypeId() ) { return; }

		$this->values['price.typeid'] = (int) $typeid;
		$this->setModified();
	}


	/**
	 * Returns the currency ID.
	 *
	 * @return string|null Three letter ISO currency code (e.g. EUR)
	 */
	public function getCurrencyId()
	{
		if( isset( $this->values['price.currencyid'] ) ) {
			return (string) $this->values['price.currencyid'];
		}

		return null;
	}


	/**
	 * Sets the used currency ID.
	 *
	 * @param string $currencyid Three letter currency code
	 * @throws \Aimeos\MShop\Exception If the language ID is invalid
	 */
	public function setCurrencyId( $currencyid )
	{
		if( $currencyid == $this->getCurrencyId() ) { return; }

		$this->values['price.currencyid'] = $this->checkCurrencyId( $currencyid, false );
		$this->setModified();
	}


	/**
	 * Returns the domain the price is valid for.
	 *
	 * @return string Domain name
	 */
	public function getDomain()
	{
		if( isset( $this->values['price.domain'] ) ) {
			return (string) $this->values['price.domain'];
		}

		return '';
	}


	/**
	 * Sets the new domain the price is valid for.
	 *
	 * @param string $domain Domain name
	 */
	public function setDomain( $domain )
	{
		if( $domain == $this->getDomain() ) { return; }

		$this->values['price.domain'] = (string) $domain;
		$this->setModified();
	}


	/**
	 * Returns the label of the item
	 *
	 * @return string Label of the item
	 */
	public function getLabel()
	{
		if( isset( $this->values['price.label'] ) ) {
			return (string) $this->values['price.label'];
		}

		return '';
	}


	/**
	 * Sets the label of the item
	 *
	 * @param string $label Label of the item
	 */
	public function setLabel( $label )
	{
		if( $label == $this->getLabel() ) { return; }

		$this->values['price.label'] = (string) $label;
		$this->setModified();
	}


	/**
	 * Returns the quantity the price is valid for.
	 *
	 * @return integer Quantity
	 */
	public function getQuantity()
	{
		if( isset( $this->values['price.quantity'] ) ) {
			return (int) $this->values['price.quantity'];
		}

		return 1;
	}


	/**
	 * Sets the quantity the price is valid for.
	 *
	 * @param integer $quantity Quantity
	 */
	public function setQuantity( $quantity )
	{
		if( $quantity == $this->getQuantity() ) { return; }

		$this->values['price.quantity'] = (int) $quantity;
		$this->setModified();
	}


	/**
	 * Returns the amount of money.
	 *
	 * @return string Price value
	 */
	public function getValue()
	{
		if( isset( $this->values['price.value'] ) ) {
			return (string) $this->values['price.value'];
		}

		return '0.00';
	}


	/**
	 * Sets the new amount of money.
	 *
	 * @param integer|double $price Amount with two digits precision
	 */
	public function setValue( $price )
	{
		if( $price == $this->getValue() ) { return; }

		$this->checkPrice( $price );

		$this->values['price.value'] = $this->formatNumber( $price );
		$this->setModified();
	}


	/**
	 * Returns costs.
	 *
	 * @return string Costs
	 */
	public function getCosts()
	{
		if( isset( $this->values['price.costs'] ) ) {
			return (string) $this->values['price.costs'];
		}

		return '0.00';
	}


	/**
	 * Sets the new costs.
	 *
	 * @param integer|double $price Amount with two digits precision
	 */
	public function setCosts( $price )
	{
		if( $price == $this->getCosts() ) { return; }

		$this->checkPrice( $price );

		$this->values['price.costs'] = $this->formatNumber( $price );
		$this->setModified();
	}


	/**
	 * Returns the rebate amount.
	 *
	 * @return string Rebate amount
	 */
	public function getRebate()
	{
		if( isset( $this->values['price.rebate'] ) ) {
			return (string) $this->values['price.rebate'];
		}

		return '0.00';
	}


	/**
	 * Sets the new rebate amount.
	 *
	 * @param string $price Rebate amount with two digits precision
	 */
	public function setRebate( $price )
	{
		if( $price == $this->getRebate() ) { return; }

		$this->checkPrice( $price );

		$this->values['price.rebate'] = $this->formatNumber( $price );
		$this->setModified();
	}


	/**
	 * Returns the tax rate
	 *
	 * @return string Tax rate
	 */
	public function getTaxRate()
	{
		if( isset( $this->values['price.taxrate'] ) ) {
			return (string) $this->values['price.taxrate'];
		}

		return '0.00';
	}


	/**
	 * Sets the new tax rate.
	 *
	 * @param string $taxrate Tax rate with two digits precision
	 */
	public function setTaxRate( $taxrate )
	{
		if( $taxrate == $this->getTaxRate() ) { return; }

		$this->checkPrice( $taxrate );

		$this->values['price.taxrate'] = $this->formatNumber( $taxrate );
		$this->setModified();
	}


	/**
	 * Returns the status of the item
	 *
	 * @return integer Status of the item
	 */
	public function getStatus()
	{
		if( isset( $this->values['price.status'] ) ) {
			return (int) $this->values['price.status'];
		}

		return 0;
	}


	/**
	 * Sets the status of the item
	 *
	 * @param integer $status Status of the item
	 */
	public function setStatus( $status )
	{
		if( $status == $this->getStatus() ) { return; }

		$this->values['price.status'] = (int) $status;
		$this->setModified();
	}


	/**
	 * Returns the item type
	 *
	 * @return Item type, subtypes are separated by slashes
	 */
	public function getResourceType()
	{
		return 'price';
	}


	/**
	 * Add the given price to the current one.
	 *
	 * @param \Aimeos\MShop\Price\Item\Iface $item Price item which should be added
	 * @param integer $quantity Number of times the Price should be added
	 */
	public function addItem( \Aimeos\MShop\Price\Item\Iface $item, $quantity = 1 )
	{
		if( $item->getCurrencyId() != $this->getCurrencyId() )
		{
			throw new \Aimeos\MShop\Price\Exception( sprintf( 'Price can not be added. Currency ID "%1$s" of price item and currently used currency ID "%2$s" does not match.', $item->getCurrencyId(), $this->getCurrencyId() ) );
		}

		$this->values['price.value'] = $this->formatNumber( $this->getValue() + $item->getValue() * $quantity );
		$this->values['price.costs'] = $this->formatNumber( $this->getCosts() + $item->getCosts() * $quantity );
		$this->values['price.rebate'] = $this->formatNumber( $this->getRebate() + $item->getRebate() * $quantity );
	}


	/**
	 * Compares the properties of the given price item with its own one.
	 *
	 * This method compare only the essential price properties:
	 * * Value
	 * * Costs
	 * * Rebate
	 * * Taxrate
	 * * Quantity
	 * * Currency ID
	 *
	 * All other item properties are not compared.
	 *
	 * @param \Aimeos\MShop\Price\Item\Iface $price Price item to compare with
	 * @return boolean True if equal, false if not
	 * @since 2014.09
	 */
	public function compare( \Aimeos\MShop\Price\Item\Iface $price )
	{
		if( $this->getValue() === $price->getValue()
			&& $this->getCosts() === $price->getCosts()
			&& $this->getRebate() === $price->getRebate()
			&& $this->getTaxrate() === $price->getTaxrate()
			&& $this->getQuantity() === $price->getQuantity()
			&& $this->getCurrencyId() === $price->getCurrencyId()
		) {
			return true;
		}

		return false;
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
				case 'price.typeid': $this->setTypeId( $value ); break;
				case 'price.currencyid': $this->setCurrencyId( $value ); break;
				case 'price.domain': $this->setDomain( $value ); break;
				case 'price.quantity': $this->setQuantity( $value ); break;
				case 'price.value': $this->setValue( $value ); break;
				case 'price.costs': $this->setCosts( $value ); break;
				case 'price.rebate': $this->setRebate( $value ); break;
				case 'price.taxrate': $this->setTaxRate( $value ); break;
				case 'price.status': $this->setStatus( $value ); break;
				case 'price.label': $this->setLabel( $value ); break;
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

		$list['price.typeid'] = $this->getTypeId();
		$list['price.type'] = $this->getType();
		$list['price.currencyid'] = $this->getCurrencyId();
		$list['price.domain'] = $this->getDomain();
		$list['price.quantity'] = $this->getQuantity();
		$list['price.value'] = $this->getValue();
		$list['price.costs'] = $this->getCosts();
		$list['price.rebate'] = $this->getRebate();
		$list['price.taxrate'] = $this->getTaxRate();
		$list['price.status'] = $this->getStatus();
		$list['price.label'] = $this->getLabel();

		return $list;
	}


	/**
	 * Tests if the price is within the requirements.
	 *
	 * @param integer|double $value Monetary value
	 */
	protected function checkPrice( $value )
	{
		if( !is_numeric( $value ) ) {
			throw new \Aimeos\MShop\Price\Exception( sprintf( 'Invalid characters in price "%1$s"', $value ) );
		}
	}


	/**
	 * Formats the money value.
	 *
	 * @param string formatted money value
	 */
	protected function formatNumber( $number )
	{
		return number_format( $number, 2, '.', '' );
	}

}
