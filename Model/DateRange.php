<?php
namespace Millwright\RadBundle\Model;

use Millwright\Util\DateUtil;
use Millwright\Util\PhpUtil;

class DateRange
{
    /**
     * @var \DateTime
     */
    protected $fromDate;

    /**
     * @var \DateTime
     */
    protected $toDate;

    public function __construct(\DateTime $fromDate, \DateTime $toDate)
    {
        DateUtil::setDateTime($this->fromDate, $fromDate);
        DateUtil::setDateTime($this->toDate, $toDate);
    }

    /**
     * {@inheritDoc}
     */
    public function setFromDate(\DateTime $fromDate)
    {
        DateUtil::setDateTime($this->fromDate, $fromDate);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getFromDate()
    {
        return PhpUtil::cloneObject($this->fromDate);
    }

    /**
     * {@inheritDoc}
     */
    public function setToDate(\DateTime $toDate)
    {
        DateUtil::setDateTime($this->toDate, $toDate);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getToDate()
    {
        return PhpUtil::cloneObject($this->toDate);
    }
}
