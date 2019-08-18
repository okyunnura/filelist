<?php

echo 'setup(): database setup' . PHP_EOL;
echo 'regex(): regex update' . PHP_EOL;
echo 'candidate(): candidate update' . PHP_EOL;
echo 'author_generate(): enabled update' . PHP_EOL;
echo 'author_allow(): enabled update' . PHP_EOL;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/database.php';
require __DIR__ . '/model/Content.php';
require __DIR__ . '/model/Author.php';

use Carbon\Carbon;
use Dotenv\Dotenv;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\NotSupportedException;
use Model\Author;
use Model\Content;

Dotenv::create(__DIR__)->load();

function logger($value)
{
    if (is_array($value)) {
        echo json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
    } else {
        echo $value . PHP_EOL;
    }
}

function setup()
{
    if (!file_exists(getenv('ROOT_PATH'))) {
        echo 'not root dir.' . PHP_EOL;
        exit;
    }

    Content::truncate();

    $fs = new Filesystem(new Local(getenv('ROOT_PATH')));

    try {
        $entries = collect($fs->listContents('.', getenv('RECURSIVE_READ')));
    } catch (NotSupportedException $e) {
        throw $e;
    }
    $entries->each(function ($entry, $index) {
        if ($index % 100 === 0) {
            logger($index);
        }

        $content = new Content();
        $content->type = array_key_exists('type', $entry) ? Normalizer::normalize($entry['type'], Normalizer::FORM_C) : null;
        $content->path = array_key_exists('path', $entry) ? getenv('ROOT_PATH') . (Normalizer::normalize($entry['path'], Normalizer::FORM_C)) : null;
        $content->timestamp = array_key_exists('timestamp', $entry) ? $entry['timestamp'] : null;
        $content->size = array_key_exists('size', $entry) ? $entry['size'] : null;
        $content->dirname = array_key_exists('dirname', $entry) ? getenv('ROOT_PATH') . (Normalizer::normalize($entry['dirname'], Normalizer::FORM_C)) : null;
        $content->basename = array_key_exists('basename', $entry) ? Normalizer::normalize($entry['basename'], Normalizer::FORM_C) : null;
        $content->extension = array_key_exists('extension', $entry) ? Normalizer::normalize($entry['extension'], Normalizer::FORM_C) : null;
        $content->created_at = Carbon::now();
        $content->updated_at = Carbon::now();
        $content->save();
    });
}

function regex()
{
    Content::query()->update(['regex' => null]);
    Content::query()
        ->where('enabled', '=', true)
        ->where('type', '=', 'file')
        ->get()
        ->each(function (Content $content, $index) {
            if ($index % 100 === 0) {
                logger($index);
            }

            if (preg_match_all(getenv('REGEX_VALUE'), $content->basename, $matches) && $matches) {
                $content->regex = $matches[0];
                $content->save();
            }
        });
}

function candidate()
{
    Content::query()->update(['candidate' => null]);
    Content::query()
        ->where('enabled', '=', true)
        ->whereNotNull('regex')
        ->get()
        ->each(function (Content $content, $index) {
            if ($index % 100 === 0) {
                logger($index);
            }
            $matches = preg_grep(getenv('CANDIDATE_VALUE'), $content->regex, PREG_GREP_INVERT);
            if ($matches) {
                $content->candidate = array_merge($matches);
                $content->save();
            }
        });
}

function author_generate()
{
    Author::truncate();
    Content::query()->update(['author_id' => null]);
    Content::query()
        ->where('enabled', '=', true)
        ->whereNotNull('candidate')
        ->get()
        ->each(function (Content $content, $index) {
            if ($index % 100 === 0) {
                logger($index);
            }
            if (is_array($content->candidate) && count($content->candidate) === 1) {
                $authorName = str_replace('[', '', str_replace(']', '', $content->candidate[0]));
                $author = Author::query()->where('name', '=', $authorName)->first();
                if (!$author) {
                    $author = new Author();
                    $author->name = $authorName;
                    $author->created_at = Carbon::now();
                    $author->updated_at = Carbon::now();
                    $author->save();
                }

                $content->author()->associate($author);
                $content->save();

            }
        });
}

function author_allow()
{
    Content::query()->update(['multiple' => null]);
    Content::query()
        ->where('enabled', '=', true)
        ->whereNull('author_id')
        ->whereNotNull('candidate')
        ->get()
        ->each(function (Content $content, $index) {
            if ($index % 100 === 0) {
                logger($index);
            }
            $authorNames = collect($content->candidate)
                ->map(function ($candidate) {
                    return str_replace('[', '', str_replace(']', '', $candidate));
                })
                ->toArray();
            $authors = Author::query()->whereIn('name', $authorNames)->get();
            if ($authors->count() === 1) {
                $content->author()->associate($authors->first());
                $content->multiple = true;
                $content->save();
            }
        });
}

function forecast()
{
    Content::query()->update(['forecast' => null]);
    Content::query()
        ->where('enabled', '=', true)
        ->whereNull('author_id')
        ->whereNotNull('candidate')
        ->get()
        ->each(function (Content $content, $index) {
            if ($index % 100 === 0) {
                logger($index);
            }
            $content->forecast = str_replace('[', '', str_replace(']', '', $content->candidate[0]));
            $content->save();
        });
}


//function forecast_allow()
//{
//    Content::query()->update(['forecast' => null]);
//    Content::query()
//        ->whereNull('enabled')
//        ->whereNotNull('forecast')
//        ->get()
//        ->each(function (Content $content, $index) {
//            if ($index % 100 === 0) {
//                logger($index);
//            }
//            $author = Author::query()->where('name','=', $content->forecast)->first();
//            if ($author) {
//                $content->author()->associate($author);
//                $content->enabled = true;
//                $content->multiple = true;
//                $content->save();
//            }
//        });
//}
