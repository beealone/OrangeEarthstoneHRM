<?php

require_once 'PHPUnit/Framework.php';
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
require_once sfConfig::get('sf_test_dir') . '/util/TestDataService.php';

class EmployeeDaoTest extends PHPUnit_Framework_TestCase {

    private $testCase;
    private $employeeDao;
    protected $fixture;

    /**
     * Set up method
     */
    protected function setUp() {
        $this->employeeDao = new EmployeeDao();
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmPimPlugin/test/fixtures/EmployeeDao.yml';
        TestDataService::populate($this->fixture);
    }

    /**
     * Testing getEmployeeListAsJson
     */
    public function testGetEmployeeListAsJson() {
        $result = $this->employeeDao->getEmployeeListAsJson();
        $this->assertTrue(!empty($result));
    }

    /**
     * Test saving EmployeePassport without sequence number
     */
    public function testSaveEmployeePassport1() {

        $empPassport = new EmpPassPort();
        $empPassport->setEmpNumber(1);
        $empPassport->country = 'LK';
        $result = $this->employeeDao->saveEmployeePassport($empPassport);
        $this->assertTrue($result);
        $this->assertEquals(1, $empPassport->seqno);
    }

    /**
     * Test saving EmployeePassport
     */
    public function testSaveEmployeePassport2() {

        $empPassport = TestDataService::fetchLastInsertedRecords('EmpPassport', 2);
        $empNumbers = array(1 => 1, 2 => 2);

        foreach ($empPassport as $passport) {

            $this->assertTrue($passport instanceof EmpPassPort);
            $this->assertEquals($empNumbers[$passport->getEmpNumber()], $passport->getEmpNumber());
            $comment = "I add more comments";
            $passport->comments = $comment;
            $result = $this->employeeDao->saveEmployeePassport($passport);
            $this->assertTrue($result);

            $savedPassport = $this->employeeDao->getEmployeePassport($passport->getEmpNumber(), $passport->getSeqno());
            $this->assertEquals($comment, $savedPassport->comments);
            $this->assertEquals($savedPassport, $passport);
        }
    }

    /**
     * Test saving getEmployeePassport returns object
     */
    public function testGetEmployeePassport1() {

        $empPassports = TestDataService::fetchLastInsertedRecords('EmpPassport', 2);

        foreach ($empPassports as $passport) {

            $empPassport = $this->employeeDao->getEmployeePassport($passport->getEmpNumber(), $passport->getSeqno());
            $this->assertEquals($passport, $empPassport);
        }
    }

    /**
     * Test saving getEmployeePassport returns Collection
     */
    public function testGetEmployeePassport2() {

        $empPassports = TestDataService::fetchLastInsertedRecords('EmpPassport', 2);

        foreach ($empPassports as $passport) {

            $collection = $this->employeeDao->getEmployeePassport($passport->getEmpNumber());
            $this->assertTrue($collection instanceof Doctrine_Collection);
        }
    }

    /**
     * Test for getEmployeeTaxExemptions returns Object
     */
    public function testGetEmployeeTaxExemptions() {
        $empTaxExemption = TestDataService::fetchObject('EmpUsTaxExemption', 1);
        $taxObject = $this->employeeDao->getEmployeeTaxExemptions($empTaxExemption->getEmpNumber());
        $this->assertTrue($taxObject instanceof EmpUsTaxExemption);
    }

    /**
     * Test saving Employee Tax Exemptions
     */
    public function testSaveEmployeeTaxExemptions() {

        $empUsTaxExemption = new EmpUsTaxExemption();
        $empUsTaxExemption->setEmpNumber(2);
        $empUsTaxExemption->stateExemptions = 4;
        $result = $this->employeeDao->saveEmployeeTaxExemptions($empUsTaxExemption);
        $this->assertTrue($result);
        $this->assertEquals(2, $empUsTaxExemption->getEmpNumber());
        $this->assertEquals(4, $empUsTaxExemption->stateExemptions);
    }

    /**
     * Test for getMembershipDetails returns collection
     */
    public function testGetMembershipDetails() {

        //$employeeMemberDetail1 = TestDataService::loadObjectList('EmployeeMemberDetail', $this->fixture, 'EmployeeMemberDetail');
        $memberDetailArray = $this->employeeDao->getMembershipDetails(1);
        $this->assertTrue($memberDetailArray[0] instanceof EmployeeMemberDetail);
        $this->assertTrue($memberDetailArray[1] instanceof EmployeeMemberDetail);
    }

