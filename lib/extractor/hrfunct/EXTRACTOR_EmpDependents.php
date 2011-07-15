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

require_once ROOT_PATH . '/lib/models/hrfunct/EmpDependents.php';

class EXTRACTOR_EmpDependents {
	
	var $empId;
	var $empDSeqNo;
	var $empDepName;
	var $empRelShip;
	
	
	
	function EXTRACTOR_EmpDependents() {

		$this->dep = new EmpDependents();
	}

	function parseData($postArr) {	
			
			$this->dep->setEmpId(CommonFunctions::cleanParam($postArr['txtEmpID']));
			$this->dep->setEmpDSeqNo(CommonFunctions::cleanParam($postArr['txtDSeqNo']));
			$this->dep->setEmpDepName(CommonFunctions::cleanParam($postArr['txtDepName'], 100));
			$this->dep->setEmpDepRel(CommonFunctions::cleanParam($postArr['txtRelShip'], 100));
												
			return $this->dep;
	}
	
	function reloadData($postArr) {	
	
			$this->txtEmpID		=	CommonFunctions::cleanParam($postArr['txtEmpID']);
			$this->txtDSeqNo	=	CommonFunctions::cleanParam($postArr['txtDSeqNo']);
			$this->txtDepName	=	CommonFunctions::cleanParam($postArr['txtDepName'], 100);
			$this->txtRelShip	=	CommonFunctions::cleanParam($postArr['txtRelShip'], 100);
						
			return $this;
	}
		
	
}
?>
