<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 8:10 PM
 */

namespace App\ELMOHRSuite\Apps\LeaveApp\InteractiveActions;


use App\ELMOHRSuite\Apps\LeaveApp\InteractiveActions\BlockActions\LeaveApprovedAction;
use App\ELMOHRSuite\Apps\LeaveApp\InteractiveActions\BlockActions\LeaveDeclinedAction;
use App\ELMOHRSuite\Apps\LeaveApp\InteractiveActions\ViewActions\LeaveFormSubmission;
use App\ELMOHRSuite\Core\InteractiveManager\AbstractInteractiveManager;

class LeaveInteractiveManager extends AbstractInteractiveManager
{

    /**
     * @var array $blockActions
     */
    protected $blockActions = [
        Store::LEAVE_APPROVED_ACTION => LeaveApprovedAction::class,
        Store::LEAVE_DECLINED_ACTION => LeaveDeclinedAction::class
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
        Store::LEAVE_SUBMISSION_ACTION => LeaveFormSubmission::class
    ];


}