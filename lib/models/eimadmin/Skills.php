<?php
/*
// OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
// all the essential functionalities required for any enterprise.
// Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com

// OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
// the GNU General Public License as published by the Free Software Foundation; either
// version 2 of the License, or (at your option) any later version.

// OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
// without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// See the GNU General Public License for more details.

// You should have received a copy of the GNU General Public License along with this program;
// if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
// Boston, MA  02110-1301, USA
*/

require_once ROOT_PATH . '/lib/confs/Conf.php';
require_once ROOT_PATH . '/lib/dao/DMLFunctions.php';
require_once ROOT_PATH . '/lib/dao/SQLQBuilder.php';
require_once ROOT_PATH . '/lib/common/CommonFunctions.php';
require_once ROOT_PATH . '/lib/common/UniqueIDGenerator.php';

class Skills {

	var $tableName = 'HS_HR_SKILL';

	var $skillId;
	var $skillDesc;
	var $skillName;
	var $arrayDispList;
	var $singleField;


	function Skills() {
	}

	function setSkillId($skillId) {
		$this->skillId = $skillId;
	}

	function setSkillDescription($skillDesc) {
		$this->skillDesc = $skillDesc;
	}

	function setSkillName($skillName) {
		$this->skillName = $skillName;
	}

	function getSkillId() {
		return $this->skillId;
	}

	function getSkillName() {
		return $this->skillName;
	}

	function getSkillDescription() {
		return $this->skillDesc;
	}

	function getListofSkills($pageNO,$schStr,$mode, $sortField = 0, $sortOrder = 'ASC') {

		$tableName = 'HS_HR_SKILL';
		$arrFieldList[0] = 'SKILL_CODE';
		$arrFieldList[1] = 'SKILL_NAME';
		$arrFieldList[2] = 'SKILL_DESCRIPTION';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->passResultSetMessage($pageNO,$schStr,$mode, $sortField, $sortOrder);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$i=0;

		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {

	    	$arrayDispList[$i][0] = $line[0];
	    	$arrayDispList[$i][1] = $line[1];
	    	$arrayDispList[$i][2] = $line[2];
	    	$i++;

	     }

	     if (isset($arrayDispList)) {
			return $arrayDispList;
		} else {
			$arrayDispList = '';
			return $arrayDispList;

		}
	}

	function countSkills($schStr,$mode) {

		$tableName = 'HS_HR_SKILL';
		$arrFieldList[0] = 'SKILL_CODE';
		$arrFieldList[1] = 'SKILL_NAME';
		$arrFieldList[2] = 'SKILL_DESCRIPTION';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->countResultset($schStr,$mode);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$line = mysql_fetch_array($message2, MYSQL_NUM);

	    return $line[0];
	}

	function delSkills($arrList) {

		$tableName = 'HS_HR_SKILL';
		$arrFieldList[0] = 'SKILL_CODE';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_delete = 'true';
		$sql_builder->arr_delete = $arrFieldList;

		$sqlQString = $sql_builder->deleteRecord($arrList);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		return $message2;
	}


	function addSkills() {

		if ($this->_isDuplicateName()) {
			throw new SkillsException("Duplicate name", 1);
		}

		$tableName = 'hs_hr_skill';

		$this->skillId = UniqueIDGenerator::getInstance()->getNextID($tableName, 'skill_code', 'SKI');
		$arrFieldList[0] = "'". $this->getSkillId() . "'";
		$arrFieldList[1] = "'". $this->getSkillName() . "'";
		$arrFieldList[2] = "'". $this->getSkillDescription() . "'";

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_insert = 'true';
		$sql_builder->arr_insert = $arrFieldList;

		$sqlQString = $sql_builder->addNewRecordFeature1();

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		return $message2;
	}

	function updateSkills() {

		if ($this->_isDuplicateName(true)) {
			throw new SkillsException("Duplicate name", 1);
		}

		$this->getSkillId();
		$arrRecordsList[0] = "'". $this->getSkillId() . "'";
		$arrRecordsList[1] = "'". $this->getSkillName() . "'";
		$arrRecordsList[2] = "'". $this->getSkillDescription() . "'";
		$arrFieldList[0] = 'SKILL_CODE';
		$arrFieldList[1] = 'SKILL_NAME';
		$arrFieldList[2] = 'SKILL_DESCRIPTION';

		$tableName = 'HS_HR_SKILL';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_update = 'true';
		$sql_builder->arr_update = $arrFieldList;
		$sql_builder->arr_updateRecList = $arrRecordsList;

		$sqlQString = $sql_builder->addUpdateRecord1();

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		return $message2;
	}


