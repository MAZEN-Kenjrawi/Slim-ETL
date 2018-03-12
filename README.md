# Slim-ETL
This application is a REST API, which functions as an ETL and responsible for extracting data from the desired CSV file located in “public_html/data_storage”. 

This api will output a json object. One of its properties is “status” which may have either “success” or “error” as value. If its value is “success” there will be another property of this object called “file” which locate the output file path from the “puplic_html” directory, and if the status is “error” the json object will have property named “message” refer to what’s wrong with the request.
