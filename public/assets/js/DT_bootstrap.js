$(document).ready(function(){
  $.extend( $.fn.dataTableExt.oStdClasses, {
  	"sWrapper": "dataTables_wrapper form-inline"
  } );
  
  $.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings )
  {
  	return {
  		"iStart":         oSettings._iDisplayStart,
  		"iEnd":           oSettings.fnDisplayEnd(),
  		"iLength":        oSettings._iDisplayLength,
  		"iTotal":         oSettings.fnRecordsTotal(),
  		"iFilteredTotal": oSettings.fnRecordsDisplay(),
  		"iPage":          Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
  		"iTotalPages":    Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
  	};
  }
  
  $.extend( $.fn.dataTableExt.oPagination, {
  	"bootstrap": {
  		"fnInit": function( oSettings, nPaging, fnDraw ) {
  			var oLang = oSettings.oLanguage.oPaginate;
  			var fnClickHandler = function ( e ) {
  				e.preventDefault();
  				if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
  					fnDraw( oSettings );
  				}
  			};

  			$(nPaging).addClass('pagination').append(
  				'<ul>'+
  					'<li class="prev disabled"><a href="#">&larr; '+oLang.sPrevious+'</a></li>'+
  					'<li class="next disabled"><a href="#">'+oLang.sNext+' &rarr; </a></li>'+
  				'</ul>'
  			);
  			var els = $('a', nPaging);
  			$(els[0]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
  			$(els[1]).bind( 'click.DT', { action: "next" }, fnClickHandler );
  		},

  		"fnUpdate": function ( oSettings, fnDraw ) {
  			var iListLength = 5;
  			var oPaging = oSettings.oInstance.fnPagingInfo();
  			var an = oSettings.aanFeatures.p;
  			var i, j, sClass, iStart, iEnd, iHalf=Math.floor(iListLength/2);

  			if ( oPaging.iTotalPages < iListLength) {
  				iStart = 1;
  				iEnd = oPaging.iTotalPages;
  			}
  			else if ( oPaging.iPage <= iHalf ) {
  				iStart = 1;
  				iEnd = iListLength;
  			} else if ( oPaging.iPage >= (oPaging.iTotalPages-iHalf) ) {
  				iStart = oPaging.iTotalPages - iListLength + 1;
  				iEnd = oPaging.iTotalPages;
  			} else {
  				iStart = oPaging.iPage - iHalf + 1;
  				iEnd = iStart + iListLength - 1;
  			}

  			for ( i=0, iLen=an.length ; i<iLen ; i++ ) {
  				// Remove the middle elements
  				$('li:gt(0)', an[i]).filter(':not(:last)').remove();

  				// Add the new list items and their event handlers
  				for ( j=iStart ; j<=iEnd ; j++ ) {
  					sClass = (j==oPaging.iPage+1) ? 'class="active"' : '';
  					$('<li '+sClass+'><a href="#">'+j+'</a></li>')
  						.insertBefore( $('li:last', an[i])[0] )
  						.bind('click', function (e) {
  							e.preventDefault();
  							oSettings._iDisplayStart = (parseInt($('a', this).text(),10)-1) * oPaging.iLength;
  							fnDraw( oSettings );
  						} );
  				}

  				// Add / remove disabled classes from the static elements
  				if ( oPaging.iPage === 0 ) {
  					$('li:first', an[i]).addClass('disabled');
  				} else {
  					$('li:first', an[i]).removeClass('disabled');
  				}

  				if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
  					$('li:last', an[i]).addClass('disabled');
  				} else {
  					$('li:last', an[i]).removeClass('disabled');
  				}
  			}
  		}
  	}
  } );

  $.fn.dataTableExt.aTypes.unshift(
    function ( sData ) {
      if (sData !== null && sData.match(/^(0[1-9]|[12][0-9]|3[01])[\/-](0[1-9]|1[012])[\/-](19|20|21)\d\d ([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$/)) {
        return 'fr_datetime';
      }
      return null;
    }
  );

  $.fn.dataTableExt.oSort['fr_datetime-asc']  = function(a,b) {
    var DHa = a.split(" ");
    var DHb = b.split(" ");
    var Datea = a[0].split(/[\/-]/);
    var Dateb = b[0].split(/[\/-]/);
    var Heurea = a[1].split(':');
    var Heureb = b[1].split(':');
    var x = (Datea[2] + Datea[1] + Datea[0] + Heurea[0] + Heurea[1] + Heurea[2]) * 1;
    var y = (Dateb[2] + Dateb[1] + Dateb[0] + Heureb[0] + Heureb[1] + Heureb[2]) * 1;
    if (isNaN(x)) { x = 0; }
    if (isNaN(y)) { y = 0; }
    return ((x < y) ? -1 : ((x > y) ?  1 : 0));
  };

  $.fn.dataTableExt.oSort['fr_datetime-desc'] = function(a,b) {
    var DHa = a.split(" ");
    var DHb = b.split(" ");
    var Datea = a[0].split(/[\/-]/);
    var Dateb = b[0].split(/[\/-]/);
    var Heurea = a[1].split(':');
    var Heureb = b[1].split(':');
    var x = (Datea[2] + Datea[1] + Datea[0] + Heurea[0] + Heurea[1] + Heurea[2]) * 1;
    var y = (Dateb[2] + Dateb[1] + Dateb[0] + Heureb[0] + Heureb[1] + Heureb[2]) * 1;
    if (isNaN(x)) { x = 0; }
    if (isNaN(y)) { y = 0; }
    return ((x < y) ? 1 : ((x > y) ?  -1 : 0));
  };

  $('.dt-table').dataTable(
    {
      "sPaginationType": "bootstrap",
      "oLanguage": {
  			"sLengthMenu": "Afficher _MENU_ enregistrements par page",
  			"sSearch": "Recherche :",
        "sZeroRecords": "Aucun résultat trouvé",
        "sInfo": "Affichage des enregistrements _START_ à _END_ parmi _TOTAL_",
        "sInfoEmpty": "Affichage des enregistrements 0 à 0 parmi 0",
        "sInfoFiltered": "(filtré parmi _MAX_ enregistrements)",
        "oPaginate": {
            "sFirst": "Premier",
            "sPrevious": "",
            "sNext": "",
            "sLast": "Dernier"
        }
  		}
  	});
});
