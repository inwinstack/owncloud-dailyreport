<?php
/**
 * @author Joas Schilling <coding@schilljs.com>
 * @author Jörn Friedrich Dreyer <jfd@butonic.de>
 * @author Victor Dubiniuk <dubiniuk@owncloud.com>
 *
 * @copyright Copyright (c) 2018, ownCloud GmbH
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\Usage_Amount\BackgroundJob;

use OCA\Usage_Amount\Services\UsageService;


class UsageJob extends \OC\BackgroundJob\TimedJob {

    protected $usageService;
	public function __construct(UsageService $usageService) {
        $this->usageService = $usageService;
        // Run once per 5 minutes
        $this->setInterval(60 * 5);
	}

	protected function run($argument) {
        $crontime = strtotime(date("Y-m-d ")."23:40:00");
        $now = strtotime(date("Y-m-d H:i:s"));
        if ($now >= $crontime){
            if ($this->usageService->checkStatistics()){
                $sqlDatas = $this->usageService->getAllUserUsage();
                $datas = $this->usageService->usageformat($sqlDatas);
                $this->usageService->insetIntoAllUserUsage($datas);
            }
        }
	}

}
