<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campagne;
use App\Models\CampagneContratArticle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CampagneContratArticleController extends Controller
{
    public function store(Request $request, Campagne $campagne): RedirectResponse
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string|max:50000',
        ]);
        $maxOrder = (int) $campagne->contratArticles()->max('sort_order');
        CampagneContratArticle::query()->create([
            'campagne_id' => $campagne->id,
            'sort_order' => $maxOrder + 1,
            'titre' => $validated['titre'],
            'contenu' => $validated['contenu'],
        ]);

        return back()->with('success_article', 'Article ajouté.');
    }

    public function update(Request $request, Campagne $campagne, CampagneContratArticle $article): RedirectResponse
    {
        $this->assertArticleCampagne($campagne, $article);
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string|max:50000',
        ]);
        $article->update($validated);

        return back()->with('success_article', 'Article enregistré.');
    }

    public function destroy(Campagne $campagne, CampagneContratArticle $article): RedirectResponse
    {
        $this->assertArticleCampagne($campagne, $article);
        $article->delete();

        return back()->with('success_article', 'Article supprimé.');
    }

    private function assertArticleCampagne(Campagne $campagne, CampagneContratArticle $article): void
    {
        if ($article->campagne_id !== $campagne->id) {
            abort(404);
        }
    }
}
