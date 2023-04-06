<?php

namespace Value;

Use Tools\Entity;

class Value extends Entity {

    private $id;
    private $value;
    private $category;
    private $date;
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
    
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }
    
    public function setValue($value): self
    {
        $this->value = $value;
    
        return $this;
    }
    
    public function getCategory()
    {
        return $this->category;
    }
    
    public function setCategory($category): self
    {
        $this->category = $category;
    
        return $this;
    }
    
    public function getDate()
    {
        return $this->date;
    }
    
    public function setDate($date): self
    {
        $this->date = $date;
    
        return $this;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function setUser($user): self
    {
        $this->user = $user;
    
        return $this;
    }
}
