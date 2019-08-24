<?php

namespace Yish\LaravelGoogleSpreadSheet\Tests;

use Yish\LaravelGoogleSpreadSheet\LaravelGoogleSpreadSheet;
use Orchestra\Testbench\TestCase as LaravelTestCase;

class SpreadSheetTest extends LaravelTestCase
{
    /**
     * @test
     */
    public function it_unset_multiple_titles()
    {
        $this->app['config']->set('google-spreadsheet.access_type', 'offline');

        $raw = [
            [
                'name', 'email', 'address'
            ],
            [
                'english name', 'email address', 'location address'
            ],
            [
                'Yish', 'hello@tt.com', 'Hello, world.'
            ],
            [
                'Joker', 'joker@tt.com', 'This is real world.'
            ],
        ];

        $expected = [
            '{"english name":"Yish","email address":"hello@tt.com","location address":"Hello, world."}',
            '{"english name":"Joker","email address":"joker@tt.com","location address":"This is real world."}'
        ];

        $instance = new LaravelGoogleSpreadSheet($this->app['config']->get('google-spreadsheet.access_type'));
        $this->assertEquals($expected, $instance->map($raw, 1, [0, 1]));
    }

    /**
     * @test
     */
    public function it_can_be_map_keys_and_values()
    {
        $this->app['config']->set('google-spreadsheet.access_type', 'offline');

        $raw = [
            [
                'name', 'email', 'address'
            ],
            [
                'Yish', 'hello@tt.com', 'Hello, world.'
            ],
            [
                'Joker', 'joker@tt.com', 'This is real world.'
            ],
        ];

        $expected = [
            '{"name":"Yish","email":"hello@tt.com","address":"Hello, world."}',
            '{"name":"Joker","email":"joker@tt.com","address":"This is real world."}'
        ];

        $instance = new LaravelGoogleSpreadSheet($this->app['config']->get('google-spreadsheet.access_type'));

        $this->assertEquals($expected, $instance->map($raw, 0, [0]));
    }
}
