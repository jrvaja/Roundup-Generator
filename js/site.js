(function() { 

	var overlay = $('body > .overlay'),
		selectElem = $('#site_select'),
		hasLocalStorage = ('localStorage' in window && window['localStorage'] !== null),
		helpTrigger = $('.trigger');
	
	var Site = {
		
		init : function() {
			var sel;
			
			// For alternate styling in browsers that don't support display:box
			if ( !this.supports('BoxPack') ) document.documentElement.className += ' noDisplayBox';
			
			// Get rid of results when (re)submitting
			$('#submit').click(function() {	
				$('.result').fadeOut(200);
			});
			
			this.showHelp();
			this.selectChange();
			this.updateSelectedOption();
		}, // init
		
		
		selectChange : function() {
			var that = this;
			
			selectElem.change(function() {
				hasLocalStorage && ( localStorage.setItem('selectedTemplate', this.value) );
				that.updateTitle( this.value );
			});
		}, // selectChange
		
		
		updateSelectedOption : function() {
			var val;
			
			if ( hasLocalStorage && localStorage.getItem('selectedTemplate') ) {
				sel = $('option', selectElem).filter(function(i, elem) {
					return elem.value === localStorage.getItem('selectedTemplate');
				});

				val = sel[0].value;
				// Weird issue...had to do this
				sel.replaceWith('<option value="' + val + '" SELECTED>' + val + '</option>');
				this.updateTitle( sel.attr('value') );
			}
		},
		
		
		updateTitle : function( text ) {
			$('h1')
				.children('span')
					.css('opacity', 0)
					.text( text + ' Round-up Generator' )
					.animate({'opacity': 1}, 1000);
		}, // updateTitle
		
		
		showHelp : function() {
			var i = 1;

			helpTrigger.click(function() {
				var oH = overlay.outerHeight() / 2,
					textarea = $('#assets');
					
				// Vertically center overlay
				overlay.css('marginTop', '-' + oH + 'px');

				// Show example usage
				if ( i % 2 === 0 ) {
					textarea.val(
						"Like This... \r\n" +
						"http://codecanyon.net/item/ajax-contact-form/50846, \r\n" +
						"http://codecanyon.net/item/simple-php-contact-form/46679, \r\n" +
						"http://themeforest.net/item/sideways-portfolio-website-template/148323");
					
					textarea.focus(function() {
						$(this)
							.val('')
							.unbind('focus');
					});
				} 
				
				// If they click the "Help" icon again...
				if ( i % 3 === 0 ) {
					overlay
						.html("<p>Sheesh - you still can't figure this out? :D Fine, <a href='http://bit.ly/fHjWRj' target='_blank'>watch this video.</a> </p>");
				}
				
				overlay.fadeToggle(500);
				i++;
			}); // trigger click
			
		}, // showHelp
		
	
	
		supports : (function() {
		   var div = document.createElement('div'),
		      vendors = 'Khtml Ms O Moz Webkit'.split(' '),
		      len = vendors.length;
	
		   return function(prop) {
		      if ( prop in div.style ) return true;
		
		      prop = prop.replace(/^[a-z]/, function(val) {
		         return val.toUpperCase();
		      });
		
		      while(len--) {
		         if ( vendors[len] + prop in div.style ) {
		            // browser supports box-shadow. Do what you need.
		            // Or use a bang (!) to test if the browser doesn't.
		            return true;
		         } 
		      }
		      return false;
		   };
		})()
	   
	}
	
	Site.init();

})();
