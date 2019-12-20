<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 5:13 PM
 */

namespace App\ELMOHRSuite\Core\InteractiveManager;


use App\ELMOHRSuite\Apps\LeaveApp\InteractiveActions\BlockActions\LeaveApprovedAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AbstractInteractiveManager
{

    protected $payload = [];
    /**
     * @var array $blockActions
     */
    protected $blockActions = [

    ];

    /**
     * @var array $blockActions
     */
    protected $viewSubmissions = [

    ];

    /**
     * @var array $blockActions
     */
    protected $dialogSubmissions = [

    ];

    public function __construct(array $payload)
    {
        $this-> payload = $payload;
    }

    /**
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function execute()
    {
        try {

            return $this->process();
        } catch (\Exception $e) {
            return JsonResponse::create([], 200);
        }
    }

    public  function process(){
        $class = null;
        switch ($this->payload['type']) {
            case 'view_submission':
//                Log::info($this->payload);
                if (isset($this->viewSubmissions[$this->payload['view']['private_metadata']])) {
                    Log::info('1111');
                    $class = $this->resolve($this->viewSubmissions[$this->payload['view']['private_metadata']]);
                }
                break;
            case 'block_actions':
                $class = $this->resolve("App\ELMOHRSuite\Apps\LeaveApp\InteractiveActions\BlockActions".'\\' .$this->payload['actions'][0]['action_id']);

                break;
            case'dialog_submission':
                if (isset($this->dialogSubmissions[$this->payload['state']])) {
                    $class = $this->resolve($this->dialogSubmissions[$this->payload['state']]);
                }
                break;
            default:
                break;
        }

        if ($class instanceof AbstractInteractive) {
            return $class->process();
        } else {
            return JsonResponse::create([], 200);
        }

    }


    /**
     * @param $className
     * @return AbstractInteractive
     */
    private function resolve($className)
    {
        $class = new $className($this->payload);
        return $class;
    }


}
