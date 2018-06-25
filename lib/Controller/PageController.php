<?php
namespace OCA\Usage_Amount\Controller;

use OCP\AppFramework\{
    Controller,
    Http\TemplateResponse
};
use OCP\IRequest;

//test
use OCA\Usage_Amount\Services\UsageService;

/**
 - Define a new page controller
 */
class PageController extends Controller {

	public function __construct($AppName, IRequest $request) {
        parent::__construct($AppName, $request);
        // Run once per 30 minutes
		// $this->setInterval(60 * 30);
	}


    /**
     - @NoCSRFRequired
     - @NoAdminRequired     
     */
    public function index() {
        // date_default_timezone_set("Asia/Taipei");
        // echo date('Y-m-d H:i:s');/
        // $usageData = $this->usageService->getAllUserUsage();
        // return [$usageData];
        return new TemplateResponse('usage_amount','main');
    }
}