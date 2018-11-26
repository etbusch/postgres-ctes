# Laravel PostgresSQL CTE Driver Extenstion


This package adds Common Table Expression support to the base PostgresSQL driver in Laravel 5.4+ and Lumen 5.

## Install

#### Via Composer and local git repository:

Clone the repository to a local directory on your server/workstation.

Add the following to your app's composer.json:

``` json
"repositories": [{
    "type": "vcs",
    "url": "path/to/postgres-ctes"
}]
```
Then run:

``` bash
$ composer require etbusch\postgres-ctes
```

#### Via Composer and Packagist

Simply run:

``` bash
$ composer require etbusch\postgres-ctes
```

Once composer has been updated and the package has been installed, the service provider will need to be loaded.


For Laravel 5.4+, open `config/app.php` and add following line to the providers array:
``` php
Etbusch\PostgresCtes\PostgresCtesServiceProvider::class,
```

For Lumen 5, open `bootstrap/app.php` and add following line under the "Register Service Providers" section:
``` php
$app->register(Etbusch\PostgresCtes\PostgresCtesServiceProvider::class);
```

## Usage

Once you include the service provider Laravel/Lumen will start using the custom grammar and the  
``` QueryBuilder::cte($as, Closure $query) ``` method will be available for use in your Models. The CTE is named with the first argument and the query itself is represented in the second argument as a closure or a string of raw SQL.

### Basic Example

``` php
$query = new FooModel();
$data = $query->cte('cte_query_name', function ($query) {
    $query->select('foo')
    ->from('bar')
    ->selectRaw('count(*) as total')
    ->selectRaw('count(case when foo_bar = 1 then 1 end) AS Low')
    ->selectRaw('count(case when foo_bar = 2 then 1 end) AS Medium')
    ->selectRaw('count(case when foo_bar = 3 then 1 end) AS High')
    ->groupBy('foo');
  })
  ->from('cte_query_name')
  ->limit(20)
  ->get();
```

### Query Scope Example

``` php
// Model Scope Method
public function scopeCteQueryName($query){
    $cte = DB::table('foo')
    ->select('foo')
    ->from('bar')
    ->selectRaw('count(*) as total')
    ->selectRaw('count(case when foo_bar = 1 then 1 end) AS Low')
    ->selectRaw('count(case when foo_bar = 2 then 1 end) AS Medium')
    ->selectRaw('count(case when foo_bar = 3 then 1 end) AS High')
    ->groupBy('foo');

    $query->cte('cte_query_name',$cte);
    return $query;
}

// Usage
$query = new FooModel();
$data = $query->CteQueryName()
  ->from('cte_query_name')
  ->limit(10)
  ->get();
```


