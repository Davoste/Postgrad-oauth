<?php

namespace Database\Seeders;

use App\Models\{Application, Department, Programme, School, User};
use Illuminate\Database\Seeder;

/**
 * Real JOOUST graduate programme catalogue (9 schools), transcribed from the
 * official Graduate Programmes brochure. One department is created per school
 * to hang programmes on and to drive the HOD/Dean review workflow.
 *
 * NOTE: tuition_per_year values are placeholders (the brochure lists no fees) —
 * update them per programme once official figures are available.
 */
class AcademicStructureSeeder extends Seeder
{
    /** school code, school name, dept code, dept name, [programmes...] */
    private array $catalogue = [
        ['EDU', 'School of Education', 'DEDU', 'Education', [
            'Master of Education in Early Childhood Education',
            'Master of Education in Guidance and Counseling',
            'Master of Education in Educational Psychology',
            'Master of Education in Planning and Economics of Education',
            'Master of Education in Educational Administration',
            'Master of Education in Curriculum Studies',
            'Master of Education in Pedagogy',
            'Master of Education in Educational Technology',
            'Master of Education in Special Needs',
            'Doctor of Philosophy in Planning and Economics of Education',
            'Doctor of Philosophy in Guidance and Counselling',
            'Doctor of Philosophy in Educational Administration',
            'Doctor of Philosophy in Curriculum Studies',
            'Doctor of Philosophy in Pedagogy',
            'Doctor of Philosophy in Educational Technology',
            'Doctor of Philosophy in Special Needs',
            'Doctor of Philosophy in Early Childhood Education',
        ]],
        ['SOBE', 'School of Business and Economics', 'DBE', 'Business and Economics', [
            'Master of Business Administration',
            'Master of Logistics and Supply Chain Management',
            'Doctor of Philosophy in Strategic Management',
            'Doctor of Philosophy in Human Resource Management',
            'Doctor of Philosophy in Procurement and Logistics',
            'Doctor of Philosophy in Finance',
            'Doctor of Philosophy in Marketing',
            'Doctor of Philosophy in Accounting',
        ]],
        ['SOHS', 'School of Health Sciences', 'DHS', 'Health Sciences', [
            'Master of Science in Epidemiology and Biostatistics',
            'Master of Public Health',
            'Doctor of Philosophy in Public Health',
        ]],
        ['SIIS', 'School of Informatics and Innovative Systems', 'DIIS', 'Informatics and Innovative Systems', [
            'Master of Science in Health Informatics',
            'Master of Science in Information Technology Security and Audit',
            'Master of Information Technology Management',
            'Master of Science in Information Systems',
            'Doctor of Philosophy in Business Information Systems',
            'Doctor of Philosophy in Information Technology Security and Audit',
            'Doctor of Philosophy in Health Informatics',
        ]],
        ['SOHSS', 'School of Humanities and Social Sciences', 'DHSS', 'Humanities and Social Sciences', [
            'Master of Arts in Geography',
            'Master of Arts in History',
            'Master of Arts in Religion',
            'Master of Arts in Linguistics',
            'Master of Arts in Kiswahili',
            'Master of Arts in Literature',
            'Doctor of Philosophy in Geography',
            'Doctor of Philosophy in History',
            'Doctor of Philosophy in Kiswahili',
            'Doctor of Philosophy in Religion',
            'Doctor of Philosophy in Linguistics',
            'Doctor of Philosophy in Literature',
        ]],
        ['SOMAS', 'School of Mathematics and Actuarial Science', 'DMAS', 'Mathematics and Actuarial Science', [
            'Master of Science in Pure Mathematics',
            'Master of Science in Applied Statistics',
            'Master of Science in Applied Mathematics',
            'Master of Science in Actuarial Science',
            'Doctor of Philosophy in Pure Mathematics',
            'Doctor of Philosophy in Applied Statistics',
            'Doctor of Philosophy in Applied Mathematics',
        ]],
        ['SOBPS', 'School of Biological and Physical Sciences', 'DBPS', 'Biological and Physical Sciences', [
            'Master of Science in Chemistry',
            'Master of Science in Microbiology',
            'Master of Science in Plant Taxonomy',
            'Master of Science in Plant Ecology',
            'Master of Science in Physics',
            'Master of Science in Ecology',
            'Doctor of Philosophy in Entomology',
            'Doctor of Philosophy in Molecular Biology',
            'Doctor of Philosophy in Applied Analytical Chemistry',
            'Doctor of Philosophy in Chemistry',
            'Doctor of Philosophy in Microbiology',
            'Doctor of Philosophy in Physics',
        ]],
        ['SSPNRM', 'School of Spatial Planning and Natural Resource Management', 'DSPN', 'Spatial Planning and Natural Resource Management', [
            'Master of Science in Urban Environmental Planning and Management',
            'Master of Arts in Project Planning Management',
            'Doctor of Philosophy in Planning',
        ]],
        ['SOAFS', 'School of Agricultural and Food Sciences', 'DAFS', 'Agricultural and Food Sciences', [
            'Master of Science in Agribusiness Management',
            'Master of Science in Animal Science',
            'Master of Science in Horticulture',
            'Master of Science in Agricultural Extension',
            'Doctor of Philosophy in Agricultural Extension',
            'Doctor of Philosophy in Agribusiness Management',
            'Doctor of Philosophy in Agricultural Economics',
            'Doctor of Philosophy in Plant Breeding',
            'Doctor of Philosophy in Animal Science',
            'Doctor of Philosophy in Agricultural Entomology',
            'Doctor of Philosophy in Applied Human Nutrition',
            'Doctor of Philosophy in Food Security and Sustainable Agriculture',
            'Doctor of Philosophy in Horticulture',
            'Doctor of Philosophy in Agricultural Biotechnology',
        ]],
    ];

