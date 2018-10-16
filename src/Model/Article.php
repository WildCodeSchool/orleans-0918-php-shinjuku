<?php
/**
 * Created by PhpStorm.
 * User: wcs
 * Date: 23/10/17
 * Time: 10:57
 * PHP version 7
 */

namespace Model;

/**
 * Class Product
 *
 */
class Article
{
    private $name;
    private $price;
    private $picture;
    private $category;
    private $id;

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice(int $price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getPicture(): string
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture(string $picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return mixed
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory(string $category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }


}
