<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $articles = Article::with('author:id,name,email')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $articles
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch articles'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 422);
        }

        try {
            $article = Article::create([
                'title' => $request->title,
                'content' => $request->content,
                'author_id' => auth('api')->user()->id,
            ]);

            $article->load('author:id,name,email');

            return response()->json([
                'success' => true,
                'message' => 'Article created successfully',
                'data' => $article
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to create article'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        try {
            $article->load('author:id,name,email');
            
            return response()->json([
                'success' => true,
                'data' => $article
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Article not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        try {
            if ($article->author_id !== auth('api')->user()->id) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized. You can only update your own articles.'
                ], 403);
            }

            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 422);
        }

        try {
            $article->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            $article->load('author:id,name,email');

            return response()->json([
                'success' => true,
                'message' => 'Article updated successfully',
                'data' => $article
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to update article'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        try {
            // Check if user is the author of the article
            if ($article->author_id !== auth('api')->user()->id) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized. You can only delete your own articles.'
                ], 403);
            }

            $article->delete();

            return response()->json([
                'success' => true,
                'message' => 'Article deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete article'
            ], 500);
        }
    }

    /**
     * Get articles by current authenticated user
     */
    public function myArticles()
    {
        try {
            $articles = Article::where('author_id', auth('api')->user()->id)
                ->with('author:id,name,email')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $articles
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch your articles'
            ], 500);
        }
    }
}
