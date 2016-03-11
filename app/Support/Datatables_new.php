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

namespace App\Support;


use Illuminate\Database\Query\Builder;

class Datatables_new
{
    private static function gatherInputs() {
        if (\Input::has('json')) {
            $inputs = \Input::except('json') + json_decode(\Input::get('json'), true);
        } else {
            $inputs = \Input::all();
        }
        return $inputs;
    }

    public static function makeDto(Builder $baseQuery)
    {
        $inputs = self::gatherInputs();
        $total = self::count(clone $baseQuery);
        $filteredTotal = self::count(self::addFilter(clone $baseQuery, $inputs));
        $dataQuery = self::addPaging(self::addOrder(self::addFilter($baseQuery, $inputs), $inputs), $inputs);
        $data = $dataQuery->get();

        $dto = [];
        $dto['draw'] = intval($inputs['draw']);
        $dto['recordsTotal'] = $total;
        $dto['recordsFiltered'] = $filteredTotal;
        $dto['data'] = $data;
        return $dto;
    }

    public static function makeQuery(Builder $baseQuery)
    {
        $inputs = self::gatherInputs();
        return self::addOrder(self::addFilter($baseQuery, $inputs), $inputs);
    }

    private static function addFilter(Builder $query, array $inputs)
    {
        // datatable的ajax传过来的数据是字符串格式，导出功能传来的数据是bool型
        // 以后可想办法统一
        $searchValueIsRegex = ($inputs['search']['regex'] === true || $inputs['search']['regex'] === 'true');
        $searchValue = $inputs['search']['value'];
        $searchableColumns = array_reduce($inputs['columns'], function ($carry, $item) {
            // datatable的ajax传过来的数据是字符串格式，导出功能传来的数据是bool型
            // 以后可想办法统一
            if ($item['searchable'] === "true" || $item['searchable'] === true) {
                $carry[] = $item['data'];
            }
            return $carry;
        }, []);

        if (trim($searchValue) != null && !empty($searchableColumns)) {
            // TODO: 有必要时再实现真正的regex search
            //if (!$searchValueIsRegex) {
            foreach ($searchableColumns as $column) {
                // convert 因为datetime字段隐式转换的chaset不是utf8mb4，在搜索中文关键字时
                // 报1271 - Illegal mix of collations for operation 'like'错误
                $query->orHaving(\DB::raw("convert({$column} using utf8mb4)"), 'like', "%$searchValue%");
            }
        }
        return $query;
    }

    private static function addOrder(Builder $query, array $inputs)
    {
        $orders = isset($inputs['order']) ? $inputs['order'] : [];
        foreach ($orders as $order) {
            $orderColumnName = array_get($inputs['columns'], $order['column'] . '.data');
            $orderDirection = $order['dir'];
            //$query->orderByRaw("convert({$orderColumnName} USING gbk) COLLATE gbk_chinese_ci {$orderDirection}");
            $query->orderBy($orderColumnName, $orderDirection);
        }
        return $query;
    }

    private static function addPaging(Builder $query, array $inputs)
    {
        $start = $inputs['start'] ?: 0;
        $limit = $inputs['length'] ?: 50;
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