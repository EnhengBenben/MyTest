<?php
/*************************************************************************
 *
 * Conglin Network CONFIDENTIAL
 * __________________
 *
 *  [2013] - [2015] Conglin Network Incorporated
 *  All Rights Reserved.
 *
 * NOTICE:  All information contained herein is, and remains
 * the property of Conglin Network Incorporated and its suppliers,
 * if any.  The intellectual and technical concepts contained
 * herein are proprietary to Conglin Network Incorporated
 * and its suppliers and may be covered by C.N. and Foreign Patents,
 * patents in process, and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from Conglin Network Incorporated.
 */

namespace App\Datatables;


use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class Datatables
{
    private $baseQuery;
    private $request;

    public function __construct(Builder $baseQuery, Request $request)
    {
        $this->baseQuery = $baseQuery;
        $this->request = $request;
    }

    public function getResponseDto()
    {
        $total = self::count(clone $this->baseQuery);
        $filteredTotal = self::count(self::addFilter(clone $this->baseQuery, $this->request));
        $dataQuery = self::addPaging(self::addOrder(self::addFilter($this->baseQuery, $this->request), $this->request),
            $this->request);
        $data = $dataQuery->get();

        $dto = [];
        $dto['draw'] = intval($this->request['draw']);
        $dto['recordsTotal'] = $total;
        $dto['recordsFiltered'] = $filteredTotal;
        $dto['data'] = $data;
        return $dto;
    }

    private static function addFilter(Builder $query, Request $request)
    {
        // datatable的ajax传过来的数据是字符串格式，导出功能传来的数据是bool型
        // 以后可想办法统一
        $searchValueIsRegex = ($request['search']['regex'] === true || $request['search']['regex'] === 'true');
        $searchValue = $request['search']['value'];
        $searchableColumns = array_reduce($request['columns'], function ($carry, $item) {
            // datatable的ajax传过来的数据是字符串格式，导出功能传来的数据是bool型
            // 以后可想办法统一
            if ($item['searchable'] === "true" || $item['searchable'] === true) {
                $carry[] = $item['name'];
            }
            return $carry;
        }, []);

        if (trim($searchValue) != null && !empty($searchableColumns)) {
            $query->where(function (Builder $query) use ($searchValueIsRegex, $searchValue, $searchableColumns) {
                if (!$searchValueIsRegex) {
                    foreach ($searchableColumns as $column) {
                        // convert 因为datetime字段隐式转换的chaset不是utf8mb4，在搜索中文关键字时
                        // 报1271 - Illegal mix of collations for operation 'like'错误
                        $query->orHaving(\DB::raw("convert({$column} using utf8mb4)"), 'like', "%$searchValue%");
                    }
                } else {
                    // TODO: 有必要时再实现真正的regex search
                    foreach ($searchableColumns as $column) {
                        $query->orHaving(\DB::raw("convert({$column} using utf8mb4)"), $searchValue);
                    }
                }
            });
        }

        return $query;
    }

    private static function addOrder(Builder $query, Request $request)
    {
        $orders = $request['order'];
        foreach ($orders as $order) {
            $orderColumnName = array_get($request['columns'], $order['column'] . '.name');
            $orderDirection = $order['dir'];
            //$query->orderByRaw("convert({$orderColumnName} USING gbk) COLLATE gbk_chinese_ci {$orderDirection}");
            $query->orderBy($orderColumnName, $orderDirection);
        }
        return $query;
    }

    private static function addPaging(Builder $query, Request $request)
    {
        $start = $request['start'] ?: 0;
        $limit = $request['length'] ?: 50;
        return $query->skip($start)->limit($limit);
    }

    private static function count(Builder $query)
    {
        $sql = $query->toSql();
        $total = \DB::select("select count(1) as datatable_total from ({$sql}) datatable_x",
            $query->getBindings())[0]->datatable_total;
        return $total;
    }
}