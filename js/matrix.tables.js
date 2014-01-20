
$(document).ready(function(){

	$('.data-table').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"sDom": '<""l>t<"F"fp>'
		
	});
	
	//Add a row number
	$('#employee').dataTable( {
		"bDestroy" : true,
		"fnDrawCallback": function ( oSettings ) {
			/* Need to redo the counters if filtered or sorted */
			if ( oSettings.bSorted || oSettings.bFiltered )
			{

				for ( var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ )
				{
					$('td:eq(0)', oSettings.aoData[ oSettings.aiDisplay[i] ].nTr ).html( i+1 );
				}
				
				var oTable = $('#employee').dataTable();
				var data = oTable._('td:nth-child(9)', {"filter": "applied"});
				var Total = 0;
				
				for ( var i = 0; i<data.length; i++)
				{
					Total += data[i].replace(/<a\b[^>]*>/i,"").replace(/<\/a>/i,"")*1;
				}

				$("#totalSalarios").fadeOut(function() {
				  $(this).text('Previsão: R$ ' + Total.toFixed(2)).fadeIn();
				});
				
				



				
			}
				
				
				
						
		},
		"aoColumnDefs": [
			{ "bSortable": false, "aTargets": [ 0 ] }
		],
		"aaSorting": [[ 1, 'asc' ]],
		"bJQueryUI": true,
		"sDom": "lfrtip",
		"sPaginationType": "full_numbers",
		
		
	} );
	

	
	//Sum columns
	
    
	

					
	$('input[type=checkbox],input[type=radio],input[type=file]').uniform();
	
	$('select').select2();
	
	$("span.icon input:checkbox, th input:checkbox").click(function() {
		var checkedStatus = this.checked;
		var checkbox = $(this).parents('.widget-box').find('tr td:first-child input:checkbox');		
		checkbox.each(function() {
			this.checked = checkedStatus;
			if (checkedStatus == this.checked) {
				$(this).closest('.checker > span').removeClass('checked');
			}
			if (this.checked) {
				$(this).closest('.checker > span').addClass('checked');
			}
		});
	});	
});
