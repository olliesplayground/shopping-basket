<?php


namespace App\Services\Database;


interface DatabaseInterface
{
    public function select( $args = '*' );

    public function from( $table );

    public function where( array $columns, $merge = 'OR' );

    public function order_by( $column, $order );

    public function get();
}
