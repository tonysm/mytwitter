# This app is meant to be like twitter, just for fun
No relations with Twitter

## installation

Remember to set the AllowOverride to All, and the VirtualHost has to point to the public directory

## Database
There's a file on the project root with the schema of the database, called *schema.sql*.

## API
There's only two availiable methods, both using the HTTP GET verb, listed below

HTTP VERB | route | Content-type
--- | --- | ---
GET | /users/:id.json | application/json
GET | /friends/:id.json | application/json

In both cases, you have to replace the :id with the user id you want. At least, but not last, I didn't implemented any Error handling or pagination (for now), cause it wasn't asked.