<?php

namespace KikCMS\Controllers;


use KikCMS\Classes\DbService;
use KikCMS\Classes\Exceptions\DbForeignKeyDeleteException;
use KikCMS\Classes\Exceptions\NotFoundException;
use KikCMS\Classes\Finder\Finder;
use KikCMS\Classes\Finder\FinderFileService;
use KikCMS\Classes\Finder\FinderFilters;
use KikCMS\Classes\Translator;
use KikCMS\Models\FinderFile;

/**
 * @property DbService $dbService
 * @property FinderFileService $finderFileService
 * @property Translator $translator
 */
class FinderController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function initialize()
    {
        parent::initialize();

        $this->view->disable();
    }

    /**
     * @return string
     */
    public function createFolderAction()
    {
        $finder     = $this->getFinder();
        $folderName = $this->request->getPost('folderName');
        $folderId   = $finder->getFilters()->getFolderId();

        $folderId = $this->finderFileService->createFolder($folderName, $folderId);

        return json_encode([
            'files'   => $finder->renderFiles(),
            'fileIds' => [$folderId],
        ]);
    }

    /**
     * @return string
     */
    public function deleteAction()
    {
        $finder       = $this->getFinder();
        $fileIds      = $this->request->getPost('fileIds');
        $errorMessage = null;

        try {
            $this->finderFileService->deleteFilesByIds($fileIds);
        } catch (DbForeignKeyDeleteException $e) {
            $errorMessage = $this->translator->tl('media.deleteErrorLinked');
        }

        return json_encode([
            'files'        => $finder->renderFiles(),
            'errorMessage' => $errorMessage
        ]);
    }

    /**
     * @return string
     */
    public function editFileNameAction()
    {
        $finder   = $this->getFinder();
        $fileId   = $this->request->getPost('fileId');
        $fileName = $this->request->getPost('fileName');

        $this->finderFileService->updateFileNameById($fileId, $fileName);

        return json_encode([
            'files'   => $finder->renderFiles(),
            'fileIds' => [$fileId]
        ]);
    }

    /**
     * @param int $fileId
     * @return string
     * @throws NotFoundException
     */
    public function fileAction(int $fileId)
    {
        /** @var FinderFile $finderFile */
        if ( ! $finderFile = FinderFile::getById($fileId)) {
            throw new NotFoundException();
        }

        $filePath = $this->finderFileService->getFilePath($finderFile);

        return $this->outputFile($filePath, $finderFile->getMimeType(), $finderFile->getName());
    }

    /**
     * @return string
     */
    public function openFolderAction()
    {
        $finder = $this->getFinder();

        return json_encode([
            'files' => $finder->renderFiles(),
            'path'  => $finder->renderPath(),
        ]);
    }

    /**
     * @return string
     */
    public function pasteAction()
    {
        $finder   = $this->getFinder();
        $fileIds  = $this->request->getPost('fileIds');
        $folderId = $finder->getFilters()->getFolderId();

        $this->finderFileService->moveFilesToFolderById($fileIds, $folderId);

        return json_encode([
            'files'   => $finder->renderFiles(),
            'fileIds' => $fileIds,
        ]);
    }

    /**
     * @return string
     */
    public function searchAction()
    {
        $finder = $this->getFinder();

        if ($finder->getFilters()->getSearch()) {
            $finder->getFilters()->setFolderId(0);
        }

        return json_encode([
            'files' => $finder->renderFiles(),
            'path'  => $finder->renderPath(),
        ]);
    }

    /**
     * @param int $fileId
     * @return string
     * @throws NotFoundException
     */
    public function thumbAction(int $fileId)
    {
        /** @var FinderFile $finderFile */
        if ( ! $finderFile = FinderFile::getById($fileId)) {
            throw new NotFoundException();
        }

        $thumbPath = $this->finderFileService->getThumbPath($finderFile);

        if ( ! file_exists($thumbPath)) {
            $this->finderFileService->createThumb($finderFile);
        }

        return $this->outputFile($thumbPath, $finderFile->getMimeType(), $finderFile->getName());
    }

    /**
     * @return string
     */
    public function uploadAction()
    {
        $finder        = $this->getFinder();
        $uploadedFiles = $this->request->getUploadedFiles();
        $uploadStatus  = $finder->uploadFiles($uploadedFiles);

        return json_encode([
            'files'   => $finder->renderFiles(),
            'fileIds' => $uploadStatus->getFileIds(),
            'errors'  => $uploadStatus->getErrors(),
        ]);
    }

    /**
     * Construct a Finder instance with filters from the requests
     *
     * @return Finder
     */
    private function getFinder(): Finder
    {
        $filters = new FinderFilters();
        $filters->setByArray($this->request->getPost());

        $finder = new Finder();
        $finder->setFilters($filters);

        return $finder;
    }
}