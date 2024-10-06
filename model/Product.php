<?php

class Product
{
	public string $name;
	public string $description;
	public string $category;
	public float $price;
	/**
	 * @var array string[]
	 */
	public array $images;

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @return float
	 */
	public function getPrice(): float
	{
		return $this->price;
	}

	/**
	 * @return array string[]
	 */
	public function getImages(): array
	{
		return $this->images;
	}
}
