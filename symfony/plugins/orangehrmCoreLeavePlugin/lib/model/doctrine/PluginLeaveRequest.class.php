<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginLeaveRequest extends BaseLeaveRequest {

    private $leave = null;
    private $leaveCount = null;
    private $numberOfDays = null;
    private $leaveDuration = null;
    private $statusCounter = array();
    private $canApprove = false;
    private $canCancel = false;
    private $workShiftHoursPerDay = null;

    const LEVAE_REQUEST_STATUS_APPROVED = 'Scheduled';
    const LEVAE_REQUEST_STATUS_CANCELLED = 'Cancelled';
    const LEVAE_REQUEST_STATUS_REJECTED = 'Rejected';

    public function getNumberOfDays() {
        $this->_fetchLeave();
        return number_format($this->numberOfDays, 2);
    }

    public function getStatusCounter() {
        return $this->statusCounter;
    }

    public function getLeaveDuration() {

        if ($this->leaveCount == 1) {
            $startTime = $this->leave[0]->getStartTime();
            $endTime = $this->leave[0]->getEndTime();

            if ((!empty($startTime) && !empty($endTime)) && ("{$startTime} {$endTime}" != '00:00:00 00:00:00')) {
                return "{$startTime} to {$endTime}";
            } else {
                $totalDuration = $this->leave[0]->getLeaveLengthHours();
                if (!empty($totalDuration)) {
                    return number_format($totalDuration, 2) . ' hours';
                } else {
                    return number_format($this->_getWorkShiftHoursPerDay(), 2) . ' hours';
                }
            }
        } else {
            return number_format($this->leaveDuration, 2) . ' hours';
        }
    }

    public function getStatus() {
        $this->_fetchLeave();

        $statusStrings = array();
        foreach ($this->statusCounter as $status => $count) {
            if (!empty($status)) {
                $statusStrings[] = __($status) . "(". $count . ")";
            }
        }

        return implode(',<br />', $statusStrings);
    }

    private function _fetchLeave() {
        if (is_null($this->leave)) {
            $leaveRequestDao = new LeaveRequestDao();
            $this->leave = $leaveRequestDao->fetchLeave($this->getLeaveRequestId());
            $this->_parseLeave();
        }
    }

    public function canApprove() {
        $this->_fetchLeave();
        return $this->canApprove;
    }

    public function canCancel($isAdmin = false) {
        if ($isAdmin && $this->_AreAllTaken()) {
            return true;
        } else {
            $this->_fetchLeave();
            return $this->canCancel;
        }
    }

    private function _parseLeave() {
        $this->numberOfDays = 0.0;
        $this->leaveDuration = 0.0;

        // Counting leave
        $this->leaveCount = $this->leave->count();

        $this->statusCounter = array();
        $this->canApprove = false;
        $this->canCancel = false;

        foreach ($this->leave as $leave) {
            // Calculating number of days and duration
            $dayLength = (float) $leave->getLeaveLengthDays();

            //this got changed to fix sf-3019087,3044234 $hourLength = $dayLength * $this->_getWorkShiftHoursPerDay();
            $hourLength = (float) $leave->getLeaveLengthHours();
            if ($dayLength >= 1) {
                $hourLength = $dayLength * (float) $leave->getLeaveLengthHours();
            }

            if ($hourLength == 0.0) {
                $hourLength = (float) $leave->getLeaveLengthHours();
            }

            $this->leaveDuration += $hourLength;

            //if($hourLength > 0) {
                $this->numberOfDays += $dayLength;
            //}

            // Populating status counter
            $key = $leave->getTextLeaveStatus();
            $statusDayLength = ($dayLength != 0) ? $dayLength : 1;
            if (!empty($key)) {
                if($hourLength > 0) {
                    if (array_key_exists($key, $this->statusCounter)) {
                        $this->statusCounter[$key]+= $statusDayLength;
                    } else {
                        $this->statusCounter[$key] = $statusDayLength;
                    }
                }
            }

            // Checking for available status changes
            if ($leave->canApprove()) {
                if ($dayLength > 0)
                    $this->canApprove = true;
            }

            if ($leave->canCancel()) {
                if ($dayLength > 0)
                    $this->canCancel = true;
            }
        }

        //is there any use of this block ?
        /* if ($this->numberOfDays == 1.0) {
          $this->numberOfDays = $this->leave[0]->getLeaveLengthDays();
          } */
//die();
    }

    private function _getWorkShiftHoursPerDay() {

        if (!isset($this->workShiftHoursPerDay)) {
            $employeeWorkshift = $this->getEmployee()->getEmployeeWorkShift();
            if ($employeeWorkshift->count() > 0) {
                $this->workShiftHoursPerDay = $employeeWorkshift[0]->getWorkShift()->getHoursPerDay();
            } else {
                $this->workShiftHoursPerDay = WorkShift::DEFAULT_WORK_SHIFT_LENGTH;
            }
        }

        return $this->workShiftHoursPerDay;
    }

    private function _AreAllTaken() {

        $flag = true;

        foreach ($this->leave as $leave) {
            if ($leave->getLeaveStatus() != Leave::LEAVE_STATUS_LEAVE_TAKEN) {
                $flag = false;
                break;
            }
        }

        return $flag;
    }

}