<?php

namespace KikCMS\Classes\Phalcon\Validator;


use KikCMS\Models\FinderFile;
use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;

class FileType extends Validator
{
    const OPTION_FILETYPES = 'fileTypes';

    /**
     * @inheritdoc
     */
    public function validate(Validation $validator, $field)
    {
        $value = $validator->getValue($field);

        if ( ! $value) {
            return true;
        }

        $allowedFileTypes = $this->getOption(self::OPTION_FILETYPES);

        $finderFile = FinderFile::getById($value);

        if ( ! $finderFile) {
            return true;
        }

        if (in_array(strtolower($finderFile->getExtension()), $allowedFileTypes)) {
            return true;
        }

        $message = $validator->getDefaultMessage('FinderFileType');
        $message = str_replace(':types', implode(', ', $allowedFileTypes), $message);

        $validator->appendMessage(new Message($message));

        return false;
    }
}