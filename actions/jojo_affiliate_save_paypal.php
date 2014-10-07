<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2008 Harvey Kane <code@ragepank.com>
 * Copyright 2008 Michael Holt <code@gardyneholt.co.nz>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Harvey Kane <code@ragepank.com>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 */

$frajax = new frajax();
$frajax->title = 'Edit Paypal address - ' . _SITETITLE;
$frajax->sendHeader();

$affiliateid = !empty($_USERID) ? $_USERID : 0;

if (!$affiliateid) {
    $frajax->alert('You do not appear to be logged in, which is required by this action');
    $frajax->sendFooter();
    exit();
}

$paypal = Util::getFormData('paypal', '');

$errors = array();
if (empty($paypal)) $errors[] = 'Please enter a Paypal address';
if (!empty($paypal) && !Jojo::checkEmailFormat($paypal)) $errors[] = 'Please enter a valid Paypal address';

if (count($errors)) {
    $frajax->alert(implode("\n", $errors));
    $frajax->sendFooter();
    exit;
}

Jojo::updateQuery("UPDATE {user} SET us_paypal=? WHERE userid=?", array($paypal, $affiliateid));
$frajax->alert('Paypal address updated');

$frajax->sendFooter();