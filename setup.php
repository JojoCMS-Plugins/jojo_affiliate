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

/* affiliates page */
Jojo::updateQuery("UPDATE {page} SET pg_link='Jojo_Plugin_jojo_affiliate' WHERE pg_link='jojo_affiliate.php'");
$data = Jojo::selectQuery("SELECT * FROM {page} WHERE pg_link='Jojo_Plugin_jojo_affiliate'");
if (!count($data)) {
    echo "Adding <b>Affiliate</b> Page to menu<br />";
    Jojo::insertQuery("INSERT INTO {page} SET pg_title='Affiliates', pg_link='Jojo_Plugin_jojo_affiliate', pg_url='affiliates'");
}

/* affiliate admin page */
$data = Jojo::selectQuery("SELECT * FROM {page} WHERE pg_link='Jojo_Plugin_jojo_affiliate_admin'");
if (!count($data)) {
    echo "Adding <b>Affiliate Admin</b> Page to menu<br />";
    Jojo::insertQuery("INSERT INTO {page} SET pg_title='Affiliate Admin', pg_link='Jojo_Plugin_jojo_affiliate_admin', pg_url='admin/affiliates', pg_parent=?, pg_order=12, pg_sitemapnav='no', pg_xmlsitemapnav='no', pg_index='no', pg_followto='no'", $_ADMIN_REPORTS_ID);
}

/* add extra fields to user table */
if (Jojo::tableExists('user')) {
    /* add field for storing Paypal address */
    if (!Jojo::fieldexists('user', 'us_paypal')) {
        echo "Add <b>us_paypal</b> to <b>user</b><br />";
        Jojo::structureQuery("ALTER TABLE {user} ADD `us_paypal` VARCHAR(255) NOT NULL;");
    }

    /* add field for referral code */
    if (!Jojo::fieldexists('user', 'us_referralcode')) {
        echo "Add <b>us_referralcode</b> to <b>user</b><br />";
        Jojo::structureQuery("ALTER TABLE {user} ADD `us_referralcode` VARCHAR(255) NOT NULL;");
    }

    /* add field for custom affiliate commission */
    if (!Jojo::fieldexists('user', 'us_affcommission')) {
        echo "Add <b>us_affcommission</b> to <b>user</b><br />";
        Jojo::structureQuery("ALTER TABLE {user} ADD `us_affcommission` DECIMAL(10,2) NOT NULL;");
    }
}

/* add extra fields to discount table */
if (Jojo::tableExists('discount')) {
    /* add field for affiliate (User ID) */
    if (!Jojo::fieldexists('discount', 'userid')) {
        echo "Add <b>userid</b> to <b>discount</b><br />";
        Jojo::structureQuery("ALTER TABLE {discount} ADD `userid` INT(11) NOT NULL DEFAULT 0;");
    }
    /* add field for affiliate percentage */
    if (!Jojo::fieldexists('discount', 'affiliatepercent')) {
        echo "Add <b>affiliatepercent</b> to <b>discount</b><br />";
        Jojo::structureQuery("ALTER TABLE {discount} ADD `affiliatepercent` decimal(10,0) NOT NULL DEFAULT 0.0;");
    }
    /* add field for set affiliate cookie */
    if (!Jojo::fieldexists('discount', 'setaffiliatecookie')) {
        echo "Add <b>setaffiliatecookie</b> to <b>discount</b><br />";
        Jojo::structureQuery("ALTER TABLE {discount} ADD `setaffiliatecookie` ENUM('yes','no') NOT NULL DEFAULT 'no';");
    }
}