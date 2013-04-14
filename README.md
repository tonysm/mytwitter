# This app is meant to be like twitter, just for fun
No relations with Twitter. I didn't implemented any Error handling or pagination (for now), cause it wasn't asked.

## Installation
Do the list below:

* Set *AllowOverride* to *All*
* Point the *VirtualHost* to the *public* directory
* Run *"composer install"* to get Composer's autoload

## Database
There's a file on the project root with the schema of the database, called *schema.sql*.

## API
There's only three availiable methods, both using the HTTP GET verb, listed below

HTTP VERB | Route | Content-type | Description
--- | --- | --- | ---
GET | /users/:id.json | application/json | Gets a single user
GET | /users/:id/messages.json | application/json | Lists all the messages of a single user
GET | /users/:id/friends.json | application/json | Lists all the friends of a single user

In both cases, you have to replace the :id with the user id you want. At least, but not last, You don't have to be logged in to use the API.