<?php

namespace OtApiClient\ValuesObject;

/**
 * Class OtParameters
 * @package OtapiClient\ValuesObject
 */
class OtParameters
{
	/*** @var int|NULL */
	private ?int $framePosition = NULL;
	/*** @var int|NULL */
	private ?int $frameSize = NULL;
	/*** @var string|NULL */
	private ?string $activityId = NULL;
	/*** @var bool|NULL */
	private ?bool $getResult = NULL;
	/*** @var string|NULL */
	private ?string $ids = NULL;
	/*** @var OtBlockList|NULL */
	private ?OtBlockList $blockList = NULL;
	/*** @var string|NULL */
	private ?string $itemId = NULL;
	/*** @var string|NULL */
	private ?string $itemReviewId = NULL;
	/*** @var string|NULL */
	private ?string $providerType = NULL;

	/*** @return int|null */
	public function getFramePosition(): ?int
	{
		return $this->framePosition;
	}

	/*** @param int $framePosition */
	public function setFramePosition(int $framePosition): void
	{
		$this->framePosition = $framePosition;
	}

	/*** @return int|null */
	public function getFrameSize(): ?int
	{
		return $this->frameSize;
	}

	/*** @param int $frameSize */
	public function setFrameSize(int $frameSize): void
	{
		$this->frameSize = $frameSize;
	}

	/*** @return string|null */
	public function getActivityId(): ?string
	{
		return $this->activityId;
	}

	/*** @param string $activityId */
	public function setActivityId(string $activityId): void
	{
		$this->activityId = $activityId;
	}

	/*** @return bool|null */
	public function getGetResult(): ?bool
	{
		return $this->getResult;
	}

	/*** @param bool $getResult */
	public function setGetResult(bool $getResult): void
	{
		$this->getResult = $getResult;
	}

	/*** @return string|null */
	public function getIds(): ?string
	{
		return $this->ids;
	}

	/*** @param array $ids */
	public function setIds(array $ids): void
	{
		$this->ids = implode(';', $ids);
	}

	/*** @return OtBlockList|null */
	public function getBlockList(): ?OtBlockList
	{
		return $this->blockList;
	}

	/*** @param OtBlockList $blockList */
	public function setBlockList(OtBlockList $blockList): void
	{
		$this->blockList = $blockList;
	}

	/*** @return string|NULL */
	public function getItemId(): ?string
	{
		return $this->itemId;
	}

	/*** @param string $itemId */
	public function setItemId(string $itemId): void
	{
		$this->itemId = $itemId;
	}

	/*** @return string|NULL */
	public function getItemReviewId(): ?string
	{
		return $this->itemReviewId;
	}

	/*** @param string|NULL $itemReviewId */
	public function setItemReviewId(?string $itemReviewId): void
	{
		$this->itemReviewId = $itemReviewId;
	}

	/*** @return string|NULL */
	public function getProviderType(): ?string
	{
		return $this->providerType;
	}

	/*** @param string|NULL $providerType */
	public function setProviderType(?string $providerType): void
	{
		$this->providerType = $providerType;
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
			if (is_object($propValue) && get_class($propValue) === OtBlockList::class) {
				$parameters[$propName] = $propValue->getData();
			} elseif ( ! is_array($propValue) || count($propValue)) {
				$parameters[$propName] = $propValue;
			}
		}
		return $parameters;
	}

}
