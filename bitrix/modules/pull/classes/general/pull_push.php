<?
IncludeModuleLangFile(__FILE__);
require_once('pushservices/services_descriptions.php');


/**
 * Class CPullPush
 * @deprecated use Bitrix\Pull\Model\PushTable
 * @see \Bitrix\Pull\Model\PushTable
 */
class CPullPush
{
	/**
	 * @deprecated use Bitrix\Pull\Model\PushTable::getList
	 * @see Bitrix\Pull\Model\PushTable::getList
	 * @param array $arOrder
	 * @param array $arFilter
	 * @param array $arSelect
	 * @param array $arNavStartParams
	 * @return \Bitrix\Main\DB\Result
	 * @throws \Bitrix\Main\ArgumentException
	 */
	public static function GetList($arOrder = array(), $arFilter = array(), $arSelect = array(), $arNavStartParams = Array())
	{
		$params = array(
			"filter" => $arFilter,
			"order" => $arOrder
		);
		if (!empty($arSelect))
		{
			$params["select"] = $arSelect;
		}

		if (is_array($arNavStartParams) && intval($arNavStartParams["nTopCount"]) > 0)
		{
			$params["limit"] = intval($arNavStartParams["nTopCount"]);
		}

		$res = \Bitrix\Pull\Model\PushTable::getList($params);

		return $res;
	}


	/**
	 * @deprecated use Bitrix\Pull\Model\PushTable::add
	 * @see Bitrix\Pull\Model\PushTable::add
	 * @param array $arFields
	 * @return int
	 * @throws Exception
	 */
	public static function Add($arFields = Array())
	{
		$result = \Bitrix\Pull\Model\PushTable::add($arFields);

		return $result->getId();
	}

	public static function getUniqueHash($user_id, $app_id)
	{
		return md5($user_id . $app_id);
	}

	/**
	 * @deprecated use Bitrix\Pull\Model\PushTable::update
	 * @see Bitrix\Pull\Model\PushTable::update
	 * @param $ID
	 * @param array $arFields
	 * @return int
	 * @throws Exception
	 */
	public static function Update($ID, $arFields = Array())
	{
		$result = \Bitrix\Pull\Model\PushTable::update($ID, $arFields);
		return $result->getId();
	}

	/**
	 * @deprecated use Bitrix\Pull\Model\PushTable::delete
	 * @see Bitrix\Pull\Model\PushTable::delete
	 * @param bool $ID
	 * @return bool
	 * @throws Exception
	 */
	public static function Delete($ID = false)
	{
		$result = \Bitrix\Pull\Model\PushTable::delete(intval($ID));
		return $result->isSuccess();
	}

	public static function cleanTokens()
	{
		global $DB;

		/**
		 * @var $DB CAllDatabase
		 */
		$killTime = ConvertTimeStamp(getmicrotime() - 24 * 3600 * 14, "FULL");
		$sqlString = "DELETE FROM b_pull_push WHERE DATE_AUTH < " . $DB->CharToDateFunction($killTime);

		$DB->Query($sqlString, false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);

		return "CPullPush::cleanTokens();";
	}
}


class CPushManager
{
	const SEND_IMMEDIATELY = 'IMMEDIATELY';
	const SEND_IMMEDIATELY_SILENT = 'IMMEDIATELY_SILENT';
	const SEND_DEFERRED = 'DEFERRED';
	const SEND_SKIP = 'SKIP';
	const RECORD_NOT_FOUND = 'NOT_FOUND';

	public static $pushServices = false;
	private static $remoteProviderUrl = "https://cloud-messaging.bitrix24.com/send/";

	public function __construct()
	{
		if (!is_array(self::$pushServices))
		{
			self::$pushServices = array();

			foreach (GetModuleEvents("pull", "OnPushServicesBuildList", true) as $arEvent)
			{
				$res = ExecuteModuleEventEx($arEvent);
				if (is_array($res))
				{
					if (!is_array($res[0]))
					{
						$res = array($res);
					}
					foreach ($res as $serv)
						self::$pushServices[$serv["ID"]] = $serv;
				}
			}
		}
	}

