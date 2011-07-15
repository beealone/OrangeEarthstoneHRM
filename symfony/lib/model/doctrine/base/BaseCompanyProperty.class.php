<?php

/**
 * BaseCompanyProperty
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $prop_id
 * @property string $prop_name
 * @property integer $emp_id
 * 
 * @method integer         getPropId()    Returns the current record's "prop_id" value
 * @method string          getPropName()  Returns the current record's "prop_name" value
 * @method integer         getEmpId()     Returns the current record's "emp_id" value
 * @method CompanyProperty setPropId()    Sets the current record's "prop_id" value
 * @method CompanyProperty setPropName()  Sets the current record's "prop_name" value
 * @method CompanyProperty setEmpId()     Sets the current record's "emp_id" value
 * 
 * @package    orangehrm
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCompanyProperty extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('hs_hr_comp_property');
        $this->hasColumn('prop_id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('prop_name', 'string', 250, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 250,
             ));
        $this->hasColumn('emp_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}