/*
 * Placester JS Widget Functions.
 * Permits quick access to the placester js widgets
 */

/*
 * Generates the line chart widget
 *
 * @param object container
 * @param string webkey
 * @param string organization_id
 * @param string on_empty_callback
 */
function line_widget (api_key_id, container, webkey, organization_id, on_empty_callback)
{
    var line_chart = new Placester.Charts("line_leads", {
		 on_error: on_empty_callback,
		 on_empty: on_empty_callback,
         container: container,
         width: 600,
         height: 250,
         legend: {
           enabled: true
         },
         line: {
            leads: {
              color: "058DC7",
              marker: {
                color: "058DC7",
                radius: 10
              }
            },
            views: {
              color: "AA4643",
              marker: {
                color: "AA4643",
                radius: 10
              }
            },
          },
         axis: {
           leads: {
             label: {
               color: "999999",
               size: 10
             }
           },
         },
         extra_args : 'chdlp=b',
         auth: {
           web_key: webkey,
           organization_id: organization_id,
           api_key_id: api_key_id
         }
       });
    
}



/*
 * Generates the pie widget
 *
 * @param object container
 * @param string webkey
 * @param string organization_id
 * @param string on_empty_callback
 */
function pie_widget (api_key_id, container, webkey, organization_id, on_empty_callback) 
{
    var pie_chart = new Placester.Charts("pie_publishers", {
	  on_error: on_empty_callback,
	  container: container,
      width: 200,
      height: 200,
      pie : {
          colors: ["058DC7","50B432","ED561B","EDEF00"]          
      },
      legend: {
        enabled: true,
        position: "bottom",
        vertical: false
      },
      label: {
        enabled: false
      },
      auth: {
        web_key: webkey,
        organization_id: organization_id,
        api_key_id: api_key_id
      }
    });
}



/*
 * Generates the leads widget
 *
 * @param object container
 * @param string webkey
 * @param string organization_id
 * @param string on_empty_callback
 */
function leads_widget (api_key_id, container, webkey, organization_id, on_empty_callback) 
{
    var list = new Placester.Leads("leads", {
  	  on_error: on_empty_callback,
      container: container,
      table: {
        paginate: true
      },
      lightbox: {
        width: 400,
        height: 300
      },
      auth: {
        web_key: webkey,
        organization_id: organization_id,
       api_key_id: api_key_id
      }
    });
}


/**
 * Null / Error Images
 *
 * @param string container_id
 * @param string null_image_url
 * @param string message
 * @param string additional_styles
 */
function show_widget_error(container_id, null_image_url, message, additional_styles)
{
    // jQuery('#' + container_id).html('test');
    jQuery('#' + container_id).html(' <img src="'+null_image_url+'"><div style="'+additional_styles+'" class="null-message"><p>'+message+'</div>');
}



/*
 * Recent news widget
 */
jQuery(document).ready(function()
{
    jQuery('#recent_news').children('.inside').html('Loading...').load(
        'admin.php?page=placester_dashboard&ajax_action=recent_news');
});