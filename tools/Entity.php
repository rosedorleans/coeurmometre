<?php
namespace Tools;

/**
 * Mother of object class
 */
abstract class Entity {
    public function hydrate(array $infos)
    {
        foreach ($infos as $clef => $donnee)
        {
            $methode = 'set'.$clef;
            if (method_exists($this, $methode))
            {
                $this->$methode($donnee);
            }
        }
    }
}