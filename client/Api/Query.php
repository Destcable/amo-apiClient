<?php

namespace Client\Api;

class Query
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function post(string $path, array $body): array
    {     
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $this->getURL($this->config['subdomain'], $path),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->getAccessToken(),
                'Content-Type: application/json'
            ],
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($body),
        ]);

        return $this->fetchCurlResponseDetails($curl);
    }

    public function get(string $path): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [ 
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $this->getURL($this->config['subdomain'], $path),
            CURLOPT_HTTPHEADER =>  [
                'Authorization: Bearer ' . $this->getAccessToken(),
                'Content-Type: application/json'
            ],
        ]);

        return $this->fetchCurlResponseDetails($curl);
    }

    public function patch(string $path, array $body): array
    { 
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_URL => $this->getURL($this->config['subdomain'], $path),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->getAccessToken(),
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode($body)  
        ]);

        return $this->fetchCurlResponseDetails($curl);
    }

    public function refreshAccessToken(string $path, array $body, string $code = null): array
    { 
        $curl = curl_init();

        if ( isset($code) ) { 
            $body ['code']= $code;
            $body ['grant_type']= 'authorization_code';
        }

        unset($body['subdomain']);

        curl_setopt_array($curl, [ 
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $this->getURL($this->config['subdomain'], $path),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($body),
        ]);

        return $this->fetchCurlResponseDetails($curl);
    }

    private function fetchCurlResponseDetails($curl): array
    {
        return [
            "data" => json_decode(curl_exec($curl), true),
            "status" => curl_getinfo($curl, CURLINFO_HTTP_CODE)
        ];
    }

    private function getAccessToken(): string
    { 
        $fileData = file_get_contents('storage/' . $this->config['client_id'] . '.json');
        $fileData = json_decode($fileData);
        $this->tokenExpired($fileData);

        return $fileData->access_token;
    }

    private function tokenExpired($fileData)
    { 
        if (abs(time() - $fileData->date) < $fileData->expires_in === false) { 
            $this->updateTokenAndSave();
        } 
    }

    private function updateTokenAndSave()
    { 
        $fileData = file_get_contents('storage/' . $this->config['client_id'] . '.json');
        $fileData = json_decode($fileData);

        $response = $this->refreshAccessToken('/oauth2/access_token', [ 
            'client_id' => $fileData->client_id,
            'refresh_token' => $fileData->refresh_token,
            'grant_type' => 'refresh_token'
        ]);

        if ($response['status'] == 200) { 
            $response['data']['date'] = time();
            $response['data']['client_id'] = $fileData->client_id;
            file_put_contents('storage/' . $this->config['client_id'] . '.json', json_encode($response['data']));
        }

        return $response;
    }

    private function getURL(string $subdomain, string $endpoint): string
    { 
        return "https://{$subdomain}.amocrm.ru" . $endpoint;
    }
}