    public function run(): void
    {
        $hod  = User::where('email', 'hod@postgrad.ac.ke')->first();
        $dean = User::where('email', 'dean@postgrad.ac.ke')->first();
        $used = [];

        foreach ($this->catalogue as [$sCode, $sName, $dCode, $dName, $programmes]) {
            // Demo data: make the demo dean/HOD head every school/department so the
            // review workflow works out of the box. In production, reassign real
            // HODs and Deans under Admin → Structure.
            $school = School::firstOrCreate(['code' => $sCode], [
                'name' => $sName,
                'dean_id' => $dean?->id,
            ]);
            $dept = Department::firstOrCreate(['code' => $dCode], [
                'school_id' => $school->id,
                'name' => $dName,
                'hod_id' => $hod?->id,
            ]);

            foreach ($programmes as $name) {
                $isPhd = stripos($name, 'Doctor of Philosophy') !== false;
                Programme::firstOrCreate(['code' => $this->makeCode($name, $used)], [
                    'department_id'     => $dept->id,
                    'name'              => $name,
                    'level'             => $isPhd ? 'phd' : 'masters',
                    'modes'             => $isPhd ? ['full_time', 'part_time'] : ['full_time', 'part_time', 'school_based'],
                    'duration_years'    => $isPhd ? 3 : 2,
                    'tuition_per_year'  => $isPhd ? 180000 : 120000, // placeholder — update with official fees
                    'is_active'         => true,
                    'applications_open' => true,
                ]);
            }
        }

        // A demo application awaiting HOD review (Informatics)
        $demoProg = Programme::where('name', 'Master of Science in Information Systems')->first();
        if ($demoProg) {
            Application::firstOrCreate(
                ['email' => 'applicant@example.com', 'programme_id' => $demoProg->id],
                [
                    'surname' => 'Ouma', 'first_name' => 'Brian', 'other_names' => 'Otieno',
                    'dob' => '1996-04-12', 'sex' => 'male', 'nationality' => 'Kenyan',
                    'id_no' => '31234567', 'phone' => '0712345678',
                    'postal_address' => 'P.O. Box 210-40601, Bondo',
                    'first_degree_university' => 'JOOUST', 'first_degree_award' => 'BSc Information Technology',
                    'first_degree_class' => 'Second Class (Upper)', 'first_degree_year' => 2019,
                    'mode' => 'full_time',
                    'referees' => [
                        ['name' => 'Dr. A. Mwangi', 'email' => 'mwangi@example.com'],
                        ['name' => 'Prof. J. Wekesa', 'email' => 'wekesa@example.com'],
                    ],
                    'status' => 'submitted',
                ]
            );
        }
    }

    /** Build a short, unique programme code from its name. */
    private function makeCode(string $name, array &$used): string
    {
        $isPhd = stripos($name, 'Doctor of Philosophy') !== false;

        if (!$isPhd && strcasecmp($name, 'Master of Business Administration') === 0) {
            return $this->dedupe('MBA', $used);
        }
        if (!$isPhd && strcasecmp($name, 'Master of Public Health') === 0) {
            return $this->dedupe('MPH', $used);
        }

        if ($isPhd) {
            $prefix = 'PHD';
        } elseif (stripos($name, 'Master of Education') !== false || stripos($name, 'Master in Education') !== false) {
            $prefix = 'MED';
        } elseif (stripos($name, 'Master of Arts') !== false) {
            $prefix = 'MA';
        } elseif (stripos($name, 'Master of Science') !== false) {
            $prefix = 'MSC';
        } else {
            $prefix = 'M';
        }

        $tail = preg_replace(
            '/^(Doctor of Philosophy|Master of Science|Master of Education|Master in Education|Master of Arts|Master of Business|Master of)\s*(in\s+)?/i',
            '', $name
        );
        $stop = ['of', 'in', 'and', 'the', 'for'];
        $init = '';
        foreach (preg_split('/[\s&]+/', $tail) as $w) {
            $w = trim($w);
            if ($w === '' || in_array(strtolower($w), $stop, true)) continue;
            $init .= strtoupper($w[0]);
        }
        $init = substr($init ?: 'GEN', 0, 8);

        return $this->dedupe($prefix . '-' . $init, $used);
    }

    private function dedupe(string $code, array &$used): string
    {
        $c = $code; $i = 1;
        while (in_array($c, $used, true)) { $c = $code . $i; $i++; }
        $used[] = $c;
        return $c;
    }
}
