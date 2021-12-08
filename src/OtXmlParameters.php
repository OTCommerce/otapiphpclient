<?php

namespace OtapiClient;

/*** */
class OtXmlParameters
{
	/*** @var string|null */
	private ?string $providerId = NULL;
	/*** @var string|null */
	private ?string $categoryId = NULL;
	/*** @var float|null */
	private ?float $minPrice = NULL;
	/*** @var float|null */
	private ?float $maxPrice = NULL;
	/*** @var int|null */
	private ?int $minVolume = NULL;
	/*** @var string|null */
	private ?string $order = NULL;

	/**
	 * @return string|null
	 */
	public function getProviderId(): ?string
	{
		return $this->providerId;
	}

	/**
	 * @param string|null $providerId
	 */
	public function setProviderId(?string $providerId): void
	{
		$this->providerId = $providerId;
	}

	/**
	 * @return string|null
	 */
	public function getCategoryId(): ?string
	{
		return $this->categoryId;
	}

	/**
	 * @param string|null $categoryId
	 */
	public function setCategoryId(?string $categoryId): void
	{
		$this->categoryId = $categoryId;
	}

	/**
	 * @return float|null
	 */
	public function getMinPrice(): ?float
	{
		return $this->minPrice;
	}

	/**
	 * @param float|null $minPrice
	 */
	public function setMinPrice(?float $minPrice): void
	{
		$this->minPrice = $minPrice;
	}

	/**
	 * @return float|null
	 */
	public function getMaxPrice(): ?float
	{
		return $this->maxPrice;
	}

	/**
	 * @param float|null $maxPrice
	 */
	public function setMaxPrice(?float $maxPrice): void
	{
		$this->maxPrice = $maxPrice;
	}

	/**
	 * @return int|null
	 */
	public function getMinVolume(): ?int
	{
		return $this->minVolume;
	}

	/**
	 * @param int|null $minVolume
	 */
	public function setMinVolume(?int $minVolume): void
	{
		$this->minVolume = $minVolume;
	}

	/**
	 * @return string|null
	 */
	public function getOrder(): ?string
	{
		return $this->order;
	}

	/**
	 * @param string|null $order
	 */
	public function setOrder(?string $order): void
	{
		$this->order = $order;
	}

	/*** @return array */
	public function getData(): array
	{
		$props      = get_object_vars($this);
		$parameters = [];
		foreach ($props as $propName => $propValue) {
			if (is_null($propValue)) {
				continue;
			}
			if ( ! is_array($propValue) || count($propValue)) {
				$parameters[$propName] = $propValue;
			}
		}
		return $parameters;
	}

}
