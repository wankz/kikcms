<?php

namespace KikCMS\Classes;


use KikCMS\Config\CacheConfig;
use KikCMS\Config\KikCMSConfig;
use KikCMS\Models\TranslationKey;
use KikCMS\Models\TranslationValue;
use KikCMS\Services\CacheService;
use KikCMS\Services\TranslationService;
use Phalcon\Di\Injectable;
use Phalcon\Mvc\Model\Query\Builder;

/**
 * @property TranslationService $translationService
 * @property DbService $dbService
 * @property CacheService $cacheService
 */
class Translator extends Injectable
{
    private $languageCode = 'nl';

    /**
     * @param string|int|null $key
     * @param array $replaces
     *
     * @return string
     */
    public function tl($key, array $replaces = []): string
    {
        // numeric values given indicate it's a translation managed from a DataTable
        if (is_numeric($key)) {
            return $this->getDbTranslation($key);
        }

        $userTranslations    = $this->getUserTranslations();
        $websiteTranslations = $this->getWebsiteTranslations();
        $cmsTranslations     = $this->getCmsTranslations();

        $translations = $userTranslations + $websiteTranslations + $cmsTranslations;

        if( ! array_key_exists($key, $translations)){
            throw new \InvalidArgumentException('Translation key "' . $key . '" does not exist');
        }

        return $this->replace($translations[$key], $replaces);
    }

    /**
     * @param string $string
     * @return array
     */
    public function getCmsTranslationGroupKeys(string $string)
    {
        $translations = $this->getCmsTranslations();

        $group = [];

        foreach ($translations as $key => $value){
            if(substr($key, 0, strlen($string) + 1) === $string . '.'){
                $group[] = $key;
            }
        }

        return $group;
    }

    /**
     * @return array
     */
    public function getContentTypeMap()
    {
        $contentTypeMap = [];

        foreach (KikCMSConfig::CONTENT_TYPES as $key => $typeId) {
            $contentTypeMap[$typeId] = $this->tl('contentTypes.' . $key);
        }

        return $contentTypeMap;
    }

    /**
     * @return mixed
     */
    public function getLanguageCode()
    {
        return $this->languageCode;
    }

    /**
     * @param mixed $languageCode
     * @return Translator
     */
    public function setLanguageCode($languageCode)
    {
        $this->languageCode = $languageCode;
        return $this;
    }

    /**
     * @param $id
     * @return string
     */
    private function getDbTranslation(int $id): string
    {
        return (string) $this->translationService->getTranslationValue($id, $this->getLanguageCode());
    }

    /**
     * @param array $array
     * @param string $prefix
     * @return array
     */
    private function flatten(array $array, $prefix = ''): array
    {
        $result = array();

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = $result + $this->flatten($value, $prefix . $key . '.');
            } else {
                $result[$prefix . $key] = $value;
            }
        }
        return $result;
    }

    /**
     * @param string $translation
     * @param array $replaces
     * @return mixed|string
     */
    private function replace(string $translation, array $replaces)
    {
        foreach ($replaces as $key => $replace) {
            if ( ! is_string($replace)) {
                continue;
            }

            $translation = str_replace(':' . $key, $replace, $translation);
        }

        return $translation;
    }

    /**
     * @return array [translationKey => value]
     */
    private function getUserTranslations(): array
    {
        $cacheKey = CacheConfig::USER_TRANSLATIONS . ':' . $this->getLanguageCode();

        return $this->cacheService->cache($cacheKey, function () {
            $query = (new Builder())
                ->columns(['tk.key', 'tv.value'])
                ->from(['tv' => TranslationValue::class])
                ->join(TranslationKey::class, 'tk.id = tv.key_id', 'tk')
                ->where('tk.key IS NOT NULL AND tv.language_code = :languageCode:', [
                    'languageCode' => $this->getLanguageCode()
                ]);

            return $this->dbService->getAssoc($query);
        });
    }

    /**
     * @return array
     */
    private function getCmsTranslations(): array
    {
        return $this->getTranslations(__DIR__ . '/../../resources/translations/' . $this->getLanguageCode() . '.php');
    }

    /**
     * @param string $file
     * @return array
     */
    private function getTranslations(string $file): array
    {
        if ( ! file_exists($file)) {
            return [];
        }

        return $this->flatten(include $file);
    }

    /**
     * @return array
     */
    private function getWebsiteTranslations(): array
    {
        return $this->getTranslations(SITE_PATH . 'resources/translations/' . $this->getLanguageCode() . '.php');
    }
}