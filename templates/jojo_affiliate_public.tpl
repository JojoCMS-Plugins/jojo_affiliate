{$pg_body}
<div>
<h3>Get started</h3>
<ul>
<li>New Affiliates - <a href="register/affiliates/" rel="nofollow">register</a></li>
<li>Existing Affiliates - <a href="login/affiliates/" rel="nofollow">login</a></li>
</ul>
</div>

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