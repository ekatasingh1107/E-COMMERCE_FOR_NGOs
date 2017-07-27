<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reports\Model\ResourceModel\Report;

/**
 * Abstract report aggregate resource model
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
abstract class AbstractReport extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Flag object
     *
     * @var \Magento\Reports\Model\Flag
     */
    protected $_flag = null;

    /**
     * Logger instance
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * Locale date instance
     *
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_localeDate;

    /**
     * Reports flag factory
     *
     * @var \Magento\Reports\Model\FlagFactory
     */
    protected $_reportsFlagFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTime;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Reports\Model\FlagFactory $reportsFlagFactory
     * @param \Magento\Framework\Stdlib\DateTime\Timezone\Validator $timezoneValidator
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param null $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Reports\Model\FlagFactory $reportsFlagFactory,
        \Magento\Framework\Stdlib\DateTime\Timezone\Validator $timezoneValidator,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->_logger = $logger;
        $this->_localeDate = $localeDate;
        $this->_reportsFlagFactory = $reportsFlagFactory;
        $this->timezoneValidator = $timezoneValidator;
        $this->dateTime = $dateTime;
    }

    /**
     * Retrieve flag object
     *
     * @return \Magento\Reports\Model\Flag
     */
    protected function _getFlag()
    {
        if ($this->_flag === null) {
            $this->_flag = $this->_reportsFlagFactory->create();
        }
        return $this->_flag;
    }

    /**
     * Saves flag
     *
     * @param string $code
     * @param mixed $value
     * @return $this
     */
    protected function _setFlagData($code, $value = null)
    {
        $this->_getFlag()->setReportFlagCode($code)->unsetData()->loadSelf();

        if ($value !== null) {
            $this->_getFlag()->setFlagData($value);
        }

        // touch last_update
        $this->_getFlag()->setLastUpdate($this->dateTime->gmtDate());

        $this->_getFlag()->save();

        return $this;
    }

    /**
     * Retrieve flag data
     *
     * @param string $code
     * @return mixed
     */
    protected function _getFlagData($code)
    {
        $this->_getFlag()->setReportFlagCode($code)->unsetData()->loadSelf();

        return $this->_getFlag()->getFlagData();
    }

    /**
     * Truncate table
     *
     * @param string $table
     * @return $this
     */
    protected function _truncateTable($table)
    {
        if ($this->getConnection()->getTransactionLevel() > 0) {
            $this->getConnection()->delete($table);
        } else {
            $this->getConnection()->truncateTable($table);
        }
        return $this;
    }

    /**
     * Clear report table by specified date range.
     * If specified source table parameters,
     * condition will be generated by source table sub-select.
     *
     * @param string $table
     * @param null|string $from
     * @param null|string $to
     * @param null|\Magento\Framework\DB\Select|string $subSelect
     * @param bool $doNotUseTruncate
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $connection
     * @return $this
     */
    protected function _clearTableByDateRange(
        $table,
        $from = null,
        $to = null,
        $subSelect = null,
        $doNotUseTruncate = false,
        $connection = null
    ) {
        if ($from === null && $to === null && !$doNotUseTruncate) {
            $this->_truncateTable($table);
            return $this;
        }

        if ($subSelect !== null) {
            $deleteCondition = $this->_makeConditionFromDateRangeSelect($subSelect, 'period', $connection);
        } else {
            $condition = [];
            if ($from !== null) {
                $condition[] = $this->getConnection()->quoteInto('period >= ?', $from);
            }

            if ($to !== null) {
                $condition[] = $this->getConnection()->quoteInto('period <= ?', $to);
            }
            $deleteCondition = implode(' AND ', $condition);
        }
        $this->getConnection()->delete($table, $deleteCondition);
        return $this;
    }

    /**
     * Generate table date range select
     *
     * @param string $table
     * @param string $column
     * @param string $whereColumn
     * @param null|string|\DateTime $from
     * @param null|string|\DateTime $to
     * @param [][] $additionalWhere
     * @param string $alias
     * @return \Magento\Framework\DB\Select
     */
    protected function _getTableDateRangeSelect(
        $table,
        $column,
        $whereColumn,
        $from = null,
        $to = null,
        $additionalWhere = [],
        $alias = 'date_range_table'
    ) {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            [$alias => $table],
            $connection->getDatePartSql(
                $this->getStoreTZOffsetQuery([$alias => $table], $alias . '.' . $column, $from, $to)
            )
        )->distinct(
            true
        );

        if ($from !== null) {
            $select->where($alias . '.' . $whereColumn . ' >= ?', $from);
        }

        if ($to !== null) {
            $select->where($alias . '.' . $whereColumn . ' <= ?', $to);
        }

        if (!empty($additionalWhere)) {
            foreach ($additionalWhere as $condition) {
                if (is_array($condition) && count($condition) == 2) {
                    $condition = $connection->quoteInto($condition[0], $condition[1]);
                } elseif (is_array($condition)) {
                    // Invalid condition
                    continue;
                }
                $condition = str_replace('{{table}}', $connection->quoteIdentifier($alias), $condition);
                $select->where($condition);
            }
        }

        return $select;
    }

    /**
     * Make condition for using in where section from select statement with single date column
     *
     * @param \Magento\Framework\DB\Select $select
     * @param string $periodColumn
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $connection
     * @return array|bool|string
     */
    protected function _makeConditionFromDateRangeSelect($select, $periodColumn, $connection = null)
    {
        if (!$connection) {
            $connection = $this->getConnection();
        }

        static $selectResultCache = [];
        $cacheKey = (string)$select;

        if (!array_key_exists($cacheKey, $selectResultCache)) {
            try {
                $selectResult = [];

                $query = $connection->query($select);

                while (true == ($date = $query->fetchColumn())) {
                    $selectResult[] = $date;
                }
            } catch (\Exception $e) {
                $selectResult = false;
            }
            $selectResultCache[$cacheKey] = $selectResult;
        } else {
            $selectResult = $selectResultCache[$cacheKey];
        }
        if ($selectResult === false) {
            return false;
        }

        $whereCondition = [];

        foreach ($selectResult as $date) {
            $whereCondition[] = $connection->prepareSqlCondition($periodColumn, ['like' => $date]);
        }
        $whereCondition = implode(' OR ', $whereCondition);
        if ($whereCondition == '') {
            $whereCondition = '1=0';  // FALSE condition!
        }

        return $whereCondition;
    }

    /**
     * Generate table date range select
     *
     * @param string $table
     * @param string $relatedTable
     * @param [] $joinCondition
     * @param string $column
     * @param string $whereColumn
     * @param string|null $from
     * @param string|null $to
     * @param [][] $additionalWhere
     * @param string $alias
     * @param string $relatedAlias
     * @return \Magento\Framework\DB\Select
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    protected function _getTableDateRangeRelatedSelect(
        $table,
        $relatedTable,
        $joinCondition,
        $column,
        $whereColumn,
        $from = null,
        $to = null,
        $additionalWhere = [],
        $alias = 'date_range_table',
        $relatedAlias = 'related_date_range_table'
    ) {
        $connection = $this->getConnection();
        $joinConditionSql = [];

        foreach ($joinCondition as $fkField => $pkField) {
            $joinConditionSql[] = sprintf('%s.%s = %s.%s', $alias, $fkField, $relatedAlias, $pkField);
        }

        $select = $connection->select()->from(
            [$alias => $table],
            $connection->getDatePartSql($connection->quoteIdentifier($alias . '.' . $column))
        )->joinInner(
            [$relatedAlias => $relatedTable],
            implode(' AND ', $joinConditionSql),
            []
        )->distinct(
            true
        );

        if ($from !== null) {
            $select->where($relatedAlias . '.' . $whereColumn . ' >= ?', $from);
        }

        if ($to !== null) {
            $select->where($relatedAlias . '.' . $whereColumn . ' <= ?', $to);
        }

        if (!empty($additionalWhere)) {
            foreach ($additionalWhere as $condition) {
                if (is_array($condition) && count($condition) == 2) {
                    $condition = $connection->quoteInto($condition[0], $condition[1]);
                } elseif (is_array($condition)) {
                    // Invalid condition
                    continue;
                }
                $condition = str_replace(
                    ['{{table}}', '{{related_table}}'],
                    [$connection->quoteIdentifier($alias), $connection->quoteIdentifier($relatedAlias)],
                    $condition
                );
                $select->where($condition);
            }
        }

        return $select;
    }

    /**
     * Retrieve query for attribute with timezone conversion
     *
     * @param string|[] $table
     * @param string $column
     * @param null|mixed $from
     * @param null|mixed $to
     * @param null|int|string|\Magento\Store\Model\Store $store
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $connection
     * @return string
     */
    public function getStoreTZOffsetQuery(
        $table,
        $column,
        $from = null,
        $to = null,
        $store = null,
        $connection = null
    ) {
        if (!$connection) {
            $connection = $this->getConnection();
        }

        $column = $connection->quoteIdentifier($column);

        if (null === $from) {
            $selectOldest = $connection->select()->from($table, ["MIN({$column})"]);
            $from = $connection->fetchOne($selectOldest);
            if (null === $from) {
                $date = new \DateTime();
                $from = $date->format('Y-m-d H:i:s');
            }
        }

        $periods = $this->_getTZOffsetTransitions(
            $this->_localeDate->scopeDate($store)->format('e'),
            $from,
            $to
        );
        if (empty($periods)) {
            return $column;
        }

        $query = "";
        $periodsCount = count($periods);

        $i = 0;
        foreach ($periods as $offset => $timestamps) {
            $subParts = [];
            foreach ($timestamps as $ts) {
                $subParts[] = "({$column} between {$ts['from']} and {$ts['to']})";
            }

            $then = $connection->getDateAddSql(
                $column,
                $offset,
                \Magento\Framework\DB\Adapter\AdapterInterface::INTERVAL_SECOND
            );

            $query .= ++$i == $periodsCount ? $then : "CASE WHEN " . join(" OR ", $subParts) . " THEN {$then} ELSE ";
        }

        return $query . str_repeat('END ', count($periods) - 1);
    }

    /**
     * Retrieve transitions for offsets of given timezone
     *
     * @param string $timezone
     * @param mixed $from
     * @param mixed $to
     * @return array
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _getTZOffsetTransitions($timezone, $from = null, $to = null)
    {
        $tzTransitions = [];
        try {
            if (!empty($from)) {
                $from = $from instanceof \DateTime
                    ? $from->getTimestamp()
                    : (new \DateTime($from))->getTimestamp();
            }

            $to = $to instanceof \DateTime
                ? $to
                : new \DateTime($to);
            $nextPeriod = $this->getConnection()->formatDate(
                $to->format('Y-m-d H:i:s')
            );
            $to = $to->getTimestamp();

            $dtz = new \DateTimeZone($timezone);
            $transitions = $dtz->getTransitions();

            for ($i = count($transitions) - 1; $i >= 0; $i--) {
                $tr = $transitions[$i];
                try {
                    $this->timezoneValidator->validate($tr['ts'], $to);
                } catch (\Magento\Framework\Exception\ValidatorException $e) {
                    continue;
                }

                $tr['time'] = $this->getConnection()->formatDate(
                    (new \DateTime($tr['time']))->format('Y-m-d H:i:s')
                );
                $tzTransitions[$tr['offset']][] = ['from' => $tr['time'], 'to' => $nextPeriod];

                if (!empty($from) && $tr['ts'] < $from) {
                    break;
                }
                $nextPeriod = $tr['time'];
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }

        return $tzTransitions;
    }
}
