{strip}
	{if $raceEthnicity}
		<div class="row">
			<div class="result-label col-sm-4">{translate text="Race and Ethnicity"} </div>
			<div class="result-value col-sm-8">
				{implode subject=$raceEthnicity}
			</div>
		</div>
	{/if}
	{if $gender}
		<div class="row">
			<div class="result-label col-sm-4">{translate text="Gender Expression/Identity"} </div>
			<div class="result-value col-sm-8">
				{implode subject=$gender}
			</div>
		</div>
	{/if}
{/strip}