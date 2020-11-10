<?php

namespace BarthyKoeln\CachedPrezentTranslation;

use Doctrine\Common\Collections\ArrayCollection;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslation;
use RuntimeException;

/**
 * @author  barthy <post@barthy.koeln>
 * @license MIT
 */
trait CachedPrezentTranslationTrait
{
    /**
     * @Prezent\CurrentLocale
     */
    protected ?string $currentLocale = null;

    /**
     * @Prezent\FallbackLocale
     */
    protected ?string $fallbackLocale = null;

    /**
     * Cache current translation. Useful in Doctrine 2.4+.
     */
    protected ?AbstractTranslation $cachedTranslation = null;

    public function translate(string $locale = null): AbstractTranslation
    {
        if (null === $locale) {
            $locale = $this->getCurrentLocale();
        }

        if (!$locale) {
            throw new RuntimeException('No locale has been set and currentLocale is empty');
        }

        $currentTranslation = $this->getCachedTranslation();
        if (null !== $currentTranslation && $currentTranslation->getLocale() === $locale) {
            return $this->getCachedTranslation();
        }

        /**
         * @var ArrayCollection $translations
         */
        $translations = $this->getTranslations();

        $translation = $translations->get($locale);
        if (null === $translation) {
            $translation = $translations->get($this->getFallbackLocale());

            if (null === $translation) {
                throw new RuntimeException('No translation in current or fallback locale');
            }
        }

        $this->setCachedTranslation($translation);

        return $translation;
    }

    protected function getCurrentLocale(): ?string
    {
        return $this->currentLocale;
    }

    protected function getCachedTranslation(): ?AbstractTranslation
    {
        return $this->cachedTranslation;
    }

    protected function setCachedTranslation(AbstractTranslation $cachedTranslation): void
    {
        $this->cachedTranslation = $cachedTranslation;
    }

    protected function getFallbackLocale(): ?string
    {
        return $this->fallbackLocale;
    }
}
