<?php

declare(strict_types=1);

namespace HPlus\Helper\DbHelper;


trait GetQueryHelper{

    private function QueryHelper($query, $data,$subSelect = null)
    {
        return (new QueryHelper)->setQuery($query)->setData($data);
    }

}