    /**
     * Test for getMembershipDetail returns List with one EmployeeMemberDetail object
     */
    public function testGetMembershipDetail() {

        $empNumber = 1;
        $membershipType = 'MEM001';
        $membership = 'MIME001';

        //$employeeMemberDetail1 = TestDataService::loadObjectList('EmployeeMemberDetail', $this->fixture, 'EmployeeMemberDetail');
        $memberDetail = $this->employeeDao->getMembershipDetail($empNumber, $membershipType, $membership);
        $this->assertTrue($memberDetail[0] instanceof EmployeeMemberDetail);
    }

    /**
     * Test for delete EmployeeMemberDetail object returns boolean
     */
    public function testDeleteMembershipDetails() {

        $empNumber = 1;
        $membershipType = 'MEM001';
        $membership = 'MIME001';

        $this->assertTrue($this->employeeDao->deleteMembershipDetails($empNumber, $membershipType, $membership));
    }

    /**
     * Test for saveReportingMethod returns ReportingMethod doctrine object
     */
    public function testSaveReportingMethod() {

        $reportingMethod = new ReportingMethod();
        $reportingMethod->reportingMethodName = "report name";

        $storedReportingMethod = $this->employeeDao->saveReportingMethod($reportingMethod);
        $this->assertTrue($storedReportingMethod instanceof ReportingMethod);
        $this->assertEquals($storedReportingMethod->reportingMethodName, "report name");
    }

    /**
     * Test for getReportingMethod returns ReportingMethod doctrine object
     */
    public function testGetReportingMethod() {

        $reportingMethod = $this->employeeDao->getReportingMethod(3);
        $this->assertTrue($reportingMethod instanceof ReportingMethod);
    }

    /**
     * Test for getReportingMethod returns ReportingMethod doctrine collection
     */
    public function testGetReportingMethodList() {

        $reportingMethod = $this->employeeDao->getReportingMethodList();
        $this->assertTrue($reportingMethod[0] instanceof ReportingMethod);
        $this->assertTrue($reportingMethod[1] instanceof ReportingMethod);
    }

    /**
     * Test for getSupervisorListForEmployee returns ReportTo doctrine collection
     */
    public function testGetSupervisorListForEmployee() {

        $supervisorReportToList = $this->employeeDao->getSupervisorListForEmployee(3);
        $this->assertTrue($supervisorReportToList[0] instanceof ReportTo);
        $this->assertEquals($supervisorReportToList[0]->supervisorId, 4);
        $this->assertTrue($supervisorReportToList[0]->getSupervisor() instanceof Employee);
    }

    /**
     * Test for getSubordinateListForEmployee returns ReportTo doctrine collection
     */
    public function testGetSubordinateListForEmployee() {

        $subordinateReportToList = $this->employeeDao->getSubordinateListForEmployee(3);
        $this->assertTrue($subordinateReportToList[0] instanceof ReportTo);
        $this->assertEquals($subordinateReportToList[0]->subordinateId, 1);
        $this->assertTrue($subordinateReportToList[0]->getSubordinate() instanceof Employee);
    }

    /**
     * Test for getReportToObject returns ReportTo doctrine Object
     */
    public function testGetReportToObject() {

        $subordinateReportTo = $this->employeeDao->getReportToObject(4, 3, 4);
        $this->assertTrue($subordinateReportTo instanceof ReportTo);
    }

    /**
     * Test for deleteReportToObject returns boolean
     */
    public function testDeleteReportToObject() {

        $this->assertTrue($this->employeeDao->deleteReportToObject(3, 1, 3));
    }

    /**
     * Test for get emergency contact returns EmpEmergencyContact doctrine 
     */
    public function testGetEmergencyContacts() {

        $empNumber = 1;

        $emergencyContact = $this->employeeDao->getEmergencyContacts($empNumber);
        $this->assertTrue($emergencyContact[0] instanceof EmpEmergencyContact);
    }

    /**
     * Test for getReportingMethod returns ReportingMethod doctrine collection
     */
    public function testDeleteEmergencyContacts() {

        $empNumber = 1;
        $emergencyContactsToDelete = array(1, 2);

        $result = $this->employeeDao->deleteEmergencyContacts($empNumber, $emergencyContactsToDelete);
        $this->assertTrue($result);
    }

