<?php

namespace OtapiClient\ValuesObject;

/**
 * Class OtXmlParameters
 * @package OtapiClient\ValuesObject
 */
class OtXmlParameters
{
	/*** @var string|null */
	private ?string $provider = NULL;
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
	/*** @var bool */
	private bool $isComplete = TRUE;
	/*** @var string|null */
	private ?string $vendorName = NULL;
	/*** @var string|null */
	private ?string $vendorId = NULL;
	/*** @var string|null */
	private ?string $itemTitle = NULL;
	/*** @var string|null */
	private ?string $brandId = NULL;

	/*** @return string|null */
	public function getProvider(): ?string
	{
		return $this->provider;
	}

	/*** @param string $provider */
	public function setProvider(string $provider): void
	{
		$this->provider = $provider;
	}

	/*** @return string|null */
	public function getCategoryId(): ?string
	{
		return $this->categoryId;
	}

	/*** @param string $categoryId */
	public function setCategoryId(string $categoryId): void
	{
		$this->categoryId = $categoryId;
	}

	/*** @return float|null */
	public function getMinPrice(): ?float
	{
		return $this->minPrice;
	}

	/*** @param float $minPrice */
	public function setMinPrice(float $minPrice): void
	{
		$this->minPrice = $minPrice;
	}

	/*** @return float|null */
	public function getMaxPrice(): ?float
	{
		return $this->maxPrice;
	}

	/*** @param float $maxPrice */
	public function setMaxPrice(float $maxPrice): void
	{
		$this->maxPrice = $maxPrice;
	}

	/*** @return int|null */
	public function getMinVolume(): ?int
	{
		return $this->minVolume;
	}

	/*** @param int $minVolume */
	public function setMinVolume(int $minVolume): void
	{
		$this->minVolume = $minVolume;
	}

	/*** @return string|null */
	public function getOrder(): ?string
	{
		return $this->order;
	}

	/*** @param string $order */
	public function setOrder(string $order): void
	{
		$this->order = $order;
	}

	/*** @return bool */
	public function getIsComplete(): bool
	{
		return $this->isComplete;
	}

	/*** @param bool $isComplete */
	public function setIsComplete(bool $isComplete): void
	{
		$this->isComplete = $isComplete;
	}

	/*** @return string|null */
	public function getVendorName(): ?string
	{
		return $this->vendorName;
	}

	/*** @param string $vendorName */
	public function setVendorName(string $vendorName): void
	{
		$this->vendorName = $vendorName;
	}

	/*** @return string|null */
	public function getVendorId(): ?string
	{
		return $this->vendorId;
	}

	/*** @param string $vendorId */
	public function setVendorId(string $vendorId): void
	{
		$this->vendorId = $vendorId;
	}

	/*** @return string|null */
	public function getItemTitle(): ?string
	{
		return $this->itemTitle;
	}

	/*** @param string $itemTitle */
	public function setItemTitle(string $itemTitle): void
	{
		$this->itemTitle = $itemTitle;
	}

	/*** @return string|null */
	public function getBrandId(): ?string
	{
		return $this->brandId;
	}

	/*** @param string $brandId */
	public function setBrandId(string $brandId): void
	{
		$this->brandId = $brandId;
	}

	/*** @return string */
	public function createXmlParameters(): string
	{
		$xmlData   = [];
		$xmlData[] = '<SearchItemsParameters>';
		if ($this->getProvider()) {
			$xmlData[] = '<Provider>' . $this->getProvider() . '</Provider>';
		}
		if ($this->getVendorName()) {
			$xmlData[] = '<VendorName>' . $this->getVendorName() . '</VendorName>';
		}
		if ($this->getVendorId()) {
			$xmlData[] = '<VendorId>' . $this->getVendorId() . '</VendorId>';
		}
		if ($this->getItemTitle()) {
			$xmlData[] = '<ItemTitle>' . $this->getItemTitle() . '</ItemTitle>';
		}
		if ($this->getBrandId()) {
			$xmlData[] = '<BrandId>' . $this->getBrandId() . '</BrandId>';
		}
		if ($this->getCategoryId()) {
			$xmlData[] = '<CategoryId>' . $this->getCategoryId() . '</CategoryId>';
		}
		if ($this->getMinPrice()) {
			$xmlData[] = '<MinPrice>' . $this->getMinPrice() . '</MinPrice>';
		}
		if ($this->getMaxPrice()) {
			$xmlData[] = '<MaxPrice>' . $this->getMaxPrice() . '</MaxPrice>';
		}
		if ($this->getMinVolume()) {
			$xmlData[] = '<MinVolume>' . $this->getMinVolume() . '</MinVolume>';
		}
		if ($this->getOrder()) {
			$xmlData[] = '<OrderBy>' . $this->getOrder() . '</OrderBy>';
		} else {
			$xmlData[] = '<OrderBy>Default</OrderBy>';
		}
		$xmlData[] = '<Features><Feature Name="IsComplete">' . ($this->getIsComplete() ? 'true' : 'false') . '</Feature></Features>';
		$xmlData[] = '</SearchItemsParameters>';
		return implode('', $xmlData);
	}

}
