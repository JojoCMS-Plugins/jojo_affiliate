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
<table>
  <thead>
    <tr>
      <th>Date</th>
      <th>Order Total</th>
      <th>Currency</th>
      <th>Commission</th>
    </tr>
  </thead>
  <tbody>
      {section name=s loop=$sales}
      <!-- [Sale: {$sales[s].saleid}] -->
      <tr>
        <td>{$sales[s].datetime|date_format}</td>
        <td style="text-align: right">{$sales[s].amount|number_format:'2'}</td>
        <td>{$sales[s].currency}</td>
        <td style="text-align: right">{$sales[s].commission|number_format:'2'}</td>
      </tr>
      {/section}

      {foreach from=$totals key=curr item=total}
      <tr>
        <td style="text-align: right" colspan="3"><strong>Total {$curr} commission:</strong></td>
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
<li><strong>Commissions are paid each month on or around the last day of the month.</strong> - Commissions will be paid out if the amount owing is greater than {$OPTIONS.affiliate_payment_minimum}{$OPTIONS.affiliate_payment_currency}. Any balances less than {$OPTIONS.affiliate_payment_minimum}{$OPTIONS.affiliate_payment_currency} will carry over to the next month until this minimum is reached.</li>
<li>Any <strong>commissions earned in currencies other than {$OPTIONS.affiliate_payment_currency} will be converted to {$OPTIONS.affiliate_payment_currency}</strong> when the commission payment is made. This conversion will be made at the current exchange rate that we receive from our chosen payment provider.</li>
<li><strong>We reserve the right to cancel any affiliate account</strong> at any time - we don't expect to have to use this rule, however we will close any account we believe to be spamming or engaging in any illegal activity.</li>
<li><strong>We will not be held liable for any lost commissions as a result of technical errors</strong> - Servers can fail, affiliate codes can get mixed up, old browsers can handle cookies incorrectly, and technical problems can happen. This is the internet! We will do our very best to keep the affiliate system reliable and accurate.</li>
<li><strong>These terms and conditions are subject to change without notice</strong> - as are commission rates, payout minimums, etc.</li>
</ul>