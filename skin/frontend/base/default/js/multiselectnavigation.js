function adj_nav_hide_products()
{
    var items = $('narrow-by-list').select('a', 'input');
    n = items.length;
    for (i=0; i<n; ++i){
        items[i].addClassName('mmd-multinav-disabled');
    }
    
    if (typeof(adj_slider) != 'undefined')
        adj_slider.setDisabled();
    
    var divs = $$('div.mmd-multinav-progress');
    for (var i=0; i<divs.length; ++i)
        divs[i].show();
}

function adj_nav_show_products(transport)
{   
    var resp = {} ;
    if (transport && transport.responseText){
        try {
            resp = eval('(' + transport.responseText + ')');
        }
        catch (e) {
            resp = {};
        }
    }
    
    if (resp.products){
        var el = $('mmd-multinav-container');
        var ajaxUrl = $('mmd-multinav-ajax').value;
        
        el.update(resp.products.gsub(ajaxUrl, $('mmd-multinav-url').value));
        //adj_nav_toolbar_init(); // reinit listeners
                
        $('mmd-multinav-navigation').update(resp.layer.gsub(ajaxUrl, $('mmd-multinav-url').value));
        
        $('mmd-multinav-ajax').value = ajaxUrl;  
    }
    
    var items = $('narrow-by-list').select('a','input');
    n = items.length;
    for (i=0; i<n; ++i){
        items[i].removeClassName('mmd-multinav-disabled');
    }
    if (typeof(adj_slider) != 'undefined')
        adj_slider.setEnabled();
}

function adj_nav_add_params(k, v, isSingleVal)
{
    var el = $('mmd-multinav-params');
    var params = el.value.parseQuery();
    
    var strVal = params[k];
    if (typeof strVal == 'undefined' || !strVal.length){
        params[k] = v;
    }
    else if('clear' == v ){
        params[k] = '';
        delete params[k];           
    }
    else {
        var values = strVal.split('-');
        if (-1 == values.indexOf(v)){
            if (isSingleVal)
                values = [v];
            else 
                values.push(v);
        } 
        else {
            values = values.without(v);
        }
                
        params[k] = values.join('-');
    } 
    
   el.value = Object.toQueryString(params).gsub('%2B', '+');
}



function adj_nav_make_request()
{   
    adj_nav_hide_products();
    new Ajax.Request($('mmd-multinav-ajax').value + '?' + $('mmd-multinav-params').value, 
        {method: 'get', onSuccess: adj_nav_show_products}
    );
}


function adj_update_links(evt, className, isSingleVal)
{
    var link = Event.findElement(evt, 'A'),
        sel = className + '-selected';
    
    if (link.hasClassName(sel))
        link.removeClassName(sel);    
    else
        link.addClassName(sel);
    
    //only one  price-range can be selected
    if (isSingleVal){
        var items = $('narrow-by-list').getElementsByClassName(className);
        var i, n = items.length;
        for (i=0; i<n; ++i){
            if (items[i].hasClassName(sel) && items[i].id != link.id)
                items[i].removeClassName(sel);   
        }
    }

    adj_nav_add_params(link.id.split('-')[0], link.id.split('-')[1], isSingleVal);
    
    adj_nav_make_request();    
    
    Event.stop(evt);    
}


function adj_nav_attribute_listener(evt)
{
    adj_update_links(evt, 'mmd-multinav-attribute', 0);
}

function adj_nav_icon_listener(evt)
{
    adj_update_links(evt, 'mmd-multinav-icon', 0);
}

function adj_nav_price_listener(evt)
{
    adj_update_links(evt, 'mmd-multinav-price', 1);
}

function adj_nav_clear_listener(evt)
{
    var link = Event.findElement(evt, 'A'),
        varName = link.id.split('-')[0];
    
    adj_nav_add_params(varName, 'clear', 1);
    
    //remove selection
    var classes = ['icon', 'attribute', 'price'];
    var currClass = '';
    for (var j=0; j<classes.length; ++j){
        currClass = 'mmd-multinav-' + classes[j] + '-selected';
        var items = $('mmd-multinav-filter-'+varName).getElementsByClassName(currClass);
        var i, n = items.length;
        for (i=0; i<n; ++i){
            items[i].removeClassName(currClass);   
        }
    }
    if ('price' == varName){
        var from =  $('mmd-multinav-price-from'),
            to   = $('mmd-multinav-price-to');
          
        if (Object.isElement(from)){
            from.value = from.name;
            to.value   = to.name;
        }
        
    }
    
    adj_nav_make_request();    
    
    Event.stop(evt);  
}


