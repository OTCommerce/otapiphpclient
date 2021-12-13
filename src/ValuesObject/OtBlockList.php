<?php

namespace OtapiClient\ValuesObject;

/**
 * Class OtBlockList
 * @package OtapiClient\ValuesObject
 */
class OtBlockList
{
	/*** @var bool */
	private bool $SubCategories = FALSE;
	/*** @var bool */
	private bool $SearchProperties = FALSE;
	/*** @var bool */
	private bool $RootPath = FALSE;
	/*** @var bool */
	private bool $Vendor = FALSE;
	/*** @var bool */
	private bool $Brand = FALSE;
	/*** @var bool */
	private bool $Category = FALSE;
	/*** @var bool */
	private bool $HintCategories = FALSE;
	/*** @var bool */
	private bool $AvailableSearchMethods = FALSE;

	/*** @param bool $SubCategories */
	public function setSubCategories(bool $SubCategories): void
	{
		$this->SubCategories = $SubCategories;
	}

	/*** @param bool $SearchProperties */
	public function setSearchProperties(bool $SearchProperties): void
	{
		$this->SearchProperties = $SearchProperties;
	}

	/*** @param bool $RootPath */
	public function setRootPath(bool $RootPath): void
	{
		$this->RootPath = $RootPath;
	}

	/*** @param bool $Vendor */
	public function setVendor(bool $Vendor): void
	{
		$this->Vendor = $Vendor;
	}

	/*** @param bool $Brand */
	public function setBrand(bool $Brand): void
	{
		$this->Brand = $Brand;
	}

	/*** @param bool $Category */
	public function setCategory(bool $Category): void
	{
		$this->Category = $Category;
	}

	/*** @param bool $HintCategories */
	public function setHintCategories(bool $HintCategories): void
	{
		$this->HintCategories = $HintCategories;
	}

	/*** @param bool $AvailableSearchMethods */
	public function setAvailableSearchMethods(bool $AvailableSearchMethods): void
	{
		$this->AvailableSearchMethods = $AvailableSearchMethods;
	}

	/*** @return bool */
	public function isSubCategories(): bool
	{
		return $this->SubCategories;
	}

	/*** @return bool */
	public function isSearchProperties(): bool
	{
		return $this->SearchProperties;
	}

	/*** @return bool */
	public function isRootPath(): bool
	{
		return $this->RootPath;
	}

	/*** @return bool */
	public function isVendor(): bool
	{
		return $this->Vendor;
	}

	/*** @return bool */
	public function isBrand(): bool
	{
		return $this->Brand;
	}

	/*** @return bool */
	public function isCategory(): bool
	{
		return $this->Category;
	}

	/*** @return bool */
	public function isHintCategories(): bool
	{
		return $this->HintCategories;
	}

	/*** @return bool */
	public function isAvailableSearchMethods(): bool
	{
		return $this->AvailableSearchMethods;
	}

	/*** @return string */
	public function getData(): string
	{
		$props      = get_object_vars($this);
		$parameters = [];
		foreach ($props as $propName => $propValue) {
			if ($propValue === TRUE) {
				$parameters[] = $propName;
			}
		}
		return count($parameters) ? implode(',', $parameters) : '';
	}

}
