<div>
	<table width="100%" align="center" cellpadding="3" cellspacing="2" style="border:#7D8C8E solid 1px;height: 23px;" class="table_main">
		<tbody id="adtbody">			
		</tbody>
	</table>
	<div id="adjson" style="display: none;">
		{insert name=adv assign=advtxt group='common_text'}
			{if $advtxt}{$advtxt}{/if}
	</div>
</div>
{literal}
<style type="text/css">
	.table_main{
		border-collapse: inherit;
		border-spacing: auto;
		background: #000000;
	}
	.table_main td {
		border: #7D8C8E solid 1px;
		height: 23px;
		text-align: center;
	}
	
	.table_main a:hover {
		color: yellow;
	}
	
	.table_main a {
		color: green
	}
</style>
<script>
	
	$(function(){
		
		var tt = $("#adjson").text();		
		tt = tt.replace(/\s*/g,"");
		if(tt.length > 10){
			try{
				var ads = $.parseJSON(tt);		
				var result = [],group = Math.ceil(ads.length/4);		
				for (var i = 0; i < ads.length; i+=4) {
					result.push(ads.slice(i,i+4));
				}		
				var tr = "",td = "";		
				for (var i = 0; i < result.length; i++) {
					tr += "<tr>";
					td = "";
					for (var j = 0; j < result[i].length; j++) {
						td += `<td width="25%">
							<a href="${result[i][j].link}" rel="nofollow" target="_blank">
								<font color="${i==0 ? 'red':''}">${result[i][j].name}</font>
							</a>
						</td>`;
					}
					tr += td + "</tr>";			
				}
				$("#adtbody").html(tr);
			}catch(e){
				$("#adtbody").parent().remove();
			}			
		}else{
			$("#adtbody").parent().remove();
		}
		
	});
</script>
{/literal}