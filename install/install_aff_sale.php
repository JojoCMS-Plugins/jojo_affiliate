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

$table = 'aff_sale';

/* ensure transactionid field is a varchar (too important to wait for user to upgrade manually) */
if (Jojo::tableExists('aff_sale')) {
    Jojo::structureQuery("ALTER TABLE {aff_sale} CHANGE `transactionid` `transactionid` VARCHAR( 255 ) NOT NULL  ");
}

$query = "
    CREATE TABLE {aff_sale} (
      `saleid` int(11) NOT NULL auto_increment,
      `userid` int(11) NOT NULL default '0',
      `transactionid` varchar(255) NOT NULL default '',
      `amount` decimal(10,2) NOT NULL default '0.00',
      `commissionpercent` decimal(10,2) NOT NULL default '0.00',
      `commissionfixed` decimal(10,2) NOT NULL default '0.00',
      `currency` VARCHAR( 255 ) NOT NULL default 'USD',
      `discountcode` VARCHAR( 255 ) NOT NULL default '',
      `paymentid` int(11) NOT NULL default '0',
      `paymentdate` int(11) NOT NULL default '0',
      `datetime` int(11) NOT NULL default '0',
      PRIMARY KEY  (`saleid`)
    ) TYPE=InnoDB ;";

/* Check table structure */
$result = Jojo::checkTable($table, $query);

/* Output result */
if (isset($result['created'])) {
    echo sprintf("jojo_affiliate: Table <b>%s</b> Does not exist - created empty table.<br />", $table);
}

if (isset($result['added'])) {
    foreach ($result['added'] as $col => $v) {
        echo sprintf("jojo_affiliate: Table <b>%s</b> column <b>%s</b> Does not exist - added.<br />", $table, $col);
    }
}

if (isset($result['different'])) Jojo::printTableDifference($table, $result['different']);