<?php 

namespace OCA\Usage_Amount\Services;

use OCP\IDBConnection;

class UsageService {
    private $db;
    public function __construct(IDBConnection $db){
        $this->db = $db;
    }
    public function getAllUserUsage(){
        // get user usage
        $sql = "SELECT m.user_id, fc.size/1024 as size 
                FROM *PREFIX*mounts m, *PREFIX*filecache fc, *PREFIX*storages s 
                WHERE m.mount_point = concat('/', m.user_id, '/') 
                AND s.numeric_id = m.storage_id 
                AND fc.storage = m.storage_id 
                AND fc.path = 'files'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $now = [];
        while($row = $stmt->fetch()){
            $now[] = $row;
        }
        $stmt->closeCursor();

        // get user  history usage
        $sql = "SELECT u.user_id,u.total as size,created_at
                FROM *PREFIX*usage_amounts u, (SELECT user_id,max(created_at) histroy_time 
                                               FROM *PREFIX*usage_amounts 
                                               GROUP BY user_id) u1
                WHERE u.created_at = u1.histroy_time AND u.user_id = u1.user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $history = [];
        while($row = $stmt->fetch()){
            $history[] = $row;
        }

        $stmt->closeCursor();
        return [
            'now' => $now,
            'history' => $history
        ];
    }
    public function insetIntoAllUserUsage($datas){
        $sqls = [];
        $sql = "INSERT INTO *PREFIX*usage_amounts (user_id,user_usage,total,created_at) VALUES";
        $created_at = strtotime(date('Y-m-d H:i:s'));
        $count = 0;
        foreach($datas['now'] as $user_id => $total){
            $count ++;
            $usage =  $datas['history'][$user_id] ? round($total) - $datas['history'][$user_id] : $total;
            $sql = $sql . "('" . $user_id . "'," . $usage . "," . round($total) . ",". $created_at . ")";
            if ($count == 900 || $count == count($datas['now'])){
                $sqls[] = $sql;
                $sql = "INSERT INTO *PREFIX*_usage_amounts (amount,user_id,created_at) VALUES";
                $count = 0;
            }else if ($count !== count($datas['now'])){
                $sql = $sql . ",";
            }
        }

        foreach($sqls as $sqlstring){
            $stmt = $this->db->prepare($sqlstring);
            $stmt->execute();
            $stmt->closeCursor();  
        }
        return true;
    }
    public function checkStatistics(){
        $queryday = [strtotime(date("Y-m-d ")."00:00:00"),strtotime(date("Y-m-d ")."23:59:59")];
        $sql = "SELECT count(*) as count_data
                FROM *PREFIX*usage_amounts
                WHERE created_at >= $queryday[0] AND created_at <= $queryday[1]";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();
        $stmt->closeCursor();
        if ((int)$row['count_data'] == 0){
            return true;
        }
        return false;                 
    } 
    public function usageformat($datas){
        $result = [];
        foreach($datas as $key => $data){
            foreach($data as $row){
                $result[$key][$row['user_id']] = $row['size'];
            }
        }
        return $result;
    }
    public function getUserUsage($user_id,$type,$webpage=false){
        if ($type == "day"){
            $sql = "SELECT a.display_name, a.email,m.user_usage, m.total, DATE_FORMAT(FROM_UNIXTIME(`created_at`), '%Y-%m-%d') as created_at
                    FROM oc_usage_amounts m, oc_accounts a
                    WHERE m.user_id = a.user_id";
            if ($webpage){
                $sql = $sql . ' ORDER BY m.id DESC LIMIT 15';
            }
        }elseif ($type == "month"){
            $sql = "SELECT user_id, sum(user_usage) as user_usage, DATE_FORMAT(FROM_UNIXTIME(`created_at`), '%Y-%m') as created_at
                    FROM *PREFIX*usage_amounts
                    WHERE user_id = '$user_id'
                    GROUP BY user_id,DATE_FORMAT(FROM_UNIXTIME(`created_at`), '%Y-%m')";
            if ($webpage){
                $sql = $sql . ' ORDER BY created_at DESC LIMIT 15';
            }
        }else{
            return "error";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $rows = [];
        while($row = $stmt->fetch()){
            $rows[] = $row;
        }
        $stmt->closeCursor();
        return $rows;                        
    }
}
