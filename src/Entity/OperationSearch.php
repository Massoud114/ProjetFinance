<?php


namespace App\Entity;


use DateTime;

class OperationSearch
{
	/**
	 * @var DateTime|null
	 */
 	private $firstDate;

 	/**
	 * @var DateTime|null
	 */
 	private $secondDate;

 	public function __construct()
	{
		$this->firstDate = new DateTime('today');
		$this->secondDate = new DateTime('today');
	}

	/**
	 * @return DateTime|null
	 */
	public function getFirstDate(): ?DateTime
	{
		return $this->firstDate;
	}

	/**
	 * @param DateTime|null $firstDate
	 * @return OperationSearch
	 */
	public function setFirstDate(DateTime $firstDate): OperationSearch
	{
		$this->firstDate = $firstDate;
		return $this;
	}

	/**
	 * @return DateTime|null
	 */
	public function getSecondDate(): ?DateTime
	{
		return $this->secondDate;
	}

	/**
	 * @param DateTime|null $secondDate
	 * @return OperationSearch
	 */
	public function setSecondDate(DateTime $secondDate): OperationSearch
	{
		$this->secondDate = $secondDate;
		return $this;
	}
}
