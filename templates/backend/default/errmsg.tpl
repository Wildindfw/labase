<div class="col-xs-12">
	{if $errors}
		<div class="alert alert-error">
			<button class="close" data-dismiss="alert"></button>
			{section name=i loop=$errors}
				{$errors[i]}<br />
			{/section}
		</div>
	{/if}
	{if $warnings}
		<div class="alert">
			<button class="close" data-dismiss="alert"></button>
			{section name=i loop=$warnings}
				{$warnings[i]}<br />
			{/section}
		</div>
	{/if}
	{if $info}
		<div class="alert alert-info">
			<button class="close" data-dismiss="alert"></button>
			{section name=i loop=$info}
				{$info[i]}<br />
			{/section}
		</div>
	{/if}	
	{if $messages}
		<div class="alert alert-success">
			<button class="close" data-dismiss="alert"></button>
			{section name=i loop=$messages}
				{$messages[i]}<br />
			{/section}
		</div>
	{/if}	
</div>