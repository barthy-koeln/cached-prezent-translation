<?php

namespace BarthyKoeln\CachedPrezentTranslation\Tests;

use BarthyKoeln\CachedPrezentTranslation\CachedPrezentTranslationTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;

class TranslatedEntity extends AbstractTranslatable
{
    use CachedPrezentTranslationTrait;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }
}
