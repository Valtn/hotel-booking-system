<?php
class DateRange {
    /**
     * @var bool|DateTime
     */
    private $dateStart;
    /**
     * @var bool|DateTime
     */
    private $dateFinish;

    /**
     * DateRange constructor.
     */
    public function __construct($dateRange, $format = 'Y-m-d') {
        $array = explode(' - ', $dateRange);
        $this->dateStart = DateTime::createFromFormat($format, $array[0]);
        $this->dateFinish = DateTime::createFromFormat($format, $array[1]);
    }

    /**
     * @return bool|DateTime
     */
    public function getDateStart() {
        return $this->dateStart;
    }

    /**
     * @return bool|DateTime
     */
    public function getDateFinish() {
        return $this->dateFinish;
    }

    public function isValid() {
        if ($this->dateStart->getTimestamp() < $this->dateFinish->getTimestamp()) {
            return true;
        }
        return false;
    }

    /**
     * @return string A formatted date string for the start date.
     */
    public function getStartSqlTimestamp() {
        return $this->getSqlTimeStamp($this->dateStart);
    }

    /**
     * @return string A formatted date string for the end date.
     */
    public function getFinishSqlTimestamp() {
        return $this->getSqlTimeStamp($this->dateFinish);
    }

    /**
     * @param DateTime $dateTime
     * @return string A formatted date string.
     */
    private function getSqlTimeStamp($dateTime) {
        return date('Y-m-d H:i:s', $dateTime->getTimestamp());
    }

}