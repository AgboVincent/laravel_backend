<?php namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;

class CollectionService
{

    public static function paginate( $output )
    {
        $total = count($output);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = array_slice(array_reverse($output), $perPage * ($currentPage - 1), $perPage);
        $paginator = new LengthAwarePaginator($currentItems, $total, $perPage, $currentPage);
        return $paginator;
        
    }
    
}
