{include file="admin/header.tpl"}

{if $messages}
<h3>Messages</h3>
<ul>
{section name=m loop=$messages}
<li>{$messages[m]}</li>
{/section}
</ul>
{/if}

<h3>Amounts outstanding</h3>
<form method="post" action="{$ADMIN}/affiliates/">
<table style="width: 500px">
  <thead>
  <tr>
    <th>Affiliate</th>
    <th>Paypal Address</th>
    {foreach from=$currencies key=k item=n}
    <th>{$k}</th>
    {/foreach}
    <th>&nbsp;</th>
  </tr>
  </thead>
  <tbody>
{section name=a loop=$affiliates}
  <tr class="{cycle values="row1,row2"}">
    <td>{$affiliates[a].firstname} {$affiliates[a].lastname} ({$affiliates[a].login})</td>
    <td>{$affiliates[a].paypal}</td>
    {foreach from=$currencies key=k item=n}
    <td style="text-align: right">{if $affiliates[a].$k}{$affiliates[a].$k}{/if}</td>
    
    {/foreach}
    <td><input type="checkbox" name="pay_affiliate[]" value="{$affiliates[a].userid}" /> </td>
  </tr>
{/section}
</tbody>
  <tfoot>
  <tr>
    <td>&nbsp;</td>
    <td style="text-align: right">Totals:</td>
    {foreach from=$currencies key=k item=v}
    <th style="text-align: right">{$v}</th>
    {/foreach}
  </tr>
  </tfoot>
</table>
<input type="submit" name="pay" value="Mark selected affiliates as paid" onclick="return (confirm('This will mark all sales for selected affiliates as being paid. Please ensure the affiliate has been paid the listed amount, and keep your own records relating to this payment as logging within the affiliate system is minimal. Are you sure you wish to continue?'));" />
</form>

Payout minimum: USD${$OPTIONS.affiliate_payment_minimum}<br />

{include file="admin/footer.tpl"}