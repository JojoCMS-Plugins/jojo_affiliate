<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2007-2008 Harvey Kane <code@ragepank.com>
 * Copyright 2007-2008 Michael Holt <code@gardyneholt.co.nz>
 * Copyright 2007 Melanie Schulz <mel@gardyneholt.co.nz>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Harvey Kane <code@ragepank.com>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 */

/* get the first look at the parsepage data */
Jojo::addHook('jojo_before_parsepage',           'cookie2session',                  'jojo_affiliate');
Jojo::addHook('jojo_before_parsepage',           'matchAffiliateLink',              'jojo_affiliate');
Jojo::addHook('jojo_before_parsepage',           'matchAffiliateDomain',            'jojo_affiliate');
Jojo::addHook('jojo_cart_success',               'jojo_cart_success',               'jojo_affiliate'); //integrate with the jojo_cart plugin
Jojo::addHook('jojo_cart_extra_fields',          'jojo_cart_extra_fields',          'jojo_affiliate'); //integrate with the jojo_cart plugin
Jojo::addHook('jojo_cart_apply_discount_code',   'apply_discount_code',             'jojo_affiliate');
Jojo::addHook('jojo_cart_transaction_report_th', 'jojo_cart_transaction_report_th', 'jojo_affiliate');
Jojo::addHook('jojo_cart_transaction_report_td', 'jojo_cart_transaction_report_td', 'jojo_affiliate');

Jojo::addFilter('jojo_cart_checkout_fields', 'jojo_cart_checkout_fields', 'jojo_affiliate');
Jojo::addFilter('jojo_cart_checkout:populate_fields', 'jojo_cart_checkout_fields', 'jojo_affiliate');

Jojo::addFilter('email_footer',              'email_footer',              'jojo_affiliate');

$_provides['pluginClasses'] = array(
        'Jojo_Plugin_jojo_affiliate'       => 'Affiliates - affiliate homepage',
        'Jojo_Plugin_jojo_affiliate_admin' => 'Affiliates - administration page'
        );

Jojo::registerURI("affiliates/[code:[a-f0-9]{16}]", 'Jojo_Plugin_jojo_affiliate'); // "affiliates/1234567890123456/"

$_options[] = array(
    'id'          => 'affiliate_payment_minimum',
    'category'    => 'Affiliates',
    'label'       => 'Minimum payment amount',
    'description' => 'Affiliates will receive a payment when their commission earned is above this minimum. Must be in the currency that commissions are paid in.',
    'type'        => 'integer',
    'default'     => '50',
    'options'     => '',
    'plugin'      => 'jojo_affiliate'
);

$_options[] = array(
    'id'          => 'affiliate_payment_currency',
    'category'    => 'Affiliates',
    'label'       => 'Payment currency',
    'description' => 'The currency that is used to pay affiliates their commissions.',
    'type'        => 'text',
    'default'     => 'USD',
    'options'     => '',
    'plugin'      => 'jojo_affiliate'
);

$_options[] = array(
    'id'          => 'affiliate_cookie_expiry',
    'category'    => 'Affiliates',
    'label'       => 'Cookie expiry',
    'description' => 'The number of days the affiliate cookie will last before expiring.',
    'type'        => 'integer',
    'default'     => '90',
    'options'     => '',
    'plugin'      => 'jojo_affiliate'
);

$_options[] = array(
    'id'          => 'affiliate_repeat_sales',
    'category'    => 'Affiliates',
    'label'       => 'Pay commission on repeat sales',
    'description' => 'If yes, then commission is to be paid on all sales while the cookie is active. If not, the cookie expires after the first purchase.',
    'type'        => 'radio',
    'default'     => 'yes',
    'options'     => 'yes,no',
    'plugin'      => 'jojo_affiliate'
);

$_options[] = array(
    'id'          => 'affiliate_override',
    'category'    => 'Affiliates',
    'label'       => 'Override existing cookies',
    'description' => 'If an affiliate cookie exists on a computer, this option prevents it being replaced by another affiliate code. In other words, the first affiliate will get commission paid.',
    'type'        => 'radio',
    'default'     => 'no',
    'options'     => 'yes,no',
    'plugin'      => 'jojo_affiliate'
);

$_options[] = array(
    'id'          => 'affiliate_default_percentage',
    'category'    => 'Affiliates',
    'label'       => 'Default commission percentage',
    'description' => 'The default commission rate (as a percentage) that is paid to affiliates.',
    'type'        => 'integer',
    'default'     => '10',
    'options'     => '',
    'plugin'      => 'jojo_affiliate'
);

$_options[] = array(
    'id'          => 'affiliate_discount_codes',
    'category'    => 'Affiliates',
    'label'       => 'Allow affiliate-created discount codes',
    'description' => 'If yes, affiliates can create their own discount codes. Some of their affiliate commission is given to the customer..',
    'type'        => 'radio',
    'default'     => 'no',
    'options'     => 'yes,no',
    'plugin'      => 'jojo_affiliate'
);