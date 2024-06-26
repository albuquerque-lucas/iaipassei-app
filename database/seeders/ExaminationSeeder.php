<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExaminationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $examinations = [
            [
                'title' => 'Concurso para BNDES',
                'institution' => 'BNDES',
                'education_level_id' => 4, // Ensino Superior
            ],
            [
                'title' => 'Concurso Público Municipal',
                'institution' => 'Prefeitura Municipal de São Paulo',
                'education_level_id' => 1, // Ensino Fundamental
            ],
            [
                'title' => 'Concurso Público Estadual',
                'institution' => 'Secretaria Estadual de Educação de Minas Gerais',
                'education_level_id' => 2, // Ensino Médio
            ],
            [
                'title' => 'Processo Seletivo para Ensino Técnico',
                'institution' => 'Instituto Federal de Brasília',
                'education_level_id' => 3, // Ensino Técnico
            ],
            [
                'title' => 'Vestibular Unificado de Ensino Superior',
                'institution' => 'Universidade Federal do Rio de Janeiro',
                'education_level_id' => 4, // Ensino Superior
            ],
            [
                'title' => 'Processo Seletivo para Pós-Graduação',
                'institution' => 'Universidade Estadual de Campinas',
                'education_level_id' => 5, // Pós-Graduação
            ],
            [
                'title' => 'Seleção para Mestrado em Ciências',
                'institution' => 'Universidade de São Paulo',
                'education_level_id' => 6, // Mestrado
            ],
            [
                'title' => 'Concurso para Doutorado em Tecnologia',
                'institution' => 'Pontifícia Universidade Católica do Rio Grande do Sul',
                'education_level_id' => 7, // Doutorado
            ],
            [
                'title' => 'Concurso Público para Professores',
                'institution' => 'Secretaria de Educação do Estado do Rio Grande do Sul',
                'education_level_id' => 2, // Ensino Médio
            ],
            [
                'title' => 'Concurso para Técnico Administrativo',
                'institution' => 'Ministério da Economia',
                'education_level_id' => 3, // Ensino Técnico
            ],
            [
                'title' => 'Soldado PM-MG',
                'institution' => 'Governo Estadual de Minas Gerais',
                'education_level_id' => 4, // Ensino Técnico
            ],
            [
                'title' => 'Soldado PM-RJ',
                'institution' => 'Governo Estadual do Rio de Janeiro',
                'education_level_id' => 4, // Ensino Técnico
            ],
            [
                'title' => 'Concurso para Agente de Polícia Federal',
                'institution' => 'Polícia Federal',
                'education_level_id' => 4, // Ensino Superior
            ],
            [
                'title' => 'Concurso para Delegado de Polícia Federal',
                'institution' => 'Polícia Federal',
                'education_level_id' => 6, // Mestrado
            ],
            [
                'title' => 'Concurso para Médico Legista',
                'institution' => 'Secretaria de Segurança Pública',
                'education_level_id' => 4, // Ensino Superior
            ],
            [
                'title' => 'Concurso para Professor do Ensino Público Federal',
                'institution' => 'Ministério da Educação',
                'education_level_id' => 6, // Mestrado
            ],
            [
                'title' => 'Concurso para Técnico Administrativo',
                'institution' => 'Ministério da Economia',
                'education_level_id' => 3, // Ensino Técnico
            ],
            [
                'title' => 'Concurso para Analista de Finanças',
                'institution' => 'Ministério da Economia',
                'education_level_id' => 4, // Ensino Superior
            ],
            [
                'title' => 'Concurso para Auditor Fiscal',
                'institution' => 'Receita Federal',
                'education_level_id' => 4, // Ensino Superior
            ],
            [
                'title' => 'Concurso para Engenheiro Civil',
                'institution' => 'Departamento Nacional de Infraestrutura de Transportes (DNIT)',
                'education_level_id' => 4, // Ensino Superior
            ],
            [
                'title' => 'Concurso para Analista Judiciário',
                'institution' => 'Tribunal Regional Federal',
                'education_level_id' => 4, // Ensino Superior
            ],
            [
                'title' => 'Concurso para Técnico Judiciário',
                'institution' => 'Tribunal Regional Federal',
                'education_level_id' => 2, // Ensino Médio
            ],
        ];

        foreach ($examinations as $examination) {
            Examination::create($examination);
        }


    }
}
