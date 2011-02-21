{include file='errors.tpl'}
<div>
  Affiliate ID: <strong>{$userid}</strong><br />
  {foreach from=$unpaidtotals key=curr item=total}
  Amount earned (since last payment): <strong> {$curr}{if $curr=='USD'}${/if}{$total|number_format:'2'}</strong><br />
  {/foreach}
  {foreach from=$totals key=curr item=total}
  Amount earned (total): <strong> {$curr}{if $curr=='USD'}${/if}{$total|number_format:'2'}</strong><br />
  {/foreach}
  <em>All commissions are converted to {$OPTIONS.affiliate_payment_currency} at the time payment is made</em><br />
  Next payment: <strong>{$nextpayment|date_format}</strong>, providing balance is above ${$OPTIONS.affiliate_payment_minimum}<br />
</div>

<div>
<h3>History</h3>
{if $sales}
<table width="100%" border="1" style="border-collapse:collapse">
  <thead>
    <tr>
      <th>Date</th>
      <th>Order Total</th>
      <th>Currency</th>
      {if $admin_created_discounts || $user_created_discounts}<th>Discount Code</th>{/if}
      <th>Commission</th>
    </tr>
  </thead>
  <tbody>
      {section name=s loop=$sales}
      <!-- [Sale: {$sales[s].saleid}] -->
      <tr>
        <td>{$sales[s].datetime|date_format}</td>
        <td style="text-align: right">{$sales[s].amount|number_format:'2'}</td>
        <td style="text-align: center">{$sales[s].currency}</td>
        {if $admin_created_discounts || $user_created_discounts}<td style="text-align: center">{$sales[s].discountcode}</td>{/if}
        <td style="text-align: right">{$sales[s].commission|number_format:'2'}</td>
      </tr>
      {/section}

      {foreach from=$totals key=curr item=total}
      <tr>
        <td style="text-align: right" colspan="{if $admin_created_discounts || $user_created_discounts}4{else}3{/if}"><strong>Total {$curr} commission:</strong></td>
        <td style="text-align: right"><strong>{$total|number_format:'2'}</strong></td>
      </tr>
      {/foreach}
  </tbody>
</table>
{else}
<p>There are no sales recorded against your account.</p>
{/if}
</div>

<div>
<h3>Websites</h3>
<p>If you plan to promote us via your website, register the website URL here. We will credit you with the sale when the referer information of a visitor matches a registered website. This means that you don't need to include the affiliate ID in the link, which makes the affiliate links look exactly the same as normal links - and are therefore more credible and clickable.</p>

<div id="domains">
  {include file='jojo_affiliate_domain_list.tpl'}
</div>

<div>
  <form method="post" target="frajax-iframe" action="actions/jojo_affiliate_register_domain.php">
    <fieldset>
      <legend>Add domain</legend>
      http://www.<input type="text" name="domain" size="50" value="" /></label> <input type="submit" name="add" value="Add" />
    </fieldset>
  </form>
</div>
</div>

{if $admin_created_discounts || $user_created_discounts}
<div>
    <h3>Discount codes</h3>
    {if  $OPTIONS.affiliate_discount_codes == 'yes'}
    <p>Affiliate discount codes allow you to give away some of your affiliate commission to the customer, making them more likely to use the code. You can choose how much of your commission you give away.</p>
    
    <form method="post" action="">
        <table width="100%" border="1" style="border-collapse:collapse">
            <tr>
                <th>Discount code</th>
                <th>Affiliate %</th>
                <th>Customer %</th>
                <th>Actions</th>
            </tr>
            {foreach from=$user_created_discounts item=d}
            <tr>
                <td><strong>{$d.discountcode}</strong></td>
                <td>{$d.affiliatepercent}%</td>
                <td>{$d.discountpercent}%</td>
                <td></td>
            </tr>
            {/foreach}
            
            <tr>
                <td><input type="text" size="8" id="discount_code" name="discount_code" value="{$discount_code}" /></td>
                <td><input type="text" size="5" id="affiliate_commission" name="affiliate_commission" value="{$affiliate_commission|default:$commission_rate}" />%</td>
                <td><input type="text" size="5" id="customer_commission" name="customer_commission" value="{$customer_commission|default:'0'}" />%</td>
                <td><input type="submit" name="save_discount_code" value="save" /></td>
            </tr>
        </table>
    </form>
    <p>The affiliate percentage and customer percentage must add up to <span id="commission_rate">{$commission_rate}</span>%.</p>
    {/if}
    {if $admin_created_discounts}
    <p>If a customer uses one of the following discount codes, you will be credited for the sale and your affiliate cookie will be set in the customer's browser for up to {$OPTIONS.affiliate_cookie_expiry} days.</p>
    <table width="100%" border="1" style="border-collapse:collapse">
        <tr>
            <th>Discount code</th>
            <th>Discount</th>
            <th>Minimum order value</th>
        </tr>
        {foreach from=$admin_created_discounts item=d}
        {if $d.setaffiliatecookie=='yes'}
        <tr>
            <td><strong>{$d.discountcode}</strong></td>
            <td>{if $d.discountpercent}{$d.discountpercent}%{/if} {if $d.discountfixed}{$OPTIONS.cart_default_currency}{$default_currency_symbol}{$d.discountfixed}{/if}</td>
            <td>{$OPTIONS.cart_default_currency}{$default_currency_symbol}{$d.minorder}</td>
        </tr>
        {/if}
        {/foreach}
    </table>
    <p>Please <a href="mailto:{$OPTIONS.contactaddress}">contact us</a> to arrange additional discount codes if required.</p>
    {/if}
