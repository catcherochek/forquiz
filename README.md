# quiz task
*
## Task:

Remote Technical Developer Task (v2)

Requirements:

1. Create a persistent file caching mechanism which implements given interface:
https://gist.github.com/briedis/d14c4fd416bab8b8b8b873a8d677a0a6

2. Create a class that connects to Printful's Shipping Rate API
(https://www.printful.com/docs/shipping) and retrieves the list of available shipping
options for given values:

a. Address: ****

b. Product variant id: ****

c. Quantity: *

API authorization docs: https://www.printful.com/docs

API Key: *****

3. API results should be cached for five minutes using the previously implemented cache.

4. Cache interface should be constructor-injected in to the service.

Other requirements:

● Use Guzzle library for API requests.

● Use Composer for autoloading.

● Avoid using frameworks.

● Avoid using any other packages (composer, etc.).

Notes:

● API key in authorization header should be base64 encoded (as stated in the API docs)

**
***
## install : 
***
### in command line**

**git clone https://github.com/catcherochek/forquiz.git**

***
go into 'quiz' directory and put in command line to resolve composer dependencies:

**composer install**

***

## start :

***

Navigate index.php with your  web server http://localhost/pathtoquiz/index.php


