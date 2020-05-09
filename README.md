# CreateRESTfulAPIs

This app creates RESTful service apis to create, show, list, edit & delete item. It uses GET, POST, PUT, PATCH & DELETE verbs to perform actions. The API returns status 1 for a successful request along with additional data and status 0 for an unsuccessful request which is an error.

Used MVC architecture


# Built
App is built on core PHP 7.2.5 and MySQL 5.6

# URI structure
URI structure http://[host]:[port]/api/{service name}]/v{version number}/{resource}

Example: http://127.0.0.1:8080/api/itemstore/v1/items


# CRUD actions
![CURD_actions.jpg](./img/CURD_actions.jpg)

# Instructions 
* Do not use:
 
 /api/itemstore/v1/getallitems 
 
 /api/itemstore/v1/createitem
 
 /api/itemstore/v1/deleteitem
 
 * A trailing forward slash (/) should not be included in URIs

Exapmle: /api/itemstore/v1/items/       
         
     
# Architecture

![architecture_create_rest_api.jpg](./img/architecture_create_rest_api.jpg)

