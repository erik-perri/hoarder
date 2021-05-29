<?php

namespace Tests\Unit\Criteria;

use App\Criteria\Comparison\Number;
use App\Criteria\Comparison\Text;
use App\Criteria\CriteriaApplier;
use App\Criteria\Field\FieldInfo;
use App\Criteria\Field\JsonColumnNameFormatter;
use Illuminate\Database\Query\Builder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

// TODO Figure out a better way to test this class, this seems awkward and will not scale well as we add more match
//      types.
class CriteriaApplierTest extends TestCase
{
    public function testNormal(): void
    {
        $applier = new CriteriaApplier([
            new FieldInfo(
                'artist',
                'Artist',
                'artist',
                'text',
                new Text()
            ),
        ]);

        /** @var MockObject|Builder $mock */
        $mock = $this->createMock(Builder::class);
        $mock->expects(self::once())->method('orWhere');

        $applier->apply($mock, true, [
            [
                'match_comparison' => Text::COMPARISON_EQUALS,
                'match_field' => 'artist',
                'match_value' => 'Artist Name',
            ],
        ]);
    }

    public function testGroup(): void
    {
        $applier = new CriteriaApplier([
            new FieldInfo(
                'artist',
                'Artist',
                'artist',
                'text',
                new Text()
            ),
        ]);

        /** @var MockObject|Builder $subMock */
        $subMock = $this->createMock(Builder::class);
        $subMock->expects(self::exactly(2))->method('orWhere');

        /** @var MockObject|Builder $mock */
        $mock = $this->createMock(Builder::class);
        $mock->expects(self::once())->method('where')->with(self::callback(function ($callback) use ($subMock) {
            $callback($subMock);

            return true;
        }));

        $applier->apply($mock, false, [
            [
                'group_type' => 'or',
                'group_conditions' => [
                    [
                        'match_comparison' => Text::COMPARISON_EQUALS,
                        'match_field' => 'artist',
                        'match_value' => 'Artist One',
                    ],
                    [
                        'match_comparison' => Text::COMPARISON_EQUALS,
                        'match_field' => 'artist',
                        'match_value' => 'Artist Two',
                    ],
                ],
            ],
        ]);
    }

    public function testInvalidField(): void
    {
        $applier = new CriteriaApplier([
            new FieldInfo(
                'artist',
                'Artist',
                'artist',
                'text',
                new Text()
            ),
        ]);

        /** @var MockObject|Builder $mock */
        $mock = $this->createMock(Builder::class);

        $this->expectException(\InvalidArgumentException::class);

        $applier->apply($mock, true, [
            [
                'match_comparison' => Text::COMPARISON_EQUALS,
                'match_field' => 'name',
                'match_value' => 'Item Name',
            ],
        ]);
    }

    public function testNameFormatter(): void
    {
        $applier = new CriteriaApplier([
            new FieldInfo(
                'id',
                'ID',
                'id',
                'number',
                new Number(),
                null
            ),
            new FieldInfo(
                'artist',
                'Artist',
                'artist',
                'text',
                new Text(),
                new JsonColumnNameFormatter('field_values')
            ),
        ]);

        /** @var MockObject|Builder $mock */
        $mock = $this->createMock(Builder::class);
        $mock->expects(self::exactly(2))
             ->method('orWhere')
             ->withConsecutive(['id', '=', 1], ['field_values->artist', '=', 'Artist Name']);

        $applier->apply($mock, true, [
            [
                'match_comparison' => Number::COMPARISON_EQUALS,
                'match_field' => 'id',
                'match_value' => 1,
            ],
            [
                'match_comparison' => Text::COMPARISON_EQUALS,
                'match_field' => 'artist',
                'match_value' => 'Artist Name',
            ],
        ]);
    }
}
