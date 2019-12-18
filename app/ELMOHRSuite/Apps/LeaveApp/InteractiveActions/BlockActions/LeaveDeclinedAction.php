<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/7/31
 * Time: 7:34 PM
 */

namespace App\ELMOHRSuite\Apps\LeaveApp\InteractiveActions\BlockActions;

use App\ELMOHRSuite\Core\InteractiveManager\AbstractInteractive;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Response;

class LeaveDeclinedAction extends AbstractInteractive
{

    /**
     * @return bool
     */
    public function validate()
    {
        return true;
    }

    /**
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle()
    {

        // todo

        $data = json_decode($this->payload['actions'][0]['value'], true);
        $data['approvalStatus'] = 2;
        $data  = base64_encode(json_encode($data));

        $client = new Client(['verify' => false ]);
        $res = $client->get('https://dev.local.elmodev.com/api/v0/form/entity-choice?data='. $data);

        return Response::json(['1234'], 200);
    }
}
