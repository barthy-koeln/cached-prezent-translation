# Cached Translation

[![CircleCI](https://circleci.com/gh/barthy-koeln/cached-prezent-translation.svg?style=svg&circle-token=55da37f6895e997d545b9548abdc22f54adecbb5)](https://circleci.com/gh/barthy-koeln/cached-prezent-translation)
[![Coverage](https://img.shields.io/endpoint.svg?url=https%3A%2F%2Fbadges.barthy.koeln%2Fbadge%2Fcached-prezent-translation%2Fcoverage)](https://circleci.com/gh/barthy-koeln/cached-prezent-translation/tree/master)

This library provides a simple trait that can be used with the [`prezent/doctrine-translatable-bundle`](https://github.com/Prezent/doctrine-translatable-bundle).

Disclaimer: This is mostly a copy-pasted thing from the [`prezent/doctrine-translatable` docs about proxy getters and setters](https://github.com/Prezent/doctrine-translatable/blob/master/doc/getting-started.md#proxy-getters-and-setters), adapted for >= php7.4 an opinionated code styles.

The trait stores the current locale, fallback locale, and caches the last fetched translation.

Usually, only one translation is necessary for an app: the current locale's translation or the fallback translation.
Since `prezent/translatable-bundle` uses [`FETCH_EXTRA_LAZY`](https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/tutorials/extra-lazy-associations.html),
the cached translation does not trigger any additional straight SELECT statements if queried from the object multiple times.

In a situation where more than one translation is needed (i.e. multiple translations must be loaded for the application,
it is best to either manually fully initialise the collection or to handle caching yourself.

## Usage

### Entity

````php
<?php

use BarthyKoeln\CachedPrezentTranslation\CachedPrezentTranslationTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;

class TranslatedEntity extends AbstractTranslatable
{

    use CachedPrezentTranslationTrait;

    /**
     * @Assert\Valid()
     * @Prezent\Translations(targetEntity="App\Entity\TranslatedEntityTranslation")
     * @var ArrayCollection
     */
    protected $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }
    
    public function getTitle(?string $locale = null): string
    {
        return $this->translate($locale)->getTitle();
    }
}
````
