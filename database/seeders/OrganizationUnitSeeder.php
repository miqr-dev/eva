<?php

namespace Database\Seeders;

use App\Enums\OrganizationUnitType;
use App\Models\OrganizationUnit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationUnitSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            foreach ($this->organizationUnits() as $parentSortOrder => $parentData) {
                $parent = OrganizationUnit::query()->updateOrCreate(
                    [
                        'name' => $parentData['name'],
                        'parent_id' => null,
                    ],
                    [
                        'type' => OrganizationUnitType::Institution,
                        'sort_order' => $parentSortOrder,
                        'is_active' => true,
                    ],
                );

                foreach ($parentData['children'] as $childSortOrder => $childName) {
                    OrganizationUnit::query()->updateOrCreate(
                        [
                            'name' => $childName,
                            'parent_id' => $parent->id,
                        ],
                        [
                            'type' => OrganizationUnitType::Department,
                            'sort_order' => $childSortOrder,
                            'is_active' => true,
                        ],
                    );
                }
            }
        });
    }

    /**
     * @return array<int, array{name: string, children: list<string>}>
     */
    private function organizationUnits(): array
    {
        return [
            [
                'name' => 'Thüringen',
                'children' => [
                    'Erfurt',
                    'Suhl',
                ],
            ],
            [
                'name' => 'Sachsen',
                'children' => [
                    'Leipzig',
                    'Chemnitz',
                    'Döbeln',
                ],
            ],
        ];
    }
}
