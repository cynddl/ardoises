function render()
{
	document.getElementById('bgCanvas').patternizer({
		stripes : [ 
		    {
		        color: '#3ab0d6',
		        rotation: 48,
		        opacity: 40,
		        mode: 'normal',
		        width: 50,
		        gap: 26,
		        offset: 0
		    },
		    {
		        color: '#9fff28',
		        rotation: 45,
		        opacity: 80,
		        mode: 'normal',
		        width: 30,
		        gap: 10,
		        offset: 0
		    }
		],
		bg : '#ffffff'
	})
};

function onResize() {
    // number of pixels of extra canvas drawn
    var buffer = 100;
    // if extra canvas size is less than the buffer amount
    if (bgCanvas.width - window.innerWidth < buffer ||
        bgCanvas.height - window.innerHeight < buffer) {
        
        // resize the canvas to window plus double the buffer
        bgCanvas.width = window.innerWidth + (buffer * 2);
    		bgCanvas.height = window.innerHeight + (buffer * 2);
    	
    	render();
    }
}

function renewHeight() {
	$('.container.full').css('min-height',$('section').outerHeight()+80); // avec marges héhé :)	
}

function next_input() {
  var next_input = $(":input")[$(":input").index(this)+1];
  next_input.focus();
}


$(document).ready(function(){
  onResize();
  
  $('select').focus();
  window.scrollTo(0,0);
  
  $('select').change(next_input);
  
  $('#documentation').popover();


	$(':input[type="number"]:last').live('keydown', function(e) { 
    var keyCode = e.keyCode || e.which;

    if (keyCode == 9 && !e.shiftKey) { 
      e.preventDefault(); 
      var row = $(this).parent();
      var actual_select = row.find('select');
      var new_row = row.clone();
			
      
      var new_select = new_row.find('select');
      new_select.change(next_input);
      
      if(actual_select.val() == 'miam') {
	      new_select.val('soft')
      } else {
	      new_select.val('miam')
      }
      
      new_row.find('input[type="number"]').val("0");
			
			// Incrémentation des noms
			var regex = new RegExp(/^(.+)(\d+)$/);
			$(new_row).find(':input').each(function() {
			    var match = $(this).attr('name').match(regex);
			    $(this).attr('name', match[1] + (++match[2]));
			});

      row.after(new_row);
      new_select.focus();
      
      // redraw height
      renewHeight()
    }
  });
	
	$('.modal-vols form').submit(function(e) {
		$form = $(this);
		produit_nom = $(this).find('select')[0].value;
		qte_volee = $(this).find('input[name=qte_volee]')[0].value;
	  e.preventDefault();
		console.log($form.serialize());   
		$.ajax({
			type: "post",
			url: "../rf/vols/add/" ,
			datatype: "json",
			data: $(this).serialize(),
			error: function(request,error){
				console.log(error);
			},
			success: function(request) {
				$($form).next('#responseTable').find('tbody:last').append('<tr><td><span class="label label-success">&#x2714;</span></td><td>'+ qte_volee + ' '+ produit_nom +'</td></tr>');
			}
		});
		return false;
	})
});
