<?php
namespace OCA\Usage_Amount\Controller;

use OCP\IRequest;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCA\Usage_Amount\Services\UsageService;

class UsageController extends Controller {
    private $usageService;
    private $user_id;

    public function __construct($AppName, IRequest $request,UsageService $usageService,$userId){
        parent::__construct($AppName, $request);
        $this->usageService = $usageService;
        $this->user_id = $userId; 
    }

    /**
    @NoAdminRequired
    */

    public function index() {
        $data = $this->usageService->getUserUsage($this->user_id,'day',true);
        return new DataResponse($data);
    }
    /**
    @NoAdminRequired
    - @NoCSRFRequired
    */
    public function exportCSV($type){
        $datas = $this->usageService->getUserUsage($this->user_id,$type);
        $content = "使用者,使用者用量(MB),日期\n";
        foreach($datas as $data){
            $content = $content . $data['user_id'] ."," . round($data['user_usage']/1024,2) . "," . $data['created_at'] . "\n";
        }
        $filename = date("YmdHis")."_". $this->user_id .".csv";
        header("Content-type: text/x-csv");
        header("Content-Disposition: attachment; filename=$filename");
        echo "\xEF\xBB\xBF";
        echo $content;
        exit;         
    }
    /**
    @NoAdminRequired
    - @NoCSRFRequired
    */
    public function crontest(){
        $crontime = strtotime(date("Y-m-d ")."23:40:00");
        $now = strtotime(date("Y-m-d H:i:s"));
        if ($now >= $crontime){
            if ($this->usageService->checkStatistics()){
                $sqlDatas = $this->usageService->getAllUserUsage();
                $datas = $this->usageService->usageformat($sqlDatas);
                $this->usageService->insetIntoAllUserUsage($datas);
            }else{
                return new DataResponse(['status' => 2]);
            }
        }else{
            return new DataResponse(['status' => 1]);
        }
        return new DataResponse(['status' => 0]);        
    }
    
    /**
     * @NoAdminRequired
    *
    * @param int $id
    */
    public function show($id) {
        // empty for now
    }

    /**
     * @NoAdminRequired
    *
    * @param string $title
    * @param string $content
    */
    public function create() {
        // empty for now
    }

    /**
     * @NoAdminRequired
    *
    * @param int $id
    * @param string $title
    * @param string $content
    */
    public function update($id, $title, $content) {
        // empty for now
    }

    /**
     * @NoAdminRequired
    *
    * @param int $id
    */
    public function destroy($id) {
        // empty for now
    }

}