<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 8:10 PM
 */

namespace App\ELMOHRSuite\LeaveApp\InteractiveActions;


use App\ELMOHRSuite\Core\InteractiveManager\AbstractInteractiveManager;
use App\ELMOHRSuite\LeaveApp\Actions\Store;
use App\ELMOHRSuite\LeaveApp\InteractiveActions\DialogActions\LeaveFormSubmission;

class LeaveInteractiveManager extends AbstractInteractiveManager
{

    /**
     * @var array $blockActions
     */
    protected $blockActions = [

    ];

    /**
     * @deprecated
     * @var array $dialogSubmissions
     */
    protected $dialogSubmissions = [

    ];

    /**
     * @var array $dialogSubmissions
     */
    protected $viewSubmissions = [
        Store::LEAVE_SUBMISSION_ACTION   => LeaveFormSubmission::class
    ];


}