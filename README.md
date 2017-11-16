## SlimCMS

A simple CMS REST API implementation using decoupled PHP libraries.


### Requirements

* PHP >= 5.6

* [Composer](https://getcomposer.org/download/)

* [SQLite3](https://www.sqlite.org/download.html) 


## Installation

Clone this repository and `cd` in it: 

`$ git clone https://github.com/rfdeoliveira/slim-cms.git cms`

`$ cd cms`

Install dependencies through Composer:

`$ composer install`

Create database schema:

`$ vendor/bin/doctrine orm:schema-tool:create`

Start PHP's built-in webserver (Not recommended for production!):

`$ php -S localhost:8000 -t public/`

That's it! Now you can:
 
* Run tests like this: `$ vendor/bin/phpunit tests/ --colors`

* Create new Posts sending a `POST` request to `/posts` with `title`, `body` and `path`

* Select one Post like this: `/posts/1`

* List all `/posts`

* Update a Post making a `PUT` request to `/posts/{id}` with `title`, `body` and `path`

* Delete a Post making a `DELETE` request to `/posts/{id}`

* Access a Blog Post sending a `GET` request to `/blog/{path}`. Example: `/blog/path-to-my-post`
