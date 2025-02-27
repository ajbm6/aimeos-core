<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */

$totalQuantity = 0;
$enc = $this->encoder();

$basketTarget = $this->config( 'client/html/basket/standard/url/target' );
$basketController = $this->config( 'client/html/basket/standard/url/controller', 'basket' );
$basketAction = $this->config( 'client/html/basket/standard/url/action', 'index' );
$basketConfig = $this->config( 'client/html/basket/standard/url/config', array() );

$detailTarget = $this->config( 'client/html/catalog/detail/url/target' );
$detailController = $this->config( 'client/html/catalog/detail/url/controller', 'catalog' );
$detailAction = $this->config( 'client/html/catalog/detail/url/action', 'detail' );
$detailConfig = $this->config( 'client/html/catalog/detail/url/config', array( 'absoluteUri' => 1 ) );

/** client/html/common/summary/detail/product/attribute/types
 * List of attribute type codes that should be displayed in the basket along with their product
 *
 * The products in the basket can store attributes that exactly define an ordered
 * product or which are important for the back office. By default, the product
 * variant attributes are always displayed and the configurable product attributes
 * are displayed separately.
 *
 * Additional attributes for each ordered product can be added by basket plugins.
 * Depending on the attribute types and if they should be shown to the customers,
 * you need to extend the list of displayed attribute types ab adding their codes
 * to the configurable list.
 *
 * @param array List of attribute type codes
 * @category Developer
 * @since 2014.09
 */
$attrTypes = $this->config( 'client/html/common/summary/detail/product/attribute/types', array( 'variant' ) );


if( isset( $this->summaryBasket ) )
{
	$price = $this->summaryBasket->getPrice();
	$priceValue = $price->getValue();
	$priceService = $price->getCosts();
	$priceRebate = $price->getRebate();
	$priceCurrency = $this->translate( 'client/currency', $price->getCurrencyId() );
}
else
{
	$priceValue = '0.00';
	$priceService = '0.00';
	$priceRebate = '0.00';
	$priceCurrency = '';
}

try
{
	$deliveryItem = $this->summaryBasket->getService( 'delivery' );
	$deliveryName = $deliveryItem->getName();
	$deliveryPriceItem = $deliveryItem->getPrice();
	$deliveryPriceService = $deliveryPriceItem->getCosts();
	$deliveryPriceValue = $deliveryPriceItem->getValue();
}
catch( Exception $e )
{
	$deliveryName = '';
	$deliveryPriceValue = '0.00';
	$deliveryPriceService = '0.00';
}

try
{
	$paymentItem = $this->summaryBasket->getService( 'payment' );
	$paymentName = $paymentItem->getName();
	$paymentPriceItem = $paymentItem->getPrice();
	$paymentPriceService = $paymentPriceItem->getCosts();
	$paymentPriceValue = $paymentPriceItem->getValue();
}
catch( Exception $e )
{
	$paymentName = '';
	$paymentPriceValue = '0.00';
	$paymentPriceService = '0.00';
}

/// Price format with price value (%1$s) and currency (%2$s)
$priceFormat = $this->translate( 'client', '%1$s %2$s' );

$unhide = $this->get( 'summaryShowHiddenAttributes', false );
$modify = $this->get( 'summaryEnableModify', false );
$errors = $this->get( 'summaryErrorCodes', array() );
$backParams = $this->get( 'summaryParams', array() );

?>
<div class="common-summary-detail container">
	<div class="header">
<?php if( isset( $this->summaryUrlBasket ) ) : ?>
		<a class="modify" href="<?php echo $enc->attr( $this->summaryUrlBasket ); ?>"><?php echo $enc->html( $this->translate( 'client', 'Change' ), $enc::TRUST ); ?></a>
