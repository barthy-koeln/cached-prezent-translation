# Cached Translation

[![CircleCI](https://circleci.com/gh/barthy-koeln/cached-prezent-translation.svg?style=svg&circle-token=55da37f6895e997d545b9548abdc22f54adecbb5)](https://circleci.com/gh/barthy-koeln/cached-prezent-translation)
[![Coverage](https://img.shields.io/endpoint.svg?url=https%3A%2F%2Fbadges.barthy.koeln%2Fbadge%2Fcached-prezent-translation%2Fcoverage)](https://circleci.com/gh/barthy-koeln/cached-prezent-translation/tree/master)

This library provides a simple trait that can be used with the [prezent/translation-bundle](https://github.com/Prezent/translation-bundle).
The trait stores the current locale, fallback locale, and current translation.
It also provides a method to extract the desired translation or the fallback translation if set.

## Usage

### Entity

````php
<?php

use Barthy\CachedPrezentTranslation\CachedPrezentTranslationTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;

class TranslatedEntity extends AbstractTranslatable
{

    use CachedPrezentTranslationTrait;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }
    
    public function getTitle(?string $locale = null): string
    {
        /**
         * @var \Prezent\Doctrine\Translatable\Entity\AbstractTranslation $trans
         */
        $trans = $this->translate($locale);
        
        return $trans->getTitle();
    }
}
````
