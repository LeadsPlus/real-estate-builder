/*
 * TinyMCE additional buttons
 */
(function() {
    var DOM = tinymce.DOM;
    tinymce.create('tinymce.plugins.placester', {

        init : function(ed, url){
            var t = this;

            tbId = ed.getParam('wordpress_adv_toolbar', 'toolbar2');

            // required because of the hackish way wordpress implemented tinymce.
            // I don't think they really anticipated others using it.
            // This gives the user the option to toggle open the second toolbar
            // Needed because if the user has never clicked the "kitchen sink"
            // button, they'll never know the second row is even there.
            ed.addCommand('showPlacester', function() {
                var cm = ed.controlManager, id = cm.get(tbId).id;

            	if ( 'undefined' == id )
            		return;

            	if ( DOM.isHidden(id) ) {
            		cm.setActive('showPlacester', 1);
            		DOM.show(id);
            		ed.settings.wordpress_adv_hidden = 0;
            		setUserSetting('hidetb', '1');
            	} else {
            		cm.setActive('showPlacester', 0);
            		DOM.hide(id);
            		ed.settings.wordpress_adv_hidden = 1;
            		setUserSetting('hidetb', '0');
            	}
            }),
			
			ed.addButton('tinyplugin', {
	            title : 'Show Placester UI tools',
           		image: url + "../../images/tinymce/placester.png",
				cmd : 'showPlacester'
			}),
        	//bedrooms
        	ed.addButton('bedrooms', {
                title : '',
           		image: url + "../../images/tinymce/bedrooms.png",
        		onclick : function() {
        				ed.execCommand(
        				'mceInsertContent',
        				false,
        				insert_placester_text("bedrooms")
        			);
        		}
        	});
        	// bathrooms
        	ed.addButton('bathrooms', {
                title : '',
           		image: url + "../../images/tinymce/bathrooms.png",
        		onclick : function() {
        				ed.execCommand(
        				'mceInsertContent',
        				false,
        				insert_placester_text("bathrooms")
        			);
        		}
        	});
        	// rent
        	ed.addButton('price', {
                title : '',
           		image: url + "../../images/tinymce/price.png",
        		onclick : function() {
        				ed.execCommand(
        				'mceInsertContent',
        				false,
        				insert_placester_text("price")
        			);
        		}
        	});
        	// available_on
        	ed.addButton('available_on', {
                title : '',
           		image: url + "../../images/tinymce/available_on.png",
        		onclick : function() {
        				ed.execCommand(
        				'mceInsertContent',
        				false,
        				insert_placester_text("available_on")
        			);
        		}
        	});
        	// address
        	ed.addButton('address', {
                title : '',
           		image: url + "../../images/tinymce/address.png",
        		onclick : function() {
        				ed.execCommand(
        				'mceInsertContent',
        				false,
        				insert_placester_text("address")
        			);
        		}
        	});
        	// city
        	ed.addButton('city', {
                title : '',
           		image: url + "../../images/tinymce/city.png",
        		onclick : function() {
        				ed.execCommand(
        				'mceInsertContent',
        				false,
        				insert_placester_text("city")
        			);
        		}
        	});
        	// state
        	ed.addButton('state', {
                title : '',
           		image: url + "../../images/tinymce/state.png",
        		onclick : function() {
        				ed.execCommand(
        				'mceInsertContent',
        				false,
        				insert_placester_text("state")
        			);
        		}
        	});
        	// zip
        	ed.addButton('zip', {
                title : '',
           		image: url + "../../images/tinymce/zip.png",
        		onclick : function() {
        				ed.execCommand(
        				'mceInsertContent',
        				false,
        				insert_placester_text("zip")
        			);
        		}
        	});
        }
        });

        tinymce.PluginManager.add('tinyplugin', tinymce.plugins.placester);

        })();

        function insert_placester_text (button) 
        {
        switch (button)
        {
            case "bedrooms":
            	return	"[bedrooms]";
            case "bathrooms":
            	return	"[bathrooms]";
            case "price":
            	return	"[price]";
            case "available_on":
            	return	"[available_on]";
            case "address":
            	return	"[location.address]";
            case "city":
            	return	"[location.city]";
            case "state":
            	return	"[location.state]";
            case "zip":
            	return	"[location.zip]";
            default:
            	return "oh no";
        }

    }
