<?php

namespace Tests\Unit\Criteria\Comparison;

use App\Criteria\Comparison\Boolean;
use Illuminate\Database\Query\Builder;
use PHPUnit\Framework\TestCase;

class BooleanTest extends TestCase
{
    public function testTrue(): void
    {
        $handler = new Boolean();

        $mock = $this->createMock(Builder::class);
        $mock->expects(self::once())->method('where')->with('column_name', '=', true, 'and');
        $handler->apply($mock, false, 'column_name', Boolean::COMPARISON_IS, Boolean::VALUE_TRUE);

        $mock = $this->createMock(Builder::class);
        $mock->expects(self::once())->method('orWhere')->with('column_name', '=', false);
        $handler->apply($mock, true, 'column_name', Boolean::COMPARISON_IS_NOT, Boolean::VALUE_TRUE);
    }

    public function testFalse(): void
    {
        $handler = new Boolean();

        $mock = $this->createMock(Builder::class);
        $mock->expects(self::once())->method('where')->with('column_name', '=', false, 'and');
        $handler->apply($mock, false, 'column_name', Boolean::COMPARISON_IS, Boolean::VALUE_FALSE);

        $mock = $this->createMock(Builder::class);
        $mock->expects(self::once())->method('orWhere')->with('column_name', '=', true);
        $handler->apply($mock, true, 'column_name', Boolean::COMPARISON_IS_NOT, Boolean::VALUE_FALSE);
    }

    public function testUnset(): void
    {
        $handler = new Boolean();

        $mock = $this->createMock(Builder::class);
        $mock->expects(self::once())->method('whereNull')->with('column_name');
        $handler->apply($mock, false, 'column_name', Boolean::COMPARISON_IS, Boolean::VALUE_UNSET);

        $mock = $this->createMock(Builder::class);
        $mock->expects(self::once())->method('orWhereNull')->with('column_name');
        $handler->apply($mock, true, 'column_name', Boolean::COMPARISON_IS, Boolean::VALUE_UNSET);

        $mock = $this->createMock(Builder::class);
        $mock->expects(self::once())->method('whereNotNull')->with('column_name');
        $handler->apply($mock, false, 'column_name', Boolean::COMPARISON_IS_NOT, Boolean::VALUE_UNSET);

        $mock = $this->createMock(Builder::class);
        $mock->expects(self::once())->method('orWhereNotNull')->with('column_name');
        $handler->apply($mock, true, 'column_name', Boolean::COMPARISON_IS_NOT, Boolean::VALUE_UNSET);
    }

    public function testInvalid(): void
    {
        $handler = new Boolean();

        $this->expectException(\InvalidArgumentException::class);

        $mock = $this->createMock(Builder::class);
        $handler->apply($mock, true, 'column_name', Boolean::COMPARISON_IS_NOT, 'a');
    }
}
