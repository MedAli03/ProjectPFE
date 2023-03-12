<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        $articles = Article::factory()->count(10)->create();
  
    }
}
