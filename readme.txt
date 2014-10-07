This plugin is under construction.

The plugin will automatically work with the jojo_cart plugins
- with no changes to the code

OR include the following code on the thank you page of your shopping cart - so sales can be credited to affiliates...


/* record affiliate payment */
$vars = array(
              'amount'        => 100.00,
              'transactionid' => 123456,
              'currency'      => 'NZD',
              'items'         => array('item 1', 'item 2', 'item 3')
              );
foreach (Jojo::listPlugins('jojo_affiliate.php') as $pluginfile) {
    require_once($pluginfile);
    break;
}
JOJO_Plugin_Jojo_affiliate::logSale($vars);