    /**
     * Test for get work expierence returns EmpWorkExperience doctrine collection
     */
    public function testGetWorkExperienceWithNullSeqNumber() {

        $empNumber = 1;

        $wrkExp = $this->employeeDao->getWorkExperience($empNumber);
        $this->assertTrue($wrkExp[0] instanceof EmpWorkExperience);
    }

    /**
     * Test for get work expierence returns EmpWorkExperience doctrine object
     */
    public function testGetWorkExperienceWithSeqNumber() {

        $empNumber = 1;
        $sequenceNo = 2;

        $wrkExp = $this->employeeDao->getWorkExperience($empNumber, $sequenceNo);
        $this->assertTrue($wrkExp instanceof EmpWorkExperience);
    }

    /**
     * Test for save work expierence returns boolean
     */
    public function testSaveWorkExperienceWithSeqNum() {

        $empWorkExp = new EmpWorkExperience;

        $empWorkExp->emp_number = 1;
        $empWorkExp->seqno = 3;
        $empWorkExp->jobtitle = "SE";
        $empWorkExp->employer = "OrangeHRM";

        $this->assertTrue($this->employeeDao->saveWorkExperience($empWorkExp));
    }

    /**
     * Test for save work expierence returns boolean
     */
    public function testSaveWorkExperienceWithoutSeqNum() {

        $empWorkExp = new EmpWorkExperience;

        $empWorkExp->emp_number = 1;
        $empWorkExp->jobtitle = "Architect";
        $empWorkExp->employer = "IFS";

        $this->assertTrue($this->employeeDao->saveWorkExperience($empWorkExp));
    }

    /**
     * Test for deleteWrkExpierence returns boolean
     */
    public function testDeleteWorkExperience() {

        $empNumber = 1;
        $workExperienceToDelete = array(1, 2);

        $result = $this->employeeDao->deleteWorkExperience($empNumber, $workExperienceToDelete);
        $this->assertTrue($result);
    }

    /**
     * Test for get education returns EmployeeEducation doctrine collection
     */
    public function testGetEducationWithNullEduCode() {

        $empNumber = 1;

        $education = $this->employeeDao->getEducation($empNumber);
        $this->assertTrue($education[0] instanceof EmployeeEducation);
    }

    /**
     * Test for get education returns EmployeeEducation doctrine object
     */
    public function testGetEducationWithEduCode() {

        $empNumber = 1;
        $eduCode = 'EDU002';

        $education = $this->employeeDao->getEducation($empNumber, $eduCode);
        $this->assertTrue($education instanceof EmployeeEducation);
    }

    /**
     * Test for save education expierence returns boolean
     */
    public function testSaveEducation() {

        $empEdu = new EmployeeEducation;

        $empEdu->emp_number = 2;
        $empEdu->code = 'EDU002';
        $empEdu->major = 'major';

        $this->assertTrue($this->employeeDao->saveEducation($empEdu));
    }

    /**
     * Test for deleteEducation returns boolean
     */
    public function testDeleteEducation() {

        $empNumber = 1;
        $educationToDelete = array(1, 2);

        $result = $this->employeeDao->deleteEducation($empNumber, $educationToDelete);
        $this->assertTrue($result);
    }

    /**
     * Test for get skill returns EmployeeSkill doctrine collection
     */
    public function testGetSkillWithNullSkillCode() {

        $empNumber = 1;

        $skill = $this->employeeDao->getSkill($empNumber);
        $this->assertTrue($skill[0] instanceof EmployeeSkill);
    }

    /**
     * Test for get work expierence returns EmployeeSkill doctrine object
     */
    public function testGetSkillWithSkillCode() {

        $empNumber = 1;
        $skillCode = 'SKI002';

        $skill = $this->employeeDao->getSkill($empNumber, $skillCode);
        $this->assertTrue($skill instanceof EmployeeSkill);
    }

    /**
     * Test for save Skill returns boolean
     */
    public function testSaveSkill() {

        $empSkill = new EmployeeSkill;

        $empSkill->emp_number = 3;
        $empSkill->code = 'SKI001';

        $this->assertTrue($this->employeeDao->saveSkill($empSkill));
    }

