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

class JOJO_Plugin_Jojo_affiliate extends JOJO_Plugin
{
    /* adds "Referred by affiliate: Affiliate Name" to all email enquiries */
    function email_footer($footer)
    {
        $affiliateid = JOJO_Plugin_Jojo_affiliate::getAffiliateCode();
        if ($affiliateid) {
            /* get affiliate name */
            $user = Jojo::selectRow("SELECT us_firstname, us_lastname, us_login FROM {user} WHERE userid=?", $affiliateid);
            if (!empty($user['us_login'])) {
                $footer .= 'Referred by affiliate: '.$user['us_firstname'].' '.$user['us_lastname'].' ('.$user['us_login'].')';
            }
        }
        return $footer;
    }

    function jojo_cart_admin_email_bottom()
    {
        global $smarty;
        return $smarty->fetch('jojo_affiliate_jojo_cart_admin_email_bottom.tpl');
    }

    function jojo_cart_success($cart=false)
    {
        /* record affiliate payment */
        $vars = array(
                      'amount'        => $cart->order['amount'],
                      'transactionid' => $cart->token,
                      'currency'      => $cart->order['currency'],
                      'items'         => $cart->items,
                      'affiliateid'   => JOJO_Plugin_Jojo_affiliate::parseReferralString($cart->fields['ReferralCode'])
                      );

        JOJO_Plugin_Jojo_affiliate::logSale($vars);
    }

    /* adds a referral code box to the checkout form */
    function jojo_cart_extra_fields()
    {
        global $smarty;
        return $smarty->fetch('jojo_affiliate_jojo_cart_extra_fields.tpl');
    }

    /* saves referral field to cart when checkout button is pressed */
    function jojo_cart_checkout_fields($fields)
    {
        $referralcode = Jojo::getFormData('ReferralCode', false);

        /* if referral code has not been specified, check their cookies */
        if (!$referralcode) {
            $referralcode = JOJO_Plugin_Jojo_affiliate::getAffiliateCode();
        }

        if ($referralcode) $fields['ReferralCode'] = $referralcode;


        $userid = JOJO_Plugin_Jojo_affiliate::parseReferralString($referralcode);

        /* set affiliate cookie, without overwriting any existing cookies */
        if ($userid) {
            JOJO_Plugin_Jojo_affiliate::setAffiliateCode($userid, false);
        }

        return $fields;
    }

    function logSale($vars)
    {
        /* make sure an affiliate cookie has been set, or an affiliate ID was passed with the transaction vars */
        if (!empty($vars['affiliateid'])) {
            $affiliateid = $vars['affiliateid'];
        } elseif (!empty($_SESSION['aff'])) {
            $affiliateid = $_SESSION['aff'];
        } elseif (!empty($_COOKIE['aff'])) {
            $affiliateid = $_COOKIE['aff'];
        } else {
            return false;
        }

        /* ensure affiliate ID matches a valid user account */
        $users = Jojo::selectQuery("SELECT * FROM {user} WHERE userid = ?", $affiliateid);
        if (!count($users)) return false;
        $affiliate = $users[0];
        $affiliate['us_affcommission'] = $affiliate['us_affcommission'] * 1;

        /* get sale details */
        $commrate      = (!empty($affiliate['us_affcommission'])) ? $affiliate['us_affcommission'] : Jojo::getOption('affiliate_default_percentage', 10);
        $amount        = $vars['amount'];
        $currency      = Jojo::either($vars['currency'], Jojo::getOption('affiliate_payment_currency'), 'USD');
        $transactionid = Jojo::either($vars['transactionid'], 0);
        $commamount    = $amount * $commrate / 100;

        Jojo::insertQuery("INSERT INTO {aff_sale} SET userid=?, transactionid=?, amount=?, commissionpercent=?, commissionfixed=?, currency=?, datetime=?",
        array(
             $affiliateid,
             $transactionid,
             $amount,
             $commrate,
             0,
             $currency,
             time()
             )
        );

        /* notify affiliate of sale via email */
        $subject = 'Affiliate sale on '._SITETITLE;
        $message = "Hi ".$affiliate['us_firstname'].",\n\nA sale has been recorded on your affiliate account at "._SITETITLE.".\n\nThis sale has earned you $currency".JOJO_Plugin_Jojo_affiliate::getCurrencySymbol($currency).number_format($commamount, 2, '.', ',')." in commission, so thanks for your hard work promoting our products.\nYou can login to your affiliate account at "._SITEURL."/affiliates/ anytime to check your balance, and manage your account. We pay out affiliates at the end of each month when the commission earned is above ".Jojo::getOption('affiliate_payment_currency')."\$".Jojo::getOption('affiliate_payment_minimum').".\n\nThanks again,\n\nThe team at "._SITETITLE;
        Jojo::simpleMail($affiliate['us_firstname'].' '.$affiliate['us_lastname'], $affiliate['us_email'], $subject, $message);
    }

