<?php

namespace Database\Factories;

use App\Models\Curriculo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Curriculo>
 */
class CurriculoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Curriculo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $escolaridades = [
            'fundamental_incompleto',
            'fundamental_completo',
            'medio_incompleto',
            'medio_completo',
            'superior_incompleto',
            'superior_completo',
            'pos_graduacao',
            'mestrado',
            'doutorado',
        ];

        $cargosDesejados = [
            'Desenvolvedor Full Stack',
            'Desenvolvedor Frontend',
            'Desenvolvedor Backend',
            'Analista de Sistemas',
            'Designer UX/UI',
            'Product Manager',
            'Gerente de Projetos',
            'Analista de Dados',
            'DevOps Engineer',
            'QA Tester',
            'Administrador de Sistemas',
            'Consultor de TI',
            'Arquiteto de Software',
            'Scrum Master',
            'Administrador de Banco de Dados',
        ];

        $fileName = fake()->uuid().'.pdf';
        $originalName = fake()->firstName().'_'.fake()->lastName().'_curriculo.pdf';

        return [
            'nome' => fake('pt_BR')->name(),
            'email' => fake('pt_BR')->unique()->safeEmail(),
            'telefone' => fake('pt_BR')->cellphoneNumber(),
            'cargo_desejado' => fake()->randomElement($cargosDesejados),
            'escolaridade' => fake()->randomElement($escolaridades),
            'observacoes' => fake('pt_BR')->optional(0.7)->paragraph(),
            'arquivo_path' => 'curriculos/'.$fileName,
            'arquivo_original_name' => $originalName,
        ];
    }

    /**
     * Candidato com ensino superior completo
     */
    public function superiorCompleto(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'escolaridade' => 'superior_completo',
                'cargo_desejado' => fake()->randomElement([
                    'Desenvolvedor Full Stack',
                    'Analista de Sistemas',
                    'Product Manager',
                    'Arquiteto de Software',
                ]),
            ];
        });
    }

    /**
     * Candidato com pós-graduação
     */
    public function posGraduacao(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'escolaridade' => 'pos_graduacao',
                'cargo_desejado' => fake()->randomElement([
                    'Gerente de Projetos',
                    'Product Manager',
                    'Consultor de TI',
                    'Arquiteto de Software',
                ]),
            ];
        });
    }

    /**
     * Candidato desenvolvedor
     */
    public function desenvolvedor(): static
    {
        return $this->state(function (array $attributes) {
            $tecnologias = [
                'PHP/Laravel',
                'JavaScript/Vue.js',
                'Python/Django',
                'Java/Spring',
                'C#/.NET',
                'Node.js/Express',
                'React/Next.js',
                'Angular/TypeScript',
            ];

            return [
                'cargo_desejado' => fake()->randomElement([
                    'Desenvolvedor Full Stack',
                    'Desenvolvedor Frontend',
                    'Desenvolvedor Backend',
                ]),
                'observacoes' => 'Experiência com: '.fake()->randomElements($tecnologias, fake()->numberBetween(2, 4))[0].', '.fake()->randomElements($tecnologias, fake()->numberBetween(2, 4))[1].'. '.fake('pt_BR')->paragraph(),
            ];
        });
    }

    /**
     * Candidato júnior (ensino médio/superior incompleto)
     */
    public function junior(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'escolaridade' => fake()->randomElement(['medio_completo', 'superior_incompleto']),
                'cargo_desejado' => fake()->randomElement([
                    'Desenvolvedor Júnior',
                    'Estagiário de TI',
                    'Assistente de Desenvolvimento',
                    'QA Tester Júnior',
                ]),
                'observacoes' => fake('pt_BR')->optional(0.8)->text(200),
            ];
        });
    }

    /**
     * Candidato sênior
     */
    public function senior(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'escolaridade' => fake()->randomElement(['superior_completo', 'pos_graduacao', 'mestrado']),
                'cargo_desejado' => fake()->randomElement([
                    'Desenvolvedor Sênior',
                    'Tech Lead',
                    'Arquiteto de Software',
                    'Gerente de Desenvolvimento',
                    'Principal Engineer',
                ]),
                'observacoes' => 'Mais de 5 anos de experiência na área. '.fake('pt_BR')->paragraph(),
            ];
        });
    }

    /**
     * Candidato com telefone específico (útil para testes)
     */
    public function comTelefone(string $telefone): static
    {
        return $this->state(function (array $attributes) use ($telefone) {
            return [
                'telefone' => $telefone,
            ];
        });
    }
}
