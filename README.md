# sp-rest

RESTful Services Database Wrapper

## Description
  
Let's say you have an existing database, which contains entities (tables or views) you want to be accesible to "consumers" (applications, users etc) via RESTful web services, in order to perform simple CRUD opertions. Of course, you may not want all your tables (or even all fields in specific tables) to be populated.

SP-REST does exactly that (nothing more, nothing less-I hope...).

It allows you to describe mapping between your existing data and web service "collections" that will be accesible via HTTP calls in the form of REST API. The response comes in JSON or XML (the client describes it's preferred format via http Accept header).

##Usage Example

I have an existing database:
Table: employees
Fields: id (it's mandatory for the tables to have a primary integer key under the name "id"), first_name, last_name, salary, hidden_field

First, I would configure my database settings in the *config.inc* file:

    $config['dbtype']='mysql';    
    $config['dbhost']='localhost';
    $config['database']='rest_test';
    $config['dbuser']='root';
    $config['dbpass']='pass';

Then, I would make a mapping between the table and my desired "collection" (let's say cs-employees):

In the *collections.inc* file:

    $collections['cs-employees']='employees';
    $fields['cs-employees']='first_name,last_name,salary'; //I exclude the hidden_field field so it will not be accesible by the web service 

If everything works as it should you can:
###Read records from the DB

    GET http://www.yourroot.com/cs-employees/{id} (retrieves record with specific id)

    GET http://www.yourroot.com/cs-employees (retrieves everything)
    
    GET http://www.yourroot.com/cs-employees?first_name=john&last_name=%D%oe% (retrieves query - uses "like" for alphanumeric values) 

###Delete record from the DB

    DELETE http://www.yourroot.com/cs-employees/{id}
    
###Create Record to the DB

    POST http://www.yourroot.com/cs-employees/
    The data should be sent in "form-data" (or "x-www-form-urlencoded") format
        
##ChangeLog

17/1/2013
* created new file to hold configuration details - config.inc  
* created new file for the database connection - db_conn.inc (having in mind future db abstraction)
* created new file for collections definitons - collections.inc
* Added query support to the GET method
* Added validations - whitelisting for error and sql injection prevention
* Added support for the DELETE method  
 

## Libraries - Snippets used

_ezsql_ for database wrapping (because it rocks...)

Class _Array2XML_ (Johny Brochard) 
<http://www.phpclasses.org/package/1826-PHP-Store-associative-array-data-on-file-in-XML.html>  
I use it for easy convertion of arrays to xml.  
Made a little modification: by default it saved an .xml file, I used it for filling the string passed to the client.   

