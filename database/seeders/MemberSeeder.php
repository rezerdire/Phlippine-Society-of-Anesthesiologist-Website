<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            ['3114', 'ILO', 'RM', 'CRISTI',              'MARIA VANESSA',    'A.',     'vanni.cristi@gmail.com',                           'Female', 'CRISTI3114',              0],
            ['3115', 'SMO', 'TM', 'ELLICA',              'SYD',              'A.',     'seedsyd@yahoo.com',                                'Male',   'ELLICA3115',              3800],
            ['3116', 'SMO', 'RM', 'ENRIQUEZ, JR.',       'RODOLFO',          'B.',     'efrenenri@yahoo.com.ph',                           'Male',   'ENRIQUEZ, JR.3116',       0],
            ['3117', 'NCR', 'RM', 'DE GUZMAN- LA ROSA',  'THERESE TONIROSE', '',       'tonideguzmanmd@gmail.com',                         'Female', 'DE GUZMAN- LA ROSA3117',  4300],
            ['3118', 'STG', 'RM', 'DE LOS SANTOS',       'ROY',              'S.',     'roy_dsantos@yahoo.com.ph',                         'Male',   'DE LOS SANTOS3118',       0],
            ['3119', 'NCR', 'RM', 'LEONGSON',            'RICHARD PAUL',     'L.',     'rpleongson@gmail.com',                             'Male',   'LEONGSON3119',            4300],
            ['3120', 'NCR', 'RM', 'QUERUBIN',            'ERLITA',           'Z.',     'erliequerubin@yahoo.com',                          'Female', 'QUERUBIN3120',            0],
            ['3121', 'NCR', 'RM', 'OCAMPO',              'MA. VIOLETA',      'C.',     'lilet.ocampo@yahoo.com',                           'Female', 'OCAMPO3121',              4300],
            ['3122', 'NCR', 'RM', 'VALDEZ',              'ROSALIE',          'S.',     'valdezrosalie81@yahoo.com',                        'Female', 'VALDEZ3122',              4300],
            ['3123', 'BBP', 'RM', 'ROSETE',              'ANNA MAY',         'V.',     'koetjape@yahoo.com',                               'Female', 'ROSETE3123',              4300],
            ['3124', 'NCR', 'RM', 'ALCANTARA',           'JOSE VICTOR',      'O.',     'victor_alcantara@yahoo.com',                       'Male',   'ALCANTARA3124',           4300],
            ['3125', 'NCR', 'RM', 'SANTOS-CAYABYAB',     'MARIA ANTONETTE',  'M.',     'netsantoscayabyab@gmail.com',                      'Female', 'SANTOS-CAYABYAB3125',     4300],
            ['3126', 'NCR', 'TM', 'SARMIENTO',           'LEONARDO',         'A.',     '',                                                 'Male',   'SARMIENTO3126',           3800],
            ['3127', 'NCR', 'RM', 'TALLON',              'ABRAHAM JOHN',     'R.',     'ajtallon93@gmail.com',                             'Male',   'TALLON3127',              4300],
            ['3128', 'NCR', 'RM', 'AGCOPRA',             'ELEANOR',          'C.',     'ecagcopra@gmail.com',                              'Female', 'AGCOPRA3128',             4300],
            ['3129', 'NCR', 'RM', 'PONCE',               'JAN MARC',         'DC.',    '',                                                 'Male',   'PONCE3129',               4300],
            ['3130', 'BOZ', 'RM', 'LUCAS, JR.',          'ROBERTO',          'B.',     'lucasrobertojr@yahoo.com',                         'Male',   'LUCAS, JR.3130',          0],
            ['3131', 'NCR', 'RM', 'PAGLINAWAN',          'AILEEN ROSE',      'V.',     'aileen_paglinawan_md@yahoo.com',                   '',       'PAGLINAWAN3131',          0],
            ['3132', 'BCL', 'RM', 'UBAÑA',               'ALFRANCIS',        'LAUREL', 'alfrancisubana@icloud.com',                        'Male',   'UBAÑA3132',               0],
            ['3133', 'STG', 'RM', 'HILADO-APOSTOL',      'JOY',              'B.',     'joyhilado.apostol@gmail.com',                      'Female', 'HILADO-APOSTOL3133',      4300],
            ['3134', 'NCR', 'RM', 'OSORIO',              'JHEROMME',         'P.',     'jheromme@yahoo.com',                               'Male',   'OSORIO3134',              4300],
            ['3135', 'STG', 'RM', 'BRAÑA-ALI-ALI',       'MARIA SUZETTE',    'C.',     'mariasuzettebrana@yahoo.com',                      'Female', 'BRAÑA-ALI-ALI3135',       0],
            ['3136', 'NCR', 'RM', 'SERRANO-SINNUNG',     'ABIGAIL',          'N.',     'abbieserrano@hotmail.com',                         'Female', 'SERRANO-SINNUNG3136',     0],
            ['3137', 'CCV', 'RM', 'APURADA',             'MYLENE',           'C.',     'myleneapurada@yahoo.com',                          'Female', 'APURADA3137',             4300],
            ['3138', 'NCR', 'RM', 'BUNAO-MAGNO',         'GERALDINE',        'S.',     'gebunaomagno@yahoo.com',                           'Female', 'BUNAO-MAGNO3138',         0],
            ['3139', 'BCL', 'RM', 'MURILLO',             'MARIA VICTORIA',   'M.',     'mavic_murillo_md@yahoo.com',                       'Female', 'MURILLO3139',             4300],
        ];

        $rows = array_map(fn($m) => [
            'member_id_no'       => $m[0],
            'psa_chapter_code'   => $m[1],
            'psa_mem_type'       => $m[2],
            'mem_last_name'      => $m[3],
            'mem_first_name'     => $m[4],
            'mem_middle_name'    => $m[5] ?: null,
            'mem_email_address'  => $m[6] ?: null,
            'mem_gender'         => $m[7] ?: null,
            'password'           => $m[8],   // raw from source — hash if needed
            'bal'                => $m[9],
        ], $members);

        DB::table('members')->insertOrIgnore($rows);
    }
}