	function filterSkills($getID) {

		$this->getID = $getID;
		$tableName = 'HS_HR_SKILL';
		$arrFieldList[0] = 'SKILL_CODE';
		$arrFieldList[1] = 'SKILL_NAME';
		$arrFieldList[2] = 'SKILL_DESCRIPTION';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->selectOneRecordFiltered($this->getID);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$i=0;

		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {

	    	$arrayDispList[$i][0] = $line[0];
	    	$arrayDispList[$i][1] = $line[1];
	    	$arrayDispList[$i][2] = $line[2];
	    	$i++;

	     }

	    if (isset($arrayDispList)) {
			return $arrayDispList;
		} else {
			$arrayDispList = '';
			return $arrayDispList;
		}
	}

	function getSkillCodes () {

		$sql_builder = new SQLQBuilder();
		$tableName = 'HS_HR_SKILL';
		$arrFieldList[0] = 'SKILL_CODE';
		$arrFieldList[1] = 'SKILL_NAME';
		$arrFieldList[2] = 'SKILL_DESCRIPTION';

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->passResultSetMessage(0, '', -1, 1);

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$common_func = new CommonFunctions();

		$i=0;

		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {

	    	$arrayDispList[$i][0] = $line[0];
	    	$arrayDispList[$i][1] = $line[1];
	    	$arrayDispList[$i][2] = $line[2];


	    	$i++;
	     }

	     if (isset($arrayDispList)) {
	       	return $arrayDispList;
	     }
	}

	function filterGetSkillInfo($getID) {

		$this->getID = $getID;
		$tableName = 'HS_HR_SKILL';
		$arrFieldList[0] = 'SKILL_CODE';
		$arrFieldList[1] = 'SKILL_NAME';
		$arrFieldList[2] = 'SKILL_DESCRIPTION';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->selectOneRecordFiltered($this->getID);

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$i=0;

		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {

	    	$arrayDispList[$i][0] = $line[0]; // Province Code
	    	$arrayDispList[$i][1] = $line[1]; // Provicne Name
	    	$arrayDispList[$i][2] = $line[2];

	    	$i++;
	     }

	     if (isset($arrayDispList)) {
			return $arrayDispList;
		} else {
			$arrayDispList = '';
			return $arrayDispList;
		}
	}

	function getUnAssSkillCodes($id) {

		$sql_builder = new SQLQBuilder();
		$tableName = 'HS_HR_SKILL';
		$arrFieldList[0] = 'SKILL_CODE';
		$arrFieldList[1] = 'SKILL_NAME';
		$arrFieldList[2] = 'SKILL_DESCRIPTION';

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;
		$sql_builder->field = 'SKILL_CODE';
		$sql_builder->table2_name = 'HS_HR_EMP_SKILL';

		$arr[0][0]='EMP_NUMBER';
		$arr[0][1]=$id;
		$sqlQString = $sql_builder->selectFilter($arr, 1, 1);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$i=0;

		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {

	    	$arrayDispList[$i][0] = $line[0];
	    	$arrayDispList[$i][1] = $line[1];
	    	$arrayDispList[$i][2] = $line[2];
	    	$i++;

	     }

	    if (isset($arrayDispList)) {
			return $arrayDispList;
		} else {
			$arrayDispList = '';
			return $arrayDispList;
		}
	}

	function filterNotEqualSubSkillInfo($getID) {

		$this->getID = $getID;

		$tableName = 'HS_HR_SKILL';
		$arrFieldList[0] = 'SKILL_CODE';
		$arrFieldList[1] = 'SKILL_NAME';
		$arrFieldList[2] = 'SKILL_DESCRIPTION';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->filterNotEqualRecordSet($this->getID);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$i=0;

		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {

	    	$arrayDispList[$i][0] = $line[0]; // Province Code
	    	$arrayDispList[$i][1] = $line[1]; // Provicne Name
	    	$arrayDispList[$i][2] = $line[2];

	    	$i++;
	     }

	     if (isset($arrayDispList)) {
			return $arrayDispList;
		} else {
			$arrayDispList = '';
			return $arrayDispList;
		}
	}

	private function _isDuplicateName($update=false) {
		$skills = $this->getListofSkills(0, $this->getSkillName(), 1);

		if (is_array($skills)) {
			if ($update) {
				if ($skills[0][0] == $this->getSkillId()) {
					return false;
				}
			}
			return true;
		}

		return false;
	}
}

class SkillsException extends Exception {
}
?>
