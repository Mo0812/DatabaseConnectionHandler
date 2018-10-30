# DatabaseConnectionHandler

DatabaseConnectionHandler is a simple PDO wrapper. The DatabaseConnectionHandler allows easy access to a MySQL database through PDO. It includes a singleton pattern which can be used to open up only one database connection throughout the whole project, so that your application not run into handling to many connections to your database. It also has a certain use pattern which is described below.

## Requirements
* PHP version 5.3.1 or higher
* MySQL database to connect to (for now)

## Installation

### Composer
`composer require mk/database-connection-handler`

### Manually
1. Just copy the included files into a folder in your project and include the ```DatabaseConnectionHandler.php``` wherever you want in your php files to use it.
2. To give the connection credentials for your own database please edit the file ```DataBaseConnection.php``` and fill out the placeholders with your own mysql server informations.

## Roadmap / Specifications

* [x] Uses namespaces
* [x] PHP 7+ compatible
* [x] Error handling with exceptions
* [x] Use of `?` and `:param` syntax for prepared statements
* [ ] Feel free to raise an issue or more features

## Usage

### Namespace
Use `\MK\DB` as namespace.

### Open up connection / create an instance
After including ```DatabaseConnectionHandler.php``` into a php file you need the get the current instance over the static function ```getInstance()```. After that you are ready to query your database.

### Query the database
After creating an instance of the ```DatabaseConnectionHandler``` you can use the ```query()``` method to either fetch results or just query inserts or anything else.

The ```query()``` method has two parameters:
* *$query* is the SQL query string.
* *$arguments* is an array with certain arguments for the query string. This argument could also be left empty.

A query with parameters is build up like any query in PDO you use "?" to signal the DatabaseConnectionHandler that there is a matching argument for the placeholder in the *$arguments* array. So i.e.:

```php
use \MK\DB;

$dbc_handler = DatabaseConnectionHandler::getInstance();

$searched_name = "Jack";
$searched_age = 21;

$result = $dbc_handler->query("INSERT INTO my_table (name, age) VALUES (?, ?);", array($searched_name, $searched_age));
```

This prepared statement behaviour also work as in pure PDO with named placeholders:

```php
use \MK\DB;

$dbc_handler = DatabaseConnectionHandler::getInstance();

$searched_name = "Jack";
$searched_age = 21;

$result = $dbc_handler->query("INSERT INTO my_table (name, age) VALUES (:name, :age);", array(":name" => $searched_name, ":age" => $searched_age));
```

### Get results from your query
To get results for your query every ```query()``` method call returns a ```DatabaseResult``` object. This object holds all the queried data and also the selected rows of the query and the last insert id, if it's meaningful.

The ```DatabaseResult``` has the following methods:
* ```getSelectedRows()```: Returns the found rows for a query if meaningful.
* ```nextRow()```: Returns the next row of a query if meaningful. This row is an associated array with the queried columns as keys.
* ```fetchAll()```: Instead of returning only the next row of the query this method returns all rows which are left packed in an array.
* ```getLastInsertID()```: Returns the last insert id if meaningful.

Here is a simple example of how to use the DatabaseResult object:

```php
use \MK\DB;

$dbc_handler = DatabaseConnectionHandler::getInstance();

$searched_name = "Jack"
$searched_age = 21

$result = $dbc_handler = $dbc_handler->query("INSERT INTO my_table (name, age) VALUES (?, ?);", array($searched_name, $searched_age));

//Last insert id
$last_insert_id = $result->getLastInsertID();

//Loop through result
$result2 = $dbc_handler->query("SELECT id, name, age FROM my_table;", array());
foreach($result2->fetchAll() as $row) {
    print_r($row);
}

//Get single (estimated) result
$result3 = $dbc_handler->query("SELECT id, name, age FROM my_table WHERE name = ?;", array($searched_name));
if($result3->getSelectedRows() > 0) { //Is there an result?
    $searched_row = $result3->nextRow();
}
```
