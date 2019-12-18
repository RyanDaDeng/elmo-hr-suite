<?php

namespace App\ELMOHRSuite\Apps\AwardApp\InteractiveActions;


use App\ELMOHRSuite\Apps\AwardApp\InteractiveActions\ViewActions\AwardFormSubmission;
use App\ELMOHRSuite\Core\InteractiveManager\AbstractInteractiveManager;

class AwardInteractiveManager extends AbstractInteractiveManager
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
        Store::AWARD_SUBMISSION_ACTION   => AwardFormSubmission::class
    ];
}