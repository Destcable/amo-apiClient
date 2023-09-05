```php
use Client\AmoClient;

$config = [
    'client_id' => 'client_id',
    'client_secret' => 'client_secret',
    'subdomain' => 'subdomain',
    'redirect_uri' => 'redirect_uri'
];

$client = new AmoClient($config);

$client->authByCode('code');

$company= $client->companies()->create();
$company->name = 'Название';
$company->save();
```