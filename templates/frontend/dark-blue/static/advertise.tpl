<div class="container">
	<h2>{t c='footer.ADVERTISE'}</h2>
  
  	<div class="well ad-body">
		{insert name=adv assign=adv group='static_ads'}
		{if $adv}{$adv}{/if}
	</div>
</div>