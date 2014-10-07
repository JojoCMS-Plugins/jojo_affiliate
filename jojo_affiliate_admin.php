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

class JOJO_Plugin_Jojo_affiliate_admin extends JOJO_Plugin
{


    function _getContent()
    {
        global $smarty, $_USERID;
        $content = array();

        Jojo_Plugin_Admin::adminMenu();
        
        /* ensure paymentdate field exists */
        if (!Jojo::fieldExists('aff_sale', 'paymentdate')) {
            echo 'You need to run <a href="'._SITEURL.'/setup/">setup</a> before you can access this page.';
            exit;
        }
        
        /* pay button pressed - this marks the sales as having been paid by entering a paymentdate against the sale */
        $pay = Jojo::getFormData('pay', false);
        if ($pay) {
            $messages = array();
            $pay_affiliate = Jojo::getFormData('pay_affiliate', array());
            foreach ($pay_affiliate as $a) {
                Jojo::updateQuery("UPDATE {aff_sale} SET paymentdate=? WHERE userid=? AND paymentid=0 AND paymentdate=0", array(time(), $a));
                $aff = Jojo::selectRow("SELECT us_login, us_firstname, us_lastname FROM {user} WHERE userid=?", $a);
                $messages[] = 'All outstanding commissions for '.$aff['us_login'].' have been marked as paid.';
            }
            $smarty->assign('messages', $messages);
        }

        /* affiliates needing to be paid */
        $minpayout = Jojo::getOption('affiliate_payment_minimum');
        $outstanding = array(); //array of userids, and the total amount oweing

        /* Originally, a sale is marked as paid using the paymentid field. Now, we track by the existence of a paymentdate but need to maintain backwards compatibility. */
        $data = Jojo::selectQuery("SELECT * FROM {aff_sale} WHERE paymentid=0 AND paymentdate=0");
        $n = count($data);
        for ($i=0; $i<$n; $i++) {
            $data[$i]['commission'] = ($data[$i]['commissionfixed'] > 0) ? $data[$i]['commissionfixed'] : ($data[$i]['amount'] * $data[$i]['commissionpercent'] / 100);
            if (!isset($outstanding[$data[$i]['userid']])) $outstanding[$data[$i]['userid']] = array();
            if (!isset($outstanding[$data[$i]['userid']]) || !isset($outstanding[$data[$i]['userid']][$data[$i]['currency']])) $outstanding[$data[$i]['userid']][$data[$i]['currency']] = 0;
            $outstanding[$data[$i]['userid']][$data[$i]['currency']] = $outstanding[$data[$i]['userid']][$data[$i]['currency']] + $data[$i]['commission'];
        }
        arsort($outstanding);
        $n = count($outstanding);
        $affiliates = array();
        $currencies = array();
        foreach ($outstanding as $userid => $owing) {
            $data = Jojo::selectQuery("SELECT userid, us_login, us_firstname, us_lastname, us_paypal FROM {user} WHERE userid=?", $userid);
            $affiliate = array('userid'=>$data[0]['userid'] ,'login'=>$data[0]['us_login'], 'firstname'=>$data[0]['us_firstname'], 'lastname'=>$data[0]['us_lastname'], 'paypal'=>$data[0]['us_paypal']);
            foreach ($owing as $curr => $amount) {
                if (!isset($currencies[$curr])) $currencies[$curr] = 0;
                $affiliate[$curr]   = $amount;
                $currencies[$curr] += $amount;
            }
            $affiliates[] = $affiliate;
        }
        $smarty->assign('affiliates', $affiliates);
        $smarty->assign('currencies', $currencies);
        $content['content'] = $smarty->fetch('jojo_affiliate_admin.tpl');

        return $content;
    }
}