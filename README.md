# related-product
Related product overwrite for Magento® 2
The related product extension makes it effortless to get related product info from API.

Installation
This is the manual for installing the Magento® 2 Plugin. Before you start up the installation process, we recommend that you make a backup of your webshop files, as well as the database.

The method to install the Magento® 2 extension.

Install by using Composer

Installation using Composer
Magento® 2 use the Composer to manage the module package and the library. Composer is a dependency manager for PHP. Composer declare the libraries your project depends on and it will manage (install/update) them for you.

Check if your server has composer installed by running the following command:

**composer –v**
If your server doesn’t have composer installed, you can easily install it by using this manual: https://getcomposer.org/doc/00-intro.md

Step-by-step to install the Magento® 2 extension through Composer:

Connect to your server running Magento® 2 using SSH or other method (make sure you have access to the command line).
Locate your Magento® 2 project root.
Install the Magento® 2 extension through composer and wait till it's completed:
**composer require asus55/product "@dev"**

Once completed run the Magento® module enable command:
**bin/magento module:enable Asus_Product**

After that run the Magento® upgrade and clean the caches:
**php bin/magento setup:upgrade**

**php bin/magento cache:flush**

If Magento® is running in production mode you also need to redeploy the static content:
**php bin/magento setup:static-content:deploy**


After the installation: Go to your Magento® admin portal and open ‘Stores’ > ‘Configuration’ > ‘RELATED PRODUCT’ > ‘Api Setting’.
set the url to get data from API
set if enable this extension