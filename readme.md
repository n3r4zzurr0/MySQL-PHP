# MySQL PHP Class
A simple class-based PHP layer over regular MySQL queries. It was developed for some specific use cases in dry.gg's source code.

## Installation

Include the class in your code:
```
require('mysql.class.php');
```

Connect to your database using the PDO interface:
```
$conn = new PDO('mysql:host=localhost;dbname=test', 'root', '');
```

Declare an object for the MySQL class and you're good to go.
```
$sql = new MySQL($conn);
```

## Usage

Let's say we have a table named "test1" with two columns, "name" `VARCHAR(50)` and "age" `INT(3)` which has 5 rows:
```
 -------------------
| Name      |  Age  |
|-------------------|
| Mike      |  44   |
|-------------------|
| Alex      |  35   |
|-------------------|
| Kid Rock  |  50   |
|-------------------|
| Rob       |  42   |
|-------------------|
| Lemmy     |  70   |
 -------------------
```
###### INSERT EXAMPLES

```
$response = $sql->put(
        array(
            'name' => 'Mike',
            'age'  => 44
        ),
       'test1'
    );

if($response)
{
    echo 'Success!';
}
```
OR
```
$response = $sql->put(
        array('Alex', 35),
       'test1'
    );
```

###### SELECT EXAMPLES

1) Get all names with age = 42
```
var_dump($sql->get('name', 'test1', array('age' => '42'), -1, -1));
```
Output:
```
array(1) { [0]=> string(3) "Rob" }
```

2) Get all names with age > 45
```
var_dump($sql->get('name', 'test1', array('age' => '>45'), -1, -1));
```
Output:
```
array(2) { [0]=> string(5) "Lemmy" [1]=> string(8) "Kid Rock" }
```

3) Get all names with age < 40
```
var_dump($sql->get('name', 'test1', array('age' => '<40'), -1, -1));
```
Output:
```
array(1) { [0]=> string(4) "Alex" }
```

4) Get everything from a table
```
var_dump($sql->get('*', 'test1', array(1 => 1), -1, -1));
```
Output:
```
array(5) { [0]=> array(4) { ["name"]=> string(4) "Mike" [0]=> string(4) "Mike" ["age"]=> string(2) "44" [1]=> string(2) "44" } [1]=> array(4) { ["name"]=> string(3) "Rob" [0]=> string(3) "Rob" ["age"]=> string(2) "42" [1]=> string(2) "42" } [2]=> array(4) { ["name"]=> string(4) "Alex" [0]=> string(4) "Alex" ["age"]=> string(2) "35" [1]=> string(2) "35" } [3]=> array(4) { ["name"]=> string(5) "Lemmy" [0]=> string(5) "Lemmy" ["age"]=> string(2) "70" [1]=> string(2) "70" } [4]=> array(4) { ["name"]=> string(8) "Kid Rock" [0]=> string(8) "Kid Rock" ["age"]=> string(2) "50" [1]=> string(2) "50" } }
```

5) Get sum of all ages
```
var_dump($sql->get('SUM(age)', 'test1', array(1 => 1), -1, -1));
```
Output:
```
array(1) { [0]=> string(3) "241" }
```

6) Count all rows
```
var_dump($sql->get('COUNT(*)', 'test1', array(1 => 1), -1, -1));
```
Output:
```
array(1) { [0]=> string(1) "5" }
```

7) Count all rows with age <= 44
```
var_dump($sql->get('COUNT(*)', 'test1', array('age' => '<=44'), -1, -1));
```
Output:
```
array(1) { [0]=> string(1) "3" }
```

I will update this readme file with the usage examples of `UPDATE` and `DELETE` queries and also with the usage of MySQL Transactions.
