<?php

namespace App\Providers;

use Hybridauth\Provider\AbstractProvider;
use Hybridauth\Data;
use Hybridauth\User;

class VKontakte extends AbstractProvider
{
    protected $apiBaseUrl = 'https://api.vk.com/method/';
    protected $authorizeUrl = 'https://oauth.vk.com/authorize';
    protected $accessTokenUrl = 'https://oauth.vk.com/access_token';

    protected function initialize()
    {
        parent::initialize();
        $this->apiRequestParameters['v'] = '5.131'; // Версия API ВКонтакте
    }

    public function getUserProfile()
    {
        $userId = $this->accessToken->get('user_id');

        $response = $this->apiRequest('users.get', [
            'user_ids' => $userId,
            'fields' => 'photo_200,email',
        ]);

        $data = new Data\Collection($response);

        if (!$data->exists('response')) {
            throw new \Exception('User profile request failed!');
        }

        $profile = $data->get('response')[0];

        $userProfile = new User\Profile();
        $userProfile->identifier = $profile['id'];
        $userProfile->displayName = $profile['first_name'] . ' ' . $profile['last_name'];
        $userProfile->email = $profile['email'] ?? null;
        $userProfile->photoURL = $profile['photo_200'] ?? null;

        return $userProfile;
    }
}