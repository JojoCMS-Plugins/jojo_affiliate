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
$frajax->title = 'Add new affiliate domain - ' . _SITETITLE;
$frajax->sendHeader();

$affiliateid = !empty($_USERID) ? $_USERID : 0;

if (!$affiliateid) {
    $frajax->alert('You do not appear to be logged in, which is required by this action');
    $frajax->sendFooter();
    exit();
}

$domain = Util::getFormData('domain', '');

/* error checking */
$errors = array();
if (count(explode('.', $domain)) < 2) $errors[] = 'Please enter a valid domain name';
$data = Jojo::selectQuery("SELECT * FROM {aff_domain} WHERE domain=?", $domain);
if (count($data)) $errors[] = 'This website is already registered in the system. If you feel this website should be registered to your account, please contact us.';

if (count($errors)) {
    $frajax->alert(implode("\n", $errors));
    $frajax->sendFooter();
    exit;
}

/* create unique approve / decline codes for the admin email */
$query = 'SELECT * FROM {aff_domain} WHERE `approvecode` = ? OR `declinecode` = ?';
do {
    $approvecode = Jojo::randomString(16, '0123456789');
    $res = Jojo::selectQuery($query, array($approvecode, $approvecode));
} while (count($res) > 0);
$declinecode = ''; //todo: implement the declinecode feature

/* add the domain to the system */
Jojo::insertQuery("INSERT INTO {aff_domain} SET `userid`=?, `domain`=?, `approved`='no', approvecode=?, declinecode=?", array($affiliateid, $domain, $approvecode, $declinecode));
$domains = Jojo::selectQuery("SELECT * FROM {aff_domain} WHERE `userid`=?", array($_USERID));
$smarty->assign('domains', $domains);
$frajax->assign('domains', 'innerHTML', $smarty->fetch('jojo_affiliate_domain_list.tpl'));

/* get user data for email */
$data = Jojo::selectQuery("SELECT us_login, us_firstname, us_lastname, us_email FROM {user} WHERE userid=?", $_USERID);
$affiliate = $data[0];

/* email admins with a link for approving / declining the domain */
$message  = "An affiliate domain has been added on " . _SITEURL . "\n\n";
$message .= "Affiliate: " . $affiliate['us_firstname'].' '.$affiliate['us_lastname']. ' ('.$affiliate['us_login'].")\n";
$message .= "Email: " . $affiliate['us_email']. "\n";
$message .= "Domain added: " . $domain . "\n\n";

$message .= "To approve this domain, click the following link\n";
$message .= _SITEURL . '/affiliates/' . $approvecode . "/\n\n";
//$message .= "To decline this domain, click the following link\n";
//$message .= _SITEURL . '/affiliates/' . $deletecode . "/\n";
$message .= Jojo::emailFooter();

/* Email notification to webmaster */
Jojo::simplemail(_WEBMASTERNAME, _WEBMASTERADDRESS, 'New affiliate domain needing approval - '.$domain, $message);

$frajax->sendFooter();
