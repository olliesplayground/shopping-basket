# Shopping Basket

## Assumptions

- Offers are based only on which products are in the basket rather than basket value.
- Any single product in the basket can only have one offer applied to it.
- Delivery rates are based only on basket value rather than which products are in the basket.


## Key requirements

- The basket must provide an interface for adding products using just a product code.
- The basket must provide an interface for retrieving the total value of the products, including offer reductions 
and delivery changes.
- The basket must accept all the data necessary for the previous two requirements.

## Architecture  

### Overview

The Basket itself is a single class that accepts a single argument. In this solution, this argument is an instance of 
a repository manager class (also provided in this project), that handles the manipulation of all external data. 

There are three data repositories, each with an accompanying entity, that handle the following data types: 

- Product
- Offer
- Delivery

The entities represent a single instance of each of these data types and the repositories handle all of the logic 
and convenience methods associated to them. This model is borrowed from [Doctrine](https://www.doctrine-project.org/).

For ease, all data is stored in [JSON](https://www.json.org/json-en.html) format and is accessed using a third-party 
[library](https://github.com/donjajo/php-jsondb) that treats this data as traditional database storage, which allows for 
a reasonably standard interface between the data and the repositories.

### Workflow

#### Basket Instantiation

When creating a new instance of the `Basket` class, an instance of the `RepositoryManager` class must be passed to it. 
The `RepositoryManager` class itself accepts two arguments:
- The repository configuration
- In instance of the `DatabaseInterface` class

The repository configuration describes the repositories that should be loaded. This is found in the `./config/config.php` 
file. An example of a repository configuration looks like the following:

```php
'products' => [
    'repository' => \App\Repository\Products::class,
    'entity' => \App\Entity\Product::class,
    'file' => 'products.json'
]
```

Where `repository` refers to the repository class, `entity` refers to the entity class, and `file` refers to the JSON 
file containing the data, which would be analogous to a `table` in a traditional database.

The `DatabaseInterface` describes a set of functions that would be needed for the repository class to construct a query. 
A `Database` class, that acts as a proxy to the third party library, is used to provide a more generic interface to the 
database.

#### Adding a product

The Basket class contains a simple array where any product that's added is stored. When adding a product to the basket, 
the product code is passed, using the Product Repository the product is retrieved from the database and stored in the 
array.

Products are described in the JSON data as follows

```json
{
    "code": "B01",
    "name": "Blue Widget",
    "price": 7.95
}
```

#### Calculating the basket total

All products stored in the array are iterated through and their values are reduced to a single basket sub-total. 

The same array is then passed to a function in the Offers Repository that does the following:

- Retrieves all offers from the database,
- Iterates through these offers and determines whether any are applicable to the provided array of products.
- For all applicable offers, the value is determined and added to a total reduction value.

An offer can either have a percentage value or a fixed value applied, which is configured in the offer, and this is 
taken into account in when determining the value of the offer when applying it to the applicable products. 

Once the total reduction has been calculated it is deducted from the sub-total.

Offers are described in the JSON data as follows

```json
{
    "id": 1,
    "products": ["R01", "R01"],
    "adjustment": 0.75,
    "adjustmentType": "percentage"
}
```

Finally, the delivery rate is calculated based on the new sub-total. Multiple delivery rates can be configured for different 
order value bands, where each delivery rate has an upper and lower limit. For whichever band the sub-total value fits into 
the corresponding delivery rate is applied.

Delivery rates are described in the JSON data as follows

```json
{
    "id": 1,
    "lower": 0,
    "upper": 49.99,
    "cost": 4.95
}
```

### Project Structure

The project has the following directory structure

- `src` - containing the main application code
- `config` - containing application configuration data
- `data` - containing entity data in JSON format
- `tests` - containing unit tests for the application

An `index.php` file in the project root directory is provided that can be executed to see the basket in action.

### Use

```php
require __DIR__ . '/vendor/autoload.php';

use App\Services\Config\Config;
use App\Services\Database\Database;
use App\Repository\RepositoryManager;
use App\Services\Basket\Basket;

define('APPPATH', __DIR__);

$config = Config::getConfig();

// instantiate the basket
$basket = new Basket(
    new RepositoryManager(
        $config['repositories'],
        new Database( $config['data_dir'] )
    )
);

// add a product
$basket->addProduct($productCode);

// get the basket total
$total = $basket->getTotal();

// clear the basket 
$basket->clearBasket();
```

### Running

A `Dockerfile` and `docker-compose.yaml` file are provided to allow for the project to be run. To do so, run the following 
from the project root

```
docker-compose up
```

[Docker](https://www.docker.com/) and [Docker Compose](https://docs.docker.com/compose/) are both required for this to work.

### Potential Improvement

The following are areas that could potentially be added or improved upon in future iterations:

- Error and exception handling
- Value based offers
- Offer priority, if multiple offers apply to a set of products in the basket
- Product based delivery rates
- Caching of products added to the basket

