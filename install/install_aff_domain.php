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

$table = 'aff_domain';
$query = "
     CREATE TABLE {aff_domain} (
      `domainid` int(11) NOT NULL auto_increment,
      `userid` int(11) NOT NULL,
      `domain` varchar(255) NOT NULL,
      `approved` ENUM('yes','no') NOT NULL DEFAULT 'no',
      `approvecode` VARCHAR(16) NOT NULL,
      `declinecode` VARCHAR(16) NOT NULL,
      PRIMARY KEY  (`domainid`)
      ) ENGINE = InnoDB ";

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