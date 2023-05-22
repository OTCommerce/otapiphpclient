<?php

namespace OtApiClient\ValuesObject;

/**
 * Class OtXmlSearchParameters
 * @package OtApiClient\ValuesObject
 */
class OtXmlSearchParameters extends OtXmlParameters
{
	/*** @var string */
	protected string $paramName = 'xmlSearchParameters';
	/*** @var string|NULL */
	private ?string $itemId = NULL;
	/*** @var string|NULL */
	private ?string $source = NULL;

	/*** @return string|NULL */
	public function getItemId(): ?string
	{
		return $this->itemId;
	}

	/*** @param string|NULL $itemId */
	public function setItemId(?string $itemId): void
	{
		$this->itemId = $itemId;
	}

	/*** @return string|NULL	 */
	public function getSource(): ?string
	{
		return $this->source;
	}

	/*** @param string|NULL $source	 */
	public function setSource(?string $source): void
	{
		$this->source = $source;
	}

	/*** @return string */
	public function createXmlParameters(): string
	{
		$xmlData   = [];
		$xmlData[] = '<SearchParameters>';
		if ($this->getItemId()) {
			$xmlData[] = '<ItemId>' . $this->getItemId() . '</ItemId>';
		}
		if ($this->getSource()) {
			$xmlData[] = '<Source>' . $this->getSource() . '</Source>';
		}
		$xmlData[] = '</SearchParameters>';
		return implode('', $xmlData);
	}

}