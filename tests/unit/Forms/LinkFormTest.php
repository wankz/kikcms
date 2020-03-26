<?php
declare(strict_types=1);

namespace Forms;

use Helpers\Unit;
use KikCMS\Classes\Phalcon\AccessControl;
use KikCMS\Classes\Translator;
use KikCMS\Forms\LinkForm;
use Phalcon\Validation;
use ReflectionMethod;

class LinkFormTest extends Unit
{
    public function testInitialize()
    {
        $linkForm = new LinkForm();

        $translator = $this->createMock(Translator::class);
        $translator->method('tl')->willReturn('x');

        $acl = $this->createMock(AccessControl::class);
        $acl->method('allowed')->willReturn(true);

        $linkForm->acl        = $acl;
        $linkForm->translator = $translator;
        $linkForm->validation = $this->createMock(Validation::class);

        $linkForm->getFilters()->setLanguageCode('en');

        $method = new ReflectionMethod(LinkForm::class, 'initialize');
        $method->setAccessible(true);

        $method->invoke($linkForm);

        $this->assertNotEmpty($linkForm->getFieldMap());
    }
}
