# sp-rest

RESTful Services Database Wrapper

## Description
  
(TBD)

##Usage

(TBD)

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

