<?php

namespace KikCMS\Classes\DataTable;


use KikCMS\Classes\Renderable\Filters;

class DataTableFilters extends Filters
{
    const FILTER_SEARCH         = 'search';
    const FILTER_PAGE           = 'page';
    const FILTER_SORT_COLUMN    = 'sortColumn';
    const FILTER_SORT_DIRECTION = 'sortDirection';
    const FILTER_EDIT_ID        = 'editId';
    const FILTER_PARENT_EDIT_ID = 'parentEditId';

    const FILTER_TYPES = [
        self::FILTER_SEARCH,
        self::FILTER_PAGE,
        self::FILTER_SORT_COLUMN,
        self::FILTER_SORT_DIRECTION,
        self::FILTER_EDIT_ID,
        self::FILTER_PARENT_EDIT_ID,
    ];

    /** @var int */
    private $page = 1;

    /** @var string */
    private $search = '';

    /** @var string */
    private $sortColumn = '';

    /** @var string */
    private $sortDirection = 'asc';

    /** @var int */
    private $editId = null;

    /** @var int */
    private $parentEditId = 0;

    /**
     * @return int|null
     */
    public function getEditId()
    {
        return $this->editId;
    }

    /**
     * @param int $editId
     * @return DataTableFilters
     */
    public function setEditId(int $editId): DataTableFilters
    {
        $this->editId = $editId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     * @return DataTableFilters
     */
    public function setPage(int $page): DataTableFilters
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return string
     */
    public function getSearch(): string
    {
        return $this->search;
    }

    /**
     * @param string $search
     * @return DataTableFilters
     */
    public function setSearch(string $search): DataTableFilters
    {
        $this->search = $search;
        return $this;
    }

    /**
     * @return string
     */
    public function getSortColumn(): string
    {
        return $this->sortColumn;
    }

    /**
     * @param string $sortColumn
     * @return DataTableFilters
     */
    public function setSortColumn(string $sortColumn): DataTableFilters
    {
        $this->sortColumn = $sortColumn;
        return $this;
    }

    /**
     * @return string
     */
    public function getSortDirection(): string
    {
        return $this->sortDirection;
    }

    /**
     * @param string $sortDirection
     * @return DataTableFilters
     */
    public function setSortDirection(string $sortDirection): DataTableFilters
    {
        $this->sortDirection = $sortDirection;
        return $this;
    }

    /**
     * @return int
     */
    public function getParentEditId(): int
    {
        return $this->parentEditId;
    }

    /**
     * @param int $parentEditId
     * @return DataTableFilters
     */
    public function setParentEditId(int $parentEditId): DataTableFilters
    {
        $this->parentEditId = $parentEditId;
        return $this;
    }
}