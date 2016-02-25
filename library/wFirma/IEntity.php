<?php
namespace wFirma;

interface IEntity
{
    public static function get(array $options);
    public static function add(array $options);
    public static function find(array $options);
    public static function edit(array $options);
    public static function delete(array $options);
}
