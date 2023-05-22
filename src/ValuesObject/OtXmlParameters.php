<?php

namespace OtApiClient\ValuesObject;

/**
 * Class OtXmlParameters
 * @package OtApiClient\ValuesObject
 */
abstract class OtXmlParameters
{
	/** @var string  */
	protected string $paramName;

	abstract public function createXmlParameters();

	/*** @return string	 */
	public function getParamName(): string
	{
		return $this->paramName;
	}


}