Create data models powered by wordpress that are served as client side json objects  
  
- install wordpress
- activate theme
- change defaults (defaultPage, defaultPageDir, pathPrefix, pathname) in dynamics.js
- create menu called "Main Menu", add menu item, and set theme location to header menu
- go to settings->reading and assign a front page with using the template “Home”
- upload htaccess to root directory (see below for sample)
- create 2 models to activate models
- create 2nd page called Foobar to activate 2nd page demo

optional simple JSON data return API:
- change defaults in _data and move to root directory
- update the htaccess in that directory (see below for sample)
  
  
demo:  
http://50.87.144.13/~illatoz/onepage/
  
.htaccess sample  
RewriteEngine On  
RewriteBase /~illatoz/  
RewriteRule ^index\.php$ - [L]  
RewriteCond %{REQUEST_FILENAME} !-f  
RewriteCond %{REQUEST_FILENAME} !-d  
RewriteRule . index.php [L]  

/_data/.htaccess sample  
RewriteBase /~illatoz/wp-content/themes/illatoz_wp/_data/  
RewriteEngine On  
RewriteBase /~illatoz/wp-content/themes/illatoz_wp/_data/  
RewriteRule ^index.php$ - [L]  
RewriteCond %{REQUEST_FILENAME} !-f  
RewriteCond %{REQUEST_FILENAME} !-d  
RewriteRule . index.php [L]  