    /* sessions are available between http / https whereas cookies aren't always shared. So affiliateid is best kept in the session */
    function cookie2session()
    {
        if (isset($_COOKIE['aff'])) $_SESSION['aff'] = $_COOKIE['aff'];
    }

    function matchAffiliateLink()
    {
        /* match the affiliate by affiliate URL */
        preg_match_all('%^(.*?/)?a([0-9]+)/?$%i', _FULLSITEURI, $result, PREG_PATTERN_ORDER);
        if (!$result[0]) return false;

        $url         = $result[1][0];
        $affiliateid = $result[2][0];

        /* a visitor has entered the site using an affiliate link */
        JOJO_Plugin_Jojo_affiliate::setAffiliateCode($affiliateid);

        Jojo::redirect(_SITEURL.'/'.$url);
    }

    function matchAffiliateDomain()
    {
        /* match the affiliate by referer */
        $r = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        if (empty($r)) return false;
        if (strpos($r, _SITEURL)) return false;
        if (strpos($r, _SECUREURL)) return false;

        $q = "SELECT `userid`, `domain` FROM {aff_domain} WHERE approved='yes' AND (0";
        $values = array();
        $pieces = explode('.', parse_url($r, PHP_URL_HOST));
        while (count($pieces) > 1) {
            $values[] = implode('.', $pieces);
            array_shift($pieces);
        }
        foreach ($values as $domain) $q .= ' OR `domain`=?';
        $q .= ')';

        $domains = Jojo::selectQuery($q, $values);
        if (count($domains)) {
            JOJO_Plugin_Jojo_affiliate::setAffiliateCode($domains[0]['userid']);
        }
        return false;
    }

    /* attempts to find an affiliate id based on email, referral code, paypal address or ID */
    function parseReferralString($string)
    {
        /* cache output of this function */
        static $_cache;
        if (!isset($_cache)) $_cache = array();
        if (isset($_cache[$string])) return $_cache[$string];

        /* remove whitespace */
        $string = trim($string);

        /* empty strings won't get you anywhere */
        if (empty($string)) return false;

        /* check for 'a1234', being the code used in affiliate links */
        preg_match_all('/^a([\\d]+)$/i', $string, $result, PREG_PATTERN_ORDER);
        if (!empty($result[1][0])) {
            /* verify user exists */
            $user = Jojo::selectRow("SELECT userid FROM {user} WHERE userid=?", $result[1][0]);
            if (!empty($user['userid'])) {
                $_cache[$string] = $user['userid'];
                return $user['userid'];
            }
        }

        /* check for '1234', being the user id / affiliate id */
        preg_match_all('/^([\\d]+)$/i', $string, $result, PREG_PATTERN_ORDER);
        if (!empty($result[1][0])) {
            /* verify user exists */
            $user = Jojo::selectRow("SELECT userid FROM {user} WHERE userid=?", $result[1][0]);
            if (!empty($user['userid'])) {
                $_cache[$string] = $user['userid'];
                return $user['userid'];
            }
        }

        /* check for 'user@domain.com' - the affiliate's email address or PayPal address*/
        if (Jojo::checkEmailFormat($string)) {
             /* verify user exists */
            $user = Jojo::selectRow("SELECT userid FROM {user} WHERE us_email=? OR us_paypal=?", array($string, $string));
            if (!empty($user['userid'])) {
                $_cache[$string] = $user['userid'];
                return $user['userid'];
            }
        }

        /* check for referral code - any other string*/
        $user = Jojo::selectRow("SELECT userid FROM {user} WHERE us_referralcode=?", array($string));
        if (!empty($user['userid'])) {
            $_cache[$string] = $user['userid'];
            return $user['userid'];
        }

        /* check string against login / username */
        $user = Jojo::selectRow("SELECT userid FROM {user} WHERE us_login=?", array($string));
        if (!empty($user['userid'])) {
            $_cache[$string] = $user['userid'];
            return $user['userid'];
        }

        $_cache[$string] = false;
        return false;
    }

    /* Attempts to set an affiliate code in a cookie. Will not override existing affiliate cookie unless $allowoverride is true */
    function setAffiliateCode($affiliateid, $allowoverride=false)
    {
        if (!$affiliateid) return false;
        $existingcode = isset($_COOKIE['aff']) ? $_COOKIE['aff'] : 0;
        if ($existingcode && !$allowoverride) return false;
        $days = Jojo::getOption('affiliate_cookie_expiry', 90);
        setcookie('aff', $affiliateid, time() + (60 * 60 * 24 * $days), '/' . _SITEFOLDER);
        $_SESSION['aff'] = $affiliateid;
        return true;
    }