<?php endif; ?>
		<h2><?php echo $enc->html( $this->translate( 'client', 'Details' ), $enc::TRUST ); ?></h2>
	</div>
	<div class="basket">
		<table>
			<thead>
				<tr>
					<th class="details"></th>
					<th class="quantity"><?php echo $enc->html( $this->translate( 'client', 'Quantity' ), $enc::TRUST ); ?></th>
					<th class="unitprice"><?php echo $enc->html( $this->translate( 'client', 'Price' ), $enc::TRUST ); ?></th>
					<th class="price"><?php echo $enc->html( $this->translate( 'client', 'Sum' ), $enc::TRUST ); ?></th>
<?php if( $modify ) : ?>
					<th class="action"></th>
<?php endif; ?>
				</tr>
			</thead>
			<tbody>
<?php if( isset( $this->summaryBasket ) ) : ?>
<?php 	foreach( $this->summaryBasket->getProducts() as $position => $product ) : $totalQuantity += $product->getQuantity(); ?>
				<tr class="product <?php echo ( isset( $errors['product'][$position] ) ? 'error' : '' ); ?>">
					<td class="details">
<?php		if( ( $url = $product->getMediaUrl() ) != '' ) : ?>
						<img src="<?php echo $this->content( $url ); ?>" />
<?php		endif; ?>
<?php		$params = array( 'd_prodid' => $product->getProductId(), 'd_name' => $product->getName( 'url' ) ); ?>
						<a class="product-name" href="<?php echo $enc->attr( $this->url( $detailTarget, $detailController, $detailAction, $params, array(), $detailConfig ) ); ?>">
<?php		echo $enc->html( $product->getName(), $enc::TRUST ); ?>
						</a>
<?php		foreach( $attrTypes as $attrType ) : ?>
						<ul class="attr-list <?php echo $enc->attr( 'attr-list-' . $attrType ); ?>">
<?php			foreach( $product->getAttributes( $attrType ) as $attribute ) : ?>
							<li class="attr-item">
								<span class="name"><?php echo $enc->html( $this->translate( 'client/code', $attribute->getCode() ) ); ?></span>
								<span class="value"><?php echo $enc->html( ( $attribute->getName() != '' ? $attribute->getName() : $attribute->getValue() ) ); ?></span>
							</li>
<?php			endforeach; ?>
						</ul>
<?php		endforeach; ?>
<?php		if( ( $attributes = $product->getAttributes( 'config' ) ) !== array() ) : ?>
						<ul class="attr-list attr-list-config">
<?php			foreach( $attributes as $attribute ) : ?>
							<li class="attr-item">
<?php					if( $modify ) : ?>
<?php						$params = array( 'b_action' => 'edit', 'b_position' => $position, 'b_quantity' => $product->getQuantity(), 'b_attrconfcode' => $attribute->getCode() ); ?>
								<a class="change" href="<?php echo $enc->attr( $this->url( $basketTarget, $basketController, $basketAction, $params, array(), $basketConfig ) ); ?>">
<?php					endif; ?>
									<span class="sign">−</span>
									<span class="name"><?php echo $enc->html( $this->translate( 'client/code', $attribute->getCode() ) ); ?></span>
									<span class="value"><?php echo $enc->html( ( $attribute->getName() != '' ? $attribute->getName() : $attribute->getValue() ) ); ?></span>
<?php					if( $modify ) : ?>
								</a>
<?php					endif; ?>
							</li>
<?php			endforeach; ?>
						</ul>
<?php		endif; ?>
<?php		if( ( $attributes = $product->getAttributes( 'custom' ) ) !== array() ) : ?>
						<ul class="attr-list attr-list-custom">
<?php			foreach( $attributes as $attribute ) : ?>
							<li class="attr-item">
								<span class="name"><?php echo $enc->html( $this->translate( 'client/code', $attribute->getCode() ) ); ?></span>
								<span class="value"><?php echo $enc->html( $attribute->getValue() ); ?></span>
							</li>
<?php			endforeach; ?>
						</ul>
<?php		endif; ?>
<?php		if( $unhide && ( $attributes = $product->getAttributes( 'hidden' ) ) !== array() ) : ?>
						<ul class="attr-list attr-list-hidden">
