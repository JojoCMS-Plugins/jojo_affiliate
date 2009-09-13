{if $domains}
  <ul>
    {section name=d loop=$domains}
    <li{if $domains[d].approved=='no'} class="aff-pending"{/if}>{$domains[d].domain}{if $domains[d].approved=='no'} (awaiting approval){/if}</li>
    {/section}
  </ul>
  {else}
  You have not registered any websites in this system.
{/if}