	public static function DeleteFromQueueByTag($userId, $tag, $appId = 'Bitrix24')
	{
		global $DB;
		if (strlen($tag) <= 0 || intval($userId) == 0)
		{
			return false;
		}

		$strSql = "DELETE FROM b_pull_push_queue WHERE USER_ID = " . intval($userId) . " AND TAG = '" . $DB->ForSQL($tag) . "'";
		$DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);

		\Bitrix\Pull\Push::add($userId, Array(
			'module_id' => 'pull',
			'push' => Array(
				'advanced_params' => Array(
					"notificationsToCancel" => array($tag),
				),
				'send_immediately' => 'Y',
				'app_id' => $appId
			)
		));

		return true;
	}

	public function AddQueue($arParams)
	{
		if (!CPullOptions::GetPushStatus())
		{
			return false;
		}

		global $DB;

		if (is_array($arParams['USER_ID']))
		{
			foreach ($arParams['USER_ID'] as $key => $userId)
			{
				$userId = intval($userId);
				if ($userId > 0)
				{
					$arFields['USER_ID'][$userId] = $userId;
				}
			}
			if (empty($arFields['USER_ID']))
			{
				return false;
			}
		}
		else
		{
			if (isset($arParams['USER_ID']) && intval($arParams['USER_ID']) > 0)
			{
				$userId = intval($arParams['USER_ID']);
				$arFields['USER_ID'][$userId] = $userId;
			}
			else
			{
				return false;
			}
		}

		$arFields['SKIP_USERS'] = array();
		if (is_array($arParams['SKIP_USERS']))
		{
			foreach ($arParams['SKIP_USERS'] as $key => $userId)
			{
				$userId = intval($userId);
				if ($userId > 0)
				{
					$arFields['SKIP_USERS'][] = $userId;
				}
			}
		}

		if (isset($arParams['MESSAGE']) && strlen(trim($arParams['MESSAGE'])) > 0)
		{
			$arFields['MESSAGE'] = str_replace(Array("\r\n", "\n\r", "\n", "\r"), " ", trim($arParams['MESSAGE']));
		}

		$arFields['TAG'] = '';
		if (isset($arParams['TAG']) && strlen(trim($arParams['TAG'])) > 0 && strlen(trim($arParams['TAG'])) <= 255)
		{
			$arFields['TAG'] = trim($arParams['TAG']);
		}

		$arFields['SUB_TAG'] = '';
		if (isset($arParams['SUB_TAG']) && strlen(trim($arParams['SUB_TAG'])) > 0 && strlen(trim($arParams['SUB_TAG'])) <= 255)
		{
			$arFields['SUB_TAG'] = trim($arParams['SUB_TAG']);
		}

		$arFields['BADGE'] = -1;
		if (isset($arParams['BADGE']) && $arParams['BADGE'] != '' && intval($arParams['BADGE']) >= 0)
		{
			$arFields['BADGE'] = intval($arParams['BADGE']);
		}

		$arFields['PARAMS'] = '';
		if (isset($arParams['PARAMS']))
		{
			if (is_array($arParams['PARAMS']) || strlen(trim($arParams['PARAMS'])) > 0)
			{
				$arFields['PARAMS'] = $arParams['PARAMS'];
			}
		}

		$arFields['ADVANCED_PARAMS'] = Array();
		if (isset($arParams['ADVANCED_PARAMS']) && is_array($arParams['ADVANCED_PARAMS']))
		{
			$arFields['ADVANCED_PARAMS'] = $arParams['ADVANCED_PARAMS'];
		}
		if (!isset($arParams['ADVANCED_PARAMS']['id']) && strlen($arFields['SUB_TAG']) > 0)
		{
			$arFields['ADVANCED_PARAMS']['id'] = $arFields['SUB_TAG'];
		}
		if (!isset($arFields['ADVANCED_PARAMS']['extra']['server_time']))
		{
			$arFields['ADVANCED_PARAMS']['extra']['server_time'] = date('c');
		}
		if (!isset($arFields['ADVANCED_PARAMS']['extra']['server_time_unix']))
		{
			$arFields['ADVANCED_PARAMS']['extra']['server_time_unix'] = microtime(true);
		}

		$arFields['EXPIRY'] = 0;
		if (isset($arParams['EXPIRY']) && intval($arParams['EXPIRY']) > 0)
		{
			$arFields['EXPIRY'] = intval($arParams['EXPIRY']);
		}

		if (strlen($arParams['SOUND']) > 0)
		{
			$arFields['SOUND'] = $arParams['SOUND'];
		}

		$arFields['APP_ID'] = (strlen($arParams['APP_ID']) > 0) ? $arParams['APP_ID'] : "Bitrix24";

		$groupMode = Array(
			self::SEND_IMMEDIATELY => Array(),
			self::SEND_IMMEDIATELY_SILENT => Array(),
			self::SEND_DEFERRED => Array(),
			self::SEND_SKIP => Array(),
		);

		$devices = Array();

		$info = self::GetDeviceInfo($arFields['USER_ID'], $arFields['APP_ID']);
		foreach ($info as $userId => $params)
		{
			if (in_array($userId, $arFields['SKIP_USERS']))
			{
				$params['mode'] = self::SEND_SKIP;
			}
			else if ($params['mode'] == self::SEND_DEFERRED && isset($arParams['SEND_IMMEDIATELY']) && $arParams['SEND_IMMEDIATELY'] == 'Y')
			{
				$params['mode'] = self::SEND_IMMEDIATELY;
			}
			elseif (
				in_array($params['mode'], Array(self::SEND_IMMEDIATELY, self::SEND_IMMEDIATELY_SILENT))
				&& isset($arParams['SEND_DEFERRED']) && $arParams['SEND_DEFERRED'] == 'Y'
			)
			{
				$params['mode'] = self::SEND_DEFERRED;
			}

			if ($params['mode'] != self::RECORD_NOT_FOUND)
			{
				foreach(GetModuleEvents("pull", "OnBeforeSendPush", true) as $arEvent)
				{
					$resultEvent = ExecuteModuleEventEx($arEvent, Array($userId, $params['mode'], $arFields));
					if ($resultEvent)
					{
						$resultEvent = strtoupper($resultEvent);
						if (in_array($resultEvent, Array(
							self::SEND_IMMEDIATELY,
							self::SEND_IMMEDIATELY_SILENT,
							self::SEND_DEFERRED,
							self::SEND_SKIP
						)))
						{
							$params['mode'] = $resultEvent;
						}
					}
				}
			}

			if (isset($groupMode[$params['mode']]))
			{
				$groupMode[$params['mode']][$userId] = $userId;
			}
			if (
				in_array($params['mode'], Array(self::SEND_IMMEDIATELY, self::SEND_IMMEDIATELY_SILENT))
				&& !empty($params['device'])
				&& !(isset($arParams['SEND_IMMEDIATELY']) && $arParams['SEND_IMMEDIATELY'] == 'Y')
			)
			{
				$devices = array_merge($devices, $params['device']);
			}
		}

		$pushImmediately = Array();
		foreach ($groupMode as $type => $users)
		{
			foreach ($users as $userId)
			{
				$pushImmediately[] = self::prepareSend($userId, $arFields, $type);
			}
		}
		if (!empty($pushImmediately))
		{
			$CPushManager = new CPushManager();
			$CPushManager->SendMessage($pushImmediately, $devices);
		}

		foreach ($groupMode[self::SEND_DEFERRED] as $userId)
		{
			$arAdd = Array(
				'USER_ID' => $userId,
				'TAG' => $arFields['TAG'],
				'SUB_TAG' => $arFields['SUB_TAG'],
				'~DATE_CREATE' => $DB->CurrentTimeFunction()
			);

			if (strlen($arFields['MESSAGE']) > 0)
			{
				$arAdd['MESSAGE'] = $arFields['MESSAGE'];
			}
			if (is_array($arFields['ADVANCED_PARAMS']))
			{
				$arAdd['ADVANCED_PARAMS'] = Bitrix\Main\Web\Json::encode($arFields['ADVANCED_PARAMS']);
			}
			if (is_array($arFields['PARAMS']))
			{
				$arAdd['PARAMS'] = Bitrix\Main\Web\Json::encode($arFields['PARAMS']);
			}
			else
			{
				if (strlen($arFields['PARAMS']) > 0)
				{
					$arAdd['PARAMS'] = $arFields['PARAMS'];
				}
			}

			$arAdd['APP_ID'] = $arFields['APP_ID'];

			$DB->Add("b_pull_push_queue", $arAdd, Array("MESSAGE", "PARAMS", "ADVANCED_PARAMS"));

			CAgent::AddAgent("CPushManager::SendAgent();", "pull", "N", 30, "", "Y", ConvertTimeStamp(time() + CTimeZone::GetOffset() + 30, "FULL"), 100, false, false);
		}

		return true;
	}

	private static function prepareSend($userId, $fields, $type = self::SEND_IMMEDIATELY)
	{
		$result = Array(
			'USER_ID' => $userId,
		);

		if ($type != self::SEND_DEFERRED)
		{
			if (is_array($fields['PARAMS']))
			{
				if (isset($fields['PARAMS']['CATEGORY']))
				{
					$result['CATEGORY'] = $fields['PARAMS']['CATEGORY'];
					unset($fields['PARAMS']['CATEGORY']);
				}
				$result['PARAMS'] = Bitrix\Main\Web\Json::encode($fields['PARAMS']);
			}
			elseif (strlen($fields['PARAMS']) > 0)
			{
				$result['PARAMS'] = $fields['PARAMS'];
			}

			if (strlen($fields['MESSAGE']) > 0)
			{
				$result['MESSAGE'] = $fields['MESSAGE'];
			}

			if ($type == self::SEND_IMMEDIATELY_SILENT)
			{
				$result['SOUND'] = 'silence.aif';
			}
			else if (strlen($fields['SOUND']) > 0)
			{
				$result['SOUND'] = $fields['SOUND'];
			}

			if (count($fields['ADVANCED_PARAMS']) > 0)
			{
				$result['ADVANCED_PARAMS'] = $fields['ADVANCED_PARAMS'];
			}
		}

		if ($type == self::SEND_SKIP)
		{
			unset($result['MESSAGE']);
			unset($result['ADVANCED_PARAMS']['senderName']);
			unset($result['ADVANCED_PARAMS']['senderMessage']);
		}

		if (strlen($fields['EXPIRY']) > 0)
		{
			$result['EXPIRY'] = $fields['EXPIRY'];
		}

		if (intval($fields['BADGE']) >= 0)
		{
			$result['BADGE'] = $fields['BADGE'];
		}
		else
		{
			$result['BADGE'] = \Bitrix\Pull\MobileCounter::get($result['USER_ID']);
		}

		$result['APP_ID'] = $fields['APP_ID'];

		return $result;
	}

	public static function GetDeviceInfo($userId, $appId = 'Bitrix24')
	{
		$result = Array();
		if (!is_array($userId))
		{
			$userId = Array($userId);
		}

		foreach ($userId as $id)
		{
			$id = intval($id);
			if ($id <= 0)
			{
				continue;
			}

			$result[$id] = Array(
				'mode' => self::RECORD_NOT_FOUND,
				'device' => Array(),
			);
		}

		if (empty($result))
		{
			return false;
		}

		$imInclude = \Bitrix\Main\Loader::includeModule('im');

		$query = new \Bitrix\Main\Entity\Query(\Bitrix\Main\UserTable::getEntity());

		$sago = Bitrix\Main\Application::getConnection()->getSqlHelper()->addSecondsToDateTime('-300');
		$query->registerRuntimeField('', new \Bitrix\Main\Entity\ExpressionField('IS_ONLINE_CUSTOM', 'CASE WHEN LAST_ACTIVITY_DATE > ' . $sago . ' THEN \'Y\' ELSE \'N\' END'));
		$query->addSelect('ID')->addSelect('ACTIVE')->addSelect('EMAIL')->addSelect('IS_ONLINE_CUSTOM');

		if ($imInclude)
		{
			$query->registerRuntimeField('', new \Bitrix\Main\Entity\ReferenceField('im', 'Bitrix\Im\Model\StatusTable', array('=this.ID' => 'ref.USER_ID')));
			$query->addSelect('im.IDLE', 'IDLE')->addSelect('im.MOBILE_LAST_DATE', 'MOBILE_LAST_DATE');
		}

		$query->registerRuntimeField('', new \Bitrix\Main\Entity\ReferenceField('push', 'Bitrix\Pull\Model\PushTable', array('=this.ID' => 'ref.USER_ID')));
		$query->registerRuntimeField('', new \Bitrix\Main\Entity\ExpressionField('HAS_MOBILE', 'CASE WHEN main_user_push.USER_ID > 0 THEN \'Y\' ELSE \'N\' END'));
		$query->addSelect('HAS_MOBILE')
			->addSelect('push.APP_ID', 'APP_ID')
			->addSelect('push.UNIQUE_HASH', 'UNIQUE_HASH')
			->addSelect('push.DEVICE_TYPE', 'DEVICE_TYPE')
			->addSelect('push.DEVICE_TOKEN', 'DEVICE_TOKEN');

		$query->addFilter('=ID', array_keys($result));
		$queryResult = $query->exec();

		while ($user = $queryResult->fetch())
		{
			$uniqueHashes[] = CPullPush::getUniqueHash($user["ID"], $appId);
			$uniqueHashes[] = CPullPush::getUniqueHash($user["ID"], $appId . "_bxdev");

			if (in_array($user['UNIQUE_HASH'], $uniqueHashes) && $user['ACTIVE'] == 'Y')
			{
				$result[$user['ID']]['device'][] = Array(
					'APP_ID' => $user['APP_ID'],
					'USER_ID' => $user['ID'],
					'DEVICE_TYPE' => $user['DEVICE_TYPE'],
					'DEVICE_TOKEN' => $user['DEVICE_TOKEN'],
				);
			}
			else
			{
				continue;
			}

			if ($result[$user['ID']]['mode'] != self::RECORD_NOT_FOUND)
			{
				continue;
			}

			if ($user['HAS_MOBILE'] == 'N')
			{
				$result[$user['ID']]['mode'] = self::RECORD_NOT_FOUND;
				$result[$user['ID']]['device'] = Array();
				continue;
			}

			if (!\Bitrix\Pull\Push::getStatus($user['ID']))
			{
				$result[$user['ID']]['mode'] = self::SEND_SKIP;
				continue;
			}

			$isMobile = false;
			$isOnline = false;
			$isDesktop = false;
			$isDesktopIdle = false;

			if ($user['IS_ONLINE_CUSTOM'] == 'Y')
			{
				$isOnline = true;
			}

			if ($imInclude)
			{
				$mobileLastDate = $user['MOBILE_LAST_DATE'] instanceof \Bitrix\Main\Type\DateTime? $user['MOBILE_LAST_DATE']->getTimestamp(): 0;
				if ($mobileLastDate > 0 && $mobileLastDate + 300 > time())
				{
					$isMobile = true;
				}

				$isDesktop = CIMMessenger::CheckDesktopStatusOnline($user['ID']);
				if ($isDesktop && $isOnline && is_object($user['IDLE']))
				{
					if ($user['IDLE']->getTimestamp() > 0)
					{
						$isDesktopIdle = true;
					}
				}
			}

			$status = self::SEND_IMMEDIATELY;
			if ($isMobile)
			{
				$status = self::SEND_IMMEDIATELY;
			}
			else if ($isOnline)
			{
				if (!\Bitrix\Pull\PushSmartfilter::getStatus())
				{
					$status = self::SEND_IMMEDIATELY_SILENT;
				}
				else
				{
					$status = self::SEND_DEFERRED;
					if ($isDesktop)
					{
						$status = self::SEND_SKIP;
						if ($isDesktopIdle)
						{
							$status = self::SEND_IMMEDIATELY;
						}
					}
					else
					{
						$result[$user['ID']]['device'] = Array();
					}
				}
			}
			$result[$user['ID']]['mode'] = $status;
		}

		return $result;
	}

	public function SendMessage($arMessages = Array(), $arDevices = Array())
	{
		if (empty($arMessages))
		{
			return false;
		}

		$uniqueHashes = Array();
		$arTmpMessages = Array();
		foreach ($arMessages as $message)
		{
			if (!$message["USER_ID"])
			{
				continue;
			}
			$uniqueHashes[] = CPullPush::getUniqueHash($message["USER_ID"], $message["APP_ID"]);
			$uniqueHashes[] = CPullPush::getUniqueHash($message["USER_ID"], $message["APP_ID"] . "_bxdev");
			if (!array_key_exists("USER_" . $message["USER_ID"], $arTmpMessages))
			{
				$arTmpMessages["USER_" . $message["USER_ID"]] = Array();
			}
			$arTmpMessages["USER_" . $message["USER_ID"]][] = htmlspecialcharsback($message);
		}

		$filter = array(
			"UNIQUE_HASH" => array_unique($uniqueHashes)
		);

		if (empty($arDevices))
		{
			$dbDevices = CPullPush::GetList(Array("DEVICE_TYPE" => "ASC"), $filter);
			while ($row = $dbDevices->Fetch())
			{
				$arDevices[] = $row;
			}
		}

		$arServicesIDs = array_keys(self::$pushServices);
		$arPushMessages = Array();

		foreach ($arDevices as $arDevice)
		{
			if (in_array($arDevice["DEVICE_TYPE"], $arServicesIDs))
			{
				$tmpMessage = $arTmpMessages["USER_" . $arDevice["USER_ID"]];
				$mode = "PRODUCTION";
				if (strpos($arDevice["APP_ID"], "_bxdev") > 0)
				{
					$mode = "SANDBOX";
				}
				$arPushMessages[$arDevice["DEVICE_TYPE"]][$arDevice["DEVICE_TOKEN"]] = Array(
					"messages" => $tmpMessage,
					"mode" => $mode
				);
			}
		}

		if (empty($arPushMessages))
		{
			return false;
		}

		$batches = array();

		/**
		 * @var CPushService $obPush
		 */
		$batchMessageCount = CPullOptions::GetPushMessagePerHit();
		$useChunks = ($batchMessageCount > 0);
		foreach ($arServicesIDs as $serviceID)
		{
			if ($arPushMessages[$serviceID])
			{
				if (class_exists(self::$pushServices[$serviceID]["CLASS"]))
				{
					$obPush = new self::$pushServices[$serviceID]["CLASS"];
					if (method_exists($obPush, "getBatch"))
					{
						if(!$useChunks)
						{
							if(count($batches) > 0)
							{
								$batches[0] .= $obPush->getBatch($arPushMessages[$serviceID]);
							}
							else
							{
								$batches[] = $obPush->getBatch($arPushMessages[$serviceID]);
							}
						}
						else
						{
							$offset = 0;
							$counter = 1;
							$messages = null;
							while($messages = array_slice($arPushMessages[$serviceID],$offset, $batchMessageCount))
							{
								$batches[] = $obPush->getBatch($messages);
								$offset += count($messages);
								$counter++;
							}
						}

					}
				}
			}
		}

		foreach ($batches as $chunkBatch)
		{
			$this->sendBatch($chunkBatch);
		}

		return true;
	}

	public function sendBatch($batch)
	{
		require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/classes/general/update_client.php");
		$key = CUpdateClient::GetLicenseKey();
		if (strlen($key) > 0 && strlen($batch) > 0)
		{
			$postData = Array(
				"Action" => "SendMessage",
				"MessageBody" => $batch
			);
			$httpClient = new \Bitrix\Main\Web\HttpClient(array("waitResponse" => false));
			$httpClient->query("POST", self::$remoteProviderUrl . "?key=" . md5($key), $postData);
			return true;

		}

		return false;
	}

	public static function DeleteFromQueueBySubTag($userId, $tag, $appId = 'Bitrix24')
	{
		global $DB;
		if (strlen($tag) <= 0 || intval($userId) == 0)
		{
			return false;
		}

		$strSql = "DELETE FROM b_pull_push_queue WHERE USER_ID = " . intval($userId) . " AND SUB_TAG = '" . $DB->ForSQL($tag) . "'";
		$DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);

		\Bitrix\Pull\Push::add($userId, Array(
			'module_id' => 'pull',
			'push' => Array(
				'advanced_params' => Array(
					"notificationsToCancel" => array($tag),
				),
				'send_immediately' => 'Y',
				'app_id' => $appId
			)
		));

		return true;
	}

	public static function SendAgent()
	{
		global $DB;

		if (!CPullOptions::GetPushStatus())
		{
			return false;
		}

		$count = 0;
		$maxId = 0;
		$pushLimit = 10;
		$arPush = Array();

		$sqlDate = "";
		$dbType = strtolower($DB->type);
		if ($dbType == "mysql")
		{
			$sqlDate = " WHERE DATE_CREATE < DATE_SUB(NOW(), INTERVAL 15 SECOND) ";
		}
		else
		{
			if ($dbType == "mssql")
			{
				$sqlDate = " WHERE DATE_CREATE < dateadd(SECOND, -15, getdate()) ";
			}
			else
			{
				if ($dbType == "oracle")
				{
					$sqlDate = " WHERE DATE_CREATE < SYSDATE-(1/24/60/60*15) ";
				}
			}
		}

		$strSql = $DB->TopSql("SELECT ID, USER_ID, MESSAGE, PARAMS, ADVANCED_PARAMS, BADGE, APP_ID FROM b_pull_push_queue" . $sqlDate, 280);
		$dbRes = $DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);
		while ($arRes = $dbRes->Fetch())
		{
			if ($arRes['BADGE'] == '')
			{
				$arRes['BADGE'] = \Bitrix\Pull\MobileCounter::get($arRes['USER_ID']);
			}

			try
			{
				$arRes['PARAMS'] = $arRes['PARAMS'] ? Bitrix\Main\Web\Json::decode($arRes['PARAMS']) : "";
			}
			catch (Exception $e)
			{
				$arRes['PARAMS'] = "";
			}
			if (is_array($arRes['PARAMS']))
			{
				if (isset($arRes['PARAMS']['CATEGORY']))
				{
					$arRes['CATEGORY'] = $arRes['PARAMS']['CATEGORY'];
					unset($arRes['PARAMS']['CATEGORY']);
				}
				$arRes['PARAMS'] = Bitrix\Main\Web\Json::encode($arRes['PARAMS']);
			}
			try
			{
				$arRes['ADVANCED_PARAMS'] = strlen($arRes['ADVANCED_PARAMS']) > 0 ? Bitrix\Main\Web\Json::decode($arRes['ADVANCED_PARAMS']) : Array();
			}
			catch (Exception $e)
			{
				$arRes['ADVANCED_PARAMS'] = Array();
			}

			$arPush[$count][] = $arRes;
			if ($pushLimit <= count($arPush[$count]))
			{
				$count++;
			}

			$maxId = $maxId < $arRes['ID'] ? $arRes['ID'] : $maxId;
		}

		if ($maxId > 0)
		{
			$strSql = "DELETE FROM b_pull_push_queue WHERE ID <= " . $maxId;
			$DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);
		}

		$CPushManager = new CPushManager();
		foreach ($arPush as $arStack)
		{
			$CPushManager->SendMessage($arStack);
		}

		$strSql = "SELECT COUNT(ID) CNT FROM b_pull_push_queue";
		$dbRes = $DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);
		if ($arRes = $dbRes->Fetch())
		{
			global $pPERIOD;
			if ($arRes['CNT'] > 280)
			{
				$pPERIOD = 10;
				return "CPushManager::SendAgent();";
			}
			else
			{
				if ($arRes['CNT'] > 0)
				{
					$pPERIOD = 30;
					return "CPushManager::SendAgent();";
				}
			}
		}

		return false;
	}

	static function _MakeJson($arData, $bWS, $bSkipTilda)
	{
		if ((version_compare(PHP_VERSION, '5.4', ">=")))
		{
			return json_encode($arData,JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE);
		}

		static $aSearch = array("\r", "\n");

		if (is_array($arData))
		{

			if ($arData == array_values($arData))
			{

				foreach ($arData as $key => $value)
				{
					if (is_array($value))
					{
						$arData[$key] = self::_MakeJson($value, $bWS, $bSkipTilda);
					}
					elseif (is_bool($value))
					{
						if ($value === true)
						{
							$arData[$key] = "true";
						}
						else
						{
							$arData[$key] = "false";
						}
					}
					elseif (is_integer($value))
					{
						$res .= $value;
					}
					else
					{
						if (preg_match("#['\"\\n\\r<\\\\]#", $value))
						{
							$arData[$key] = "\"" . CUtil::JSEscape($value) . "\"";
						}
						else
						{
							$arData[$key] = "\"" . $value . "\"";
						}
					}
				}
				return '[' . implode(',', $arData) . ']';
			}

			$sWS = ',' . ($bWS ? "\n" : '');
			$res = ($bWS ? "\n" : '') . '{';
			$first = true;

			foreach ($arData as $key => $value)
			{
				if ($bSkipTilda && substr($key, 0, 1) == '~')
				{
					continue;
				}

				if ($first)
				{
					$first = false;
				}
				else
				{
					$res .= $sWS;
				}

				if (preg_match("#['\"\\n\\r<\\\\]#", $key))
				{
					$res .= "\"" . str_replace($aSearch, '', CUtil::addslashes($key)) . "\":";
				}
				else
				{
					$res .= "\"" . $key . "\":";
				}

				if (is_array($value))
				{
					$res .= self::_MakeJson($value, $bWS, $bSkipTilda);
				}
				elseif (is_bool($value))
				{
					if ($value === true)
					{
						$res .= "true";
					}
					else
					{
						$res .= "false";
					}
				}
				elseif (is_integer($value))
				{
					$res .= $value;
				}
				else
				{
					if (preg_match("#['\"\\n\\r<\\\\]#", $value))
					{
						$res .= "\"" . CUtil::JSEscape($value) . "\"";
					}
					else
					{
						$res .= "\"" . $value . "\"";
					}
				}
			}
			$res .= ($bWS ? "\n" : '') . '}';

			return $res;
		}
		elseif (is_bool($arData))
		{
			if ($arData === true)
			{
				return 'true';
			}
			else
			{
				return 'false';
			}
		}
		elseif (is_integer($value))
		{
			return $value;
		}
		else
		{
			if (preg_match("#['\"\\n\\r<\\\\]#", $arData))
			{
				return "\"" . CUtil::JSEscape($arData) . "'";
			}
			else
			{
				return "\"" . $arData . "\"";
			}
		}
	}

	public function getServices()
	{
		return self::$pushServices;
	}

	public function sendBadges($userId = null, $appId = 'Bitrix24')
	{
		return \Bitrix\Pull\MobileCounter::send($userId, $appId);
	}
}
?>