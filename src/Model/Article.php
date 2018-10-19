<?php
/**
 * Created by PhpStorm.
 * User: wcs
 * Date: 23/10/17
 * Time: 10:57
 * PHP version 7
 */

namespace Model;

/*
 * Class Article
 *
 */
class Article
{

    private $id;
    private $name;
    private $category;
    private $price;
    private $picture;
    private $description;
    private $review;
    private $highlight;

    /**
     * @return int
     */
    public function getId():int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCategory():string
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getPrice():float
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture(string $picture): void
    {
        $this->picture = $picture;
    }

    /**
     * @return string
     */
    public function getDescription():string
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getReview():string
    {
        return $this->review;
    }

    /**
     * @param mixed $review
     */
    public function setReview(string $review): void
    {
        $this->review = $review;
    }

    /**
     * @return mixed
     */
    public function getHighlight()
    {
        return $this->highlight;
    }

    /**
     * @param mixed $highlight
     */
    public function setHighlight($highlight): void
    {
        $this->highlight = $highlight;

    }
}
