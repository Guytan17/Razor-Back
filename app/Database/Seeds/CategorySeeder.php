<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'=>'U7',
                'gender'=>'mixed'
            ],
            [
                'name'=>'U9',
                'gender'=>'mixed'
            ],
            [
                'name'=>'U11',
                'gender'=>'mixed'
            ],
            [
                'name'=>'U13-Filles',
                'gender'=>'woman'
            ],
            [
                'name'=>'U13-Garçons',
                'gender'=>'man'
            ],
            [
                'name'=>'U15-Filles',
                'gender'=>'woman'
            ],
            [
                'name'=>'U15-Garçons',
                'gender'=>'man'
            ],
            [
                'name'=>'U18-Filles',
                'gender'=>'man'
            ],
            [
                'name'=>'U18-Garçons',
                'gender'=>'woman'
            ],
            [
                'name'=>'U21-Femmes',
                'gender'=>'woman'
            ],
            [
                'name'=>'U21-Hommes',
                'gender'=>'man'
            ],
            [
                'name'=>'Seniors Femmes',
                'gender'=>'woman'
            ],
            [
                'name'=>'Seniors Hommes',
                'gender'=>'man'
            ],
            [
                'name'=>'Loisirs Mixtes',
                'gender'=>'mixed'
            ],
            [
                'name'=>'Loisirs Femmes',
                'gender'=>'woman'
            ],
            [
                'name'=>'Loisirs Hommes',
                'gender'=>'man'
            ],
            [
                'name'=>'Vétérans Mixtes',
                'gender'=>'mixed'
            ],
            [
                'name'=>'Vétérans Femmes',
                'gender'=>'woman'
            ],
            [
                'name'=>'Vétérans Hommes',
                'gender'=>'man'
            ],
        ];

        $categoryModel = model('CategoryModel');

        foreach ($data as $category) {
            $categoryModel->save($category);
        }
    }
}
