<?php


namespace App\ELMOHRSuite\Apps\LeaveApp\Controllers;

use App\ELMOHRSuite\Apps\LeaveApp\Commands\LeaveGroupCommand;
use App\ELMOHRSuite\Apps\LeaveApp\InteractiveActions\LeaveInteractiveManager;
use App\Http\Controllers\Controller;
use Google_Client;
use Google_Service_Calendar;
use Illuminate\Http\Request;

class SlackController extends Controller
{

    /**
     * @return string
     */
    public function hello()
    {
        return 'Hello World';
    }


    /**
     * @param Request $request
     * @return mixed|string
     */
    public function command(Request $request)
    {

        return (new LeaveGroupCommand($request->all()))->execute();
    }


    /**
     * @param Request $request
     * @return LeaveInteractiveManager|array
     */
    public function interactive(Request $request)
    {

        return (new LeaveInteractiveManager(json_decode($request->all()['payload'], 1)))->process();
    }

//    function getClient()
//    {
//        $client = new Google_Client();
//        $client->setApplicationName('Google Calendar API PHP Quickstart');
//        $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
//        $client->setAccessToken()
//        $client->setAuthConfig('credentials.json');
//        $client->setAccessType('offline');
//        $client->setPrompt('select_account consent');
//
//        // Load previously authorized token from a file, if it exists.
//        // The file token.json stores the user's access and refresh tokens, and is
//        // created automatically when the authorization flow completes for the first
//        // time.
//        $tokenPath = 'token.json';
//        if (file_exists($tokenPath)) {
//            $accessToken = json_decode(file_get_contents($tokenPath), true);
//            $client->setAccessToken($accessToken);
//        }
//
//        // If there is no previous token or it's expired.
//        if ($client->isAccessTokenExpired()) {
//            // Refresh the token if possible, else fetch a new one.
//            if ($client->getRefreshToken()) {
//                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
//            } else {
//                // Request authorization from the user.
//                $authUrl = $client->createAuthUrl();
//                printf("Open the following link in your browser:\n%s\n", $authUrl);
//                print 'Enter verification code: ';
//                $authCode = trim(fgets(STDIN));
//
//                // Exchange authorization code for an access token.
//                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
//                $client->setAccessToken($accessToken);
//
//                // Check to see if there was an error.
//                if (array_key_exists('error', $accessToken)) {
//                    throw new Exception(join(', ', $accessToken));
//                }
//            }
//            // Save the token to a file.
//            if (!file_exists(dirname($tokenPath))) {
//                mkdir(dirname($tokenPath), 0700, true);
//            }
//            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
//        }
//        return $client;
//    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calendarInsert(Request $request)
    {
        $response = [
            'hello' => 'world'
        ];

        return response()->json($response);
    }
}