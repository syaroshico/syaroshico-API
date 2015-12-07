<?php

namespace App\Tools\Options;


use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;


class Options {

    const TABLE_NAME = 'options';

    protected static $options = [ ];
    protected static $dateformat = 'Y-m-d H:i:s';
    private static $autoloaded = false;


    /**
     * Get Option value by key
     *
     * @param string $key
     * @param mixed $default
     *
     * @return string|null|false|$default
     */
    public static function get( $key, $default = null ) {
        if ( ! self::$autoloaded ) {
            self::autoload();
        }
        if ( isset( self::$options[ $key ] ) ) {
            return self::$options[ $key ];
        }
        $res = DB::table( self::TABLE_NAME )->where( 'name', $key )->value( 'value' );
        if ( $res ) {
            self::$options[ $key ] = $res;
        }
        if ( is_null( $res ) && ! is_null( $default ) ) {
            return $default;
        }

        return $res;

//		return false;
    }

    /**
     * Set a option
     *
     * Auto select Add/Update
     *
     * @param string $name
     * @param string $value
     * @param bool|null $autoload
     *
     * @return bool succeed?
     */
    public static function set( $name, $value, $autoload = null ) {
        // @todo
        $old_value = self::get( $name );
        if ( is_null( $old_value ) ) {
            $autoload = is_null( $autoload ) ? true : (bool) $autoload;

            return self::addAction( $name, $value, $autoload );
        }

        if ( $old_value === $value ) {
            return false;
        }

        return self::updateAction( $name, $value, $autoload ) === 1;


    }

    /**
     * Update option
     *
     * @param string $key
     * @param string $value
     * @param bool|null $autoload
     *
     * @return bool succeed?
     */
    public static function update( $key, $value, $autoload = null ) {
        // @todo 条件分岐?
        // I don't know if there is no record :(

        /** @var int $result effected rows */
        $result = self::updateAction( $key, $value, $autoload );

        return $result === 1;
    }

    /**
     * @param string $name
     * @param string $value
     * @param bool|true $autoload
     *
     * @return bool succeed?
     */
    public static function add( $name, $value, $autoload = true ) {
        // @todo

        // Record Already exit
        if ( ! is_null( self::get( $name ) ) ) {
            return false;
        }

        return self::addAction( $name, $value, $autoload );
    }

    /**
     * @param string $key
     * @param null $value
     */
    public static function remove( $name, $value = null ) {
        return self::removeAction( $name, $value ) === 1;
    }

    /**
     * Set the dateformat to query
     *
     * @param string $format date format
     *
     * @return void
     */
    public static function setDateFormat( $format ) {
        self::$dateformat = $format;
    }

    /**
     * Autoload options
     *
     * @return void
     */
    private static function autoload() {
        $options = DB::table( self::TABLE_NAME )->select( 'name', 'value' )->where( 'autoload', 1 )->get();
        foreach ( $options as $option ) {
            self::$options[ $option->name ] = $option->value;
        }
        self::$autoloaded = true;
    }


    /**
     * @param string $name
     * @param string $value
     * @param bool|null $autoload
     *
     * @return int effected rows
     */
    private static function updateAction( $name, $value, $autoload ) {
        $attr = [ 'value' => $value, 'updated_at' => date( self::$dateformat ) ];
        if ( ! is_null( $autoload ) ) {
            $attr['autoload'] = $autoload;
        }
        self::$options[ $name ] = $value;

        return DB::table( self::TABLE_NAME )->where( 'name', $name )->update( $attr );
    }

    /**
     * @param string $name
     * @param bool $value
     */
    private static function removeAction( $name, $value ) {

        /** @var Builder $qb QueryBuilder */
        $qb = DB::table( self::TABLE_NAME )->where( 'name', $name );
        if ( $value ) {
            $qb = $qb->where( 'value', $value );
        }

        unset( self::$options[ $name ] );

        return $qb->delete();

    }

    /**
     * @param string $name
     * @param string $value
     * @param bool|null $autoload
     *
     * @return bool
     */
    private static function addAction( $name, $value, $autoload ) {
        /** @var Builder $qb */
        $qb                     = DB::table( self::TABLE_NAME );
        $res                    = $qb->insert(
            [
                'name'       => $name,
                'value'      => $value,
                'autoload'   => ! ! $autoload,
                'created_at' => date( self::$dateformat ),
                'updated_at' => date( self::$dateformat )
            ] );
        self::$options[ $name ] = $value;

        return $res;
    }


}