function adj_nav_round(num){
    num = parseFloat(num);
    if (isNaN(num))
        num = 0;
        
    return Math.round(num*100)/100;
}

function adj_nav_price_input_listener(evt){
    if (evt.type == 'keypress' && 13 != evt.keyCode)
        return;
    
    
    var numFrom = adj_nav_round($('mmd-multinav-price-from').value),
        numTo   = adj_nav_round($('mmd-multinav-price-to').value);
  
    if ((numFrom<0.01 && numTo<0.01) || numFrom<0 || numTo<0)   
        return;

    adj_nav_add_params('price', numFrom + ',' + numTo, true);
    adj_nav_make_request();         
}

function adj_nav_category_listener(evt){
    var link = Event.findElement(evt, 'A');
    var catId = link.id.split('-')[1];
    
    var reg = /cat=/;
    if (reg.test(link.href)){ //is search
        adj_nav_add_params('cat', catId, 1);
    }
    else {
        $('mmd-multinav-ajax').value = $('mmd-multinav-ajax').value.replace(/\/id\/\d+\//, '/id/'+catId+'/');  
    }
    adj_nav_make_request(); 
    Event.stop(evt);  
}

function adj_nav_toolbar_listener(evt){
    adj_nav_toolbar_make_request(Event.findElement(evt, 'A').href);
    Event.stop(evt); 
}

function adj_nav_toolbar_make_request(href)
{
    var pos = href.indexOf('?');
    if (pos > -1){
        $('mmd-multinav-params').value = href.substring(pos+1, href.length);
    }
    adj_nav_make_request();
}


function adj_nav_toolbar_init()
{
    var items = $('mmd-multinav-container').select('.pages a', '.view-by a');
    var n = items.length;
    for (i=0; i<n; ++i){
        Event.observe(items[i], 'click', adj_nav_toolbar_listener);
    }
}

function adj_nav_dt_listener(evt){
    var e = Event.findElement(evt, 'DT');
    e.nextSiblings()[0].toggle();
    e.toggleClassName('mmd-multinav-dt-selected');
}

function adj_nav_init()
{
    var items, i, j, n, 
        classes = ['category', 'attribute', 'icon', 'price', 'clear', 'dt'];
    
    for (j=0; j<classes.length; ++j){
        items = $('narrow-by-list').select('.mmd-multinav-' + classes[j]);
        n = items.length;
        for (i=0; i<n; ++i){
            Event.observe(items[i], 'click', eval('adj_nav_' + classes[j] + '_listener'));
        }
    }
    
    var btn = $('mmd-multinav-price-go');
    if (Object.isElement(btn)){
        Event.observe(btn, 'click', adj_nav_price_input_listener);
        Event.observe($('mmd-multinav-price-from'), 'keypress', adj_nav_price_input_listener);
        Event.observe($('mmd-multinav-price-to'), 'keypress', adj_nav_price_input_listener);
    }
    
}

  
function adj_nav_create_slider(width, from, to, max_price) 
{
    var price_slider = $('mmd-multinav-price-slider');

    return new Control.Slider(price_slider.select('.handle'), price_slider, {
      range: $R(0, width),
      sliderValue: [from, to],
      restricted: true,
      
      onChange: function (values){
        var f = adj_nav_round(max_price*values[0]/width),
            t = adj_nav_round(max_price*values[1]/width);
            
        adj_nav_add_params('price', f + ',' + t, true);
          
        // we can change values without sliding  
        $('mmd-multinav-range-from').update(f); 
        $('mmd-multinav-range-to').update(t);
            
        adj_nav_make_request();  
      },
      onSlide: function(values) { 
          $('mmd-multinav-range-from').update(adj_nav_round(max_price*values[0]/width));
          $('mmd-multinav-range-to').update(adj_nav_round(max_price*values[1]/width));
      }
    });
}