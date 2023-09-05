<?php
 
namespace Client;

use Client\Services\CompanyService;
use Client\Services\ContactService;
use Client\Services\LeadService;
use Client\Services\TaskService;
use Client\Api\Query;

class AmoClient
{
    private array $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function authByCode(string $code): array
    {
        $clientID = $this->config['client_id'];
        $query = new Query($this->config);

        $response = $query->refreshAccessToken('/oauth2/access_token', $this->config, $code);
        
        if ($response['status'] == 200) {
            $response['data']['client_id'] = $clientID;
            $response['data']['date'] = time();
            file_put_contents('storage/' . $clientID . '.json', json_encode($response['data']));
        }

        return $response;
    }

    public function companies()
    { 
        return new CompanyService($this->config);
    }

    public function contacts()
    { 
        return new ContactService($this->config);
    }

    public function leads()
    {
        return new LeadService($this->config);
    }

    public function tasks()
    { 
        return new TaskService($this->config);
    }
}