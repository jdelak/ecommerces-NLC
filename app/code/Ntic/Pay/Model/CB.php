<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ntic\Payment\Model;



/**
 * Pay In Store payment method model
 */
class CB extends \Magento\Payment\Model\Method\Cc
{
    /**
     * @var string
     */
    const CODE = 'cb';
    protected $_formBlockType = 'Ntic\Payment\Block\Form\Cc';
    protected $_code = self::CODE;
}