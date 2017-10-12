# MageProfis_StaticProduct
Save Product Pages as Static File

Sample NGINX Config:
[nginx.conf](https://github.com/mageprofis/MageProfis_StaticProduct/blob/master/nginx.sample.conf)

This Modul is a Proof-Of-Concept Modul

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
