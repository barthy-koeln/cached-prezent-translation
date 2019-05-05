<?php

namespace Barthy\CachedPrezentTranslation;

use Doctrine\Common\Collections\ArrayCollection;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslation;
use RuntimeException;

/**
 * Trait CachedPrezentTranslationTrait.
 *
 * @author  barthy <post@barthy.koeln>
 * @license MIT
 */
trait CachedPrezentTranslationTrait
{
    /**
     * @var string
     * @Prezent\CurrentLocale
     */
    protected $currentLocale;

    /**
     * @var string
     * @Prezent\FallbackLocale
     */
    protected $fallbackLocale;

    /**
     * @var AbstractTranslation
     *
     * Cache current translation. Useful in Doctrine 2.4+
     */
    protected $currentTranslation;

    /**
     * Translation helper method.
     *
     * @return AbstractTranslation|null
     */
    public function translate(string $locale = null): AbstractTranslation
    {
        if (null === $locale) {
            $locale = $this->getCurrentLocale();
        }

        if (!$locale) {
            throw new RuntimeException('No locale has been set and currentLocale is empty');
        }

        $currentTranslation = $this->getCurrentTranslation();
        if ($currentTranslation && $currentTranslation->getLocale() === $locale) {
            return $this->getCurrentTranslation();
        }

        /**
         * @var ArrayCollection $translations
         */
        $translations = $this->getTranslations();

        $translation = $translations->get($locale);
        if (empty($translation)) {
            $translation = $translations->get($this->getFallbackLocale());
            if (empty($translation)) {
                throw new RuntimeException('No translation in current or fallback locale');
            }
        }

        $this->setCurrentTranslation($translation);

        return $translation;
    }

    /**
     * @return string
     */
    protected function getCurrentLocale(): ?string
    {
        return $this->currentLocale;
    }

    protected function getCurrentTranslation(): ?AbstractTranslation
    {
        return $this->currentTranslation;
    }

    protected function setCurrentTranslation(AbstractTranslation $currentTranslation): void
    {
        $this->currentTranslation = $currentTranslation;
    }

    /**
     * @return string
     */
    protected function getFallbackLocale(): ?string
    {
        return $this->fallbackLocale;
    }
}
