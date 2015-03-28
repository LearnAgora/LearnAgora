# OAuth2

## Usage in production

WE NEED TO DISCUSS THIS. 
MOST OF THE BELOW IS CURRENTLY NOT APPLICABLE IN PRODUCTION 
AND I DON'T WANT TO DISCUSS IT RIGHT THIS MOMENT.

## Usage in development

Your instance of the "frontend application" needs to be registered 
with the "host api".

This is done by using the ```la:security:client:create``` command on 
the machine running the "host api". Refer to its instructions before 
continuing.

We need to allow actual users access, so we need to set them up 
with the ```password``` ```grant_type```:

```app/console la:security:client:create --redirect-uri=http://www.example.com --grant-type=password```

Running the above returns a ```client_id``` and ```client secret```.

These need to be shared with the "frontend application" and are thus 
inherently insecure and serve as identification only.
