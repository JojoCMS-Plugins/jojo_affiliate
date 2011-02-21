<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2011 Jojo CMS
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Harvey Kane <harvey@jojocms.org>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 */




// Discount percentage Field
$default_fd['discount']['affiliatepercent'] = array(
        'fd_name'     => "Affiliate percentage",
        'fd_type'     => "decimal",
        'fd_order'    => "7",
        'fd_units'    => "%",
        'fd_help'     => "",
    );

// Affiliate (User ID) Field
$default_fd['discount']['userid'] = array(
        'fd_name'     => "Affiliate",
        'fd_type'     => "dblist",
        'fd_options'  => "user",
        'fd_required' => "no",
        'fd_order'    => "10",
        'fd_help'     => "",
    );

// Set Affiliate Cookie Field
$default_fd['discount']['setaffiliatecookie'] = array(
        'fd_name'     => "Set Affiliate Cookie",
        'fd_type'     => "list",
        'fd_options'  => "yes\nno",
        'fd_default'  => "no",
        'fd_order'    => "11",
        'fd_units'    => "",
        'fd_help'     => "",
    );