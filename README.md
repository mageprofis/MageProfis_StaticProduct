# MageProfis_StaticProduct
Save Product and Category Pages as Static File.
It is possible to use it in nginx mode (fast; TTFB ~50ms) or in magento mode (slower; TTFB ~100ms).

Sample NGINX Config:
[nginx.conf](https://github.com/mageprofis/MageProfis_StaticProduct/blob/master/nginx.sample.conf)

This Modul is a Proof-Of-Concept Modul

# Important
- Category Caching is experimental. Please check the cache keys.
- The cart info (qty, price) in the header should always return 0. Load the correct information via ajax
- Check Form Keys
- Wrap Messages on Category page in ```<div id="messages_category_view"></div>```
- Include js/mpstaticproduct.js in your main js file

# Functions
- Cache Product Page
- Cache Category Page
- Ingore categories by ID

# Todo
- Category Product Sorting
- Category Products per Page

```
#!/bin/sh

# Cache Warm Up
n98-magerun.phar sys:url:list --add-products 1 > products.txt
for i in `cat products.txt | grep -v "catalog/product/view"`; do
  curl -s --max-time 5 --connect-timeout 5 -k -I "$i" > /dev/null
  echo $i
  date
done
rm -f products.txt
```
