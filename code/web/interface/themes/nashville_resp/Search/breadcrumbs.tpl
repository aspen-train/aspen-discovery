{if $searchId}
	<li>{translate text="Search"}: {$lookfor|capitalize|escape:"html"} <span class="divider">&raquo;</span></li>
{elseif $pageTitleShort}
	<li>{$pageTitleShort} <span class="divider">&raquo;</span></li>
{/if}

{* Moved result-head info here from list.tpl - JE 6/18/15 *}
    {if !empty($recordCount)}
		{if $displayMode == 'covers'}
			There are {$recordCount|number_format} total results.
		{else}
			{translate text="Showing"}
			{$recordStart} - {$recordEnd}
			{translate text='of'} {$recordCount|number_format}
		{/if}
	{/if}
