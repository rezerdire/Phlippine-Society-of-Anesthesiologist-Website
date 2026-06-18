<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            ['3114', 'ILO', 'RM', 'CRISTI',              'MARIA VANESSA',    'A.',     'vanni.cristi@gmail.com',                            'CRISTI3114',              0],
            ['3115', 'SMO', 'TM', 'ELLICA',              'SYD',              'A.',     'seedsyd@yahoo.com',                                   'ELLICA3115',              3800],
            ['3116', 'SMO', 'RM', 'ENRIQUEZ, JR.',       'RODOLFO',          'B.',     'efrenenri@yahoo.com.ph',                              'ENRIQUEZ, JR.3116',       0],
            ['3117', 'NCR', 'RM', 'DE GUZMAN- LA ROSA',  'THERESE TONIROSE', '',       'tonideguzmanmd@gmail.com',                          'DE GUZMAN- LA ROSA3117',  4300],
            ['3118', 'STG', 'RM', 'DE LOS SANTOS',       'ROY',              'S.',     'roy_dsantos@yahoo.com.ph',                           'DE LOS SANTOS3118',       0],
            ['3119', 'NCR', 'RM', 'LEONGSON',            'RICHARD PAUL',     'L.',     'rpleongson@gmail.com',                               'LEONGSON3119',            4300],
            ['3120', 'NCR', 'RM', 'QUERUBIN',            'ERLITA',           'Z.',     'erliequerubin@yahoo.com',                          'QUERUBIN3120',            0],
            ['3121', 'NCR', 'RM', 'OCAMPO',              'MA. VIOLETA',      'C.',     'lilet.ocampo@yahoo.com',                           'OCAMPO3121',              4300],
            ['3122', 'NCR', 'RM', 'VALDEZ',              'ROSALIE',          'S.',     'valdezrosalie81@yahoo.com',                        'VALDEZ3122',              4300],
            ['3123', 'BBP', 'RM', 'ROSETE',              'ANNA MAY',         'V.',     'koetjape@yahoo.com',                               'ROSETE3123',              4300],
            ['3124', 'NCR', 'RM', 'ALCANTARA',           'JOSE VICTOR',      'O.',     'victor_alcantara@yahoo.com',                         'ALCANTARA3124',           4300],
            ['3125', 'NCR', 'RM', 'SANTOS-CAYABYAB',     'MARIA ANTONETTE',  'M.',     'netsantoscayabyab@gmail.com',                      'SANTOS-CAYABYAB3125',     4300],
            ['3126', 'NCR', 'TM', 'SARMIENTO',           'LEONARDO',         'A.',     '',                                                   'SARMIENTO3126',           3800],
            ['3127', 'NCR', 'RM', 'TALLON',              'ABRAHAM JOHN',     'R.',     'ajtallon93@gmail.com',                               'TALLON3127',              4300],
            ['3128', 'NCR', 'RM', 'AGCOPRA',             'ELEANOR',          'C.',     'ecagcopra@gmail.com',                              'AGCOPRA3128',             4300],
            ['3129', 'NCR', 'RM', 'PONCE',               'JAN MARC',         'DC.',    '',                                                   'PONCE3129',               4300],
            ['3130', 'BOZ', 'RM', 'LUCAS, JR.',          'ROBERTO',          'B.',     'lucasrobertojr@yahoo.com',                           'LUCAS, JR.3130',          0],
            ['3131', 'NCR', 'RM', 'PAGLINAWAN',          'AILEEN ROSE',      'V.',     'aileen_paglinawan_md@yahoo.com',                         'PAGLINAWAN3131',          0],
            ['3132', 'BCL', 'RM', 'UBAÑA',               'ALFRANCIS',        'LAUREL', 'alfrancisubana@icloud.com',                          'UBAÑA3132',               0],
            ['3133', 'STG', 'RM', 'HILADO-APOSTOL',      'JOY',              'B.',     'joyhilado.apostol@gmail.com',                      'HILADO-APOSTOL3133',      4300],
            ['3134', 'NCR', 'RM', 'OSORIO',              'JHEROMME',         'P.',     'jheromme@yahoo.com',                                 'OSORIO3134',              4300],
            ['3135', 'STG', 'RM', 'BRAÑA-ALI-ALI',       'MARIA SUZETTE',    'C.',     'mariasuzettebrana@yahoo.com',                      'BRAÑA-ALI-ALI3135',       0],
            ['3136', 'NCR', 'RM', 'SERRANO-SINNUNG',     'ABIGAIL',          'N.',     'abbieserrano@hotmail.com',                         'SERRANO-SINNUNG3136',     0],
            ['3137', 'CCV', 'RM', 'APURADA',             'MYLENE',           'C.',     'myleneapurada@yahoo.com',                          'APURADA3137',             4300],
            ['3138', 'NCR', 'RM', 'BUNAO-MAGNO',         'GERALDINE',        'S.',     'gebunaomagno@yahoo.com',                           'BUNAO-MAGNO3138',         0],
            ['3139', 'BCL', 'RM', 'MURILLO',             'MARIA VICTORIA',   'M.',     'mavic_murillo_md@yahoo.com',                       'MURILLO3139',             4300],
        ];

        $rows = array_map(fn($m) => [
            'member_id_no'       => $m[0],
            'psa_chapter_code'   => $m[1],
            'psa_mem_type'       => $m[2],
            'mem_last_name'      => $m[3],
            'mem_first_name'     => $m[4],
            'mem_middle_name'    => $m[5] ?: null,
            'mem_email_address'  => $m[6] ?: null,
            'password'           => $m[7],   // raw from source — hash if needed
            'bal'                => $m[8],
        ], $members);

        DB::table('members')->insertOrIgnore($rows);
    }
}