if (typeof Product == 'undefined') {
    var Product = {};
}

Product.StaticCache = Class.create();
Product.StaticCache.prototype = {
    initialize: function(config){
        var product_id = parseInt(config.product_id, 10);
        new Ajax.Request(config.url, {
            parameters: {product_id: product_id},
            onSuccess: function(response) {
                var product = response.responseText.evalJSON();
                var messages = $('messages_product_view');
                if (messages) {
                    messages.innerHTML = '';
                }
                product.items.forEach(function(e) {
                    if(e.content != '' && e.content != '&nbsp;') {
                        var element = $$(e.class)[0];
                        if(typeof element != 'undefined') {
                            if (e.type === 'replace') {
                                element.replace(e.content);
                            } else if(e.type === 'attribute') {
                                element.writeAttribute(e.attribute, e.content);
                            } else {
                                element.innerHTML = e.content;
                            }
                        }
                    }
                });
                var form = $('product_addtocart_form');
                if (form && product.form) {
                    form.writeAttribute('action', product.form);
                }
                var key = $$('input[name="form_key"]');
                if (key && product.info) {
                    key.forEach(function(e) {
                        e.replace(product.info);
                    });
                }
            }
        });
    }
}