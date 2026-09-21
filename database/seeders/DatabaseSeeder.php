<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User for All_Books
        User::updateOrCreate(
            ['email' => 'admin@allbooks.store'],
            [
                'name' => 'Admin All_Books',
                'password' => bcrypt('AllBooks_Admin2026!'),
                'is_admin' => true,
            ]
        );

        // Standard Client User
        User::updateOrCreate(
            ['email' => 'client@allbooks.store'],
            [
                'name' => 'Lecteur Passionné',
                'password' => bcrypt('password'),
                'is_admin' => false,
            ]
        );

        // 2. High Quality Digital Books Catalog
        $books = [
            [
                'title' => 'Père Riche, Père Pauvre — L\'Édition Numérique',
                'author' => 'Robert T. Kiyosaki',
                'category' => 'Finance & Investissement',
                'format' => 'PDF + EPUB',
                'pages_count' => 336,
                'language' => 'Français',
                'publication_year' => 2024,
                'isbn' => '978-2-920914-11-2',
                'description' => '<p>Ce que les riches enseignent à leurs enfants à propos de l\'argent et que les pauvres et la classe moyenne ne font pas. Ce livre révolutionnaire brise les mythes financiers et vous enseigne comment faire travailler l\'argent pour vous au lieu de travailler toute votre vie pour de l\'argent.</p><p><strong>Au sommaire :</strong> l\'intelligence financière, la différence cruciale entre un actif et un passif, et comment développer vos colonnes d\'actifs pour atteindre la liberté financière.</p>',
                'price' => 2500,
                'file_path' => 'https://drive.google.com/file/d/demo-pere-riche/view',
                'sample_file' => 'https://drive.google.com/file/d/demo-pere-riche-extrait/preview',
                'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?q=80&w=1000&auto=format&fit=crop',
            ],
            [
                'title' => 'L\'Effet Cumulé — Décuplez votre Réussite',
                'author' => 'Darren Hardy',
                'category' => 'Développement Personnel',
                'format' => 'PDF',
                'pages_count' => 240,
                'language' => 'Français',
                'publication_year' => 2024,
                'isbn' => '978-2-89225-871-3',
                'description' => '<p>Pas de formule magique, pas de raccourcis miracles. L\'Effet Cumulé est basé sur un principe simple mais puissant : de petites actions intelligentes et répétées avec constance créent avec le temps des résultats gigantesques.</p><p>Apprenez à éliminer les mauvaises habitudes qui freinent votre progression et installez une routine imparable pour votre santé, vos finances et vos relations.</p>',
                'price' => 2000,
                'file_path' => 'https://drive.google.com/file/d/demo-effet-cumule/view',
                'sample_file' => 'https://drive.google.com/file/d/demo-effet-cumule-extrait/preview',
                'image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?q=80&w=1000&auto=format&fit=crop',
            ],
            [
                'title' => 'Maîtriser l\'Intelligence Artificielle & Prompt Engineering',
                'author' => 'Dr. Alex Danvers',
                'category' => 'Tech & Programmation',
                'format' => 'PDF + EPUB',
                'pages_count' => 380,
                'language' => 'Français',
                'publication_year' => 2026,
                'isbn' => '978-2-10-085421-9',
                'description' => '<p>Le guide pratique définitif pour comprendre les architectures LLM, concevoir des prompts avancés (Chain of Thought, Few-Shot, ReAct) et intégrer l\'IA dans vos projets de développement web et mobile.</p><p>Inclus : plus de 150 templates de prompts prêts à l\'emploi et des études de cas d\'automatisation d\'entreprises.</p>',
                'price' => 5000,
                'file_path' => 'https://drive.google.com/file/d/demo-ia-prompt/view',
                'sample_file' => 'https://drive.google.com/file/d/demo-ia-prompt-extrait/preview',
                'image' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?q=80&w=1000&auto=format&fit=crop',
            ],
            [
                'title' => 'La Psychologie de l\'Argent',
                'author' => 'Morgan Housel',
                'category' => 'Finance & Investissement',
                'format' => 'EPUB',
                'pages_count' => 288,
                'language' => 'Français',
                'publication_year' => 2025,
                'isbn' => '978-2-84001-923-4',
                'description' => '<p>Réussir avec l\'argent n\'a pas grand-chose à voir avec votre intelligence, mais beaucoup avec votre comportement. À travers 19 histoires courtes et fascinantes, Morgan Housel explore la façon dont nos émotions, nos biais cognitifs et notre histoire personnelle influencent nos décisions financières.</p>',
                'price' => 3500,
                'file_path' => 'https://drive.google.com/file/d/demo-psychologie-argent/view',
                'sample_file' => null,
                'image' => 'https://images.unsplash.com/photo-1592496431122-2349e0fbc666?q=80&w=1000&auto=format&fit=crop',
            ],
            [
                'title' => 'L\'Art de la Guerre Moderne pour Dirigeants',
                'author' => 'Sun Tzu & Marc Legrand',
                'category' => 'Business & Entrepreneuriat',
                'format' => 'PDF',
                'pages_count' => 210,
                'language' => 'Français',
                'publication_year' => 2025,
                'isbn' => '978-2-36878-102-5',
                'description' => '<p>Une réinterprétation contemporaine des 13 chapitres du célèbre traité militaire antique, adaptée au monde des affaires, à la négociation commerciale, au management d\'équipes et à la stratégie de marque numérique.</p>',
                'price' => 3000,
                'file_path' => 'https://drive.google.com/file/d/demo-art-de-la-guerre/view',
                'sample_file' => 'https://drive.google.com/file/d/demo-art-guerre-extrait/preview',
                'image' => 'https://images.unsplash.com/photo-1532012164546-f432f2e3777f?q=80&w=1000&auto=format&fit=crop',
            ],
            [
                'title' => 'Deep Work : Retrouver la Concentration Ultime',
                'author' => 'Cal Newport',
                'category' => 'Développement Personnel',
                'format' => 'PDF + EPUB',
                'pages_count' => 304,
                'language' => 'Français',
                'publication_year' => 2024,
                'isbn' => '978-2-7440-6678-9',
                'description' => '<p>Dans un monde envahi par les notifications, les e-mails et les réseaux sociaux, la capacité à se concentrer intensément sans distraction devient une compétence rare et extrêmement précieuse. Cal Newport propose un cadre rigoureux pour cultiver le travail en profondeur et démultiplier votre productivité.</p>',
                'price' => 2500,
                'file_path' => 'https://drive.google.com/file/d/demo-deep-work/view',
                'sample_file' => null,
                'image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?q=80&w=1000&auto=format&fit=crop',
            ],
        ];

        foreach ($books as $bookData) {
            Product::updateOrCreate(
                ['title' => $bookData['title']],
                $bookData
            );
        }
    }
}
