<?php

/**
 * BaseNationality
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $nat_code
 * @property string $nat_name
 * @property Doctrine_Collection $Employee
 * 
 * @method string              getNatCode()  Returns the current record's "nat_code" value
 * @method string              getNatName()  Returns the current record's "nat_name" value
 * @method Doctrine_Collection getEmployee() Returns the current record's "Employee" collection
 * @method Nationality         setNatCode()  Sets the current record's "nat_code" value
 * @method Nationality         setNatName()  Sets the current record's "nat_name" value
 * @method Nationality         setEmployee() Sets the current record's "Employee" collection
 * 
 * @package    orangehrm
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseNationality extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('hs_hr_nationality');
        $this->hasColumn('nat_code', 'string', 13, array(
             'type' => 'string',
             'primary' => true,
             'length' => 13,
             ));
        $this->hasColumn('nat_name', 'string', 120, array(
             'type' => 'string',
             'length' => 120,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Employee', array(
             'local' => 'nat_code',
             'foreign' => 'nation_code'));
    }
}