<?php
declare(strict_types=1);

namespace Helpers;


use Exception;
use KikCMS\Classes\Permission;
use KikCMS\Models\Language;
use KikCMS\Models\User;
use KikCMS\Services\CacheService;
use KikCMS\Services\DataTable\NestedSetService;
use KikCMS\Services\DataTable\PageRearrangeService;
use KikCMS\Services\DataTable\RearrangeService;
use KikCMS\Services\LanguageService;
use KikCMS\Services\ModelService;
use KikCMS\Services\Pages\PageLanguageService;
use KikCMS\Services\Pages\PageService;
use KikCMS\Services\Pages\UrlService;
use KikCMS\Services\TranslationService;
use KikCMS\Services\WebForm\RelationKeyService;
use KikCMS\Services\WebForm\StorageService;
use KikCmsCore\Services\DbService;
use Phalcon\Cache\Backend\File;
use Phalcon\Cache\Frontend\Data;
use Phalcon\Cache\Frontend\Json;
use Phalcon\Config;
use Phalcon\Db\Adapter\Pdo\Sqlite;
use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Di;
use Phalcon\Escaper;
use Phalcon\Flash\Direct;
use Phalcon\Mvc\Model\Manager;
use Phalcon\Mvc\Model\MetaData\Memory;
use Phalcon\Security;
use Phalcon\Validation;
use Website\TestClasses\TemplateFields;
use Website\TestClasses\WebsiteSettings;
use KikCMS\Classes\Phalcon\PdoDialect\Sqlite as SqliteDialect;

class Unit extends \Codeception\Test\Unit
{
    /** @var Di */
    private $cachedDbDi;

    public function addDefaultLanguage()
    {
        $language = new Language();

        $language->code   = 'en';
        $language->active = 1;
        $language->save();
    }

    /**
     * Get a Di with a Db class that contains a Sqlite version of the KikCMS's dedb structure
     *
     * @return Di
     */
    public function getDbDi(): Di
    {
        if($this->cachedDbDi){
            Di::setDefault($this->cachedDbDi);
            return $this->cachedDbDi;
        }

        $di = new Di();
        $db = new Sqlite([
            "dbname"       => ":memory:",
            'dialectClass' => SqliteDialect::class
        ]);

        $translator = (new TestHelper)->getTranslator();

        $config = new Config();
        $config->application = new Config();
        $config->application->defaultLanguage = 'en';

        $frontend = new Json(["lifetime" => 3600 * 24 * 365 * 1000]);
        $keyValue = new File($frontend, ['cacheDir' => (new TestHelper)->getSitePath() . 'storage/keyvalue/']);

        $di->set('db', $db);
        $di->set('config', $config);
        $di->set('cacheService', new CacheService);
        $di->set('dbService', new DbService);
        $di->set('security', new Security);
        $di->set('modelsManager', new Manager);
        $di->set('modelsMetadata', new Memory);
        $di->set('languageService', new LanguageService);
        $di->set('templateFields', new TemplateFields);
        $di->set('pageService', new PageService);
        $di->set('nestedSetService', new NestedSetService);
        $di->set('pageRearrangeService', new PageRearrangeService);
        $di->set('websiteSettings', new WebsiteSettings);
        $di->set('pageLanguageService', new PageLanguageService);
        $di->set('urlService', new UrlService);
        $di->set('validation', new Validation);
        $di->set('storageService', new StorageService);
        $di->set('relationKeyService', new RelationKeyService);
        $di->set('flash', new Direct);
        $di->set('escaper', new Escaper);
        $di->set('modelService', new ModelService);
        $di->set('translationService', new TranslationService);
        $di->set('rearrangeService', new RearrangeService);
        $di->set('cache', new \Phalcon\Cache\Backend\Memory(new Data));
        $di->set('translator', $translator);
        $di->set('keyValue', $keyValue);

        Di::setDefault($di);

        $sql = file_get_contents((new TestHelper)->getTestPath() . 'sqlite.sql');

        $queries = explode(';', $sql);

        $db->begin();

        foreach ($queries as $query){
            $db->query($query);
        }

        $db->commit();

        $this->cachedDbDi = $di;

        return $di;
    }

    /**
     * @return User
     * @throws Exception
     */
    public function createAndSaveTestUser(): User
    {
        $user = new User();
        $user->id = 1;
        $user->email = 'test@test.com';
        $user->blocked = 0;
        $user->role = Permission::ADMIN;

        $user->save();

        return $user;
    }
}