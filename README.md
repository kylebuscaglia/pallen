# Pallen

Pallen is a [Laravel](https://laravel.com/) based web service that can parse, interpret and respond to a SMS message. 

It is the proccessing unit that works in conjunction with [Jallen](https://github.com/kylebuscaglia/jallen) to form **Allen**, a SMS based personal assistant.

Pallen communicates directly with **Jallen** using an exposed GraphQL endpoint. When an incoming SMS message is received, **Pallen** interprets what to do next and what data it needs to respond. It will then query **Jallen** GraphQL datalayer for the necessary data it needs to respond back over SMS.

Pallen also works well with [Twilio](https://www.twilio.com/). Simply configure your Twilio account to use a webhook on an incoming SMS message and utilize Pallen's callback endpoint as a processor. 

Architecture Overview
--------
![Image of Allen-Architecture]( https://raw.githubusercontent.com/kylebuscaglia/jallen/master/Allen-Architecture.jpeg )

Installation
--------
**Step 1.** Ensure you have the latest version of PHP, Apache, MySql and Git installed. 

    apt-get install git
    apt-get install apache2
    apt-get install mysql-server
    apt-get install php7.3-cli
    apt-get install php7.3-mysql
    apt-get install libapache2-mod-php7.3

**Step 2.** Download the latest version of composer  
`wget https://getcomposer.org/composer.phar`

**Step 3.** Clone the Pallen repository and point your apache server to the **/public** directory

**Step 4.** Go to the project's root directory and issue the command  
`php composer.phar install`
to install the project dependencies

**Step 5.** Enable apache mod_rewrite  
`sudo a2enmod rewrite`

**Step 6.** Create a database table for the application to utilize.

**Step 7.** Create a **.env** environment file in the project's root directory. The following is a sample file.
    
    APP_NAME=Laravel
    APP_ENV=local
    APP_KEY=base64:bLU8NRYxAR0ZDyEzb9P/P9tyhO0X/ORuBt5HRRXMpKI=
    APP_DEBUG=true
    APP_URL=http://localhost

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE={database}
    DB_USERNAME=root
    DB_PASSWORD={password}

    AUTH_TOKEN={Twilio Auth Token}
    
**Step 8.** Issue the command `php artisan migrate` in the project's root directory to create the necessary database tables.

Pallen should now be up and running ready to use!

Following the instructions from the offfical Laravel installation page https://laravel.com/docs/5.8/installation is also helpful to get Pallen up and running

Demo Environment
--------

A public instance is located at `pallen.bakeshow.us`

You can interact with it directly with this sample HTTP request

    POST /callback HTTP/1.1
    Host: pallen.bakeshow.us
    User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.14; rv:67.0) Gecko/20100101 Firefox/67.0
    Accept: application/json, text/plain, */*
    Accept-Language: en-US,en;q=0.5
    Accept-Encoding: gzip, deflate
    Referer: http://qa2.raft.com/
    Content-Type: application/x-www-form-urlencoded
    Connection: close
    Content-Length: 39

    From=7164454510&Body=Hi&FromZip=14216`


Simply replace the `From` parameter value with a target phone number you'd like Allen to respond. You should see a welcome greeting from Allen come over SMS.

SMS Demo
--------
You can use the phone number +1 (716) 239 4598 to interact directly with Allen.

Send a text message containing
        
        Hi
        Help me
        I'm bored
        I'm hungry
        Give me a random fact

and Allen will give you a reply!
