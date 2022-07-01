<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class Post
{
    public $title, $excerpt, $date, $body, $slug;

    public function __construct($title, $excerpt, $date, $body, $slug)
    {
        $this->title = $title;
        $this->excerpt = $excerpt;
        $this->date = $date;
        $this->body = $body;
        $this->slug = $slug;
    }

    public static function find($slug)
    {
        $posts = static::all();
        return $posts->firstWhere('slug', $slug);

        // $path = resource_path("posts/{$slug}.html");
        // if (!file_exists($path)) {
        //     throw new ModelNotFoundException();
        // }

        // return cache()->remember("posts.{$slug}", 1200, fn () =>
        // file_get_contents($path));
    }

    public static function all()
    {
        return cache()->rememberForever('posts.all', function () {
            return collect(File::files(resource_path('posts/')))
                ->map(function ($file) {
                    return YamlFrontMatter::parseFile($file);
                })
                ->map(function ($doc) {
                    return new Post(
                        $doc->title,
                        $doc->excerpt,
                        $doc->date,
                        $doc->body(),
                        $doc->slug
                    );
                })->sortByDesc('date');
        });
    }
}
