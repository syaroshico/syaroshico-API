<?php

use App\Tools\Options\Options;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class OptionsTest extends TestCase {
    use DatabaseMigrations;

    public function testOptionBasic() {
        $name  = 'FooOption';
        $value = 'Bar';
        $this->assertTrue( Options::add( $name, $value ) );
        $this->assertEquals( DB::table( 'options' )->where( 'name', $name )->value( 'value' ), $value );
        $this->assertEquals( App\Tools\Options\Options::get( $name ), $value );


    }

    public function testUpdateNonExist() {
        $name = 'NonUsedName';
        $value = 'ExampleValue';
        $this->assertFalse( Options::update( $name, $value ) );

    }

    public function testGetNonExist() {
        $name = 'NonUsedNameForGet';
        $this->assertNull( Options::get( $name ) );
    }

    public function testMultiByteName() {
        $name  = 'ココアちゃん';
        $value = 'Bar';
        $this->assertTrue( Options::add( $name, $value ) );
        $this->assertEquals( DB::table( 'options' )->where( 'name', $name )->value( 'value' ), $value );
        $this->assertEquals( App\Tools\Options\Options::get( $name ), $value );

    }

    public function testMultiByteValue() {
        $name  = 'Amausa';
        $value = '千夜ちゃん';
        $this->assertTrue( Options::add( $name, $value ) );
        $this->assertEquals( DB::table( 'options' )->where( 'name', $name )->value( 'value' ), $value );
        $this->assertEquals( App\Tools\Options\Options::get( $name ), $value );

    }

    public function testEmojiName() {
        $name  = 'チノちゃんの☕';
        $value = 'Wooooooooo!';
        $this->assertTrue( Options::add( $name, $value ) );
        $this->assertEquals( DB::table( 'options' )->where( 'name', $name )->value( 'value' ), $value );
        $this->assertEquals( App\Tools\Options\Options::get( $name ), $value );

    }

    public function testEmojiValue() {
        $name  = 'himitsuHeiki';
        $value = 'シャロちゃん+☕';
        $this->assertTrue( Options::add( $name, $value ) );
        $this->assertEquals( DB::table( 'options' )->where( 'name', $name )->value( 'value' ), $value );
        $this->assertEquals( App\Tools\Options\Options::get( $name ), $value );

    }


}
