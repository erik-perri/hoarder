<?php

namespace Tests\Unit\Criteria;

use App\Criteria\CollectibleCriteriaBuilder;
use Illuminate\Database\Query\Builder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

// TODO Figure out a better way to test this class, this seems awkward and will not scale well as we add more match
//      types.
class CollectibleCriteriaBuilderTest extends TestCase
{
    public function testNormal(): void
    {
        $builder = new CollectibleCriteriaBuilder(['artist', 'types']);

        /** @var MockObject|Builder $mock */
        $mock = $this->createMock(Builder::class);
        $mock->expects(self::once())->method('orWhere');
        $mock->expects(self::once())->method('orWhereJsonContains');

        $builder->apply($mock, true, [
            [
                'match_comparison' => 'equals',
                'match_field' => 'artist',
                'match_value' => 'Artist Name',
            ],
            [
                'match_comparison' => 'tags_contains',
                'match_field' => 'types',
                'match_value' => 'Type Name',
            ],
        ]);
    }

    public function testGroup(): void
    {
        $builder = new CollectibleCriteriaBuilder(['artist', 'types']);

        /** @var MockObject|Builder $subMock */
        $subMock = $this->createMock(Builder::class);
        $subMock->expects(self::exactly(2))->method('orWhere');

        /** @var MockObject|Builder $mock */
        $mock = $this->createMock(Builder::class);
        $mock->expects(self::once())->method('where')->with(self::callback(function ($callback) use ($subMock) {
            $callback($subMock);

            return true;
        }));
        $mock->expects(self::once())->method('whereJsonContains');

        $builder->apply($mock, false, [
            [
                'group_type' => 'or',
                'group_conditions' => [
                    [
                        'match_comparison' => 'equals',
                        'match_field' => 'artist',
                        'match_value' => 'Artist One',
                    ],
                    [
                        'match_comparison' => 'equals',
                        'match_field' => 'artist',
                        'match_value' => 'Artist Two',
                    ],
                ],
            ],
            [
                'match_comparison' => 'tags_contains',
                'match_field' => 'types',
                'match_value' => 'Type Name',
            ],
        ]);
    }

    public function testInvalidField(): void
    {
        $builder = new CollectibleCriteriaBuilder(['artist', 'types']);

        /** @var MockObject|Builder $mock */
        $mock = $this->createMock(Builder::class);

        $this->expectException(\InvalidArgumentException::class);

        $builder->apply($mock, true, [
            [
                'match_comparison' => 'equals',
                'match_field' => 'name',
                'match_value' => 'Item Name',
            ],
        ]);
    }
}
