# About
This is a simple balance using Laravel as api!

# Setup

## Running the server on Docker
1. Download or clone the project.
2. Make sure that you have docker already installed. (*[Download Here](https://docs.docker.com/get-docker/)*)
3. Create the docker image using cli inside the project folder : 
> docker build -t laravelapi -< Dockerfile

4. Create a application of the image that you create passing the path of the project:

>docker run -d -p 8080:8080 --name Larave-Api -v {path}:/var/www/html laravelapi
>
> Windows example:
> docker run -d -p 8080:8080 --name Larave-Api -v C:\XXX\Laravel-Api>:/var/www/html laravelapi
>
> Linux example: 
> docker run -d -p 8080:8080 --name Larave-Api -v /home/Laravel-Api>:/var/www/html laravelapi

5. Done its running on your localhost:8080 !

## Running the server on local
1. Download or clone the project.
2. Make sure that you have those packages already installed.
> php php-curl curl wget php-mbstring php-gd php-zip zip unzip php-dom composer.
3. Install the Composer vendor:
> composer install
4. Run Artisan server:
> php artisan serve --host=0.0.0.0 --port=8080


# Methods

### [get] /bance (account_id = int)
return the balance of informed id
> example : /bance?account_id=ID

### [get] /bance (account_id = int)
return the balance of informed id

### [post] /event (json object)
do transactions like :

*withdraw*
> example: {"type":"withdraw", "origin":"ID", "amount":FLOAT}

*transfer*
> example: {"type":"transfer", "origin":"ID", "amount":FLOAT, "destination":"ID"}

*deposit*
> example: {"type":"deposit", "destination":"ID "amount":FLOAT}
