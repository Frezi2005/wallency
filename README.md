# Wallency
Wallency is currency wallet with many features.

## Installation
```sh
git clone https://github.com/Frezi2005/wallency.git
```
After cloning the repository you need to go to wallency-master/Vendor/cakephp/cakephp/app/config/database.php and fill in your databse credentials. Then go to wallency-master/Vendor/cakephp/cakephp/app/config/email.php and fill in your email and password for your email server.

If you see a blank page after clicking one of these links below, see if one of these methods will help you out.
- If you have Ubuntu and LAMP installed: 
    execute this command: 
    ```sh
    sudo a2enmod rewrite
    ```
    then make change in

    ```sh
    /var/apache2/sites-available/000-default.conf
    ```

    add this below line DocumentRoot /var/www/html

    ```sh
    <Directory /var/www/html>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Order Allow,Deny
        Allow from all
    </Directory>
    ```

    and for ssl/https i make some change as above

    ```sh
    /var/apache2/sites-available/default-ssl.conf
    ```

    below line
    ```sh
    <Directory /usr/lib/cgi-bin>
            SSLOptions +StdEnvVars
    </Directory>
    ```


## Basic usage
To go to wallency home page you need to go to [Link 1](localhost/wallency-master/cakephp/cakephp) or [Link 2](localhost/wallency-master/cakephp/cakephp/home)

## Pages

### Before logging in
- [Home page](localhost/wallency-master/Vendor/cakephp/cakephp/home) - There you can find screenshots of website, some info and links to other pages
- [About page](localhost/wallency-master/Vendor/cakephp/cakephp/about) - You can find info about author of the webiste
- [Contact page](localhost/wallency-master/Vendor/cakephp/cakephp/contact) - Here you can write message to me
- [Career page](localhost/wallency-master/Vendor/cakephp/cakephp/career) - There you'll see fake job offers
- [Login page](localhost/wallency-master/Vendor/cakephp/cakephp/login) - There you can login to see your wallet
- [Register page](localhost/wallency-master/Vendor/cakephp/cakephp/career) - And here you can create an account

### After logging in
- [Profile page](localhost/wallency-master/Vendor/cakephp/cakephp/profile) - You can see your wallet sum history and change some settings
- [Wallet page](localhost/wallency-master/Vendor/cakephp/cakephp/wallet) - This website has 3 tables, one for currencies, one for crypto and the last one for resources. It also has currency calculator.
- [Deposit page](localhost/wallency-master/Vendor/cakephp/cakephp/deposit) - Here you can deposit (not real)money into your account
- [Withdraw page](localhost/wallency-master/Vendor/cakephp/cakephp/withdraw) - Here you can withdraw money from your account
- [Exchange page](localhost/wallency-master/Vendor/cakephp/cakephp/exchange) - On this page you can exchange currencies to different ones
- [Transfer page](localhost/wallency-master/Vendor/cakephp/cakephp/transfer) - You can transfer money to other users by their login
- [History page](localhost/wallency-master/Vendor/cakephp/cakephp/history) - There you can view transaction history
- [FAQ page](localhost/wallency-master/Vendor/cakephp/cakephp/faq) - There you can see (made up)frequently asked questions

## Other features
* You can change language from polish to english and vice versa
* Wallet sends notifications whenever currency rates drop or raise by a big amount
