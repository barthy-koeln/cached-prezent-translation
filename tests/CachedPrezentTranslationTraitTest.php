<?php

namespace BarthyKoeln\CachedPrezentTranslation\Tests;

use BarthyKoeln\BeautifySpecify\Specify;
use BarthyKoeln\CachedPrezentTranslation\CachedPrezentTranslationTrait;
use Codeception\AssertThrows;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

class CachedPrezentTranslationTraitTest extends TestCase
{
    use Specify;
    use AssertThrows;

    private ?TranslatedEntity $entity                   = null;
    private ?TranslatedEntityTranslation $translationEN = null;
    private ?TranslatedEntityTranslation $translationDE = null;

    public function testAll(): void
    {
        $this->describe(
            CachedPrezentTranslationTrait::class,
            function () {
                $this->it(
                    'returns correct translations',
                    function () {
                        self::assertEquals($this->translationEN, $this->entity->translate('en'));
                        self::assertEquals($this->translationDE, $this->entity->translate('de'));
                    }
                );

                $this->it(
                    'returns correct fallback translations',
                    function () {
                        try {
                            $class = new ReflectionClass($this->entity);
                            $property = $class->getProperty('fallbackLocale');
                            $property->setAccessible(true);
                            $property->setValue($this->entity, 'en');

                            self::assertEquals($this->translationEN, $this->entity->translate('ru'));
                        } catch (ReflectionException $e) {
                            $this->assertTrue(false, $e->getMessage());
                        }
                    }
                );

                $this->it(
                    'returns correct current (aka. application locale) translations',
                    function () {
                        try {
                            $class = new ReflectionClass($this->entity);
                            $property = $class->getProperty('currentLocale');
                            $property->setAccessible(true);
                            $property->setValue($this->entity, 'de');

                            self::assertEquals($this->translationDE, $this->entity->translate());
                        } catch (ReflectionException $e) {
                            $this->assertTrue(false, $e->getMessage());
                        }
                    }
                );

                $this->it(
                    'sets and returns correct cached translations',
                    function () {
                        try {
                            $class = new ReflectionClass($this->entity);
                            $property = $class->getProperty('cachedTranslation');
                            $property->setAccessible(true);

                            $property->setValue($this->entity, null);
                            $this->entity->translate('de');

                            self::assertEquals($this->translationDE, $property->getValue($this->entity));
                            self::assertEquals($this->translationDE, $this->entity->translate());
                        } catch (ReflectionException $e) {
                            $this->assertTrue(false, $e->getMessage());
                        }
                    }
                );

                $this->it(
                    'throws an exception if no translation is found (including fallback)',
                    function () {
                        $this->assertThrows(RuntimeException::class, function () {
                            $invalidEntity = new TranslatedEntity();
                            $invalidEntity->translate();
                        });

                        $this->assertThrows(RuntimeException::class, function () {
                            $this->entity->removeTranslation($this->translationEN);
                            $this->entity->removeTranslation($this->translationDE);
                            $this->entity->translate('ru');
                        });
                    }
                );
            }
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->entity = new TranslatedEntity();

        $this->translationEN = new TranslatedEntityTranslation();
        $this->translationEN->setLocale('en');
        $this->entity->addTranslation($this->translationEN);

        $this->translationDE = new TranslatedEntityTranslation();
        $this->translationDE->setLocale('de');
        $this->entity->addTranslation($this->translationDE);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->translationDE = null;
        $this->translationEN = null;
        $this->entity        = null;
    }
}
