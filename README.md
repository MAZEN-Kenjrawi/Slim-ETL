# Slim-ETL
[![StyleCI](https://styleci.io/repos/124799393/shield?branch=master)](https://styleci.io/repos/124799393) [![CircleCI](https://circleci.com/gh/MAZEN-Kenjrawi/Slim-ETL/tree/master.svg?style=svg)](https://circleci.com/gh/MAZEN-Kenjrawi/Slim-ETL/tree/master)

This application is a REST API, which functions as an ETL and responsible for extracting data from the desired CSV file located in “public_html/data_storage”. 

This api will output a json object. One of its properties is “status” which may have either “success” or “error” as value. If its value is “success” there will be another property of this object called “file” which locate the output file path from the “puplic_html” directory, and if the status is “error” the json object will have property named “message” refer to what’s wrong with the request.

#

### Responses:

``
{"status":"success","file":".\/data_storage\/json_d2de131575f51c0e9276ec36706faa71.json"}
``

``
{"status":"error","error_messages":"Unsupported format!"}
``

#

### The possibilities of the http request, (* is required):

1 - ‘/’ => would return the default output for the data, which is:
-	Default output file format: which is ‘json’
-	Filtering the name column with ` sanitize_ascii`, which is a customized filter for cleaning the name from the non-ascii characters.
-	Filtering the number of the stars in the stars column, if it’s not in [1-5] range then return “Invalid!”.
-	Filtering the uri column to be a valid url.
-	Filtering all the columns using `trim`
-	Ordering the output data by the stars column value by Descending order.

2 - ‘/format/{format*}’ => like: “http://domain.com/format/xml”

To export the data in a desired format (either ‘json’ or ‘xml’).


3 - ‘/{format*}/{filters*}/{sort*}’ like “http://domain.com/json/name-trim/stars&name-0” 
=> while:

{format}: is either ‘json’ or ‘xml’,

{filters}: is a string must be structured to define multiple filter for multiple column, like this: 

“fieldname1-filterName1|filterName2|filterName3&fieldindex1-filterName1|filterName3”

Example: “name-trim|sanitize_ascii|clean_string&uri-clean_url&stars-hotel_stars”

In this example, what’s gona happen is that the filters (trim, sanitize_ascii and clean_string) going to be applied on name column, and the filter (clean_url) gonna be applied on uri column, and the filter (hotel_stars) applied on stars column.


{sort}: is also well-structured string to define the sorting criteria, like this:

“fieldname1-0&fieldindex2-1&fieldname3-DESC&fieldname4”

Examples: 
-	“stars-0” to order by stars column in descending order.
-	“stars-1” to order by stars column in ascending order.
-	“stars” to order by stars column in ascending order.
-	“stars&name-0” to order by stars column in ascending order and then to order by name column in descending order.