</div>
{/if}

<div>
<h3>Paypal address</h3>
<div>
  <form method="post" target="frajax-iframe" action="actions/jojo_affiliate_save_paypal.php">
    <fieldset>
      <legend>Paypal address</legend>
      <input type="text" name="paypal" size="50" value="{$paypal}" /></label> <input type="submit" name="save" value="Save" />
    </fieldset>
  </form>
</div>
</div>

<h3>Your affiliate code</h3>
<p>Your affiliate code is <strong>a{$userid}</strong> - to link to us with the affiliate code, simply add the affiliate code to the end of any page on this website. Examples...</p>
<ul>
  <li>{$SITEURL}/a{$userid}/</li>
  <li>{$SITEURL}/contact/a{$userid}/</li>
  <li>{$SITEURL}/any/page/you/like/a{$userid}/</li>
</ul>
<p>Note the affiliate ID is not required when linking to us from a registered website (above).</p>
<h4>Text link to homepage</h4>
<p>Copy-paste the following HTML code onto your website or email newsletter to link to us.</p>
<a href="{$SITEURL}/a{$userid}/" target="_BLANK">{$sitetitle}</a><br />
<textarea rows="3" cols="50">&lt;a href="{$SITEURL}/a{$userid}/" target="_BLANK"&gt;{$sitetitle}&lt;/a&gt;</textarea>
{jojoHook hook="jojo_affiliate_example_links"}
<h3>The fine print</h3>
<p>The following information explains how the affiliate system works, and also serves as our affiliate terms and conditions.</p>
<ul>
<li><strong>Commissions will be paid at the default rate of {$OPTIONS.affiliate_default_percentage}%</strong> on all transactions linked to your affiliate ID. We reserve the right to change this percentage at any time. Commissions may vary on a per product basis, as profit margins vary on a per product basis. High-performing affiliates may be offered a more favourable commission rate.</li>
<li><strong>A cookie is stored on the customer's computer</strong> to determine which affiliate link they used to enter this website. The cookie is used after payments are complete to assign commissions to affiliates. If the cookie is unable to be read by our system, then commissions are not able to be assigned. If a customer changes computers before making a purchase, or deletes the cookie from their computer, or has cookies blocked, then this is out of our control, and we can't allocate a commission from these sales.</li>
<li><strong>Cookies are stored on a visitor's computer for up to {$OPTIONS.affiliate_cookie_expiry} days</strong> - unless the cookie is manually removed by the visitor.</li>
{if $OPTIONS.affiliate_repeat_sales=='yes'}
<li><strong>Affiliates will receive commission on repeat sales</strong> while the cookie is active. That is, all sales made within {$OPTIONS.affiliate_cookie_expiry} days of the visitor entering the site.</li>
{else}
<li><strong>Commission is not paid on repeat sales</strong> - that is, the cookie is removed from the visitor's computer after the first successful transaction has been completed.</li>
{/if}
{if $OPTIONS.affiliate_override=='yes'}
<li><strong>An affiliate cookie can be replaced</strong> by another affiliate cookie, if the same visitor enters the site using a different affiliate link.</li>
{else}
<li><strong>An affiliate cookie can not be replaced</strong> by another affiliate cookie, if the same visitor enters the site using a different affiliate link. The original alliliate will get the commission even if the visitor subsequently visits the site using a different affiliate link.</li>
{/if}
<li><strong>Do not engage in spam marketing to promote our products</strong> - If we consider any marketing tactic to be inappropriate or damaging to our brand (such as spam emails containing the affiliate link) then we reserve the right to cancel your account.</li>
<li><strong>Commissions are not payable if a customer is refunded</strong> - if we have to refund a purchase due to lack of stock, a cancelled order or for any other reason, then we will reverse any commissions owing on that transaction. Commissions are only paid on successful transactions.</li>
<li>Commission is not paid on the freight portion of any order.</li>
<li><strong>Commissions are paid each month on or around the last day of the month.</strong> - Commissions will be paid out if the amount owing is greater than {$OPTIONS.affiliate_payment_minimum}{$OPTIONS.affiliate_payment_currency}. Any balances less than {$OPTIONS.affiliate_payment_minimum}{$OPTIONS.affiliate_payment_currency} will carry over to the next month until this minimum is reached.</li>
<li>Any <strong>commissions earned in currencies other than {$OPTIONS.affiliate_payment_currency} will be converted to {$OPTIONS.affiliate_payment_currency}</strong> when the commission payment is made. This conversion will be made at the current exchange rate that we receive from our chosen payment provider.</li>
<li><strong>We reserve the right to cancel any affiliate account</strong> at any time - we don't expect to have to use this rule, however we will close any account we believe to be spamming or engaging in any illegal activity.</li>
<li><strong>We will not be held liable for any lost commissions as a result of technical errors</strong> - Servers can fail, affiliate codes can get mixed up, old browsers can handle cookies incorrectly, and technical problems can happen. This is the internet! We will do our very best to keep the affiliate system reliable and accurate.</li>
<li><strong>These terms and conditions are subject to change without notice</strong> - as are commission rates, payout minimums, etc.</li>
</ul>