<?php			foreach( $attributes as $attribute ) : ?>
<?php				if( $attribute->getCode() === 'download' ) : ?>
							<li class="attr-item">
								<span class="name"><?php echo $enc->html( $this->translate( 'client/code', $attribute->getCode() ) ); ?></span>
								<span class="value"><a class="" href="<?php echo $enc->attr( $this->content( $attribute->getValue() ) ); ?>" ><?php echo $enc->html( $attribute->getName() ); ?></a></span>
							</li>
<?php				endif; ?>
<?php			endforeach; ?>
						</ul>
<?php		endif; ?>
					</td>
<?php		$prodPrice = $product->getPrice()->getValue(); ?>
					<td class="quantity">
<?php		if( $modify && ( $product->getFlags() & \Aimeos\MShop\Order\Item\Base\Product\Base::FLAG_IMMUTABLE ) == 0 ) : ?>
<?php			if( $product->getQuantity() > 1 ) : ?>
						<a class="minibutton change" href="<?php echo $enc->attr( $this->url( $basketTarget, $basketController, $basketAction, array( 'b_action' => 'edit', 'b_position' => $position, 'b_quantity' => $product->getQuantity() - 1 ) + $backParams, array(), $basketConfig ) ); ?>">−</a>
<?php			else : ?>
						&nbsp;
<?php			endif; ?>
						<input class="value" type="text" name="<?php echo $enc->attr( $this->formparam( array( 'b_prod', $position, 'quantity' ) ) ); ?>" value="<?php echo $enc->attr( $product->getQuantity() ); ?>" maxlength="10" required="required" />
						<input type="hidden" name="<?php echo $enc->attr( $this->formparam( array( 'b_prod', $position, 'position' ) ) ); ?>" value="<?php echo $enc->attr( $position ); ?>" />
						<a class="minibutton change" href="<?php echo $enc->attr( $this->url( $basketTarget, $basketController, $basketAction, array( 'b_action' => 'edit', 'b_position' => $position, 'b_quantity' => $product->getQuantity() + 1 ) + $backParams, array(), $basketConfig ) ); ?>">+</a>
<?php		else : ?>	
<?php 			echo $enc->html( $product->getQuantity() ); ?>
<?php		endif; ?>
					</td>
					<td class="unitprice"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $prodPrice ), $priceCurrency ) ); ?></td>
					<td class="price"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $prodPrice * $product->getQuantity() ), $priceCurrency ) ); ?></td>
<?php		if( $modify && ( $product->getFlags() & \Aimeos\MShop\Order\Item\Base\Product\Base::FLAG_IMMUTABLE ) == 0 ) : ?>
					<td class="action">
						<a class="minibutton change" href="<?php echo $enc->attr( $this->url( $basketTarget, $basketController, $basketAction, array( 'b_action' => 'delete', 'b_position' => $position ), array(), $basketConfig ) ); ?>"><?php echo $this->translate( 'client', 'X' ); ?></a>
					</td>
<?php		endif; ?>
				</tr>
<?php 	endforeach; ?>
<?php	if( $deliveryPriceValue > 0 ) : ?>
				<tr class="delivery">
					<td class="details">
<?php		if( isset( $this->summaryUrlServiceDelivery ) ) : ?>
						<a href="<?php echo $enc->attr( $this->summaryUrlServiceDelivery ); ?>">
							<?php echo $enc->html( $deliveryName ); ?>
						</a>
<?php		else : ?>
						<?php echo $enc->html( $deliveryName ); ?>
<?php		endif; ?>
					</td>
					<td class="quantity">1</td>
					<td class="unitprice"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $deliveryPriceValue ), $priceCurrency ) ); ?></td>
					<td class="price"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $deliveryPriceValue ), $priceCurrency ) ); ?></td>
<?php		if( $modify ) : ?>
					<td class="action"></td>
<?php		endif; ?>
				</tr>
<?php	endif; ?>
<?php	if( $paymentPriceValue > 0 ) : ?>
				<tr class="payment">
					<td class="details">