    /**
     * Test for deleteSkill returns boolean
     */
    public function testDeleteSkill() {

        $empNumber = 1;
        $skillToDelete = array(1, 2);

        $result = $this->employeeDao->deleteSkill($empNumber, $skillToDelete);
        $this->assertTrue($result);
    }

    /**
     * Test for get skill returns EmployeeLanguage doctrine collection
     */
    public function testGetLanguageWithNullLangCodeAndLangType() {

        $empNumber = 1;

        $language = $this->employeeDao->getLanguage($empNumber);
        $this->assertTrue($language[0] instanceof EmployeeLanguage);
    }

    /**
     * Test for get work expierence returns EmployeeLanguage doctrine collection
     */
    public function testGetLanguageWithNullLangCode() {

        $empNumber = 1;
        $langType = 2;

        $language = $this->employeeDao->getLanguage($empNumber, null, $langType);
        $this->assertTrue($language[0] instanceof EmployeeLanguage);
    }

    /**
     * Test for get work expierence returns EmployeeLanguage doctrine collection
     */
    public function testGetLanguageWithNullLangType() {

        $empNumber = 1;
        $langCode = 'LAN001';

        $language = $this->employeeDao->getLanguage($empNumber, $langCode);
        $this->assertTrue($language[0] instanceof EmployeeLanguage);
    }

    /**
     * Test for get work expierence returns EmployeeLanguage doctrine object
     */
    public function testGetLanguage() {

        $empNumber = 1;
        $langCode = 'LAN001';
        $langType = 2;

        $language = $this->employeeDao->getLanguage($empNumber, $langCode, $langType);
        $this->assertTrue($language instanceof EmployeeLanguage);
    }

    /**
     * Test for save language expierence returns boolean
     */
    public function testSaveLanguage() {

        $empLang = new EmployeeLanguage;

        $empLang->emp_number = 2;
        $empLang->code = 'LAN001';

        $this->assertTrue($this->employeeDao->saveLanguage($empLang));
    }

    /**
     * Test for deleteLanguage returns boolean
     */
    public function testDeleteLanguage() {

        $empNumber = 1;
        $languagesToDelete = array('LAN001' => 2, 'LAN002' => 1);

        $result = $this->employeeDao->deleteLanguage($empNumber, $languagesToDelete);
        $this->assertTrue($result > 0);
    }

    /**
     * Test for get skill returns EmployeeLicense doctrine collection
     */
    public function testGetLicenseWithNullLicenseCode() {

        $empNumber = 1;

        $license = $this->employeeDao->getLicense($empNumber);
        $this->assertTrue($license[0] instanceof EmployeeLicense);
    }

    /**
     * Test for get work expierence returns EmployeeLicense doctrine object
     */
    public function testGetLicenseWithLicenseCode() {

        $empNumber = 1;
        $licenseCode = 'LIC001';

        $licenseCode = $this->employeeDao->getLicense($empNumber, $licenseCode);
        $this->assertTrue($licenseCode instanceof EmployeeLicense);
    }

    /**
     * Test for save license expierence returns boolean
     */
    public function testSaveLicense() {

        $empLicense = new EmployeeLicense;

        $empLicense->emp_number = 3;
        $empLicense->code = 'LIC001';

        $this->assertTrue($this->employeeDao->saveLicense($empLicense));
    }

    /**
     * Test for deleteLicence returns boolean
     */
    public function testDeleteLicense() {

        $empNumber = 1;
        $licenseToDelete = array(1, 2);

        $result = $this->employeeDao->deleteLicense($empNumber, $licenseToDelete);
        $this->assertTrue($result);
    }

    /**
     * Test for get dependents returns EmpDependent doctrine collection
     */
    public function testGetDependents() {

        $empNumber = 1;

        $empDep = $this->employeeDao-> getDependents($empNumber);
        $this->assertTrue($empDep[0] instanceof EmpDependent);
        $this->assertTrue($empDep[1] instanceof EmpDependent);
    }

     /**
     * Test for delete dependents returns boolean
     */
    public function testDeleteDependents() {

        $empNumber = 1;
        $entriesToDelete = array(1, 2);

        $result = $this->employeeDao->deleteDependents($empNumber, $entriesToDelete);
        $this->assertTrue($result);
    }


}