    /* gets the affiliate ID from the session / cookie */
    function getAffiliateCode()
    {
        if (!empty($_SESSION['aff'])) return $_SESSION['aff'];
        if (!empty($_COOKIE['aff']))  return $_COOKIE['aff'];
        return false;
    }

    /* gets the next date that affiliates will get paid - currently only the last day of the month */
    function getNextPaymentDate()
    {
        return strtotime(date('t M Y'));
    }

    function _getContent()
    {
        global $smarty, $_USERID;
        $content = array();

        $code = Util::getFormData('code', false);

        /* if a code has been set, approve or decline */
        if ($code) {
            /* approve domains */
            $domains = Jojo::selectQuery("SELECT * FROM {aff_domain} WHERE approvecode=?", $code);
            if (count($domains)) {
                Jojo::updateQuery("UPDATE {aff_domain} SET approved='yes' WHERE domainid=?", $domains[0]['domainid']);
                echo 'Domain approved: '.$domains[0]['domain'];
                /* todo: email the affiliate */
                exit;
            }
            /* decline domains */
            $domains = Jojo::selectQuery("SELECT * FROM {aff_domain} WHERE declinecode=?", $code);
            if (count($domains)) {
                Jojo::updateQuery("UPDATE {aff_domain} SET approved='no' WHERE domainid=?", $domains[0]['domainid']);
                echo 'Domain declined: '.$domains[0]['domain'];
                /* todo: email the affiliate */
                exit;
            }
            /* invalid code */
            echo 'Invalid code - unable to match an affiliate domain';
            exit;
        }

        /* cater for non-logged-in users */
        if (empty($_USERID)) {
            $content['content'] = $smarty->fetch('jojo_affiliate_public.tpl');
            return $content;
        }

        /* all sales recorded by this affiliate */
        $sales = Jojo::selectQuery("SELECT * FROM {aff_sale} WHERE userid=?", $_USERID);
        $n = count($sales);
        for ($i=0; $i<$n; $i++) {
            $sales[$i]['commission'] = ($sales[$i]['commissionfixed'] > 0) ? $sales[$i]['commissionfixed'] : ($sales[$i]['commissionpercent'] * $sales[$i]['amount'] / 100);
            $totals[$sales[$i]['currency']] += $sales[$i]['commission'];
        }
        $smarty->assign('sales',  $sales);
        $smarty->assign('totals', $totals);

        /* unpaidsales - sales since the last payment was made */
        $unpaidsales = Jojo::selectQuery("SELECT * FROM {aff_sale} WHERE paymentid=0 AND userid=?", $_USERID);
        $n = count($unpaidsales);
        for ($i=0;$i<$n;$i++) {
            $unpaidsales[$i]['commission'] = ($unpaidsales[$i]['commissionfixed']>0) ? $unpaidsales[$i]['commissionfixed'] : ($unpaidsales[$i]['commissionpercent'] * $unpaidsales[$i]['amount'] / 100);
            $unpaidtotals[$unpaidsales[$i]['currency']] += $unpaidsales[$i]['commission'];
        }
        $smarty->assign('unpaidsales',  $unpaidsales);
        $smarty->assign('unpaidtotals', $unpaidtotals);

        /* get info on domains registered to this affiliate */
        $domains = Jojo::selectQuery("SELECT * FROM {aff_domain} WHERE userid=?", $_USERID);
        $smarty->assign('domains', $domains);

        /* get paypal address */
        $user = Jojo::selectQuery("SELECT us_paypal FROM {user} WHERE userid=?", $_USERID);
        $smarty->assign('paypal', $user[0]['us_paypal']);

        /* calculate the next payment date */
        $smarty->assign('nextpayment', JOJO_Plugin_Jojo_affiliate::getNextPaymentDate());

        $smarty->assign('userid', $_USERID);
        $content['content'] = $smarty->fetch('jojo_affiliate.tpl');

        return $content;
    }

    /* returns a $ for USD, NZD, AUD etc */
    /* todo - add more currencies, check for character set issues */
    function getCurrencySymbol($currency)
    {
        $currencies = array(
                            'USD' => '$',
                            'NZD' => '$',
                            'AUD' => '$',
                            'CAD' => '$',
                            'GBP' => '£',
                            'EUR' => '€',
                            'JPY' => '¥',
                           );
        if (isset($currencies[$currency])) return $currencies[$currency];
        return '';
    }
    function getCorrectUrl()
    {
        //Assume the URL is correct
        return _PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }

}