<?php		if( isset( $this->summaryUrlServicePayment ) ) : ?>
						<a href="<?php echo $enc->attr( $this->summaryUrlServicePayment ); ?>">
							<?php echo $enc->html( $paymentName ); ?>
						</a>
<?php		else : ?>
						<?php echo $enc->html( $paymentName ); ?>
<?php		endif; ?>
					</td>
					<td class="quantity">1</td>
					<td class="unitprice"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $paymentPriceValue ), $priceCurrency ) ); ?></td>
					<td class="price"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $paymentPriceValue ), $priceCurrency ) ); ?></td>
<?php		if( $modify ) : ?>
					<td class="action"></td>
<?php		endif; ?>
				</tr>
<?php	endif; ?>
<?php endif; ?>
			</tbody>
			<tfoot>
				<tr class="subtotal">
					<td colspan="3"><?php echo $enc->html( $this->translate( 'client', 'Sub-total' ) ); ?></td>
					<td class="price"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $priceValue ), $priceCurrency ) ); ?></td>
<?php if( $modify ) : ?>
					<td class="action"></td>
<?php endif; ?>
				</tr>
				<tr class="delivery">
					<td colspan="3"><?php echo $enc->html( $this->translate( 'client', 'Shipping' ) ); ?></td>
					<td class="price"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $priceService - $paymentPriceService ), $priceCurrency ) ); ?></td>
<?php if( $modify ) : ?>
					<td class="action"></td>
<?php endif; ?>
				</tr>
<?php if( $paymentPriceService > 0 ) : ?>
				<tr class="payment">
					<td colspan="3"><?php echo $enc->html( $this->translate( 'client', 'Payment costs' ) ); ?></td>
					<td class="price"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $paymentPriceService ), $priceCurrency ) ); ?></td>
<?php	if( $modify ) : ?>
					<td class="action"></td>
<?php endif; ?>
				</tr>
<?php endif; ?>
				<tr class="total">
					<td colspan="3"><?php echo $enc->html( $this->translate( 'client', 'Total' ) ); ?></td>
					<td class="price"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $priceValue + $priceService ), $priceCurrency ) ); ?></td>
<?php if( $modify ) : ?>
					<td class="action"></td>
<?php endif; ?>
				</tr>
<?php foreach( $this->get( 'summaryTaxRates', array() ) as $taxRate => $priceValue ) : ?>
<?php	if( $taxRate > '0.00' && $priceValue > '0.00' ) : ?>
				<tr class="tax">
					<td colspan="3"><?php echo $enc->html( sprintf( $this->translate( 'client', 'Incl. %1$s%% VAT' ), $this->number( $taxRate ) ) ); ?></td>
					<td class="price"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $priceValue / ( $taxRate + 100 ) * $taxRate ), $priceCurrency ) ); ?></td>
<?php		if( $modify ) : ?>
					<td class="action"></td>
<?php		endif; ?>
				</tr>
<?php	endif; ?>
<?php endforeach; ?>
<?php if( $priceRebate > '0.00' ) : ?>
				<tr class="rebate">
					<td colspan="3"><?php echo $enc->html( $this->translate( 'client', 'Included rebates' ) ); ?></td>
					<td class="price"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $priceRebate ), $priceCurrency ) ); ?></td>
<?php	if( $modify ) : ?>
					<td class="action"></td>
<?php	endif; ?>
				</tr>
<?php endif; ?>
				<tr class="quantity">
					<td colspan="3"><?php echo $enc->html( $this->translate( 'client', 'Total quantity' ) ); ?></td>
					<td class="value"><?php echo $enc->html( sprintf( $this->translate( 'client', '%1$d article', '%1$d articles', $totalQuantity ), $totalQuantity ) ); ?></td>
<?php if( $modify ) : ?>
					<td class="action"></td>
<?php endif; ?>
				</tr>
			</tfoot>
		</table>
	</div>
<?php echo $this->get( 'detailBody' ); ?>
</div>
