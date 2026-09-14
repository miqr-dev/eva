<?php

namespace Database\Seeders;

use App\Models\OrganizationUnit;
use App\Models\Teacher;
use App\Models\TeacherRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizationUnits = OrganizationUnit::query()
            ->whereIn('name', ['Erfurt', 'Suhl'])
            ->get()
            ->keyBy('name');

        $teacherRoles = TeacherRole::query()
            ->whereIn('name', ['Sozialpädagoge', 'Psychologe', 'Dozent'])
            ->get()
            ->keyBy('name');

        foreach ($this->teachers($organizationUnits, $teacherRoles) as $teacher) {
            Teacher::query()->updateOrCreate(
                [
                    'name' => $teacher['name'],
                    'organization_unit_id' => $teacher['organization_unit_id'],
                ],
                [
                    'teacher_role_id' => $teacher['teacher_role_id'],
                    'is_remote' => $teacher['is_remote'],
                    'is_active' => true,
                ],
            );
        }
    }

    /**
     * @param  \Illuminate\Support\Collection<string, OrganizationUnit>  $organizationUnits
     * @param  \Illuminate\Support\Collection<string, TeacherRole>  $teacherRoles
     * @return list<array{name: string, organization_unit_id: int, teacher_role_id: int, is_remote: bool}>
     */
    private function teachers($organizationUnits, $teacherRoles): array
    {
        $erfurt = $organizationUnits['Erfurt']->id;
        $suhl = $organizationUnits['Suhl']->id;
        $sozialpaedagoge = $teacherRoles['Sozialpädagoge']->id;
        $psychologe = $teacherRoles['Psychologe']->id;
        $dozent = $teacherRoles['Dozent']->id;

        return [
            // Erfurt
            ['name' => 'Fr. Ullmann', 'organization_unit_id' => $erfurt, 'teacher_role_id' => $sozialpaedagoge, 'is_remote' => false],
            ['name' => 'Fr. Grassal', 'organization_unit_id' => $erfurt, 'teacher_role_id' => $sozialpaedagoge, 'is_remote' => false],
            ['name' => 'Fr. Hujber', 'organization_unit_id' => $erfurt, 'teacher_role_id' => $psychologe, 'is_remote' => false],
            ['name' => 'Fr. Boldt', 'organization_unit_id' => $erfurt, 'teacher_role_id' => $psychologe, 'is_remote' => false],
            ['name' => 'Hr. Hebest', 'organization_unit_id' => $erfurt, 'teacher_role_id' => $dozent, 'is_remote' => false],
            ['name' => 'Hr. Effenberger', 'organization_unit_id' => $erfurt, 'teacher_role_id' => $dozent, 'is_remote' => false],
            ['name' => 'Hr. Gommlich', 'organization_unit_id' => $erfurt, 'teacher_role_id' => $dozent, 'is_remote' => false],
            ['name' => 'Fr. Dr. Wende', 'organization_unit_id' => $erfurt, 'teacher_role_id' => $dozent, 'is_remote' => false],
            ['name' => 'Fr. Eichfeld', 'organization_unit_id' => $erfurt, 'teacher_role_id' => $dozent, 'is_remote' => false],
            ['name' => 'Fr. Tittel', 'organization_unit_id' => $erfurt, 'teacher_role_id' => $dozent, 'is_remote' => false],
            ['name' => 'Hr. Schulze', 'organization_unit_id' => $erfurt, 'teacher_role_id' => $dozent, 'is_remote' => false],
            // Nicht fest in Erfurt ansässig, unterrichtet standortübergreifend.
            ['name' => 'Fr. Apel', 'organization_unit_id' => $erfurt, 'teacher_role_id' => $dozent, 'is_remote' => true],
            ['name' => 'Hr. Voigt', 'organization_unit_id' => $erfurt, 'teacher_role_id' => $dozent, 'is_remote' => false],
            ['name' => 'Hr. Wolf', 'organization_unit_id' => $erfurt, 'teacher_role_id' => $dozent, 'is_remote' => false],

            // Suhl
            ['name' => 'Fr. Koch', 'organization_unit_id' => $suhl, 'teacher_role_id' => $sozialpaedagoge, 'is_remote' => false],
            ['name' => 'Hr. Balschik', 'organization_unit_id' => $suhl, 'teacher_role_id' => $sozialpaedagoge, 'is_remote' => false],
            ['name' => 'Hr. Kahlmann', 'organization_unit_id' => $suhl, 'teacher_role_id' => $psychologe, 'is_remote' => false],
            ['name' => 'Fr. Reichardt', 'organization_unit_id' => $suhl, 'teacher_role_id' => $dozent, 'is_remote' => false],
            ['name' => 'Hr. Truckenbrodt', 'organization_unit_id' => $suhl, 'teacher_role_id' => $dozent, 'is_remote' => false],
            ['name' => 'Hr. Hammerl', 'organization_unit_id' => $suhl, 'teacher_role_id' => $dozent, 'is_remote' => false],
            ['name' => 'Fr. Truckenbrodt', 'organization_unit_id' => $suhl, 'teacher_role_id' => $dozent, 'is_remote' => false],
            ['name' => 'Hr. Büchner', 'organization_unit_id' => $suhl, 'teacher_role_id' => $dozent, 'is_remote' => false],
            ['name' => 'Hr. Reinhardt', 'organization_unit_id' => $suhl, 'teacher_role_id' => $dozent, 'is_remote' => false],
            ['name' => 'Hr. Makowski', 'organization_unit_id' => $suhl, 'teacher_role_id' => $dozent, 'is_remote' => false],
            ['name' => 'Fr. Liebtrau', 'organization_unit_id' => $suhl, 'teacher_role_id' => $dozent, 'is_remote' => false],
            ['name' => 'Hr. Schmidt', 'organization_unit_id' => $suhl, 'teacher_role_id' => $dozent, 'is_remote' => false],
            ['name' => 'Hr. Stummer', 'organization_unit_id' => $suhl, 'teacher_role_id' => $dozent, 'is_remote' => false],

            // Heimatstandort Suhl, unterrichten zusätzlich standortübergreifend
            // (u. a. in Erfurt).
            ['name' => 'Hr. Kaiser', 'organization_unit_id' => $suhl, 'teacher_role_id' => $dozent, 'is_remote' => true],
            ['name' => 'Fr. Kubitza', 'organization_unit_id' => $suhl, 'teacher_role_id' => $dozent, 'is_remote' => true],
        ];
    }
}
