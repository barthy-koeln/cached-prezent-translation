<?php

namespace Barthy\CachedPrezentTranslation\Tests;

use Barthy\CachedPrezentTranslation\CachedPrezentTranslationTrait;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslation;
use ReflectionClass;
use RuntimeException;

class TranslatedEntity extends AbstractTranslatable
{
    use CachedPrezentTranslationTrait;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }
}

class EntityTranslation extends AbstractTranslation
{
}

class CachedPrezentTranslationTraitTest extends TestCase
{
    /**
     * @var TranslatedEntity
     */
    private $entity;

    /**
     * @throws \ReflectionException
     */
    protected function setUp()
    {
        $this->entity = new TranslatedEntity();

        $class = new ReflectionClass($this->entity);

        $property = $class->getProperty('fallbackLocale');
        $property->setAccessible(true);
        $property->setValue($this->entity, 'en');

        $property = $class->getProperty('currentLocale');
        $property->setAccessible(true);
        $property->setValue($this->entity, 'de');

        parent::setUp();
    }

    public function testTranslation()
    {
        $translation = new EntityTranslation();
        $translation->setLocale('de');
        $this->entity->addTranslation($translation);

        self::assertEquals($translation, $this->entity->translate('de'));
        self::assertEquals($translation, $this->entity->translate('de'));
        self::assertEquals($translation, $this->entity->translate());
    }

    public function testLocaleException()
    {
        self::expectException(RuntimeException::class);
        $invalidEntity = new TranslatedEntity();
        $invalidEntity->translate();
    }

    public function testTranslationException()
    {
        self::expectException(RuntimeException::class);
        $this->entity->translate('ru');
    }

    protected function tearDown()
    {
        $this->entity = null;

        parent::tearDown();
    }
}
